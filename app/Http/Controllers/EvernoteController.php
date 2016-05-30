<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Evernote\Client;
use EDAM\Types\Notebook;
use EDAM\Types\Note;
use EDAM\Types\NoteAttributes;
use EDAM\NoteStore\NoteFilter;
use EDAM\Error\EDAMSystemException,
    EDAM\Error\EDAMUserException,
    EDAM\Error\EDAMErrorCode,
    EDAM\Error\EDAMNotFoundException;
use Auth;
use Log;
use App\User;
use App\EvernoteNotebook;
use App\EvernoteNote;
use App\Note as DbNote;
use App\Wishlist;
use App\WishListTitle;
use Illuminate\Cookie\CookieJar;


class EvernoteController extends Controller
{
	
	/**
	 * Evernoteに書き込み（通常投稿）.
	 * 
	 */
	public function writingNoteToEvernote(Request $request){
		$loginUser = Auth::user();
        $evernote_notebooks = EvernoteNotebook::where('userid',$loginUser->id)->get();

        if(count($evernote_notebooks) == 0 || empty($evernote_notebooks[0]->token)){
        	return;
        }


		$isbn = $request->input("isbn");
		$title = $request->input("title");
		$author = $request->input("author");

   		$notebookGuid = $this->getNotebookGuid();
   		$guid = $this->getNote($isbn,$title,$author,$notebookGuid);
   		return $guid;
   }
   

    /**
     * Ｅｖｅｒｎｏｔｅ認証用ＵＲＬを取得
     *
     * @return 認証用ＵＲＬ
     */
    public function getTemporaryCredentials(CookieJar $cookieJar, Request $request)
    {

    	$client = new Client(array(
	  		'consumerKey' => env('EVERNOTE_CONSUMER_KEY'),
  			'consumerSecret' => env('EVERNOTE_CONSUMER_SECRET'),
            'sandbox' => env('EVERNOTE_SANDBOX')));
    	$callbackUrl = $this->getCallbackUrl();
		$requestTokenInfo = $client->getRequestToken($callbackUrl);
		$authorizeUrl = $client->getAuthorizeUrl($requestTokenInfo['oauth_token']);

		if ($requestTokenInfo) {
                    $cookieJar->queue(cookie('requestToken',$requestTokenInfo['oauth_token'], 45000));
                    $cookieJar->queue(cookie('requestTokenSecret',$requestTokenInfo['oauth_token_secret'], 45000));
                    $cookieJar->queue(cookie('accessUrl',$request->input("url"), 45000));

        }else{
        	Log::error("evernote oauth failed.");
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
        		EvernoteNotebook::where('userid',$loginUser->id)->delete();
        	}
	        return $this->redirectOriginalUrl($request);
        }
        // アクセストークン取得
    	$client = new Client(array(
	  		'consumerKey' => env('EVERNOTE_CONSUMER_KEY'),
  			'consumerSecret' => env('EVERNOTE_CONSUMER_SECRET'),
            'sandbox' => env('EVERNOTE_SANDBOX')));
        $requestToken = $request->cookie('requestToken');
        $requestTokenSecret = $request->cookie('requestTokenSecret');

        $accessTokenInfo = $client->getAccessToken($requestToken,$requestTokenSecret,$oauth_verifier);
        if ($accessTokenInfo) {
               $accessToken  = $accessTokenInfo['oauth_token'];

               if(count($evernote_notebooks) > 0){
                    //更新
               		$evernote_notebooks[0]->token = $accessToken;
               		$evernote_notebooks[0]->save();
               }else{
                    // 新規作成
                    $evernote = new EvernoteNotebook;
                    $evernote->userid = $loginUser->id;
                    $evernote->token = $accessToken;
                    $evernote->save();
               }
        }

        return $this->redirectOriginalUrl($request);
    }

    public function deleteNote(Request $request,$titleid){
        // evernoteに書き込み
        $this->deleteWishListToEvernote(Auth::user()->id,$titleid);
        EvernoteNote::where('isbn','wishlist_'.$titleid)->delete();
    }

    function redirectOriginalUrl(Request $request){
        // 元のページを表示
        $redirectUrl = $request->cookie('accessUrl');
        $redirectUrl = str_replace($this->getHostUrl(),"",$redirectUrl);
        $redirectUrl = str_replace("#evernoteDialog", "", $redirectUrl);
        return redirect(substr($redirectUrl[0],1));    	
    }



}
