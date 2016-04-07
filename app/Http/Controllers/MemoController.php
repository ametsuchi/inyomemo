<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use Auth;

class MemoController extends Controller
{
    public function index(Request $request)
    {	
        // ユーザー情報
        $user = Auth::user();


    	$notes = Note::where('userid',$user->id)
    	->orderBy('created_at','desc')
    	->take(10)
    	->get();

    	// 同一本への投稿が連続している場合まとめる
    	$comments = [];
    	$count = count($notes);

    	for($i=0;$i<$count;$i++){
    		// コメントセット生成
    		$comment = [];
    		$comment["comment"] = "".$notes[$i]->quote." ".$notes[$i]->note;
    		$comment["created_at"] = $notes[$i]->created_at;
    		$comments[] = $comment;
    		if($i < $count-1){
    			if($notes[$i]->isbn != $notes[$i+1]->isbn){
    				$notes[$i]->comments = $comments;
    				$comments = [];
    			}else{
    				unset($notes[$i]);
    			}
    		}else{
    			$notes[$i]->comments = $comments;
    			$comments = [];
    		}
    	}

    	$res['notes'] = $notes;

        // ユーザー情報
        $res['avatar'] = null;// 適当な一時画像をつかう
        $res['name'] = "";
        $res['avatar'] = $user->avatar;
        $res['name'] = $user->name;

    	return view('pages.home',$res);
    }

    public function show($isbn){
    	$res = [];
    	$res['isbn'] = $isbn;

        // ユーザー情報
        $user = Auth::user();

    	// 既存メモ
    	$showNotes = Note::where('userid',$user->id)
    	->where('isbn',$isbn)
    	->orderBy('created_at','desc')
    	->get();

    	$res['notes'] = $showNotes; 

    	$image_url = "";
    	$title = "";
    	$author = "";
        $amazon_url = "";

    	if(count($showNotes) > 0){
    		$title = $showNotes[0]->title;
    		$author = $showNotes[0]->author;
    		$image_url = $showNotes[0]->image_url;
            $amazon_url = $showNotes[0]->amazon_url;
    	}

    	// // Amazon書籍情報
    	// $amazon = $this->amazonItemLookup($isbn);

    	$res['title'] = $title;
    	$res['author'] = $author;
    	$res['image_url'] = $image_url;
        $res['amazon_url'] = $amazon_url;
 

    	return view('pages.memo',$res);
    }

    public function edit(Request $request){
        // varidationとくになし
        $user = Auth::user();
        $note = new Note;
         
        $note->userid = $user->id;
        $note->isbn = $request->input("isbn");
        $note->title = $request->input("title");
        $note->author = $request->input("author");
        $note->quote = $request->input("quote");
        $note->note = $request->input("note");
        $note->image_url = $request->input("image_url");
        $note->amazon_url = $request->input("amazon_url");
        $note->save();

        return redirect("/memo/".$request->input("isbn"));
    }
}
