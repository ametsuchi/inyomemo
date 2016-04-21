<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Evernote\Client;
use EDAM\Types\Notebook;
use EDAM\Error\EDAMSystemException,
    EDAM\Error\EDAMUserException,
    EDAM\Error\EDAMErrorCode,
    EDAM\Error\EDAMNotFoundException;
use Auth;
use App\User;
use App\EvernoteNotebook;
use App\EvernoteNote;

class EvernoteController extends Controller
{

    /**
     * Ｅｖｅｒｎｏｔｅ認証用ＵＲＬを取得
     *
     * @return 認証用ＵＲＬ
     */
    public function getTemporaryCredentials(Request $request)
    {

    	$client = new Client(array(
	  		'consumerKey' => env('EVERNOTE_CONSUMER_KEY'),
  			'consumerSecret' => env('EVERNOTE_CONSUMER_SECRET'),
            'sandbox' => env('EVERNOTE_SANDBOX')));
    	$callbackUrl = $this->getCallbackUrl();
		$requestTokenInfo = $client->getRequestToken($callbackUrl);
		$authorizeUrl = $client->getAuthorizeUrl($requestTokenInfo['oauth_token']);

		if ($requestTokenInfo) {			
                $request->session()->push('requestToken',$requestTokenInfo['oauth_token']);
                $request->session()->push('requestTokenSecret',$requestTokenInfo['oauth_token_secret']);
                $currentStatus = 'Obtained temporary credentials';

                // 現在アクセス中のページ保存
                $request->session()->push('accessUrl',$request->input("url"));

        }else{
        	error("evernote oauth failed.");
        }

        return $authorizeUrl;
    }

    /**
     * Evernoteのoauth認証コールバック用ハンドラ
     *
     * @return redirect 直前のＵＲＬ
     */
    public function callback(Request $request)
    {

        $request->session()->push('oauthVerifier',$request->input('oauth_verifier'));
        $currentStatus = 'Content owner authorized the temporary credentials';
        $oauth_verifier = $request->input('oauth_verifier');
        //　トークンを保存
	    $loginUser = Auth::user();
        $evernote_notebooks = EvernoteNotebook::where('userid',$loginUser->id)->get();

        if(empty($oauth_verifier)){
        	// 連携拒否
        	if(count($evernote_notebooks) > 0) {
        		$evernote_notebooks->delete();
        	}
	        return $this->redirectOriginalUrl($request);
        }
        // アクセストークン取得
    	$client = new Client(array(
	  		'consumerKey' => env('EVERNOTE_CONSUMER_KEY'),
  			'consumerSecret' => env('EVERNOTE_CONSUMER_SECRET'),
            'sandbox' => env('EVERNOTE_SANDBOX')));
    	$requestToken = $request->session()->pull('requestToken')[0];
    	$requestTokenSecret = $request->session()->pull('requestTokenSecret')[0];
        $accessTokenInfo = $client->getAccessToken($requestToken,$requestTokenSecret,$oauth_verifier);
        if ($accessTokenInfo) {
               $accessToken  = $accessTokenInfo['oauth_token'];

               if(count($evernote_notebooks) > 0){
               		$evernote_notebooks[0]->token = $accessToken;
               		$evernote_notebooks[0]->save();
               }
        }

        return $this->redirectOriginalUrl($request);
    }

    function redirectOriginalUrl(Request $request){
        // 元のページを表示
        $redirectUrl = $request->session()->pull('accessUrl');
        $redirectUrl = str_replace($this->getHostUrl(),"",$redirectUrl);
        return redirect(substr($redirectUrl[0],1));    	
    }


    /**
     * ノートブックのGUIDを取得
     *
     */
    function getNotebookGuid(Request $request){
        try {
        	$user = Auth::user();
            $evernote_notebook = EvernoteNotebook::where('userid',$user->id)->get();

            if(count($evernote_notebook) == 0){
            	error("[EVERNOTE] don't get accessToken for DB.(userid:".$user->id.")");
            	return;
            }

            $accessToken = $evernote_notebook[0]->token;
            $notebookGuid = $evernote_notebook[0]->notebook_guid;
            info($accessToken);
            $client = new Client(array(
            	'token' => $accessToken,
                'sandbox' => true
            ));
            $store = $client->getNoteStore();
            $notebooks = $store->listNotebooks();
            info("_");
            if(!empty($notebookGuid)){
	            try{
    	        	$store->getNotebook($notebookGuid);
        	    }catch (EDAMNotFoundException $e){
            		// ノートブックがない場合新規作成
					$notebookGuid = $this->createNotebook($store,$accessToken,$user,$evernote_notebook[0]);
            	}
            }else{
				$notebookGuid = $this->createNotebook($store,$accessToken,$user,$evernote_notebook[0]);
           }
            return $notebookGuid;
        } catch (EDAMSystemException $e) {
        	info($e->getMessage());
            if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
                $lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
            } else {
                $lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
            }
        } catch (EDAMUserException $e) {
        	info($e->getMessage());
            if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
                $lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
            } else {
                $lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
            }
        } catch (EDAMNotFoundException $e) {
        	info($e->getMessage());
            if (isset(EDAMErrorCode::$__names[$e->errorCode])) {
                $lastError = 'Error listing notebooks: ' . EDAMErrorCode::$__names[$e->errorCode] . ": " . $e->parameter;
            } else {
                $lastError = 'Error listing notebooks: ' . $e->getCode() . ": " . $e->getMessage();
            }
        } catch (Exception $e) {
        	info($e->getMessage());
            $lastError = 'Error listing notebooks: ' . $e->getMessage();
        }

        info($lastError);
    }


    function getHostUrl(){
    	$thisUrl = (empty($_SERVER['HTTPS'])) ? "http://" : "https://";
        $thisUrl .= $_SERVER['SERVER_NAME'];
        $thisUrl .= ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? "" : (":".$_SERVER['SERVER_PORT']);
        return $thisUrl;
    }

    /**
     * 新規にノートブックを作成
     *
     * @return guid
     */
    function createNotebook($store,$accessToken,$user,$evernote_notebook){
    	$newNotebook = new Notebook();
        $newNotebook->name = "bkim";
        $createdNotebook = $store->createNotebook($accessToken,$newNotebook);
        $notebookGuid = $createdNotebook->guid;
        // DBに保存
        $evernote_notebook->notebook_guid = $notebookGuid;
        $evernote_notebook->save();
        return $notebookGuid;
    }
}
