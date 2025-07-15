<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function test_tasks_index_returns_tasks_list()
    {
        Task::query()->create([
            'name' => 'Task 1',
            'description' => 'Desc 1',
            'completed' => false,
        ]);
        Task::query()->create([
            'name' => 'Task 2',
            'description' => 'Desc 2',
            'completed' => true,
        ]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'completed', 'created_at']
                ]
            ]);
    }

    #[Test]
    public function test_can_create_task_with_valid_data()
    {
        $taskData = [
            'name' => 'New Task',
            'description' => 'Task description',
            'completed' => false,
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => $taskData['name'],
                'completed' => false,
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => $taskData['name'],
        ]);
    }

    #[Test]
    public function test_validation_errors_returned_for_invalid_data()
    {
        $taskData = [
            'name' => '',
            'description' => 'desc',
            'completed' => false,
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    #[Test]
    public function test_can_update_task()
    {
        $task = Task::query()->create([
            'name' => 'Old name',
            'description' => 'Old desc',
            'completed' => false,
        ]);

        $updateData = [
            'name' => 'Updated name',
            'description' => 'Updated desc',
            'completed' => true,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => $updateData['name'],
                'completed' => true,
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => $updateData['name'],
            'completed' => true,
        ]);
    }

    #[Test]
    public function test_can_delete_task()
    {
        $task = Task::query()->create([
            'name' => 'To be deleted',
            'description' => 'desc',
            'completed' => false,
        ]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
