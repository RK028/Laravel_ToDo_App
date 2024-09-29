<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class TodoControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for testing with a proper password.
        $this->user = User::factory()->create([
            'password' => bcrypt('password'), // Use bcrypt to hash the password.
        ]);
    }

    /** @test */
    public function it_can_show_a_todo()
    {
        $this->actingAs($this->user);

        $todo = $this->user->todos()->create([
            'title' => 'Test Todo',
            'description' => 'This is a test todo.',
            'completed' => false,
        ]);

        $response = $this->getJson("/api/todos/{$todo->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'title' => 'Test Todo',
                     'description' => 'This is a test todo.',
                     'completed' => false,
                 ]);
    }

    /** @test */
    public function it_can_create_a_todo()
    {
        $this->actingAs($this->user);

        $response = $this->postJson('/api/todos', [
            'title' => 'New Todo',
            'description' => 'This is a new todo.',
            'completed' => false,
        ]);

        $response->assertStatus(201)
                 ->assertJson([
                     'title' => 'New Todo',
                     'description' => 'This is a new todo.',
                     'completed' => false,
                 ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'New Todo',
            'description' => 'This is a new todo.',
            'completed' => false,
        ]);
    }

    /** @test */
    public function it_can_update_a_todo()
    {
        $this->actingAs($this->user);

        $todo = $this->user->todos()->create([
            'title' => 'Old Todo',
            'description' => 'This is an old todo.',
            'completed' => false,
        ]);

        $response = $this->putJson("/api/todos/{$todo->id}", [
            'title' => 'Updated Todo',
            'description' => 'This is an updated todo.',
            'completed' => true,
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'title' => 'Updated Todo',
                     'description' => 'This is an updated todo.',
                     'completed' => true,
                 ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'Updated Todo',
            'description' => 'This is an updated todo.',
            'completed' => true,
        ]);
    }

    /** @test */
    public function it_can_delete_a_todo()
    {
        $this->actingAs($this->user);

        $todo = $this->user->todos()->create([
            'title' => 'Todo to be deleted',
            'description' => 'This todo will be deleted.',
            'completed' => false,
        ]);

        $response = $this->deleteJson("/api/todos/{$todo->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }
}
