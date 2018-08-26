<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\Mail\ConfirmYourEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_has_a_default_username_after_registeration()
    {
        $this->withoutExceptionHandling();
        $this->post('/register', [
            'name' => 'kati frantz',
            'email' => 'kati@frantz.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'

        ])->assertRedirect();

        $this->assertDatabaseHas('users', [
            'username' => str_slug('kati frantz')
        ]);
    }

    public function test_a_user_has_a_token_after_registeration()
    {
        // $this->withoutExceptionHandling();
        Mail::fake();

        $this->post('/register', [
            'name' => 'kati frantz',
            'email' => 'kati@frantz.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'

        ])->assertRedirect();

        $user = User::find(1);
        $this->assertNotNull($user->confirm_token);
        $this->assertFalse($user->isConfirmed());
    }

    public function test_an_email_is_sent_to_newly_registered_users()
    {
        $this->withoutExceptionHandling();
        Mail::fake();

        $this->post('/register', [
            'name' => 'kati frantz',
            'email' => 'kati@frantz.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'

        ])->assertRedirect();

        Mail::assertSent(ConfirmYourEmail::class);
    }
}
