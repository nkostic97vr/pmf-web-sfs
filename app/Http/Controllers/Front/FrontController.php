<?php

// note: Public is a reserved word
namespace App\Http\Controllers\Front;

use View;
use Activity;
use App\User;
use App\Post;
use App\Topic;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Display a listing of the resource.

     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('public.index')->with('categories', Category::all());
    }

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $onlineUsersMinutes = config('custom.refresh_online_users_minutes');

            $visibleOnlineUsers = Activity::users($onlineUsersMinutes)
                ->join('users', 'sessions.user_id', 'users.id')
                ->where('is_invisible', false)
                ->get();

            $guestCount = Activity::guests()->count();
            $visibleOnlineUsersCount = $visibleOnlineUsers->count();
            $allOnlineUsersCount = Activity::users($onlineUsersMinutes)->count();

            View::share('is_admin', is_admin());
            View::share('postCount', Post::count());
            View::share('guestCount', $guestCount);
            View::share('topicCount', Topic::count());
            View::share('newestUser', User::newestUser());
            View::share('userCount', User::all()->count());
            View::share('visibleOnlineUsers', $visibleOnlineUsers);
            View::share('onlineUsersMinutes', $onlineUsersMinutes);
            View::share('peopleOnline', $allOnlineUsersCount + $guestCount);
            View::share('visibleOnlineUsersCount', $visibleOnlineUsersCount);
            View::share('invisibleOnlineUsersCount', $allOnlineUsersCount - $visibleOnlineUsersCount);

            return $next($request);
        });
    }
}