<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemoTest extends TestCase
{
	// DBをテスト用にマイグレーション
	use DatabaseTransactions;
    /**
     * 認証確認.
     *
     * @return void
     */
    public function testIsLogin()
    {
    	$user = factory(App\User::class)->create();

    	$this->actingAs($user)
    		->visit('/home')
    		->see('MEMO');
    }

    /**
     * 認証してない場合、ログイン画面に飛ばす
     *
     * @return void
     */
    public function testFailLogin()
    {

    	$this->visit('/home')
    		->seePageIs('/auth/login');
    }


    /**
     * 既存のメモありの表示
     *
     * @return void
     */
    public function testShowWritedMemo(){
    	// テスト用ダミーデータ
    	$testUserId = -1;
    	$testIsbn = '1234';
    	$testTitle = 'テストタイトル';
    	$testAuthor = '作者名';
    	$testQuote = '引用部分';
    	$testNote = 'メモ部分';
    	$testImageUrl = 'http://dummy.image.1234';
    	$testAmazonUrl = 'http://dummy.amazon.1234';

    	$note = new App\Note;
 		$note->userId = $testUserId;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $testQuote;
 		$note->note = $testNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;

 		$note->save();


    	$user = factory(App\User::class)->create();
    	$user->id = -1;

    	$this->actingAs($user)
    		->visit('/memo/'.$testIsbn)
    		->see($testTitle)
    		->see($testAuthor)
    		->see($testQuote)
    		->see($testNote)
    		->see($testImageUrl)
    		->see($testAmazonUrl);
    }


    public function testEdit(){
    	
    }

}
