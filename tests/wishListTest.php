<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class wishListTest extends TestCase
{

	// DBをトランザクション
	use DatabaseTransactions;

	/**
	 * 表示確認
	 *
	 **/
    public function testShow(){
        $user = factory(App\User::class)->create();
        $user->id = -1;

        // 未分類
        $noCategory = $this->makeDataWishlist(0);
        // 1
        $category1Title = $this->makeDataWishTitle();
        $category1 = $this->makeDataWishlist($category1Title->id);

        // 操作
        $this->actingAs($user)
        	->visit("/wishlist/0")
        	->see("未分類")
        	->see($noCategory->title)
        	->click("wishlist".$category1Title->id)
        	->see($category1Title->name)
        	->see($category1->title);
    }
    /** ページャー
     *
     **/
    public function testPage(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;

        // 未分類
        $noCategory = $this->makeDataWishlist(0);
        for($i = 0;$i<8;$i++){
        	$this->makeDataWishlist(0);
        }
        $noCategory10 = $this->makeDataWishlist(0);
        $noCategory11 = $this->makeDataWishlist(0);

        // 操作
        $this->actingAs($user)
        	->visit("/wishlist/0")
        	->see($noCategory11->title)
        	->see("<span>1</span>")
    		->see('<a href="/wishlist/0/2">2</a>')
    		->see('<a href="/wishlist/0/2">次 »</a>')
    		->click('次 »')
    		->see($noCategory->title)
    		->see("<span>2</span>")
    		->see('<a href="/wishlist/0/1">1</a>')
    		->see('<a href="/wishlist/0/1">« 前</a>');
    }

    /** ページャー
     * (not 未分類)
     **/
    public function testPageCategory(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        $category1Title = $this->makeDataWishTitle();
        $id = $category1Title->id;
        $noCategory = $this->makeDataWishlist($id);
        for($i = 0;$i<8;$i++){
        	$this->makeDataWishlist($id);
        }
        $noCategory10 = $this->makeDataWishlist($id);
        $noCategory11 = $this->makeDataWishlist($id);

        // 操作
        $this->actingAs($user)
        	->visit("/wishlist/".$id)
        	->see($noCategory11->title)
        	->see("<span>1</span>")
    		->see('<a href="/wishlist/'.$id.'/2">2</a>')
    		->see('<a href="/wishlist/'.$id.'/2">次 »</a>')
    		->click('次 »')
    		->see($noCategory->title)
    		->see("<span>2</span>")
    		->see('<a href="/wishlist/'.$id.'/1">1</a>')
    		->see('<a href="/wishlist/'.$id.'/1">« 前</a>');
    }

    /**
     * 存在しないid
     **/
    public function testErrorId(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        // 未分類
        $noCategory = $this->makeDataWishlist(0);


        // 操作
        $this->actingAs($user)
        	->visit("/wishlist/100")
        	->see("リスト名")
        	->click("wishlist0")
        	->see($noCategory->title);
    }

    /**
     * 存在しないpage
     **/
    public function testErrorPage(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        // 未分類
        $noCategory = $this->makeDataWishlist(0);

        // 操作
        $this->actingAs($user)
        	->visit("/wishlist/0/10")
        	->see("リスト名");
    }

    // 追加（未分類)
    public function testAddNocategory(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        // データ
   		$name = "未分類";
   		$new_flg = "false";
   		$isbn ='999';
   		$title = 'title_12';
    	$author = 'authour_1';
    	$imageUrl = 'http://image.com';
    	$amazonUrl = 'http://amazon.com';
    	$publicationDate = '2015/01/01';


    	// 操作
    	$this->actingAs($user)
    		->post("/wishlist/add",
    			[
    			"name" => $name,
    			"new_flg" => $new_flg,
    			"isbn" => $isbn,
    			"title" => $title,
    			"author" => $author,
    			"imageUrl" => $imageUrl,
    			"amazonUrl" => $amazonUrl,
    			"publicationDate" => $publicationDate
    		]);

    	// 確認
    	$this->seeInDatabase('wishlists',
    			["isbn" => $isbn,
    			"title" => $title,
    			"author" => $author,
    			"image_url" => $imageUrl,
    			"amazon_url" => $amazonUrl,
    			"publication_date" => $publicationDate,
    			"userid" => -1,
    			"titleid" => 0]
    		);
    }

    // 追加（既存の）
    public function testAddCategory(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        // データ
        $category1Title = $this->makeDataWishTitle();
   		$name = $category1Title->name;
   		$new_flg = "false";
   		$isbn ='999';
   		$title = 'title_12';
    	$author = 'authour_1';
    	$imageUrl = 'http://image.com';
    	$amazonUrl = 'http://amazon.com';
    	$publicationDate = '2015/01/01';


    	// 操作
    	$this->actingAs($user)
    		->post("/wishlist/add",
    			[
    			"name" => $name,
    			"new_flg" => $new_flg,
    			"isbn" => $isbn,
    			"title" => $title,
    			"author" => $author,
    			"imageUrl" => $imageUrl,
    			"amazonUrl" => $amazonUrl,
    			"publicationDate" => $publicationDate
    		]);

    	// 確認
    	$this->seeInDatabase('wishlists',
    			["isbn" => $isbn,
    			"title" => $title,
    			"author" => $author,
    			"image_url" => $imageUrl,
    			"amazon_url" => $amazonUrl,
    			"publication_date" => $publicationDate,
    			"userid" => -1,
    			"titleid" => $category1Title->id]
    		);
    }
    // 追加（新規）
    public function testAddNew(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        // データ
   		$name = "新規";
   		$new_flg = "true";
   		$isbn ='999';
   		$title = 'title_12';
    	$author = 'authour_1';
    	$imageUrl = 'http://image.com';
    	$amazonUrl = 'http://amazon.com';
    	$publicationDate = '2015/01/01';


    	// 操作
    	$this->actingAs($user)
    		->post("/wishlist/add",
    			[
    			"name" => $name,
    			"new_flg" => $new_flg,
    			"isbn" => $isbn,
    			"title" => $title,
    			"author" => $author,
    			"imageUrl" => $imageUrl,
    			"amazonUrl" => $amazonUrl,
    			"publicationDate" => $publicationDate
    		]);

    	$lists = App\WishListTitle::where('userid',$user->id)
    					->where('name',"新しいリスト")
    					->where('renamed_flg',false)->get();

    	// 確認
    	$this->seeInDatabase('wishlists',
    			["isbn" => $isbn,
    			"title" => $title,
    			"author" => $author,
    			"image_url" => $imageUrl,
    			"amazon_url" => $amazonUrl,
    			"publication_date" => $publicationDate,
    			"userid" => -1,
    			"titleid" => $lists[0]->id]
    		);
    }

    // 名前変える
    public function testRename(){
    	$item = new App\WishListTitle;

    	$item->userid = -1;
    	$item->name = $this->makeRandStr(5);
    	$item->renamed_flg = false;
    	$item->save();

    	$user = factory(App\User::class)->create();
        $user->id = -1;

        $name ="change";

		$this->actingAs($user)
    		->get("/wishlist/rename?titleid=".$item->id."&name=".$name);
    	info($item->id);

    	$this->seeInDatabase('wishlisttitles',[
    		'id' => $item->id,'name' => $name,"renamed_flg" => true]);
    }
    
    // 削除(未分類)
    public function testDeleteFromNonCategory(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        // 未分類
        $noCategory = $this->makeDataWishlist(0);
        // ほかのデータ。
        $wish = new App\Wishlist;

        $wish->isbn = $noCategory->isbn;
        $wish->userid = -1;
        $wish->title = "title".$this->makeRandStr(4);
        $wish->author = "author".$this->makeRandStr(3);
        $wish->image_url = "http://dummy/".$this->makeRandStr(3);
        $wish->amazon_url = "http://amazon".$this->makeRandStr(3);
        $wish->titleid = 1;

        $wish->save();

        $this->actingAs($user)
        	->post("/wishlist/delete",["titleid" => 0,"isbn" => $noCategory->isbn]);

        // 確認
        $this->dontSeeInDatabase('wishlists',
        	["userid" => -1,"titleid" => 0,"isbn" => $noCategory->isbn])
        ->seeInDatabase('wishlists',
        	["userid" => -1,"titleid" => 1,"isbn" => $noCategory->isbn]); 	

    }
    // 削除(not 未分類)
    public function testDeleteFromCategory(){
    	$user = factory(App\User::class)->create();
        $user->id = -1;
        // 未分類
        $noCategory = $this->makeDataWishlist(0);
        // ほかのデータ。
        $wish = new App\Wishlist;

        $wish->isbn = $noCategory->isbn;
        $wish->userid = -1;
        $wish->title = "title".$this->makeRandStr(4);
        $wish->author = "author".$this->makeRandStr(3);
        $wish->image_url = "http://dummy/".$this->makeRandStr(3);
        $wish->amazon_url = "http://amazon".$this->makeRandStr(3);
        $wish->titleid = 1;

        $wish->save();

        $this->actingAs($user)
        	->post("/wishlist/delete",["titleid" => 1,"isbn" => $noCategory->isbn]);

        // 確認
        $this->seeInDatabase('wishlists',
        	["userid" => -1,"titleid" => 0,"isbn" => $noCategory->isbn])
        ->dontSeeInDatabase('wishlists',
        	["userid" => -1,"titleid" => 1,"isbn" => $noCategory->isbn]); 	

    }

    // リスト削除
    public function testDeleteList(){

 		$list = $this->makeDataWishTitle();

        $wish = $this->makeDataWishlist($list->id);
    	
    	$user = factory(App\User::class)->create();
        $user->id = -1;

        $this->actingAs($user)
        	->get("/wishlist/deletelist?titleid=".$list->id);

        // 確認
        $this->dontSeeInDatabase("wishlisttitles",["userid" => $user->id,"id" => $list->id])
        ->dontSeeInDatabase("wishlists",["userid" => -1,"titleid" => $list->id]);
    }


    /**
     * ダミーデータつくって保存(Wishlist)
     *
     * @return Wishlist Object.
     */
    protected function makeDataWishlist($titleid){
        $wish = new App\Wishlist;

        $wish->isbn = $this->makeRandStr(6);
        $wish->userid = -1;
        $wish->title = "title".$this->makeRandStr(4);
        $wish->author = "author".$this->makeRandStr(3);
        $wish->image_url = "http://dummy/".$this->makeRandStr(3);
        $wish->amazon_url = "http://amazon".$this->makeRandStr(3);
        $wish->titleid = $titleid;

        $wish->save();

        return $wish;
    }

    protected function makeDataWishTitle(){
    	$item = new App\WishListTitle;

    	$item->userid = -1;
    	$item->name = $this->makeRandStr(5);
    	$item->save();

    	return $item;
    }

}
