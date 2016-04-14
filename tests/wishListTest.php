<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class wishListTest extends TestCase
{

	// DBをトランザクション
	use DatabaseTransactions;

    /**
     * addwishListのテスト
     *
     * @return void
     */
    public function testAddWishList()
    {
    	//テストデータ
    	$testIsbn = "1234";
    	$testTitle = "めんどくさいテストを投げださないためには";
    	$testAuthor = "テストおじさん";
    	$testImageUrl = "http://dummy.com/aaa.jpag";
    	$testAmazonUrl = "http://amazon.dummy/1234";
    	$testDate = "2015/04/10";
    	$testUserId = -1;

    	$user = new App\User;
    	$user->id = -1;

    	// 操作
        $this->actingAs($user)
        ->post('/wishlist/add',
        	["isbn"=>$testIsbn,
        	"title"=>$testTitle,
        	"author"=>$testAuthor,
        	"imageUrl"=>$testImageUrl,
        	"amazonUrl"=>$testAmazonUrl,
        	"publicationDate"=>$testDate,
        	"_token"=>Session::token()]);
        // 確認
        $this->seeInDatabase('wishlists',
        	['userid'=>$testUserId,
        	'isbn'=>$testIsbn,
        	'title'=>$testTitle,
        	'author'=>$testAuthor,
        	'image_url'=>$testImageUrl,
        	'amazon_url'=>$testAmazonUrl,
        	'publication_date'=>$testDate]);
    }


    /**
     * deleteWishListのテスト
     *
     * @return void
     */
    public function testDeleteWishList()
    {
    	//テストデータ
    	$testIsbn = "1234";
    	$testTitle = "めんどくさいテストを投げださないためには";
    	$testAuthor = "テストおじさん";
    	$testImageUrl = "http://dummy.com/aaa.jpag";
    	$testAmazonUrl = "http://amazon.dummy/1234";
    	$testDate = "2015/04/10";
    	$testUserId = -1;

    	$user = new App\User;
    	$user->id = -1;


    	// テストデーターを保存
    	$testTargetData = new App\Wishlist;
    	$testTargetData->userid = $testUserId;
    	$testTargetData->title = $testTitle;
    	$testTargetData->author = $testAuthor;
    	$testTargetData->image_url = $testImageUrl;
    	$testTargetData->amazon_url = $testAmazonUrl;
    	$testTargetData->publication_date = $testDate;

    	$testTargetData->save();

    	// ダミーデータ
    	//  ISBN違い
    	$dummy1 = new App\Wishlist;
    	$dummy1->userid = $testUserId;
    	$dummy1->isbn = "9999";
    	$dummy1->title = $testTitle;
    	$dummy1->author = $testAuthor;
    	$dummy1->image_url = $testImageUrl;
    	$dummy1->amazon_url = $testAmazonUrl;
    	$dummy1->publication_date = $testDate;
    	$dummy1->save();
    	// ダミー2
    	//  ユーザーID違い
    	$dummy2 = new App\Wishlist;
    	$dummy2->userid = "999";
    	$dummy2->isbn = $testIsbn;
    	$dummy2->title = $testTitle;
    	$dummy2->author = $testAuthor;
    	$dummy2->image_url = $testImageUrl;
    	$dummy2->amazon_url = $testAmazonUrl;
    	$dummy2->publication_date = $testDate;
    	$dummy2->save();

    	// 操作
        $this->actingAs($user)
        ->post('/wishlist/delete',
        	["isbn"=>$testIsbn,
        	  "_token"=>Session::token()]);
        // 確認
        $this->dontSeeInDatabase('wishlists',
        	['userid'=>$testUserId,
        	'isbn'=>$testIsbn,
        	'title'=>$testTitle,
        	'author'=>$testAuthor,
        	'image_url'=>$testImageUrl,
        	'amazon_url'=>$testAmazonUrl,
        	'publication_date'=>$testDate]);
        // dummy1のデータが残っていること
        $this->seeInDatabase('wishlists',
        	['userid'=>$testUserId,
        	'isbn'=>'9999']);
        // dummy2のデータが残っていること
        $this->seeInDatabase('wishlists',
        	['userid'=>"999",
        	'isbn'=>$testIsbn]);

    }

}
