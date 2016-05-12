<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }


    /**
     * ダミーデータつくって保存(Note)
     *
     * @return Note Object.
     */
    protected function makeData(){
        $note = new App\Note;

        $note->isbn = $this->makeRandStr(6);
        $note->userid = -1;
        $note->title = "title".$this->makeRandStr(4);
        $note->author = "author".$this->makeRandStr(3);
        $note->page = mt_rand(1, 100);
        $note->image_url = "http://dummy/".$this->makeRandStr(3);
        $note->amazon_url = "http://amazon".$this->makeRandStr(3);
        $note->note = $this->makeRandStr(10);
        $note->quote = $this->makeRandStr(15);

        $note->save();

        return $note;
    }


    /**
     * ダミーデータつくる.保存しない.(Note)
     *
     * @return Note Object.
     */
    protected function makeDataNoSave(){
        $note = new App\Note;

        $note->isbn = $this->makeRandStr(6);
        $note->userid = -1;
        $note->title = "title".$this->makeRandStr(4);
        $note->author = "author".$this->makeRandStr(3);
        $note->page = mt_rand(1, 100);
        $note->image_url = "http://dummy/".$this->makeRandStr(3);
        $note->amazon_url = "http://amazon".$this->makeRandStr(3);
        $note->note = $this->makeRandStr(10);
        $note->quote = $this->makeRandStr(15);

        return $note;
    }

    /**
     * ランダム文字列生成 (英数字)
     * $length: 生成する文字数
     *
     * @return string
     */
    protected function makeRandStr($length = 8) {
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; ++$i) {
            $str .= $chars[mt_rand(0, 61)];
        }
        return $str;
    }
}
