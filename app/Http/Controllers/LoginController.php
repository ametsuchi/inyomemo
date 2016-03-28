<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\User;
use Auth;
use Laravel\Socialite\Contracts\Factory as Socialite;

class LoginController extends Controller
{
	protected $socialite;



    // ログイン用コンストラクタ
    public function __construct(Socialite $socialite)
    {
        $this->socialite = $socialite;
    }


    public function login($provider)
    {
    	// ソーシャルログイン処理
    	return $this->socialite->driver($provider)->redirect();
    }

   public function callback($provider)
	{
    	// ユーザー情報取得
    	$userData = $this->socialite->driver($provider)->user();
    	// ユーザー作成
    	$users = User::where('service_name',$provider)
    	->where('socialite_id',$userData->getId())
    	->take(1)
    	->get();
    	
    	$user = null;
    	if(count($users) == 0){
	    	$user = User::create([
	    		'name' => $userData->getNickname(),
	    		'socialite_id' => $userData->getId(),
	    		'email' => $userData->getEmail(),
	    		'avatar' => $userData->getAvatar(),
	    		'service_name' => $provider,
 	    		]);

    	}else{
    		$user = $users[0];
    	}

    	Auth::login($user);
    	return redirect('index');
	}

}
