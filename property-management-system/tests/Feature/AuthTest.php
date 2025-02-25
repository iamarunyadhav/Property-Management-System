<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    //test the user can register
    public function test_a_user_can_register()
    {
        // $this->withoutExceptionHandling();

        $response = $this->postJson('/api/register', [
            'name' => 'Raman',
            'email' => 'raman@gmail.com',
            'password' => 'Raman@2020',
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'data' => ['id', 'name', 'email']
           ]);
    }


    public function test_a_user_can_login()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create([
            'email' => 'raman@gmail.com',
            'password' => bcrypt('Raman@2020'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'raman@gmail.com',
            'password' => 'Raman@2020',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                        'status',
                        'message',
                        'token',
                        'user_id',
                ]);
    }

    public function test_can_logged_out()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message'
                ]);
    }
}
