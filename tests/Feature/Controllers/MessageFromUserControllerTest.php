<?php

namespace Tests\Feature;

use App\Mail\UserMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MessageFromUserControllerTest extends TestCase
{
    public static function dataProvider(): array
    {
        
        return [
            [
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => 'Pan',
                'mail' => 'mail@mail.pl'

            ],
            [
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => 'Pan',
                'mail' => null
            ],
            [
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => 'Pan',
                'mail' => 'mail@mail.pl'

            ],
            [
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => null,
                'mail' => 'mail@mail.pl'

            ], 
        ];
    }

    public function test_user_can_send_message(): void
    {
       Mail::fake();
       $array=MessageFromUserControllerTest::dataProvider();
       foreach($array as $data)
       {
       $response = $this->post('/message/type/message', $data);
       $response->assertStatus(202);
       Mail::assertSent(UserMail::class);
       }
    }

    public function test_invalid_request_is_rejected(): void
    {
       Mail::fake();
       
       $response = $this->post('/message/type/message', ['title' => 'Tytuł']);
       $response->assertInvalid(['message'=>'The message field is required.']);
       Mail::assertNotSent(UserMail::class);
       
    }
}
