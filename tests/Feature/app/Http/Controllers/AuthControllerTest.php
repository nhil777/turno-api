<?php

namespace Tests\Feature\App\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Group("Register")]
    #[Test]
    public function it_creates_user_account_with_valid_registration_data()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => ['token']]);
    }

    #[Group("Register")]
    #[Test]
    public function it_fails_to_create_new_user_account_with_invalid_registration_data()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => '',
            'email' => 'invalid_email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    #[Group("Login")]
    #[Test]
    public function it_login_user_with_valid_login_credentials()
    {
        $password = 'phpunit';

        $user = $this->createUser(['password' => $password]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['token']]);
    }

    #[Group("Login")]
    #[Test]
    public function it_fails_to_login_user_with_invalid_login_credentials()
    {
        $user = $this->createUser();

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertStatus(401);
    }

    #[Group("JWT")]
    #[Test]
    public function it_logout_user_and_invalidates_token()
    {
        $user = $this->createUser();

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->postJson(
            uri: route('auth.logout'),
            headers: ['Authorization' => 'Bearer '.$token]
        );
        $response->assertStatus(200);

        $response = $this->postJson(
            uri: route('auth.refresh'),
            data: ['Authorization' => 'Bearer '.$token]
        );
        $response->assertStatus(401);
    }

    #[Group("JWT")]
    #[Test]
    public function it_fails_to_logout_user_when_not_authenticated()
    {
        $response = $this->postJson(route('auth.logout'));
        $response->assertStatus(401);
    }

    #[Group("JWT")]
    #[Test]
    public function it_refreshes_user_token_with_a_valid_token()
    {
        $user = $this->createUser();

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $token = $response->json('token');

        $response = $this->postJson(
            uri: route('auth.refresh'),
            headers: ['Authorization' => 'Bearer '.$token]
        );

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['token']]);
    }

    #[Group("JWT")]
    #[Test]
    public function it_fails_to_refresh_user_token_with_an_invalid_token()
    {
        $response = $this->postJson(
            uri: route('auth.refresh'),
            headers: ['Authorization' => 'Bearer invalid_token']
        );
        $response->assertStatus(401);
    }
}
