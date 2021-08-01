<?php

namespace App\Http\Middleware;

use App\Config;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfUserSetup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user = Auth::user();
        $slackConfig = Config::getConfig($user->id, CONFIG_TYPE_SLACK_WEBHOOK);
        $trialAccepted = Config::getConfig($user->id, CONFIG_TYPE_TRIAL_ACCEPTED);
//        dd($trialAccepted);
//        if (!$user->payment) {
//            return redirect()->route('install-payment');
//        }

        if (!$slackConfig) {
            return redirect()->route('install-slack');
        }

        if (!$trialAccepted) {
            return redirect()->route('video');
        }

        return $next($request);
    }
}
