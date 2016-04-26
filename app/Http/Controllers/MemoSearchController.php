<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Note;
use DB;

class MemoSearchController extends Controller
{
    public function search(Request $request,$page = 1){
    	$keyword = $request->input("keyword");

    	if(empty($keyword)){
			$data["notes"] = [];
			$data["user"] = Auth::user();
			$data["keyword"] = "";
			$data["message"] = "";
    	return view('pages.memosearch',$data);
    	}

    	$user = Auth::user();
    	//
    	$query = 'select distinct isbn,title,author,image_url from notes where userid = ? and (';
    	$array =  preg_split('/[\s|\x{3000}]+/u', $keyword);
    	$params = array();
    	$params[] = $user->id;
    	foreach ($array as $param) {
    		$query .= '(';
    		$query .= ' title like ? or';
    		$query .= ' author like ? or';
    		$query .= ' quote like ? or';
    		$query .= ' note like ? ) and';
			
			$params[] = "%".$param."%";
			$params[] = "%".$param."%";
			$params[] = "%".$param."%";
			$params[] = "%".$param."%";
    	}

    	$query = substr($query, 0,-3);
    	info($query);
    	$query .= ')';

		$notes = DB::select($query,$params);


		// ぺジネーション関係
		$take = 30;
		$count = count($notes);
		if ($count % $take == 0){
            $data["totalPages"] = $count / $take;
        }else{
            $data["totalPages"] = floor($count / $take) + 1;
        }
       	$pages = array();
    	for($i=1;$i<$data["totalPages"]+1 ;$i++){
	    	array_push($pages,$i);
    	}

    	$results = array();
    	for($j = $page*$take-$take;$j<$page*$take;$j++){
    		if(array_key_exists($j, $notes)){
				$results[] = $notes[$j];    			
    		}else{
    			break;
    		}
    	}

	    $data["pages"] = $pages;
    	$data["currentPage"] = $page;

		$data["notes"] = $results;
		$data["user"] = $user;
		$data["keyword"] = $keyword;
		$data["message"] = "";

		return view('pages.memosearch',$data);

    }
}
