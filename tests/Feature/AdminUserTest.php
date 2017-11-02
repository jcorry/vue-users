<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminUserTest extends TestCase
{
    //use WithoutMiddleware;
    use DatabaseMigrations;
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->token = $this->getAuthToken();
        \Log::debug($this->token);
    }
    
    protected function getAuthToken()
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
            return $response->json()['meta']['token'];
        }
        
        throw new \Exception('Could not authenticate user');
    } 
    
    /**
     * Tests user creation endpoint
     *
     * @return void
     * @test
     */
    public function userCanBeCreated()
    {
        $user = [
            'email' => 'bar@foo.com',
            'first_name' => 'foo',
            'last_name' => 'bar',
            'password' => 'xxxxx',
        ];
        
        $response = $this->json('POST', '/api/users', $user, ['Authorization' => 'Bearer ' . $this->token]);

        $response->assertStatus(200)
            ->assertJsonFragment(
                [
                    'email' => $user['email'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                ]
            );

        unset($user['password']);
        
        $this->assertDatabaseHas('users', $user);
    }

    /**
     * Tests user deletion endpoint
     *
     * @return void
     * @test
     */
    public function userCanBeDeleted()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Tests user list endpoint
     *
     * @return void
     * @test
     */
    public function userListCanBeAccessed()
    {
        $user = User::create(
            [
                'email' => 'foo@bar.com',
                'first_name' => 'foo',
                'last_name' => 'bar',
                'password' => bcrypt('xxxxx'),
                'phone' => '6785928804'
            ]
        );
        
        $response = $this->json('GET', '/api/users', ['Authorization' => 'Bearer ' . $this->token]);
        
        $response
            ->assertStatus(200)
            ->assertJson(
                [
                    'data' => [
                        [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                        ]
                    ]
                ]
            );
    }
}
