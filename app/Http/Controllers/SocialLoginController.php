<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Team;
use Illuminate\Support\Facades\Hash;


class SocialLoginController extends Controller
{

    function providerRedirect(String $provider) {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    function providerCallback(String $provider) {
        $user = Socialite::driver($provider)->stateless()->user();

        $alreadyExistingUser = User::where($provider . '_id', $user->id)->first();
        $hasPhoto = isset($alreadyExistingUser->profile_photo_path);

        $newUser = User::updateOrCreate([
            $provider . '_id' => $user->id,
        ], [
            'social_user' => true,
            'name' => $user->name,
            'social_email' => $user->email,
            'password' => Hash::make($user->name . $user->email),
            $provider . '_token' => $user->token,
            $provider . '_refresh_token' => $user->refreshToken,
            'profile_photo_path' => $hasPhoto ? $alreadyExistingUser->profile_photo_path : $user->getAvatar()
        ]);

        // create a personal team for the user
        $newTeam = Team::forceCreate([
            'user_id' => $newUser->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]);
        // save the team and add the team to the user.
        $newTeam->save();
        $newUser->current_team_id = $newTeam->id;
        $newUser->save();

        Auth::login($newUser);

        return redirect('/lists');
    }
}
