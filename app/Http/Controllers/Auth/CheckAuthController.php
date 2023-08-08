<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckAuthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $authenticated=Auth::user();
            
        return response()->noContent()->cookie(
            'user-name',$authenticated->name,null,null,null,null, false)->cookie( 'user-id',$authenticated->id,null,null,null,null, false)->cookie( 'role',$authenticated->role,null,null,null,null, false);
    }
}
