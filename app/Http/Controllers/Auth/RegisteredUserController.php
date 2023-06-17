<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAdress;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required',  Rules\Password::defaults()],
            'adress'=>['required', 'string','max:255'],
            'postal_code'=>['required', 'max:6'],
            'city'=>['required', 'string','max:255'],
            'nip'=>['nullable', 'string','max:30']
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $adress=new UserAdress(
            [
                'adress'=>$request->adress,
                'postal_code'=>$request->postal_code,
                'city'=>$request->city,
                'nip'=>$request->nip
                
            ]
            );
        $user->UserAdress()->save($adress);

        event(new Registered($user));

        Auth::login($user);  

        return response()->noContent();
    }
}
