<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Evernote\Client;
use Auth;
use App\User;

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
        //　ユーザーテーブルに追加
        // 認証を取り消された場合は空文字をそのままぶっこむ
        $loginUser = Auth::user();
        $user = User::find($loginUser->id);
        $user->evernote_token = $oauth_verifier;
        $user->save();

        $redirectUrl = $request->session()->pull('accessUrl');
        $redirectUrl = str_replace($this->getHostUrl(),"",$redirectUrl);
        return redirect(substr($redirectUrl[0],1));
    }



    function getHostUrl(){
    	$thisUrl = (empty($_SERVER['HTTPS'])) ? "http://" : "https://";
        $thisUrl .= $_SERVER['SERVER_NAME'];
        $thisUrl .= ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? "" : (":".$_SERVER['SERVER_PORT']);
        return $thisUrl;
    }
}
