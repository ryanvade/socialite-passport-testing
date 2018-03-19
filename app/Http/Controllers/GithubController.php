<?php

namespace App\Http\Controllers;

use \Auth;
use \Cookie;
use App\User;
use \Socialite;
use Illuminate\Http\Request;

class GithubController extends Controller
{
    public function oAuthLogin(Request $request) {
      $request->session()->put('back_url', redirect()->back()->getTargetUrl());
      if($request->has('api') && $request->api == true) {
        $request->session()->put('api', true);
      }
      return Socialite::driver("github")->redirect();
    }

    public function handleCallback(Request $request) {
      $uri = $request->session()->get('back_url');
      $request->session()->forget('back_url');
      try {
          $user = Socialite::driver("github")->user();
          $authUser = self::findOrCreateUser($user);
          if($request->session()->has('api') && $request->session()->get('api') == true) {
            $request->session()->forget('api');
            Cookie::queue('auth_token', $authUser->createToken("AuthToken")->accessToken, 20, null, null, false, false);
            return redirect('/auth/success');
          }
          Auth::login($authUser);
          return redirect($uri);
      } catch (Exception $e) {
          dd($e);
      }
    }

    public static function findOrCreateUser($user) {
      $authUser = User::where('email', $user->getEmail())->first();
      if(!$authUser) {
        $authUser = User::create([
          'name' => ($user->getNickName() != null)? $user->getNickName(): $user->name,
          'email' => $user->getEmail(),
          'password' => bcrypt('password') // Testing Only
        ]);
      }
      return $authUser;
    }
}
