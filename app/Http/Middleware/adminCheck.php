<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class adminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
                        //Log::info('inside middleware');
                        if ($request->path() == 'app/login') {
                            return $next($request);
                        }
                        if (!Auth::check()) {
                            return response()->json([
                                'msg' => 'You are not allowed to access this route.',
                                'url' => $request->path()
                            ], 403);
                        }
                        $user = Auth::user();
                        if ($user->userType !== 'admin') {
                            // return response()->json([
                            //     'msg' => 'You are not allowed to access this route.',
                            // ], 403);
                            return view('notfound');
                        }
                return $next($request);
    }
}
