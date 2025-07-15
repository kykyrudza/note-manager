@extends('welcome')

@section('title', 'Tasks')

@section('content')
    <div class="lg:flex gap-6">
        <div class="w-full lg:w-4/12 bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-semibold mb-4">Create Task</h1>

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

                <input type="hidden" name="completed" value="0">

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                >
                    Create
                </button>
            </form>
        </div>

        <div class="w-full lg:w-8/12 mt-8 lg:mt-0">
            <h2 class="text-2xl font-semibold mb-4">Task List</h2>
            <div id="tasksList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">

            </div>
        </div>
    </div>

    <script>
        const taskForm = document.getElementById('taskForm');
        const tasksList = document.getElementById('tasksList');
        const errorMessages = document.getElementById('errorMessages');
        const csrfToken = '{{ csrf_token() }}';

        async function loadTasks() {
            tasksList.innerHTML = 'Loading...';

            try {
                const response = await fetch('/api/tasks');
                if (!response.ok) throw new Error('Failed to load tasks');
                const tasks = await response.json();

                if (tasks.length === 0) {
                    tasksList.innerHTML = '<p class="text-gray-500 italic">No tasks found.</p>';
                    return;
                }

                tasksList.innerHTML = '';
                tasks.data.forEach(task => {
                    const div = document.createElement('div');
                    div.className = 'bg-white p-4 rounded shadow';

                    div.innerHTML = `
                    <a href="tasks/${task.id}">
                        <h3 class="font-bold text-lg">${task.name}</h3>
                        <p class="text-gray-700 mt-1">${task.description || ''}</p>
                        <p class="text-sm mt-2 text-gray-500">
                            Completed: ${task.completed ? 'True' : 'False'}
                        </p>
                    </a>

                `;
                    tasksList.appendChild(div);
                });
            } catch (error) {
                tasksList.innerHTML = `<p class="text-red-600">Error loading tasks: ${error.message}</p>`;
            }
        }

        taskForm.addEventListener('submit', async e => {
            e.preventDefault();
            errorMessages.classList.add('hidden');
            errorMessages.innerHTML = '';

            const formData = new FormData(taskForm);

            try {
                const response = await fetch('/api/tasks', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                if (response.status === 422) {
                    const data = await response.json();
                    const errors = data.errors || {};
                    let html = '<ul class="list-disc list-inside text-sm">';
                    for (const key in errors) {
                        errors[key].forEach(msg => {
                            html += `<li>${msg}</li>`;
                        });
                    }
                    html += '</ul>';
                    errorMessages.innerHTML = html;
                    errorMessages.classList.remove('hidden');
                    return;
                }

                if (!response.ok) throw new Error('Failed to create task');

                const newTask = await response.json();

                taskForm.reset();

                loadTasks();
            } catch (error) {
                errorMessages.innerHTML = `<p>${error.message}</p>`;
                errorMessages.classList.remove('hidden');
            }
        });

        loadTasks();
    </script>
@endsection
