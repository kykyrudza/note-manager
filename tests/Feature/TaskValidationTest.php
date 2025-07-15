<?php

namespace Tests\Feature;

use Tests\TestCase;

class TaskValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh');
    }

    public function test_tasks_route_returns_successful()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
    }

    public function test_tasks_can_be_created_with_valid_data()
    {
        $taskData = [
            'name' => 'test name',
            'description' => 'test description',
            'completed' => false,
        ];

        $response = $this->post('/tasks', $taskData);
        $response->assertStatus(302);

        $this->assertDatabaseHas('tasks', [
            'name' => $taskData['name'],
        ]);
    }

    public function test_validation_errors_are_triggered_for_invalid_data()
    {
        $taskData = [
            'name' => '',
            'description' => 'test description',
            'completed' => false,
        ];

        $response = $this->post('/tasks', $taskData);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['name']);
    }
}
