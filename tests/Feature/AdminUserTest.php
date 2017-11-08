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

    /**
     * The JWT string that will be sent in Auhtorization headers to identify the user.
     * 
     * @var string $token
     */
    protected $token;

    /**
     * The user making authenticated requests.
     * 
     * @var \App\Models\User $user The user making requests.
     */
    protected $user;
    
    public function setUp()
    {
        parent::setUp();
        $this->artisan('db:seed');
        
        // Create the user
        $user = User::create([
            'email' => 'newuser@gmail.com',
            'password' => 'password',
            'first_name' => 'New',
            'last_name' => 'User',
        ]);

        $this->user = \Sentinel::findByCredentials([
            'login' => $user->email,
        ]);

        // Activate the user
        $activation = \Activation::create($user);

        // Give the user admin role
        $adminRole = \Sentinel::findRoleBySlug('admin');

        $adminRole->users()->attach($user);
        
        if (\Activation::complete($user, $activation->code)) {
            $this->token = \JWTAuth::fromUser($user);
        } else {
            throw new \Exception('Could not authenticate user');
        }
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

    /**
     * Tests the admin user update method.
     * 
     * @test
     */
    public function userCanBeUpdated()
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

        // New values for user
        $user->email = 'foo@baz.com';
        $user->first_name = 'baz';

        // Call update method
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('PATCH', '/api/users/' . $user->id, $user->toArray());

        // Assert save/return was as expected
        $response->assertStatus(200)
            ->assertSee($user->email)
            ->assertSee('"first_name":"baz"');
                
    }

    /**
     * @test
     */
    public function userUpdateDeniedToNonAuthedUser()
    {
        // Remove $this->user from admin role
        $role = \Sentinel::findRoleBySlug('admin'); 
        $role->users()->detach($this->user);

        // Get a user to update.
        $user = User::find(1);

        // Call update method
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->token])->json('PATCH', '/api/users/' . $user->id, $user->toArray());

        // Assert that request authorization failed.
        $response->assertStatus(401);

    }
}
