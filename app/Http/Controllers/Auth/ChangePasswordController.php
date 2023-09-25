<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ChangePasswordController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
           'password'=> ['required','confirmed', Rules\Password::defaults()]
        ]);
        Auth::user()->forceFill(['password'=>Hash::make($request->input('password'))])->save();
        return response()->noContent();
    }
}
