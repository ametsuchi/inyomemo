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


    public function login($provider,Request $request)
    {
        // ログイン継続チェックボックスをsessionに
        $remember = $request->input("remember");
        info($remember);
        $request->session()->pull('remember',$remember);
    	// ソーシャルログイン処理
    	return $this->socialite->driver($provider)->redirect();
    }

    public function logout(){
        Auth::logout();
        return redirect("/");
    }
   public function callback($provider,Request $request)
	{
    	// ユーザー情報取得
    	$userData = $this->socialite->driver($provider)->user();
    	// ユーザー作成
    	$users = User::where('service_name',$provider)
    	->where('socialite_id',$userData->getId())
    	->take(1)
    	->get();

        // name
        // Githubはnickname
        $name = $userData->getNickName();
    	
    	$user = null;
    	if(count($users) == 0){
	    	$user = User::create([
	    		'name' => $name,
	    		'socialite_id' => $userData->getId(),
	    		'email' => $userData->getEmail(),
	    		'avatar' => $userData->getAvatar(),
	    		'service_name' => $provider,
 	    		]);

    	}else{
    		$user = $users[0];

            $user->name = $name;
            $user->email = $userData->getEmail();
            $user->avatar = $userData->getAvatar();
            $user->save();
    	}

        // ログイン継続か否か
        $remember = $request->session()->get("remember");
        // Todo::
        $remember = true;


    	Auth::login($user,$remember);

       return redirect('home');
	}

}
