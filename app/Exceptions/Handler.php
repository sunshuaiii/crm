<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Auth;

class Handler extends ExceptionHandler
{
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        if ($request->is('admin') || $request->is('admin/*')) {
            return redirect()->guest('/login/admin');
        }
        if ($request->is('marketingStaff') || $request->is('marketingStaff/*')) {
            return redirect()->guest('/login/marketingStaff');
        }
        if ($request->is('supportStaff') || $request->is('supportStaff/*')) {
            return redirect()->guest('/login/supportStaff');
        }
        return redirect()->guest(route('login'));
    }
}
