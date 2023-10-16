<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Subscription;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isNull;

class DiscountCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $codes=DiscountCode::all();
       return response(['discountCodes'=>$codes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function verify(string $code)
    {
        $discountCode=DiscountCode::where('code',$code)->first();
         $discount= is_null( $discountCode) ? null: $discountCode->discount;
        return response(['code'=>$discountCode, 'discount'=>$discount]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'discount'=>'numeric|required|max:100',
            'code'=>'string|max:250'

        ]);

        $code=DiscountCode::create(['code'=>$request->code, 'discount'=>$request->discount]);

        foreach($request->subscriptions as $subscriptionId)
        {
            $subscription=Subscription::find($subscriptionId);
            $subscription->discount_codes()->attach($code);
        }
        return response(['discountCode'=>$code]);
    }

    /**
     * Display the specified resource.
     */
    public function show(DiscountCode $discountCode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DiscountCode $discountCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DiscountCode $discountCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DiscountCode $discountCode)
    {
        $discountCode->delete();
        return response(['deletedCode'=>$discountCode]);
    }
}
