<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use App\Wishlist;
use Auth;
use DB;

class WishListController extends Controller
{
    
    public function show($page = 1){
        $user = Auth::user();
        $data = [];

        // 件数
        $count = DB::table('wishlists')
                            ->where('userid',$user->id)
                            ->count();
        if ($count % 10 == 0){
            $data["totalPages"] = $count / 10;
        }else{
            $data["totalPages"] = floor($count / 10) + 1;
            info($data["totalPages"]);
        }


        // 取得件数r
        $take = 10;
        $skip = $page * $take - $take;

        // ＤＢからレコード取得
        $wishlists = DB::table('wishlists')
                    ->where('userid',$user->id)
                    ->orderBy('id','desc')
                    ->skip($skip)
                    ->take($take)
                    ->get();

        if(count($wishlists) > 0){
            $data["results"] = $wishlists;

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
        return view('pages.wishlist',$data);
    }

        /**
     *
     */
    public function addWishList(Request $request){
    	// ユーザー情報
        $user = Auth::user();


    	// Amazonへのアクセス回数を極力減らしたいのでPostでもういっかいもらう。
    	$wishlist = new Wishlist;
    	$wishlist->userid = $user->id;
    	$wishlist->isbn = $request->input('isbn');
    	$wishlist->title = $request->input('title');
    	$wishlist->author = $request->input('author');
    	$wishlist->image_url = $request->input('imageUrl');
    	$wishlist->amazon_url = $request->input('amazonUrl');
    	$wishlist->publication_date = $request->input('publicationDate');

    	$wishlist->save();
    }

    public function deleteFromWishList(Request $request){
    	// ユーザー情報
    	$user = Auth::user();

    	$wishlist = Wishlist::where('userid',$user->id)
    	->where('isbn',$request->input('isbn'))
    	->delete();
    }

}
