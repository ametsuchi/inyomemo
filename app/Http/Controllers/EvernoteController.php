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


class EvernoteController extends Controller
{
	public $noteBookStore = null;
	
	/**
	 * Evernoteに書き込み（通常投稿）.
	 * 
	 */
	public function writingNoteToEvernote(Request $request){
		$loginUser = Auth::user();
        $evernote_notebooks = EvernoteNotebook::where('userid',$loginUser->id)->get();

        if(count($evernote_notebooks) == 0){
        	return;
        }


		$isbn = $request->input("isbn");
		$title = $request->input("title");
		$author = $request->input("author");

   		$notebookGuid = $this->getNotebookGuid($request);
   		$guid = $this->getNote($isbn,$title,$author,$notebookGuid);
   		return $guid;
   }
   





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
     * @return guid
     */
    public function getNotebookGuid(Request $request){

    		$user = Auth::user();
    		$evernote_notebooks = EvernoteNotebook::where('userid',Auth::user()->id)->get();
	    	$accessToken = $evernote_notebooks[0]->token;
	    	$notebookGuid = $evernote_notebooks[0]->notebook_guid;


    		$store = $this->getNoteStore();
    		if(empty($store)){
    			return;
    		}
            $notebooks = $store->listNotebooks();
            if(!empty($notebookGuid)){
	            try{
    	        	$store->getNotebook($notebookGuid);
        	    }catch (EDAMNotFoundException $e){
            		// ノートブックがない場合新規作成
            		$evernote_notebook = EvernoteNotebook::where('userid',$user->id)->get();

					$notebookGuid = $this->createNotebook($store,$accessToken,$user,$evernote_notebook[0]);
            	}
            }else{
        		$evernote_notebook = EvernoteNotebook::where('userid',$user->id)->get();
				$notebookGuid = $this->createNotebook($store,$accessToken,$user,$evernote_notebook[0]);
           }
            return $notebookGuid;
    }

    /**
     * ノートを更新（一般投稿）
     *
     * @param $isbn,$title,$authour,$親ノートブックのＧＵＩＤ
     * @param $note->guid
     */
    public function getNote($isbn,$title,$authour,$parentNotebookGuid){
    	$noteIsbnLink = EvernoteNote::where('userid',Auth::user()->id)
    					->where('isbn',$isbn)->get();

    	$guid = null;
    	$store = $this->getNoteStore();
    	$evernote_notebooks = EvernoteNotebook::where('userid',Auth::user()->id)->get();
    	$accessToken = $evernote_notebooks[0]->token;

    	if(count($noteIsbnLink) > 0){
    		$guid = $noteIsbnLink[0]->note_guid;
    	}else{
    		$guid = "";
    	}

    	$tags = array();
    	$tags[] = "bikm";
    	$tags[] = $title;
    	$tags[] = $authour;

    	// 本文作成
    	$content = $this->createContent($isbn);
    	// Evernoteにノート更新
    	$newGuid = $this->updateNote($guid,$parentNotebookGuid,$title,$tags,$content);


    	// DBに紐づけを保存
    	EvernoteNote::where('userid',Auth::user()->id)
    				->where('isbn',$isbn)->delete();
    	$isbnlink = new EvernoteNote;
    	$isbnlink->userid = Auth::user()->id;
    	$isbnlink->isbn = $isbn;
    	$isbnlink->note_guid =$newGuid;
    	$isbnlink->save();

    	return $newGuid;

    }




    // non - public

    /**
     * NoteStore Objectを取得
     *
     * @return notestore
     */
    function getNoteStore(){
    	if($this->noteBookStore != null){
    		return $this->noteBookStore;
    	}

    	if(!Auth::check()){
    			Log::error("[EVERNOTE] don't login.");
    			return;
    	}

        $user = Auth::user();
        $evernote_notebook = EvernoteNotebook::where('userid',$user->id)->get();

        if(count($evernote_notebook) == 0){
           	Log::error("[EVERNOTE] don't get accessToken for DB.(userid:".$user->id.")");
            	return;
        }

        $accessToken = $evernote_notebook[0]->token;
        $notebookGuid = $evernote_notebook[0]->notebook_guid;
        $client = new Client(array(
           'token' => $accessToken,
           'sandbox' => true
        ));
        $this->noteBookStore = $client->getNoteStore();
        return $this->noteBookStore;
    }

    /**
     * host urlを取得
     * 
     * @return host url
     */
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
  	function createNotebook($store,$accessToken,$user){
    	$newNotebook = new Notebook();
        $newNotebook->name = "bkim_notebook_test";
        $createdNotebook = $store->createNotebook($accessToken,$newNotebook);
        $notebookGuid = $createdNotebook->guid;
        // DBに保存
        $evernote_notebook = EvernoteNotebook::where('userid',Auth::user()->id)->get();
        $evernote_notebook[0]->notebook_guid = $notebookGuid;
        $evernote_notebook[0]->save();
        return $notebookGuid;
    }


    /**
     * Evernoteのノート更新処理.
     *
     * @param isbn,title,ノートブックのguid
     */
    function updateNote($noteGuid,$parentNotebookGuid,$title,$tags,$content){
    	$store = $this->getNoteStore();
    	$evernote_notebooks = EvernoteNotebook::where('userid',Auth::user()->id)->get();
    	$accessToken = $evernote_notebooks[0]->token;

    	// 既存のノート検索

    	if(!empty($noteGuid)){
    		$oldNote = $store->getNote($accessToken, $noteGuid, true, true, false, false);
    		if(empty($oldNote)){
    			$noteGuid = "";
    		}else{
			// 既存のノートの更新の場合はタイトルとタグを既存の優先
    			$tags = $oldNote->tagNames;
    			$title = $oldNote->title;
    		}
    	}
    	// ノート更新
		$newNote = new Note();

		$newNote->title = $title;
		$nBody = '<?xml version="1.0" encoding="UTF-8"?>';
    	$nBody .= '<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd">';
    	$nBody .= '<en-note>'.$content.'</en-note>';
     	$newNote->content = $nBody;
     	$newNote->notebookGuid = $parentNotebookGuid;
     	$newNote->tagNames = $tags;
     	$noteAttributes = new NoteAttributes;
     	$noteAttributes->contentClass = "readonly"; //　読み取り専用にするため適当な文字列を設定
     	$newNote->attributes = $noteAttributes;
     	if($noteGuid != ""){
     		$newNote->guid = $noteGuid;
     	}

		// Evernote作成/更新処理
		if(empty($noteGuid)){
		    $note = $store->createNote($newNote);
		}else{
			$note = $store->updateNote($newNote);
		}

    	return $note->guid;
    }

    function createContent($isbn){
    	$user = Auth::user();
    	$notes = DBNote::where('userid',$user->id)
    			->where('isbn',$isbn)->orderby('id','desc')->get();

    	// 本
    	$content = '<h3>'.$notes[0]->title.'</h3>';
    	$content .= '<div>'.$notes[0]->author.'</div><br/>';
    	$content .= '<img src="'.$notes[0]->image_url.'"></img><br/><br/>';
    	$content .= '<a href="'.$notes[0]->amazon_url.'">amazonの詳細ページ</a><br/>';
    	$content .= '<a href="'.$this->getHostUrl().'/memo/'.$isbn.'">bkimでこのページを編集</a><br/>';
    	$content .= '<br/><hr></hr>';
    	//メモ
    	foreach ($notes as $note) {
    		if($note->page != 0){
    			$content .= '<div>P.'.$note->page.'</div>';
    		}
    		if(!empty($note->quote)){
    			$content .= '<blockquote>'.nl2br($note->quote).'</blockquote>';
    		}
    		$content .= '<div>'.nl2br($note->note).'</div>';
    		$content .= '<div style="text-align:right">'.date_format($note->created_at,'Y/m/d H:i').'</div>';
    		$content .= '<hr></hr>';
    	}

    	return $content;
    }




}
