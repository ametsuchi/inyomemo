<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

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


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Evernoteのnotebookstore Object.
    public $noteBookStore = null;


    // amazon
    protected function amazonItemLookup($isbn)
    {
    	$params = array();
 
    	// 必須
    	$access_key_id = env('AMAZON_ACCESS_ID','');
    	$secret_access_key = env('AMAZON_SECRET_ACCESS_KEY','');
    	$params['AssociateTag'] = env('ASSOCIATE_TAG','');
    	$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
 
    	// パラメータ
    	$params['Service'] = 'AWSECommerceService';
    	$params['AWSAccessKeyId'] = $access_key_id;
    	$params['Version'] = '2011-08-01';
    	$params['Operation'] = 'ItemLookup';
    	$params['ItemId'] = $isbn;
    	$params['ResponseGroup'] = 'ItemAttributes,Images';
    	$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
    	ksort($params);
 
    	// 送信用URL・シグネチャ作成
    	$canonical_string = '';
    	foreach ($params as $k => $v) {
        	$canonical_string .= '&' . $this->urlencode_RFC3986($k) . '=' . $this->urlencode_RFC3986($v);
    	}
    	$canonical_string = substr($canonical_string, 1);
    	$parsed_url = parse_url($baseurl);
    	$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
    	$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret_access_key, true));
    	$url = $baseurl . '?' . $canonical_string . '&Signature=' . $this->urlencode_RFC3986($signature);
 
    	$amazon_xml = $this->requestbook($url);
    	$xmlObj = simplexml_load_string($amazon_xml);
    	$data = get_object_vars($xmlObj);
    	
    	if (!(array_key_exists("Items", $data))){
    		info("not data.");
    		return array();
    	}

    	$item = $data["Items"]->Item;

        // 著者が複数ある場合あり
        $author = "";
        foreach ($item->ItemAttributes->Author as $value) {
            $author = $author.",".$value;
        }
        $author = mb_substr($author, 1);

    	$results = array();
    	$results['title'] = $item->ItemAttributes->Title;
    	$results['author'] = $author;
    	$results['image'] = $item->LargeImage->URL;
    	$results['mimage'] = $item->MediumImage->URL;
    	$results['simage'] = $item->SmallImage->URL;
        $results['url'] = $item->DetailPageURL;
    	return $results;
    }

    protected function amazonItemSearch($str,$page)
    {
    	$params = array();
 
    	// 必須
    	$access_key_id = env('AMAZON_ACCESS_ID','');
    	$secret_access_key = env('AMAZON_SECRET_ACCESS_KEY','');
    	$params['AssociateTag'] = env('ASSOCIATE_TAG','');
    	$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
 
    	// パラメータ
    	$params['Service'] = 'AWSECommerceService';
    	$params['AWSAccessKeyId'] = $access_key_id;
    	$params['Version'] = '2011-08-01';
    	$params['Operation'] = 'ItemSearch';
    	$params['SearchIndex'] = 'Books';
    	$params['Keywords'] = $str;
    	$params['ItemPage'] = $page;
    	$params['ResponseGroup'] = 'ItemAttributes,Images';
    	$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
    	// とりあえずKindle除外
    	$params['Power'] = "binding:not kindle";
    	ksort($params);
 
    	// 送信用URL・シグネチャ作成
    	$canonical_string = '';
    	foreach ($params as $k => $v) {
        	$canonical_string .= '&' . $this->urlencode_RFC3986($k) . '=' . $this->urlencode_RFC3986($v);
    	}
    	$canonical_string = substr($canonical_string, 1);
    	$parsed_url = parse_url($baseurl);
    	$string_to_sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$canonical_string}";
    	$signature = base64_encode(hash_hmac('sha256', $string_to_sign, $secret_access_key, true));
    	$url = $baseurl . '?' . $canonical_string . '&Signature=' . $this->urlencode_RFC3986($signature);
 
    	$amazon_xml = $this->requestbook($url);

    	$xmlObj = simplexml_load_string($amazon_xml);
    	$data = get_object_vars($xmlObj);
    	
    	if (!(array_key_exists("Items", $data))){
    		info("not data.");
    		return array();
    	}
        // 検索結果
    	$items = array();
        // 検索結果のＩＳＢＮのセット
        $isbns = array();
    	foreach ($data["Items"]->Item as $item) {
    		$result = array();
    		$result["title"] = $item->ItemAttributes->Title;
    		$result["author"] = $item->ItemAttributes->Author;
    		$result["image"] = $item->MediumImage->URL;
    		$result["isbn"] = $item->ItemAttributes->ISBN;
            // ISBNがない商品はASINをセット
            if($result["isbn"] == ""){
                $result["isbn"] = $item->ASIN;
            }
            $result["publicationDate"] = $item->ItemAttributes->PublicationDate;
            $result["url"] = $item->DetailPageURL;
    		// MediumImageになかった場合はImageSetsに入ってるはず
    		/*
    		if($result["image"] == null){
    			if(count($item->ImageSets->ImageSet) > 0){
	    			foreach ($item->ImageSets->ImageSet as $imageset) {
    					if($imageset["Category"] == "primary"){
    						$result["image"] = $imageset->MediumImage->URL;
    					}
    				}
    			}
    		}
    		// Category=variantしかないぞ…？
    		if($result["image"] == null){
    			if(count($item->ImageSets->ImageSet) > 0){
    				$result["image"] = $item->ImageSets->ImageSet->MediumImage->URL;
    			}
    		}*/
    		array_push($items,$result);
            array_push($isbns,$result["isbn"]);
    	}
        $results = [];
        $results["items"] = $items;
        $results["isbns"] = $isbns;
    	$results["totalPages"] = intval($data["Items"]->TotalPages);
    	return $results;
    }

    protected function getCallbackUrl()
    {
        $thisUrl = (empty($_SERVER['HTTPS'])) ? "http://" : "https://";
        $thisUrl .= $_SERVER['SERVER_NAME'];
        $thisUrl .= ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? "" : (":".$_SERVER['SERVER_PORT']);
        $thisUrl .= "/evernote/callback";

        return $thisUrl;
    }


    function urlencode_RFC3986($str)
	{
    	return str_replace('%7E', '~', rawurlencode($str));
	}
 
	function requestbook($url){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    	$response = curl_exec($ch);
    	curl_close($ch);
     	return $response;
	}


    // Evernote関連
    //       ほんとはファサード作ったほうがよいのだろうけど。。。。。
    /**
    * ほしいものリストをＥｖｅｒｎｏｔｅに書き込み 
    */
    protected function writingWishListToEvernote($userid,$name,$titleid){
        $evernote_notebooks = EvernoteNotebook::where('userid',$userid)->get();

        Log::debug("evernote wishlist :start");
        if(count($evernote_notebooks) == 0 || empty($evernote_notebooks[0]->token)){
            Log::debug("evernote wishlist :empty");
            return;
        }
        $notebookGuid = $this->getNotebookGuid();
        $guid = $this->updateWishList($notebookGuid,$name,$titleid);

        return $guid;
    }

    protected function deleteWishListToEvernote($userid,$titleid){
        $evernote_notebooks = EvernoteNotebook::where('userid',$userid)->get();

        if(count($evernote_notebooks) == 0 || empty($evernote_notebooks[0]->token)){
            Log::debug("evernote wishlist :empty");
            return;
        }
        $notebookGuid = $this->getNotebookGuid();
        // 削除
        $titleGuid = EvernoteNote::where('userid',$userid)
                    ->where('isbn','wishlist_'.$titleid)
                    ->select('note_guid')
                    ->get();
        $store = $this->getNoteStore();
        if(count($titleGuid) == 0){
            return;
        }
        //noteStore.deleteNote(authToken, created2.getGuid());
        info($titleGuid[0]->note_guid);
        $store->deleteNote($evernote_notebooks[0]->token,$titleGuid[0]->note_guid);
    }

    /**
     * ノートブックのGUIDを取得
     *
     * @return guid
     */
    protected function getNotebookGuid(){

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
        $newGuid = $this->updateNote($guid,$parentNotebookGuid,$title,$tags,$content,false);


        // DBに紐づけを保存
        $links =  EvernoteNote::where('userid',Auth::user()->id)
                    ->where('isbn',$isbn)->get();
        $isbnlink = new EvernoteNote;
        if(count($links) > 0){
            $isbnlink = $links[0];            
        }
        $isbnlink->userid = Auth::user()->id;
        $isbnlink->isbn = $isbn;
        $isbnlink->note_guid =$newGuid;
        $isbnlink->save();

        return $newGuid;

    }



    /**
     * ノートを更新（ほしいものリスト）
     *
     * @param $親ノートブックのＧＵＩＤ
     * @param $リストの名前
     * @param $wishListTitleId
     */
    protected function updateWishList($parentNotebookGuid,$name,$titleid){
        Log::debug("evernote update wishlist");

        $noteIsbnLink = EvernoteNote::where('userid',Auth::user()->id)
                        ->where('isbn','wishlist_'.$titleid)->get();

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
        $tags[] = "ほしいものリスト"; // タグにリスト名は入れない。

        // タイトル 
        $noteTitle = "ほしいものリスト（".$name."）";
        // 本文作成
        $content = $this->createContentForWishlist($titleid);
        // Evernoteにノート更新
        $newGuid = $this->updateNote($guid,$parentNotebookGuid,$noteTitle,$tags,$content,true);

        info("evernote update wishlist:note write");

        // DBに紐づけを保存
        // isbn を 「wishlist_xxx」にする
        $links = EvernoteNote::where('userid',Auth::user()->id)
                    ->where('isbn','wishlist_'.$titleid)->get();
        $isbnlink = new EvernoteNote;
        if(count($links) > 0){
            $isbnlink = $links[0];
        }
        $isbnlink->userid = Auth::user()->id;
        $isbnlink->isbn = 'wishlist_'.$titleid;
        $isbnlink->note_guid =$newGuid;
        $isbnlink->save();

        Log::debug("evernote update wishlist:db save.");
        return $newGuid;

    }



    /**
     * NoteStore Objectを取得
     *
     * @return notestore
     */
    protected function getNoteStore(){
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
           'sandbox' => env('EVERNOTE_SANDBOX')
        ));
        $this->noteBookStore = $client->getNoteStore();
        return $this->noteBookStore;
    }

    /**
     * host urlを取得
     * 
     * @return host url
     */
    protected function getHostUrl(){
        $thisUrl = (empty($_SERVER['HTTPS'])) ? "http://" : "https://";
        $thisUrl .= $_SERVER['SERVER_NAME'];
        $thisUrl .= ($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443) ? "" : (":".$_SERVER['SERVER_PORT']);
        return $thisUrl;
    }

// todo;;;;evernoteから既存のノートブック引っ張ってくる。
    /**
     * 新規にノートブックを作成
     *
     * @return guid
     */
    protected function createNotebook($store,$accessToken,$user){
        $newNotebook = new Notebook();
        $newNotebook->name = "bkim_notebook_backup";
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
    protected function updateNote($noteGuid,$parentNotebookGuid,$title,$tags,$content,$isWishList){
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
                if(!$isWishList){
                    $title = $oldNote->title;
                }
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

    /** 
     * ほしいものリスト用の本文生成
     *
     * @return Ｅｖｅｒｎｏｔｅ本文
     **/
    protected function createContentForWishlist($titleid){
        $user = Auth::user();
        $wishlists = Wishlist::where('userid',$user->id)->where('titleid',$titleid)->orderby('id','desc')->get();

        $content = "";
        $content .= '<a href="'.$this->getHostUrl().'/wishlist/show'.'">bkimでこのページを編集</a><br/>';
        $content .= '<hr></hr>';
        foreach ($wishlists as $wishlist) {
            $content .= '<h3>'.$wishlist->title.'</h3>';
            $content .= '<div>'.$wishlist->author.'</div><br/>';
            $content .= '<img src="'.$wishlist->image_url.'"></img><br/><br/>';
            $content .= '<a href="'.$wishlist->amazon_url.'">amazonの詳細ページ</a><br/>';
            $content .= '<hr></hr>';
        }
        return $content;
    }

    protected function createContent($isbn){
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
