<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use Auth;
use DB;

class MemoController extends Controller
{

    public function index(Request $request)
    {	
        // ユーザー情報
        $user = Auth::user();

        // $query = "select title,author,image_url,max(id),isbn";
        // $query .= "     from notes ";
        // $query .= "     where userid = ?";
        // $query .= "     group by title,author,image_url,isbn";
        // $query .= "     order by max(id) desc";
        // $query .= "     limit 10";

        // $params = array();
        // $params[] = $user->id;
        // $notes = DB::select($query,$params);

        $notes = DB::table('notes')
                    ->select(DB::raw('title,author,image_url,max(id),isbn'))
                    ->where('userid','=',$user->id)
                    ->groupBy('title','author','image_url','isbn')
                    ->orderBy('max(id)','desc')
                    ->take(10)
                    ->get();

    	$res['notes'] = $notes;

        // ユーザー情報
        $res['user'] = $user;

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
            // 
    		$title = $showNotes[0]->title;
    		$author = $showNotes[0]->author;
    		$image_url = $showNotes[0]->image_url;
            $amazon_url = $showNotes[0]->amazon_url;
    	}else {

            // Amazonから書籍情報取得
            $amazon = $this->amazonItemLookup($isbn);

            $title = $amazon['title'];
            $author = $amazon['author'];
            $image_url = $amazon['mimage'];
            $amazon_url = $amazon['url'];
        }

    	$res['title'] = $title;
    	$res['author'] = $author;
    	$res['image_url'] = $image_url;
        $res['amazon_url'] = $amazon_url;

        // ユーザー
        $res['user'] = $user;
 

    	return view('pages.memo',$res);
    }

    public function post(Request $request){
        // varidationとくになし
        $user = Auth::user();
        $note = new Note;
         
        $note->userid = $user->id;
        $note->isbn = $request->input("isbn");
        $note->title = $request->input("title");
        $note->author = $request->input("author");
        $note->quote = $request->input("quote");
        $note->note = $request->input("note");
        $note->image_url = trim($request->input("image_url"));
        $note->amazon_url = $request->input("amazon_url");
        $note->page = $request->input("page");
        $note->save();

        // 先ほどのid取得
        $savedNote = Note::where('userid',$user->id)
                    ->orderBy('created_at','desc')
                    ->take(1)
                    ->get();
        return $savedNote[0]->id;
    }

    public function delete($id){
        $note = Note::find($id);
        $note->delete();
    }

    public function edit($id){
        $note = Note::find($id);
        $data = [];
        $data["page"] = $note->page;
        if($note->page == 0){
            $data["page"] = "";
        }
        $data["quote"] = $note->quote;
        $data["note"] = $note->note;
        $data["id"] = $note->id;
        $data["isbn"] = $note->isbn;
        $data["title"] = $note->title;
        $data["author"] = $note->author;
        // ユーザー情報
        $data["user"] = Auth::user();

        return view("pages.edit",$data);
    }

    public function update(Request $request,$id){
        $note = Note::find($id);
        $note->page = $request->input("page");
        $note->quote = $request->input("quote");
        $note->note = $request->input("note");
        $note->save();

        return redirect("/memo/".$note->isbn);
    }
}
