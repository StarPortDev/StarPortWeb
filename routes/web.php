<?php

use App\Models\User;
use App\Models\UserData;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('home');
});

Route::get('/auth/login', function () {
    return Socialite::driver('eveonline')->scopes(['publicData'])->redirect();
});

Route::get('/auth/logout', function () {
    Auth::logout();

    return redirect('/');
});

Route::get('/callback', function () {
    $eveUser = Socialite::driver('eveonline')->user();

    $user = User::updateOrCreate([
        'character_id' => $eveUser->character_id,
    ], [
        'character_id' => $eveUser->character_id,
        'character_name' => $eveUser->character_name,
        'character_owner_hash' => $eveUser->character_owner_hash,
        'token' => $eveUser->token,
        'refresh_token' => $eveUser->refreshToken,
    ]);

    $response = Http::get("https://esi.evetech.net/latest/characters/$user->character_id/portrait/?datasource=tranquility");
    $response = json_decode($response->body());
    $avatarUrl = $response->px512x512;

    $userData = UserData::updateOrCreate([
        'character_id' => $user->character_id,
    ], [
        'character_id' => $user->character_id,
        'avatar_url' => $avatarUrl
    ]);

    Auth::login($user);

    return redirect('/');
});

