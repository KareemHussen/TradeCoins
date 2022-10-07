<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;

class EnsurePhoneValid
{

    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ($request->user() instanceof MustVerifyEmail && $request->user()->number_verified_at == null)) {

            $response = [
                'status'=> 401,
                'message'=> "Please Verify Your Number",
                'data' => null
            ];

            return response($response , 401);
        }

        return $next($request);
    }
}
