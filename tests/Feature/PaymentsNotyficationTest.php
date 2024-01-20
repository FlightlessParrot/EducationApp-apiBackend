<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PaymentsNotyficationTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_user_get_subscription_when_payment_is_confirmed(): void
    {
        $subscription=Subscription::factory()->create();
      $user=User::factory()->create();
        $payment=$user->payments()->create((['indempotency_key'=>'123', 'subscription_id'=> $subscription->id,'price'=>100, 'signature'=>'456',"payment_id"=> "NOLV-8F9-08K-WGD",]));
        $response = $this->post('api/payment/update',[ 
            "paymentId"=> "NOLV-8F9-08K-WGD",
            "externalId"=>$payment->id,
        "status"=> "CONFIRMED",
        "modifiedAt"=> "2018-12-12T13:24:52"],['Signature'=>'456' 
        ]);
        $payment->refresh();
        $response->assertStatus(200);
        $this->assertEquals('CONFIRMED',$payment->status);
        $this->assertModelExists($subscription->users()->first());
    }
}
