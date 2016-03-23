<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class NoteController extends Controller
{
    
    public function item(){
    	$res = $this->amazonItemLookup('4121023617');
    	return view('pages.note',$res);
    }

	public function search(){
    	$results = $this->amazonItemSearch('米澤　古典部');
    	$data = [];
    	$data["results"] = $results;
    	return view('pages.search',$data);
    }
}
