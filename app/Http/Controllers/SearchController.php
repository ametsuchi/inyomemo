<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use App\Wishlist;
use Auth;
use DB;

class SearchController extends Controller
{
	
	/**
	 * 検索欄からの検索をGetに直す 
	 * (JS使うとテストしにくいので)
	 * @return /search
	 */
	public function postSearch(Request $request){
		$keyword = $request->input('keyword');
		return redirect('/search?keyword='.$keyword);
    }


    /**
     * 検索結果を表示
     * 
     * @return 
     */
	public function search(Request $request){
		$keyword = $request->input('keyword');
		$page = $request->input('page',1);


        if(empty($keyword)){
            $data["results"] = [];
            $data["totalPages"] = 0;
            $data["pages"] = [];
            $data["keyword"] = $keyword;
            $data["currentPage"] = $page;
            $data["user"] = Auth::user();
            $data["message"] = "キーワードを入力してください";
            return view('pages.search',$data);
        }

    	$results = $this->amazonItemSearch($keyword,$page);
    	$data = [];
    	if(count($results) > 0){
            $wishlists = DB::table('wishlists')
                            ->where('userid',"=",Auth::user()->id)
                            ->lists('isbn');
            $items = array();
            foreach ($results["items"] as $item) {
                $newItem = array();

                if(in_array($item["isbn"], $wishlists)){
                    $newItem["add"] = "add";
                }else{
                    $newItem["add"] = "";
                }
                // ?
                $newItem["title"] = $item["title"];
                $newItem["author"] = $item["author"];
                $newItem["image"] = $item["image"];
                $newItem["isbn"] = $item["isbn"];
                $newItem["publicationDate"] = $item["publicationDate"];
                $newItem["url"] = $item["url"];

                $items[] = $newItem;
            }

	    	$data["results"] = $items;
	    	$data["totalPages"] = $results["totalPages"];

    		// ページャーがforだとsyntax errorになるので配列に入れてやる
	    	$pages = array();
    		for($i=1;$i<$results["totalPages"]+1 ;$i++){
	    		array_push($pages,$i);
    		}

	    	$data["pages"] = $pages;
    		$data["keyword"] = $keyword;
    		$data["currentPage"] = $page;


    	}else{
    		// Todo:Amazonの戻り値、検索結果0と制限引っかかったときの区別つく？
	    	$data["results"] = [];
	    	$data["totalPages"] = 0;
	    	$data["pages"] = [];
    		$data["keyword"] = $keyword;
    		$data["currentPage"] = $page;
    	}
        // ユーザー
        $data["user"] = Auth::user();
        $data["message"] = "";
    	return view('pages.search',$data);
    }

}
