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
    	$notes = Note::where('userid',0)
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
    				// 同一の本はいっこにまとめる
    				unset($notes[$i]);
    			}
    		}else{
    			$notes[$i]->comments = $comments;
    			$comments = [];
    		}
    	}

    				info($notes[2]->comments);

    	$res['notes'] = $notes;

        // ユーザー情報
        $res['avatar'] = null;// 適当な一時画像をつかう
        $res['name'] = "";
        $user = Auth::user();
        $res['avatar'] = $user->avatar;
        $res['name'] = $user->name;

    	return view('pages.home',$res);
    }
}
