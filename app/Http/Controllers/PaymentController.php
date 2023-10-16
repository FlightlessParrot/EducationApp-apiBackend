<?php

namespace App\Http\Controllers;

use App\Events\PaymentStatusChange;
use App\Models\DiscountCode;
use App\Models\Payment;
use App\Models\Subscription;
use App\Payments\HashCalculator;
use Error;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\error;

class PaymentController extends Controller
{
    public function makePayment(Request $request, Subscription $subscription)
    {
        $user=Auth::user();
        $code=DiscountCode::where('code',$request->code)->first();
        $price=$subscription->discount_price === null ? $subscription->price : $subscription->discount_price;
        if($code!==null)
        {
            $price=round((100-$code->discount)/100*$price*100)/100;
        }
        if($price!==(float)$request->price)
        {
            throw new Error('Price mismatch '.$price.'||'.$request->price);
        }
        $indempotencyKey='u:'.$user->id.':s:'.$subscription->id;
        $payment=Payment::where('indempotency_key',$indempotencyKey)->first();
        if($payment===null)
        {
        $payment=$user->payments()->create(['indempotency_key'=>$indempotencyKey, 'subscription_id'=> $subscription->id,'price'=>$price]);
        }
        $message=["amount"=> $price*100,
        "externalId"=> $payment->id,
        "description"=>$user->name.' kupił subskrypcję '.$subscription->name,
        "buyer"=> [
            "email"=>$user->email
        ]];
        $hasher = new HashCalculator();
        $signature= $hasher->calculateHmac(json_encode($message));
        $response = Http::withHeaders(
            [
                'Api-Key'=>env('PAYMENTS_API_KEY'),
                'Signature'=>$signature,
                "Idempotency-Key"=>$indempotencyKey
            ]
        )->post(env('PAYMENTS_ENDPOINT'),$message);
        $body=$response->json();
        $payment->signature=$signature;
        $payment->status=$body['status'];
        $payment->payment_id=$body['paymentId'];
        $payment->save();




        return response([...$body]);
    }

    public function updatePayment(Request $request)
    {
       
       $signature=$request->header('Signature');
       $id=$request->paymentId;
        $payment=Payment::where('payment_id',$id)->first();
        Log::info($request->paymentId);
        Log::info($payment->id);
        Log::info($signature);
        Log::info($payment->signature);
        $hasher = new HashCalculator();
        $calcSignature= $hasher->calculateHmac(json_encode($request->all()));
        Log::info($calcSignature);
        if($payment->status!=='CONFIRMED'){
        if($calcSignature!==$signature)
        {
            throw new Error('Signature mismatch');
        }
        else{
            $payment->status=$request->status;
            $payment->save();
            PaymentStatusChange::dispatch($payment);
            
        }
     
       
        }
        return response()->noContent(200);
    }
}
