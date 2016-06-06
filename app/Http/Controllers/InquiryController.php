<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Inquiry;
use Auth;

class InquiryController extends Controller
{
    public function index(){
    	$data["msg"] = "";
    	return view('pages.inquiry',$data);
    }


    public function post(Request $request){
    	$inquiry = new Inquiry;

    	$inquiry->type = $request->input("type");
    	$inquiry->comment = $request->input("comment");

    	if (Auth::check()) {
    		$inquiry->userid = Auth::user()->id;
    	}

    	$inquiry->save();

    	$data["msg"] = "送信しました。ブラウザバックでお戻りください";
    	return view('pages.inquiry',$data);
    }

}
