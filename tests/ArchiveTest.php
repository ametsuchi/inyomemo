<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ArchiveTest extends TestCase
{

	// DBをトランザクション
	use DatabaseTransactions;
    
    /**
     * 1件表示
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
        	->visit("/archive")
        	->see($note->title)
        	->see($note->author)
        	->see("/memo/".$note->isbn)
        	->see($note->image_url)
        	->see('<span>1</span>');
    }

    /**
     * 0件表示
     *
     * @return void
     */
    public function testShow0()
    {
    	$user = factory(App\User::class)->create();
    	$user->id = -1;

    	$this->actingAs($user)
    		->visit("/archive")
    		->see("読んだ本");


    }

    /** 
     * 2ページ
     *
     *@return void
     **/
    public function testPage()
    {	
    	// データ準備
    	$user = factory(App\User::class)->create();
    	$user->id = -1;

    	$notePage2First = $this->makeData();
    	$notePage1Last = $this->makeData();
    	for ($i = 0;$i < 8;$i++){
    		$this->makeData();
    	}
    	$notePage1First = $this->makeData();

    	// 2ページクリック
    	$this->actingAs($user)
    		->visit("/archive")
    		->see($notePage1First->title)
    		->see($notePage1Last->title)
    		->see("<span>1</span>")
    		->see('<a href="/archive/2">2</a>')
    		->see('<a href="/archive/2">次 »</a>')
    		->click('次 »')
    		->see($notePage2First->title)
    		->see("<span>2</span>")
    		->see('<a href="/archive/1">1</a>')
    		->see('<a href="/archive/1">« 前</a>');
    }


}
