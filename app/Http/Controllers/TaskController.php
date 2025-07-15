<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

/**
 * @OA\Tag(
 *     name="Tasks",
 *     description="API для управления задачами"
 * )
 *
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     required={"id", "name", "completed"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Test task"),
 *     @OA\Property(property="description", type="string", example="Task description"),
 *     @OA\Property(property="completed", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-15T12:00:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="TaskRequest",
 *     type="object",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", example="New task"),
 *     @OA\Property(property="description", type="string", example="Task details"),
 *     @OA\Property(property="completed", type="boolean", example=false)
 * )
 */
class TaskController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Получить список задач",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ с массивом задач",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Task")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return TaskResource::collection(Task::all());
    }

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     tags={"Tasks"},
     *     summary="Создать новую задачу",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Задача успешно создана",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     )
     * )
     */
    public function store(TaskRequest $request)
    {
        $task = Task::query()->create($request->validated());

        return new TaskResource($task);
    }

    /**
     * @OA\Get(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Получить задачу по ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Задача успешно получена",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     )
     * )
     */
    public function show(Task $task)
    {
        return new TaskResource($task);
    }

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Обновить задачу",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи для обновления",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TaskRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Задача успешно обновлена",
     *         @OA\JsonContent(ref="#/components/schemas/Task")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Ошибка валидации"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     )
     * )
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());
        return new TaskResource($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     tags={"Tasks"},
     *     summary="Удалить задачу",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID задачи для удаления",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Задача успешно удалена"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Задача не найдена"
     *     )
     * )
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
