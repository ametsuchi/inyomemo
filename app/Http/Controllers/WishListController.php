<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use App\Wishlist;
use Auth;

class WishListController extends Controller
{
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
