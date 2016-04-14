<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use App\Wishlist;
use Auth;

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

    	$results = $this->amazonItemSearch($keyword,$page);
    	$data = [];
    	if(count($results) > 0){
	    	$data["results"] = $results["items"];
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

    	return view('pages.search',$data);
    }

}
