<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Note;
use DB;

class ArchiveController extends Controller
{
    public function show($page = 1){
        $user = Auth::user();
        $data = [];

        // 取得件数
        $take = 10;
        $skip = $page * $take - $take;


        // 件数
        $all = DB::table('notes')
        					->distinct()
        					->select('isbn')
                            ->where('userid',$user->id)
                            ->get();
        $count = count($all);

        if ($count % $take == 0){
            $data["totalPages"] = $count / $take;
        }else{
            $data["totalPages"] = floor($count / $take) + 1;
        }


        // ＤＢからレコード取得
        $notes = DB::table('notes')
        			->distinct()
        			->select('isbn','title','author','image_url')
                    ->where('userid',$user->id)
                    ->orderBy('id','desc')
                    ->skip($skip)
                    ->take($take)
                    ->get();

        if(count($notes) > 0){
            $data["results"] = $notes;

            // ページャーがforだとsyntax errorになるので配列に入れてやる
            $pages = array();
            for($i=1;$i<$data["totalPages"]+1 ;$i++){
                array_push($pages,$i);
            }

            $data["pages"] = $pages;
            $data["currentPage"] = $page;


        }else{
            $data["results"] = [];
            $data["pages"] = [];
            $data["currentPage"] = $page;
        }

        // ユーザー
        $data["user"] = $user;
        return view('pages.archive',$data);
    }

}
