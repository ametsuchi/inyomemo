<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use App\Wishlist;
use App\WishListTitle;
use Auth;
use DB;

class WishListController extends Controller
{
    
    function getTotalPages($titleid,$userid){
        $result = 0;
        // 件数
        $count = DB::table('wishlists')
                            ->where('userid',$userid)
                            ->where('titleid',$titleid)
                            ->count();
        if ($count % 10 == 0){
            $result = $count / 10;
        }else{
            $result = floor($count / 10) + 1;
        }
        return $result;
    }
    /**
     * 表示
     *
     * @return view
     **/
    public function show($titleid,$page = 1){
        $user = Auth::user();
        $data = [];
        $data["titleid"] = $titleid;

        $titleList = WishListTitle::where('userid','=',$user->id)
                    ->orderBy('id')
                    ->get();

        // 件数
        $data["totalPages"] = $this->getTotalPages($titleid,$user->id);
        
        $list = array();
        $list[] = [0,"未分類"];
        foreach ($titleList as $item) {
            $list[] = [$item->id,$item->name];
        }
        $data["list"] = $list;
        if($titleid == 0){
            $data["selectedName"] = "未分類";
        }else{

            $data["selectedName"] = "";
            foreach ($titleList as $wishlistTitle) {
                if($titleid == $wishlistTitle->id){
                    $data["selectedName"] = $wishlistTitle->name;
                    break;
                }
            }
        }

        // 取得件数
        $take = 10;
        $skip = $page * $take - $take;

        // ＤＢからレコード取得
        $wishlists = DB::table('wishlists')
                    ->where('userid',$user->id)
                    ->where('titleid',$titleid)
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
     * ほしいものリストに追加 + Ｅｖｅｒｎｏｔｅ書き込み
     * @return void
     **/
    public function addWishList(Request $request){
    	// ユーザー情報
        $user = Auth::user();

        // name
        $name = $request->input('name');
        // 新規か否か
        $new = $request->input('new_flg');

        // リストタイトル
        $titleid = 0;
        if ($new == "false"){
            $listTitle = WishListTitle::where('userid','=',$user->id)
                        ->where('name','=',$name)
                        ->get();
            if(count($listTitle) > 0){
                $titleid = $listTitle[0]->id;
            }
        }else{

            // 新規作成
            $count = WishListTitle::where('userid','=',$user->id)
                        ->where('renamed_flg',false)
                        ->count();
            
            $name = "新しいリスト";
            if($count > 0){
                $name .= "(".$count.")";
            }
            $wishlistTitle = new WishListTitle;
            $wishlistTitle->userid = $user->id;
            $wishlistTitle->name = $name;
            $wishlistTitle->renamed_flg = false;
            $wishlistTitle->save();
            // id取得
            $createdList = WishListTitle::where('userid','=',$user->id)
                        ->orderBy('created_at','desc')
                        ->take(1)
                        ->get();
            if(count($createdList) > 0){
                $titleid = $createdList[0]->id;
            }
        }

    	// Amazonへのアクセス回数を極力減らしたいのでPostでもういっかいもらう。
    	$wishlist = new Wishlist;
    	$wishlist->userid = $user->id;
    	$wishlist->isbn = $request->input('isbn');
    	$wishlist->title = $request->input('title');
    	$wishlist->author = $request->input('author');
    	$wishlist->image_url = $request->input('imageUrl');
    	$wishlist->amazon_url = $request->input('amazonUrl');
    	$wishlist->publication_date = $request->input('publicationDate');
        $wishlist->titleid = $titleid;

    	$wishlist->save();

        // evernoteに書き込み
        $this->writingWishListToEvernote($user->id,$name,$titleid);
    }

    /**
     * ほしいものリストから削除 + Ｅｖｅｒｎｏｔｅ書き込み
     * @return void
     **/
    public function deleteFromWishList(Request $request){
    	// ユーザー情報
    	$user = Auth::user();

        $name = "";
        if($request->input('titleid') == 0){
            $name = "未分類";
        }else{
            $listTitle = WishListTitle::find($request->input('titleid'))->get();
            $name = $listTitle[0]->name;
        }

    	$wishlist = Wishlist::where('userid',$user->id)
    	->where('isbn',$request->input('isbn'))
        ->where('titleid',$request->input('titleid'))
    	->delete();

        // evernoteに書き込み
        $this->writingWishListToEvernote($user->id,$name,$request->input('titleid'));
    }

    /**
     * ほしいものリストのタイトルを変更
     * 
     *
     *　@return void
     **/
    public function renameTitle(Request $request){
        $user = Auth::user();
        $lists = WishListTitle::where('userid','=',$user->id)
                        ->get();
        $name = $request->input("name");
        $titleid = $request->input("titleid");

        if(empty($titleid) || empty($name)){
            // エラー
            return;
        }


        $title = WishListTitle::find($titleid);
        $title->name = $name;
        $title->renamed_flg = true;
        $title->save();

        // evernoteに書き込み
        $this->writingWishListToEvernote($user->id,$name,$titleid);
    }


    /**
     * ほしいものリストを削除
     * @return void
     **/
    public function deleteWishList(Request $request){
        // ユーザー情報
        $user = Auth::user();

        $titleid = $request->input("titleid");

        if(empty($titleid)){
            // エラー
            return;
        }

        $title = WishListTitle::find($titleid);
        $title->delete();

        $wishlists = Wishlist::where('userid','=',$user->id)
                    ->where('titleid','=',$titleid)
                    ->delete();
    }

}
