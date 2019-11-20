<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    private function model()
    {
        return User::class;
    }

    private function createRedirect($user)
    {
        if ($this->model()::where(['email' => $user->email])->count()) {

            if ($this->model()::withTrashed()->count()) {
                $this->model()::where(['email' => $user->email])->first()->update([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'avatar' => $user->avatar
                ]);
                $user_login = $this->model()::where(['email' => $user->email])->first();
            } else {
                $user_login = $this->model()::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'avatar' => $user->avatar,
                    'role' => 'normal'
                ]);
            }
            return $user_login;
        } else {
            if (!$this->model()::withTrashed()->count()) {
                $role = 'admin';
                $user_login = $this->model()::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'avatar' => $user->avatar,
                    'role' => $role,
                    'token_gg' => $user->token,
                    'access_token' => null
                ]);
                return $user_login;
            } else {
                return 0;
            }
        }
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Không thể thực thi!!!');
        }

        // check if they're an existing user

        $check = $this->createRedirect($user);

        if (gettype($check) == 'object') {
            auth()->login($check, true);
            return redirect()->route('page.index');
        } else {
            return redirect()->route('login')->with('error', 'Tài khoản của bạn không tồn tại!');
        }
    }
}
