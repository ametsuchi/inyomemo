<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MemoSearchTest extends TestCase
{
	// DBをトランザクション
	use DatabaseTransactions;
    
    /**
     * 初期表示
     *
     * @return void
     */
    public function testShow()
    {
    	// データ準備
    	$note = $this->makeData();

    	$user = factory(App\User::class)->create();
    	$user->id = -1;
        
        $this->actingAs($user)
        	->visit("/wordsearch")
        	->see("過去のメモから")
        	->dontSee($note->title);
   		// 何も表示しない
    }

    /**
     * １件検索(title)
     *
     * @return void
     */
    public function test1Title(){
    	// データ準備
    	$noteTarget = $this->makeData();
    	$noteDummy1 = $this->makeData();
    	$noteDummy2 = $this->makeData();
    	$noteDummy3 = $this->makeData();

    	// 違うユーザー
    	$noteDiffUser = new App\Note;
    	$noteDiffUser->userid = 10;
    	$noteDiffUser->isbn = $this->makeRandStr(5);
    	$noteDiffUser->title = $noteTarget->title;
    	$noteDiffUser->save();

    	$user = factory(App\User::class)->create();
    	$user->id = -1;

   		// 操作
	   	$this->actingAs($user)
   			->visit("/wordsearch")
   			->type($noteTarget->title,"keyword")
   			->press("search")
   			->see($noteTarget->title)
   			->see($noteTarget->author)
   			->see($noteTarget->image_url)
   			->see('<a href="/memo/'.$noteTarget->isbn.'">')
   			->dontSee($noteDummy1->title)
   			->dontSee($noteDiffUser)
   			->see("<span>1</span>");

    }

    /**
     * １件検索(author)
     *
     * @return void
     */
    public function test1Author(){
    	// データ準備
    	$noteTarget = $this->makeData();
    	$noteDummy1 = $this->makeData();
    	$noteDummy2 = $this->makeData();
    	$noteDummy3 = $this->makeData();

    	// 違うユーザー
    	$noteDiffUser = new App\Note;
    	$noteDiffUser->userid = 10;
    	$noteDiffUser->isbn = $this->makeRandStr(5);
    	$noteDiffUser->author = $noteTarget->author;
    	$noteDiffUser->save();

    	$user = factory(App\User::class)->create();
    	$user->id = -1;

   		// 操作
	   	$this->actingAs($user)
   			->visit("/wordsearch")
   			->type($noteTarget->author,"keyword")
   			->press("search")
   			->see($noteTarget->title)
   			->see($noteTarget->author)
   			->see($noteTarget->image_url)
   			->see('<a href="/memo/'.$noteTarget->isbn.'">')
   			->dontSee($noteDummy1->title)
   			->dontSee($noteDiffUser)
   			->see("<span>1</span>");

    }

    /**
     * １件検索(note)
     *
     * @return void
     */
    public function test1Note(){
    	// データ準備
    	$noteTarget = $this->makeData();
    	$noteDummy1 = $this->makeData();
    	$noteDummy2 = $this->makeData();
    	$noteDummy3 = $this->makeData();

    	// 違うユーザー
    	$noteDiffUser = new App\Note;
    	$noteDiffUser->userid = 10;
    	$noteDiffUser->isbn = $this->makeRandStr(5);
    	$noteDiffUser->note = $noteTarget->note;
    	$noteDiffUser->save();

    	$user = factory(App\User::class)->create();
    	$user->id = -1;

   		// 操作
	   	$this->actingAs($user)
   			->visit("/wordsearch")
   			->type($noteTarget->note,"keyword")
   			->press("search")
   			->see($noteTarget->title)
   			->see($noteTarget->author)
   			->see($noteTarget->image_url)
   			->see('<a href="/memo/'.$noteTarget->isbn.'">')
   			->dontSee($noteDummy1->title)
   			->dontSee($noteDiffUser)
   			->see("<span>1</span>");

    }

    /**
     * １件検索(quote)
     *
     * @return void
     */
    public function test1Quote(){
    	// データ準備
    	$noteTarget = $this->makeData();
    	$noteDummy1 = $this->makeData();
    	$noteDummy2 = $this->makeData();
    	$noteDummy3 = $this->makeData();

    	// 違うユーザー
    	$noteDiffUser = new App\Note;
    	$noteDiffUser->userid = 10;
    	$noteDiffUser->isbn = $this->makeRandStr(5);
    	$noteDiffUser->quote = $noteTarget->quote;
    	$noteDiffUser->save();

    	$user = factory(App\User::class)->create();
    	$user->id = -1;

   		// 操作
	   	$this->actingAs($user)
   			->visit("/wordsearch")
   			->type($noteTarget->quote,"keyword")
   			->press("search")
   			->see($noteTarget->title)
   			->see($noteTarget->author)
   			->see($noteTarget->image_url)
   			->see('<a href="/memo/'.$noteTarget->isbn.'">')
   			->dontSee($noteDummy1->title)
   			->dontSee($noteDiffUser)
   			->see("<span>1</span>");

    }

    /**
     * ページャー
     *
     * @return void
     **/
    public function testPager(){
    	$user = factory(App\User::class)->create();
    	$user->id = -1;
    	// 2ページ（1ページ30件)
    	$note1 = $this->makeDataNoSave();
    	$note1->save();
    	for($i = 0;$i < 28;$i++){
    		$data = $this->makeDataNoSave();
    		$data->note = $note1->note;
    		$data->save();
    	}
    	$note30 = $this->makeDataNoSave();
    	$note30->note = $note1->note;
    	$note30->save();
    	$note31 = $this->makeDataNoSave();
    	$note31->note = $note1->note;
    	$note31->save();

    	// 操作
    	$this->actingAs($user)
    		->visit("/wordsearch")
   			->type($note1->note,"keyword")
   			->press("search")
   			->see($note30->title)
   			->see($note31->title)
   			->see("<span>1</span>")
    		->see('<a href="/wordsearch?keyword='.$note1->note.'&amp;page=2">2</a>')
    		->see('<a href="/wordsearch?keyword='.$note1->note.'&amp;page=2">次 »</a>')
    		->click('次 »')
    		->see($note1->title)
    		->see("<span>2</span>")
    		->see('<a href="/wordsearch?keyword='.$note1->note.'&amp;page=1">1</a>')
    		->see('<a href="/wordsearch?keyword='.$note1->note.'&amp;page=1">« 前</a>');
    }

    // スペース区切り 2個
    public function testKeyword2(){
    	// データ準備
    	$noteTarget = $this->makeData();
    	$noteNo = $this->makeDataNoSave();
    	$noteNo->note = $noteTarget->note;
    	$noteNo->save();

    	$user = factory(App\User::class)->create();
    	$user->id = -1;

   		// 操作
	   	$this->actingAs($user)
   			->visit("/wordsearch")
   			->type($noteTarget->title." ".$noteTarget->note,"keyword")
   			->press("search")
   			->see($noteTarget->title)
   			->dontSee($noteNo->title);
    }

    /**
     * キーワードありで0件
     *
     * @return void
     **/
    public function test0(){
    	// データ準備
    	$noteTarget = $this->makeData();

  		$user = factory(App\User::class)->create();
  		$user->id = -1;

  		// 操作
  		$this->actingAs($user)
  			->visit("/wordsearch")
   			->type($noteTarget->title."a","keyword")
   			->press("search")
   			->dontSee($noteTarget->author);
    }

    /**
     * エスケープの確認（％で検索）
     *
     * @return void
     **/
    public function testEscape(){
    	// データ準備
    	$noteTarget = $this->makeData();

  		$user = factory(App\User::class)->create();
  		$user->id = -1;

  		// 操作
  		$this->actingAs($user)
  			->visit("/wordsearch")
   			->type("%","keyword")
   			->press("search")
   			->dontSee($noteTarget->title);
    }

    /**
     * エスケープの確認（％あり文字列を検索）
     *
     * @return void
     **/
    public function testEscape2(){
    	// データ準備
    	$noteTarget = $this->makeDataNoSave();
    	$noteTarget->note = "aiu%eo";
    	$noteTarget->save();

  		$user = factory(App\User::class)->create();
  		$user->id = -1;

  		// 操作
  		$this->actingAs($user)
  			->visit("/wordsearch")
   			->type("%","keyword")
   			->press("search")
   			->see($noteTarget->title);
    }

}
