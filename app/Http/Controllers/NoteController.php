<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Note;
use Auth;

class NoteController extends Controller
{
    
    public function item($isbn){
    	// Amazon書籍情報
    	$res = $this->amazonItemLookup($isbn);
    	$res['isbn'] = $isbn;

    	// 既存メモ
    	$showNotes = Note::where('userid',0)
    	->where('isbn',$isbn)
    	->orderBy('created_at','desc')
    	->get();
    	$res['notes'] = $showNotes; 

    	return view('pages.note',$res);
    }

    public function search(Request $request){
		$keyword = $request->input('keyword');
		return redirect('/searchbooklists?keyword='.$keyword);
    }

	public function searchBookLists(Request $request,$page = "1"){
		$keyword = $request->input('keyword');

    	$results = $this->amazonItemSearch($keyword,$page);
    	$data = [];
    	$data["results"] = $results;

    	// ページャーがforだとsyntax errorになるので配列に入れてやる
    	$pages = array();
    	for($i=1;$i<$results["totalPages"]+1 ;$i++){
    		array_push($pages,$i);
    	}
    	$data["pages"] = $pages;
    	$data["keyword"] = $keyword;
    	return view('pages.search',$data);
    }

    public function index(Request $request){
    	
    	$notes = Note::where('userid',0)
    	->orderBy('created_at','desc')
    	->take(10)
    	->get();

    	$images = array();
    	// amazonから書影を取得
    	foreach ($notes as $note) {
            $image = "";
    		if(in_array($note->isbn,$images)){
	    		$image = $images[$note->isbn];
	    	}else{

    			$amazon = $this->amazonItemLookup($note->isbn);
    			// Todo: データ取れなかった時の処理
    			if(array_key_exists("mimage", $amazon)){
                    $image = $amazon["mimage"];
                    $images['isbn'] = $image;
                }
    		}
    		$note->image = $image;
    	}

    	$res['notes'] = $notes;

        // ユーザー情報
        $res['avatar'] = null;// 適当な一時画像をつかう
        $res['name'] = "";
        $user = Auth::user();
        $res['avatar'] = $user->avatar;
        $res['name'] = $user->name;

    	return view('pages.index',$res);
    }

    public function notesubmit(Request $request){
		 $note = new Note;
		 

		 $note->userid = 0;// とりあえず
		 $note->isbn = $request->input("isbn");
		 $note->title = $request->input("title");
		 $note->author = $request->input("author");
		 $note->page = $request->input("page");
		 $note->quote = $request->input("quote");
		 $note->note = $request->input("note");
         $note->image_url = $request->input("image");
		 $note->save();

		 $show = Note::all();
		 redirect("/index");
    }

    public function logout(){
        Auth::logout();
    }
}
