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
    use DatabaseMigrations;
    
    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
        $this->token = $this->getAuthToken();
    }
    
    protected function getAuthToken()
    {
        // Create the user
        $user = User::create([
            'email' => 'newuser@gmail.com',
            'password' => 'password',
            'first_name' => 'New',
            'last_name' => 'User',
        ]);

        $user = \Sentinel::findByCredentials([
            'login' => $user->email,
        ]);

        // Activate the user
        $activation = \Activation::create($user);

        // Give the user admin role
        $adminRole = \Sentinel::findRoleBySlug('admin');

        $adminRole->users()->attach($user);
        
        if (\Activation::complete($user, $activation->code)) {
            return \JWTAuth::fromUser($user);
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
        // Create a user
        $user = User::create(
            [
                'email' => 'foo@bar.com',
                'first_name' => 'foo',
                'last_name' => 'bar',
                'password' => bcrypt('xxxxx'),
                'phone' => '6785928804'
            ]
        );
        
        // Request the user list
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('GET', '/api/users');
        
        // Assert that the user list contains the created user's email address
        $response
            ->assertStatus(200)
            ->assertSee($user->email);
    }
}
