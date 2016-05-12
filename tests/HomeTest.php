<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
	// DBをトランザクション
	use DatabaseTransactions;



    /**
     * 1件表示
     *
     * @return void
     */
    public function testShow1()
    {
        $note1 = $this->makeDummyNote("1");
        $note1->save();

        $noteDiffUser = $this->makeDummyNote("diff");
        $noteDiffUser->userid = -10;
        $noteDiffUser->save();

    	$user = factory(App\User::class)->create();
    	$user->id = -1;

    	$this->actingAs($user)
    		->visit('/home')
    		->see($note1->title)
    		->see($note1->author)
    		->see($note1->imge_url)
    		->dontSee($noteDiffUser->title);
    }

    public function testShow0(){
    	$note1 = $this->makeDummyNote("1");
    	$note1->save();

    	$user = factory(App\User::class)->create();
    	$user->userid = -20;

    	$this->actingAs($user)
    		->visit('/home')
    		->see("最近読んだ本")
    		->dontSee($note1->title);
    }

    public function testShow10()
    {
        $note1 = $this->makeDummyNote("1");
        $note1->save();
        $note2 = $this->makeDummyNote("2");
        $note2->save();
        $note3 = $this->makeDummyNote("3");
        $note3->save();
        $note4 = $this->makeDummyNote("4");
        $note4->save();
        $note5 = $this->makeDummyNote("5");
        $note5->save();
        $note6 = $this->makeDummyNote("6");
        $note6->save();
        $note7 = $this->makeDummyNote("7");
        $note7->save();
        $note8 = $this->makeDummyNote("8");
        $note8->save();
        $note9 = $this->makeDummyNote("9");
        $note9->save();
        $note10 = $this->makeDummyNote("ten");
        $note10->save();

        $noteDiffUser = $this->makeDummyNote("diff");
        $noteDiffUser->userid = -10;
        $noteDiffUser->save();

        $user = factory(App\User::class)->create();
        $user->id = -1;

        $this->actingAs($user)
            ->visit('/home')
            ->see($note1->title)
            ->see($note1->author)
            ->see($note1->imge_url)
            ->dontSee($noteDiffUser->title)
            ->see($note2->title)
            ->see($note2->author)
            ->see($note2->imge_url)
            ->see($note3->title)
            ->see($note3->author)
            ->see($note3->imge_url)
            ->see($note4->title)
            ->see($note4->author)
            ->see($note4->imge_url)
            ->see($note5->title)
            ->see($note5->author)
            ->see($note5->imge_url)
            ->see($note6->title)
            ->see($note6->author)
            ->see($note6->imge_url)
            ->see($note7->title)
            ->see($note7->author)
            ->see($note7->imge_url)
            ->see($note8->title)
            ->see($note8->author)
            ->see($note8->imge_url)
            ->see($note9->title)
            ->see($note9->author)
            ->see($note9->imge_url)
            ->see($note10->title)
            ->see($note10->author)
            ->see($note10->imge_url);

    }

        public function testShow10Data11()
    {
        $note1 = $this->makeDummyNote("one");
        $note1->save();
        $note2 = $this->makeDummyNote("2");
        $note2->save();
        $note3 = $this->makeDummyNote("3");
        $note3->save();
        $note4 = $this->makeDummyNote("4");
        $note4->save();
        $note5 = $this->makeDummyNote("5");
        $note5->save();
        $note6 = $this->makeDummyNote("6");
        $note6->save();
        $note7 = $this->makeDummyNote("7");
        $note7->save();
        $note8 = $this->makeDummyNote("8");
        $note8->save();
        $note9 = $this->makeDummyNote("9");
        $note9->save();
        $note10 = $this->makeDummyNote("10");
        $note10->save();
        $note11 = $this->makeDummyNote("11");
        $note11->save();

        $noteDiffUser = $this->makeDummyNote("diff");
        $noteDiffUser->userid = -10;
        $noteDiffUser->save();

        $user = factory(App\User::class)->create();
        $user->id = -1;

        $this->actingAs($user)
            ->visit('/home')
            ->dontSee($note1->title)
            ->dontSee($note1->author)
            ->dontSee($noteDiffUser->title)
            ->see($note2->title)
            ->see($note2->author)
            ->see($note2->imge_url)
            ->see($note3->title)
            ->see($note3->author)
            ->see($note3->imge_url)
            ->see($note4->title)
            ->see($note4->author)
            ->see($note4->imge_url)
            ->see($note5->title)
            ->see($note5->author)
            ->see($note5->imge_url)
            ->see($note6->title)
            ->see($note6->author)
            ->see($note6->imge_url)
            ->see($note7->title)
            ->see($note7->author)
            ->see($note7->imge_url)
            ->see($note8->title)
            ->see($note8->author)
            ->see($note8->imge_url)
            ->see($note9->title)
            ->see($note9->author)
            ->see($note9->imge_url)
            ->see($note10->title)
            ->see($note10->author)
            ->see($note10->imge_url)
            ->see($note11->title)
            ->see($note11->author)
            ->see($note11->imge_url);
    }


        public function testShow10Distinct()
    {
        $note1 = $this->makeDummyNote("one");
        $note1->save();
        $note2 = $this->makeDummyNote("2");
        $note2->save();
        $note3 = $this->makeDummyNote("3");
        $note3->save();
        $note4 = $this->makeDummyNote("4");
        $note4->save();
        $note5 = $this->makeDummyNote("5");
        $note5->save();
        $note6 = $this->makeDummyNote("6");
        $note6->save();
        $note7 = $this->makeDummyNote("7");
        $note7->save();
        $note8 = $this->makeDummyNote("8");
        $note8->save();
        $note9 = $this->makeDummyNote("9");
        $note9->save();
        $note9_2 = $this->makeDummyNote("9");
        $note9_2->save();
        $note10 = $this->makeDummyNote("10");
        $note10->save();
        $note11 = $this->makeDummyNote("11");
        $note11->save();


        $noteDiffUser = $this->makeDummyNote("diff");
        $noteDiffUser->userid = -10;
        $noteDiffUser->save();

        $user = factory(App\User::class)->create();
        $user->id = -1;

        $this->actingAs($user)
            ->visit('/home')
            ->dontSee($note1->title)
            ->dontSee($note1->author)
            ->dontSee($noteDiffUser->title)
            ->see($note2->title)
            ->see($note2->author)
            ->see($note2->imge_url)
            ->see($note3->title)
            ->see($note3->author)
            ->see($note3->imge_url)
            ->see($note4->title)
            ->see($note4->author)
            ->see($note4->imge_url)
            ->see($note5->title)
            ->see($note5->author)
            ->see($note5->imge_url)
            ->see($note6->title)
            ->see($note6->author)
            ->see($note6->imge_url)
            ->see($note7->title)
            ->see($note7->author)
            ->see($note7->imge_url)
            ->see($note8->title)
            ->see($note8->author)
            ->see($note8->imge_url)
            ->see($note9->title)
            ->see($note9->author)
            ->see($note9->imge_url)
            ->see($note10->title)
            ->see($note10->author)
            ->see($note10->imge_url)
            ->see($note11->title)
            ->see($note11->author)
            ->see($note11->imge_url);
    }


    function makeDummyNote($str){
    	// テスト用ダミーデータ
	    $testUserId = -1;
    	$testIsbn = '1234'.$str;
    	$testTitle = 'テストタイトル'.$str;
    	$testAuthor = '作者名'.$str;
   	 	$testQuote = '引用部分'.$str;
    	$testNote = 'メモ部分'.$str;
    	$testImageUrl = 'http://dummy.image.1234'.$str;
    	$testAmazonUrl = 'http://dummy.amazon.1234'.$str;


    	$note = new App\Note;
 		$note->userid = $testUserId;
 		$note->isbn = $testIsbn;
 		$note->title = $testTitle;
 		$note->author = $testAuthor;
 		$note->quote = $testQuote;
 		$note->note = $testNote;
 		$note->image_url = $testImageUrl;
 		$note->amazon_url = $testAmazonUrl;
 		return $note;
    }




}
