<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemoTest extends TestCase
{
	// DBをトランザクション
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
    		->see($testAmazonUrl)
            ->dontSee("P.0");
    }

    /**
     * 既存のメモありの表示(ページあり)
     *
     * @return void
     */
    public function testShowWritedMemoPage(){
        // テスト用ダミーデータ
        $testUserId = -1;
        $testIsbn = '12345678';
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
        $note->page = 100;

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
            ->see($testAmazonUrl)
            ->see("P.100");
    }

    /**
     * Amazonから書籍情報を取得して表示
     *
     * @return void
     */
    public function testShowForAmazon(){

        // テスト用ダミーデータ
        $testUserId = -100;
        $testIsbn = '4121023560';
        $testTitle = 'イタリア現代史 - 第二次世界大戦からベルルスコーニ後まで (中公新書)';
        $testAuthor = '伊藤 武';
        $testImageUrl = "http://ecx.images-amazon.com/images/I/51wlPxGYMRL._SX100_.jpg";
        $testAmazonUrl = "http://www.amazon.co.jp/%E3%82%A4%E3%82%BF%E3%83%AA%E3%82%A2%E7%8F%BE%E4%BB%A3%E5%8F%B2-%E7%AC%AC%E4%BA%8C%E6%AC%A1%E4%B8%96%E7%95%8C%E5%A4%A7%E6%88%A6%E3%81%8B%E3%82%89%E3%83%99%E3%83%AB%E3%83%AB%E3%82%B9%E3%82%B3%E3%83%BC%E3%83%8B%E5%BE%8C%E3%81%BE%E3%81%A7-%E4%B8%AD%E5%85%AC%E6%96%B0%E6%9B%B8-%E4%BC%8A%E8%97%A4-%E6%AD%A6/dp/4121023560%3FSubscriptionId%3DAKIAJWHI7FEI3OTZSXWQ%26tag%3Dmomongaa88-22%26linkCode%3Dxm2%26camp%3D2025%26creative%3D165953%26creativeASIN%3D4121023560";


        $user = factory(App\User::class)->create();
        $user->id = -100;

        $this->actingAs($user)
            ->visit('/memo/'.$testIsbn)
            ->see($testTitle)
            ->see($testAuthor)
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
            ->post("/memo/post",["page"=>0,
                        "quote" => $testQuote,
                        "note" => $testNote,
                        "title" => $testTitle,
                        "author" => $testAuthor,
                        "image_url" => $testImageUrl,
                        "amazon_url" => $testAmazonUrl,
                        "isbn" => $testIsbn]);

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
            ->post("/memo/post",["page"=>0,
                        "quote" => "",
                        "note" => $testNote,
                        "title" => $testTitle,
                        "author" => $testAuthor,
                        "image_url" => $testImageUrl,
                        "amazon_url" => $testAmazonUrl,
                        "isbn" => $testIsbn]);

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
            ->post("/memo/post",["page"=>0,
                        "quote" => "",
                        "note" => "",
                        "title" => $testTitle,
                        "author" => $testAuthor,
                        "image_url" => $testImageUrl,
                        "amazon_url" => $testAmazonUrl,
                        "isbn" => $testIsbn]);

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
            ->post("/memo/post",["page"=>0,
                        "quote" => $testQuote,
                        "note" => "",
                        "title" => $testTitle,
                        "author" => $testAuthor,
                        "image_url" => $testImageUrl,
                        "amazon_url" => $testAmazonUrl,
                        "isbn" => $testIsbn]);

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
