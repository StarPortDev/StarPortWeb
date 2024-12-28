@use('App\Models\UserData')

<div class="navbar bg-base-100">
    <div class="flex-none">
        <label for="nav-drawer" class="btn btn-square btn-ghost drawer-button">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                class="inline-block h-5 w-5 stroke-current">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </label>
    </div>
    <div class="flex-1">
        <a href="/" class="btn btn-ghost text-xl">StarPort</a>
    </div>
    <div class="flex-none">
        @if (!Auth::check())
            <a href="/auth/login">
                <img src="/images/eve-sso-login-black-large.png" />
            </a>
        @else
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="avatar">
                    <div class="w-12 rounded-full">
                        @php
                            $userId = Auth::id();
                            $characterId = \App\Models\User::where('id', $userId)->limit(1)->get()->first()->character_id;
                            $avatarUrl = Cache::get("$characterId:avatar");
                            if($avatarUrl == null){
                                $avatarUrl = UserData::where('character_id', $characterId)->limit(1)->get()->first()->avatar_url;
                                cache(["$characterId:avatar" => $avatarUrl], now()->addMinutes(10));
                            }
                        @endphp
                        <img src={{ $avatarUrl }} />
                    </div>
                </div>
                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a href="/user/settings">Settings</a></li>
                    <li><a href="/auth/logout">Log out</a></li>
                </ul>
            </div>

        @endif
    </div>
</div>
