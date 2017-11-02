<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    //use WithoutMiddleware;
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }
    
    /**
     * Tests that a user can register.
     * 
     * @test
     */
    public function userCanRegister()
    {
        $credentials = [
            'email' => 'newuser@gmail.com',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/auth/register', $credentials);
        // endpoint responded
        $response->assertStatus(200);

        $user = \Sentinel::findByCredentials(['login' => $credentials['email']]);
        // user is there
        $this->assertSame($user->email, $credentials['email']);
    }

    /**
     * Tests that a registered user can log into the app after being activated.
     * 
     * @test
     */
    public function registeredUserCanLogInAfterActivation()
    {
        $credentials = [
            'email' => 'newuser@gmail.com',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/auth/register', $credentials);
        $response
            ->assertStatus(200);

        $user = \Sentinel::findByCredentials([
            'login' => $credentials['email'],
        ]);
        // Activate the user
        $activation = \Activation::create($user);
        
        if (\Activation::complete($user, $activation->code)) {
            $response = $this->json('POST', '/api/auth/login', $credentials);
            // Login should have data and meta elements
            $response
                ->assertStatus(200)
                ->assertJsonStructure([
                    'data',
                    'meta' => [
                        'token'
                    ],
                ]);
        }
    }

    

}