<?php

namespace Tests\Feature;

use App\Mail\UserMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;

use Tests\TestCase;

class UserMailTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public static function mailDataProvider(): array
    {
        $user = User::factory()->create();
        return [
            [[
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => 'Pan',
                'mail' => 'mail@mail.pl'

            ], null],
            [[
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => 'Pan',
                'mail' => null
            ], null],
            [[
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => 'Pan',
                'mail' => 'mail@mail.pl'

            ], $user],
            [[
                'title' => 'Tytuł',
                'message' => 'Wiadomość',
                'sirOrLady' => null,
                'mail' => 'mail@mail.pl'

            ], null],
        ];
    }

    
    public function test_mail_can_be_created(): void
    {
        $array=UserMailTest::mailDataProvider();
        foreach($array as [$req, $user])
        {
        $mailable = new UserMail($req, $user);
        $mailable->assertFrom('user@localhost');
        $mailable->assertTo('admin@localhost');
        $mailable->assertSeeInHtml(...array_values($req));
        $mailable->assertSeeInHtml('Tytuł', 'Wiadomość');
        }

    }
    public function test_mail_can_be_send()
    {
        Mail::fake();

        $array=UserMailTest::mailDataProvider();
        foreach($array as [$req, $user])
        {
            $mailable = new UserMail($req, $user);
            Mail::send($mailable);
            Mail::assertSent(UserMail::class);

        }
    }

  
}
