@extends('welcome')

@section('title', 'Task Details')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-semibold mb-4">Task Details</h1>

        <div id="errorMessages" class="mb-4 hidden p-4 bg-red-100 text-red-700 rounded"></div>

        <form id="taskForm">
            @csrf
            <label for="name" class="block mb-1 font-medium text-gray-700">Task Name</label>
            <input
                type="text"
                name="name"
                id="name"
                required
                class="w-full mb-4 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            />

            <label for="description" class="block mb-1 font-medium text-gray-700">Task Description</label>
            <textarea
                name="description"
                id="description"
                class="w-full mb-4 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows="4"
            ></textarea>

            <label class="inline-flex items-center mb-4">
                <input type="checkbox" id="completed" name="completed" class="form-checkbox">
                <span class="ml-2">Completed</span>
            </label>

            <button
                type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition mr-2"
            >
                Update
            </button>

            <button
                type="button"
                id="deleteBtn"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
            >
                Delete
            </button>
        </form>
    </div>

    <script>
        const taskId = "{{ $taskId }}";
        const csrfToken = '{{ csrf_token() }}';
        const form = document.getElementById('taskForm');
        const errorMessages = document.getElementById('errorMessages');
        const deleteBtn = document.getElementById('deleteBtn');

        // Загрузка данных задачи из API
        async function loadTask() {
            try {
                const response = await fetch(`/api/tasks/${taskId}`);
                if (!response.ok) throw new Error('Failed to load task');

                const task = await response.json();

                form.name.value = task.name || '';
                form.description.value = task.description || '';
                form.completed.checked = task.completed;
            } catch (error) {
                errorMessages.textContent = error.message;
                errorMessages.classList.remove('hidden');
            }
        }

        // Отправка обновления задачи
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorMessages.classList.add('hidden');
            errorMessages.innerHTML = '';

            const data = {
                name: form.name.value,
                description: form.description.value,
                completed: form.completed.checked ? 1 : 0,
            };

            try {
                const response = await fetch(`/api/tasks/${taskId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data),
                });

                if (response.status === 422) {
                    const result = await response.json();
                    const errors = result.errors || {};
                    let html = '<ul class="list-disc list-inside text-sm">';
                    for (const key in errors) {
                        errors[key].forEach(msg => html += `<li>${msg}</li>`);
                    }
                    html += '</ul>';
                    errorMessages.innerHTML = html;
                    errorMessages.classList.remove('hidden');
                    return;
                }

                if (!response.ok) throw new Error('Failed to update task');

                alert('Task updated successfully!');
            } catch (error) {
                errorMessages.textContent = error.message;
                errorMessages.classList.remove('hidden');
            }
        });

        // Удаление задачи
        deleteBtn.addEventListener('click', async () => {
            if (!confirm('Are you sure you want to delete this task?')) return;

            try {
                const response = await fetch(`/api/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error('Failed to delete task');

                alert('Task deleted!');
                window.location.href = '/tasks'; // Перенаправить на список задач
            } catch (error) {
                alert('Error deleting task: ' + error.message);
            }
        });

        // Загрузка данных при старте
        loadTask();
    </script>
@endsection
