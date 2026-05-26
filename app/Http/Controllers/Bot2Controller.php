<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use App\Models\Bot2;
use App\Models\User;
use Carbon\Carbon;
use Hekmatinasser\Verta\Verta;

class Bot2Controller extends Controller
{
    public function dashboard() {

        $AllUsers = Bot2::count();
        $ActiveUsers = Bot2::where('status', 1)->whereNotNull('lbank_uid')->where('step',12)->count();
        $now = Carbon::now();
    
        $deactiveUsers = Bot2::where('status', 0)->where('lbank_uid',null)->where('step',12)->count();
        $Bot = "Bot2";
        return view("dashboard.Botdashboard", compact("AllUsers", "ActiveUsers", "deactiveUsers","Bot"));

    }

    public function users() {

        $Users = Bot2::all();
        session()->put('backlink', route('bot2.users'));
        $Bot = "Bot2";
        return view("dashboard.BotUsers", compact("Users","Bot"));

    }

    public function activeUsers() {

        $Users = Bot2::where('status', 1)->whereNotNull('lbank_uid')->where('step',12)->get();
        session()->put('backlink', route('bot2.activeUsers'));
        $Bot = "Bot2";
        return view("dashboard.BotUsers", compact("Users","Bot"));

    }

    public function deactiveUsers() {

        $now = Carbon::now();
        $Users = Bot2::where('step', 11)
        ->where('status', 0)
        ->where('vip', null)
        ->whereNotNull('exp_vip')
        ->where('exp_vip', '<', Carbon::now())
        ->get();
        session()->put('backlink', route('bot2.deactiveUsers'));
        $Bot = "Bot2";
        return view("dashboard.BotUsers", compact("Users","Bot"));

    }

    public function botUserInfo(Bot2 $user) {

        $Bot = "Bot2";
        $SiteUser = User::where('mobile',$user->mobile)->first();
        return view("dashboard.BotUserInfo", compact("user", "Bot","SiteUser"));

    }




}
