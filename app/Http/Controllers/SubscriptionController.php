<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user=Auth::user();
        $date=new DateTime();
        $ownedSubscriptions=$user->subscriptions()->get();
        $allSubscriptions=Subscription::where('active',true)->where('license_duration','>=',$date)->get();
        $unownedSubscriptions=$allSubscriptions->diff($ownedSubscriptions);
        $unownedSubscriptionWithTests=[];
       
        foreach($unownedSubscriptions as $unownedSubscription)
        {
            $tests=$unownedSubscription->tests()->get()->toArray();
            $unownedSubscription['tests']=$tests;
            array_push($unownedSubscriptionWithTests,$unownedSubscription);

        }
      
    return response(['unownedSubscriptions'=>$unownedSubscriptions,'ownedSubscriptions'=>$ownedSubscriptions, 'subscriptions'=>$allSubscriptions]);

    }

    public function showAllSubscriptions()
    {
        $subscriptions=Subscription::all();
        $subscriptionsWithTests=[];
        foreach($subscriptions as $subscription)
        {
            $tests=$subscription->tests()->get()->toArray();
            $subscription['tests']=$tests;
            array_push($subscriptionsWithTests,$subscription);

        }
      
        return response(['subscriptions'=>$subscriptionsWithTests]);
    }

    public function showAllActiveSubscriptions()
    {
        $date=new DateTime();
        $subscriptions=Subscription::where('active',true)->where('license_duration','>=',$date)->get();
        $subscriptionsWithTests=[];
        foreach($subscriptions as $subscription)
        {
            $tests=$subscription->tests()->get()->toArray();
            $subscription['tests']=$tests;
            array_push($subscriptionsWithTests,$subscription);

        }
        return response(['subscriptions'=>$subscriptionsWithTests]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'max:250|required',
            'price'=>'numeric|max:100000|required',
            'discount_price'=>'numeric|max:100000',
            'license_duration'=>'required',
            'lowest_price'=>'numeric'
        ]);
        
        $input=$request->all();
        $subscription=Subscription::create([...$input,'active'=>false]);
        return response(['subscription'=>$subscription->id]);

    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'name'=>'max:250|required',
            'price'=>'numeric|max:100000|required',
            'discount_price'=>'numeric|max:100000',
            'license_duration'=>'required',
            'lowest_price'=>'numeric|nullable'
        ]);
        
        $subscription->name=$request->name;
        $subscription->price=$request->price;
        $subscription->discount_price=$request->discount_price;
        $subscription->license_duration=$request->license_duration;
        $subscription->lowest_price=$request->lowest_price;
        $subscription->description=$request->description;
        $subscription->save();

        return response(['subscription'=>$subscription]);

    }

    public function showInactiveSubscriptions()
    {
        $subscriptions=Subscription::where('active',false)->get();
        $subscriptionsWithTest=new Collection();
        foreach($subscriptions as $subscription)
        {
            $subscription['tests']=$subscription->tests()->get();
            $subscriptionsWithTest->push($subscription);
        }
        return response(['unactiveSubscriptions'=>$subscriptions]);
    }
    public function activateSubscription(Subscription $subscription)
    {
        $date=new DateTime();
        if($date>new DateTime($subscription->license_duration))
        {
            return response('Subscription is too old',500);
        }
        $subscription->active=true;

        $subscription->save();
        return response('Subscritpion is active');
    }
    public function disactivateSubscription(Subscription $subscription)
    {
        $subscription->active=false;
        $subscription->save();
        return response('Subsccription is disactive');
    }
    /**
     * Display the specified resource.
     */
    public function show(Subscription $subscription)
    {
        $tests=$subscription->tests()->get()->toArray();
        $subscription['tests']=$tests;
       return response(['subscription'=>$subscription]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        //
    }

    /**
     * Deleting previous image and add the new one to the subscription.
     */
    public function storeImage(Request $request, Subscription $subscription)
    {
        $request->validate([
            'image'=>'image',
           
        ]);
        if($subscription->path!==null)
        {
         
          Storage::delete(str_replace('/storage','public',$subscription->path));
        }
        $path=$request->image->store('public/images/subscriptions');
        $subscription->path=Storage::url($path);
        $subscription->save();

        return response(['image'=>$subscription->path]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response(['deletedSubscription'=>$subscription]);
    }
}
