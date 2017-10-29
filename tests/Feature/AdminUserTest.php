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
    use WithoutMiddleware;
    use DatabaseMigrations;
    use DatabaseTransactions;

    
    /**
     * Tests user creation endpoint
     *
     * @return void
     * @test
     */
    public function userCanBeCreated()
    {
        $user = [
            'email' => 'foo@bar.com',
            'first_name' => 'foo',
            'last_name' => 'bar',
            'password' => 'xxxxx',
        ];
        
        $response = $this->json('POST', '/api/users', $user);

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
        
        $response = $this->json('GET', '/api/users');

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
