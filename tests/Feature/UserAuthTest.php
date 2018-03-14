<?php

namespace Tests\Feature;

use App\Http\Resources\AuthTokenResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserAuthTest extends TestCase
{
    public function testGetAuthAccessFailed()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', 'api/auth/token',
            [
                'email' => 'imrancluster@gmail.com',
                'password' => 'password',
            ]);

        $response
            ->assertStatus(401)
            ->assertJson(
                [
                    'data' => [
                        'message' => 'Wrong Credential'
                    ]
                ]);
    }

    public function testGetAuthAccessSuccess()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json('POST', '/api/auth/token',
            [
                'email' => 'imrancluster@gmail.com',
                'password' => 'imran',
            ]);


        $user = User::where('email','imrancluster@gmail.com')->first();

        $response
            ->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'api_token' => $user->api_token,
                    ]
                ]);
    }

    public function testPasswordResetRequest()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/auth/reset-password', [
            'email' => 'imrancluster@gmail.com'
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'message' => 'Password reset key sent to your email'
                ]
            ]);
    }

    public function testChangePassword()
    {
        $user = User::where('email', 'imrancluster@gmail.com')->first();

        $response = $this->withHeaders([
            'Accept' => 'application/json'
        ])->json('POST', '/api/auth/change-password', [
            'email' => 'imrancluster@gmail.com',
            'password' => 'imran',
            'reset_key' => $user->reset_key,
        ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'message' => 'Password changed successfully.'
                ]
            ]);
    }
}
