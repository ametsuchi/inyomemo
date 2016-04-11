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


    /**
     * 投稿
     * 
     * @return void
     */
    public function testEdit(){
    	// ユーザー
    	$user = factory(App\User::class)->create();

    	// テスト用データ
    	// 既存のメモ
    	$testIsbn = '1234567';
    	$testTitle = 'テストタイトル';
    	$testAuthor = '作者名';
    	$dummyQuote = '引用部分（既存)';
    	$dummyNote = 'メモ部分(既存)';
    	$testImageUrl = 'http://dummy.image.1234567';
    	$testAmazonUrl = 'http://dummy.amazon.1234567';

    	$testQuote = "引用部分（新規）";
    	$testNote = "引用部分（新規）";

    	$note = new App\Note;
 		$note->userId = $user->id;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $dummyQuote;
 		$note->note = $dummyNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;

 		$note->save();


    	// 操作

    	$this->actingAs($user)
    	->visit('/memo/'.$testIsbn)
    	->type($testQuote,'quote')
    	->type($testNote,'note')
    	->press('save');

    	// DBの値確認
    	$this->seeInDatabase('notes',
    		['title' => $testTitle,
    		 'author' => $testAuthor,
    		 'isbn' => $testIsbn,
    		 'quote' => $testQuote,
    		 'note' => $testNote,
    		 'image_url' => $testImageUrl,
    		 'amazon_url' => $testAmazonUrl,
    		 'userid' => $user->id
    		 ]
    		);
    }


    /**
     * 引用部分なしの投稿
     * 
     * @return void
     */
    public function testEditNoQuote(){
    	// ユーザー
    	$user = factory(App\User::class)->create();

    	// テスト用データ
    	// 既存のメモ
    	$testIsbn = '1234567';
    	$testTitle = 'テストタイトル';
    	$testAuthor = '作者名';
    	$dummyQuote = '引用部分（既存)';
    	$dummyNote = 'メモ部分(既存)';
    	$testImageUrl = 'http://dummy.image.1234567';
    	$testAmazonUrl = 'http://dummy.amazon.1234567';

    	$testNote = "引用部分（新規）";

    	$note = new App\Note;
 		$note->userId = $user->id;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $dummyQuote;
 		$note->note = $dummyNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;

 		$note->save();


    	// 操作

    	$this->actingAs($user)
    	->visit('/memo/'.$testIsbn)
    	->type($testNote,'note')
    	->press('save');

    	// DBの値確認
    	$this->seeInDatabase('notes',
    		['title' => $testTitle,
    		 'author' => $testAuthor,
    		 'isbn' => $testIsbn,
    		 'quote' => "",
    		 'note' => $testNote,
    		 'image_url' => $testImageUrl,
    		 'amazon_url' => $testAmazonUrl,
    		 'userid' => $user->id
    		 ]
    		);
    }


    /**
     * 引用部分,メモ部分なしの投稿
     * 
     * @return void
     */
    public function testEditNoQuoteAndNoNote(){
    	// ユーザー
    	$user = factory(App\User::class)->create();

    	// テスト用データ
    	// 既存のメモ
    	$testIsbn = '1234567';
    	$testTitle = 'テストタイトル';
    	$testAuthor = '作者名';
    	$dummyQuote = '引用部分（既存)';
    	$dummyNote = 'メモ部分(既存)';
    	$testImageUrl = 'http://dummy.image.1234567';
    	$testAmazonUrl = 'http://dummy.amazon.1234567';


    	$note = new App\Note;
 		$note->userId = $user->id;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $dummyQuote;
 		$note->note = $dummyNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;

 		$note->save();


    	// 操作

    	$this->actingAs($user)
    	->visit('/memo/'.$testIsbn)
    	->press('save');

    	// DBの値確認
    	$this->seeInDatabase('notes',
    		['title' => $testTitle,
    		 'author' => $testAuthor,
    		 'isbn' => $testIsbn,
    		 'quote' => "",
    		 'note' => "",
    		 'image_url' => $testImageUrl,
    		 'amazon_url' => $testAmazonUrl,
    		 'userid' => $user->id
    		 ]
    		);
    }


    /**
     * メモ部分なしの投稿
     * 
     * @return void
     */
    public function testEditNoNote(){
    	// ユーザー
    	$user = factory(App\User::class)->create();

    	// テスト用データ
    	// 既存のメモ
    	$testIsbn = '1234567';
    	$testTitle = 'テストタイトル';
    	$testAuthor = '作者名';
    	$dummyQuote = '引用部分（既存)';
    	$dummyNote = 'メモ部分(既存)';
    	$testImageUrl = 'http://dummy.image.1234567';
    	$testAmazonUrl = 'http://dummy.amazon.1234567';
    	$testQuote = "引用部分（新規）";

    	$note = new App\Note;
 		$note->userId = $user->id;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $dummyQuote;
 		$note->note = $dummyNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;

 		$note->save();


    	// 操作

    	$this->actingAs($user)
    	->visit('/memo/'.$testIsbn)
    	->type($testQuote,'quote')
    	->press('save');

    	// DBの値確認
    	$this->seeInDatabase('notes',
    		['title' => $testTitle,
    		 'author' => $testAuthor,
    		 'isbn' => $testIsbn,
    		 'quote' => $testQuote,
    		 'note' => "",
    		 'image_url' => $testImageUrl,
    		 'amazon_url' => $testAmazonUrl,
    		 'userid' => $user->id
    		 ]
    		);
    }



}
