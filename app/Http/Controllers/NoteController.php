<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class NoteController extends Controller
{
    
    public function item($isbn){
    	$res = $this->amazonItemLookup($isbn);
    	return view('pages.note',$res);
    }

	public function search($keyword,$page = "1"){
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
}
