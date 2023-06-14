<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{

    function githubRedirect() {
        return Socialite::driver('github')->redirect();
    }
    function githubCallback() {
        



        $githubUser = Socialite::driver('github')->user();
        
        $user = User::where('github_id', $githubUser->id)->first();
        
        if ($user) {
            $user->update([
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        } else {
            $user = User::create([
                'name' => $githubUser->name,
                'email' => $githubUser->email,
                'github_id' => $githubUser->id,
                'github_token' => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
        }

        // $newTeam = Team::forceCreate([
        //     'user_id' => $newUser->id,
        //     'name' => explode(' ', $user->name, 2)[0]."'s Team",
        //     'personal_team' => true,
        // ]);
        // // save the team and add the team to the user.
        // $newTeam->save();
        // $newUser->current_team_id = $newTeam->id;
        // $newUser->save();
        
        Auth::login($user);
        
        return redirect('/dashboard');
        
        // $user->token
    }
}
