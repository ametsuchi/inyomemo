<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemoDeleteEditTest extends TestCase
{
    /**
     * 削除
     *
     */
    public function testDelete(){
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
        $savedNote = App\Note::where('userid',-1)
                    ->orderBy('created_at','desc')
                    ->take(1)
                    ->get();

        // 事前確認
        $this->seeInDatabase('notes',
            ['id' => $savedNote[0]->id]
            );
        // 操作
        $this->get("/memo/delete/".$savedNote[0]->id)
            ->seeInDatabase('notes',["id" => $savedNote[0]->id]);
    }

    /**
     * 編集
     */
    public function testEditShow(){
    	    	// ユーザー
    	$user = factory(App\User::class)->create();

    	// テスト用データ
    	// 既存のメモ
    	$testIsbn = '1234567';
    	$testTitle = 'テストタイトル';
    	$testAuthor = '作者名';
    	$testQuote = '引用部分（既存)';
    	$testNote = 'メモ部分(既存)';
    	$testImageUrl = 'http://dummy.image.1234567';
    	$testAmazonUrl = 'http://dummy.amazon.1234567';

    	$note = new App\Note;
 		$note->userId = $user->id;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $testQuote;
 		$note->note = $testNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;
 		$note->page = 100;

 		$note->save();
 		$savedNote = App\Note::where('userid',$user->id)
                    ->orderBy('created_at','desc')
                    ->take(1)
                    ->get();
        $id = $savedNote[0]->id;


    	// 操作
        $this->actingAs($user)
        	 ->visit("/memo/edit/".$id)
        	 ->see($testQuote)
        	 ->see($testNote)
        	 ->see("100");
    }

    /**
     * 編集
     */
    public function testEditPost(){
    	    	// ユーザー
    	$user = factory(App\User::class)->create();

    	// テスト用データ
    	// 既存のメモ
    	$testIsbn = '1234567';
    	$testTitle = 'テストタイトル';
    	$testAuthor = '作者名';
    	$testQuote = '引用部分（既存)';
    	$testNote = 'メモ部分(既存)';
    	$testImageUrl = 'http://dummy.image.1234567';
    	$testAmazonUrl = 'http://dummy.amazon.1234567';

    	$note = new App\Note;
 		$note->userId = $user->id;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $testQuote;
 		$note->note = $testNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;

 		$note->save();
 		$savedNote = App\Note::where('userid',$user->id)
                    ->orderBy('created_at','desc')
                    ->take(1)
                    ->get();
        $id = $savedNote[0]->id;


    	// 操作
        $updateNote = "note（更新）";
        $updateQuote = "引用（更新）";
        $updatePage = 99;

        $this->actingAs($user)
        	 ->post("/memo/edit/".$id."/save",[
        	 	"note" => $updateNote,
        	 	"quote" => $updateQuote,
        	 	"page" => $updatePage
        	 	]);

       	// 確認
       	$this->seeInDatabase('notes',[
       		"id" => $id,
       		"isbn" => $testIsbn,
       		"title" => $testTitle,
       		"author" => $testAuthor,
       		"image_url" => $testImageUrl,
       		"amazon_url" => $testAmazonUrl,
       		"note" => $updateNote,
       		"quote" => $updateQuote,
       		"page" => $updatePage
       		]
       		);
    }
}
