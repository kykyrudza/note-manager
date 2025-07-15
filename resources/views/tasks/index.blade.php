@extends('welcome')

@section('title', 'Tasks')

@section('content')
    <div class="lg:flex gap-6">
        <div class="w-full lg:w-4/12 bg-white p-6 rounded shadow">
            <h1 class="text-2xl font-semibold mb-4">Create Task</h1>
            <form method="POST" action="{{ route('tasks.store') }}">
                @csrf

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                        <ul class="list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <label for="name" class="block mb-1 font-medium text-gray-700">Task Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    required
                    class="w-full mb-4 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('name') }}"
                />

                <label for="description" class="block mb-1 font-medium text-gray-700">Task Description</label>
                <textarea
                    name="description"
                    id="description"
                    class="w-full mb-4 p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="4"
                >{{ old('description') }}</textarea>

                <input type="hidden" name="completed" value="0">

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Create
                </button>
            </form>
        </div>

        <div class="w-full lg:w-8/12 mt-8 lg:mt-0">
            <h2 class="text-2xl font-semibold mb-4">Task List</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @forelse ($tasks as $task)
                    <div class="bg-white p-4 rounded shadow">
                        <h3 class="font-bold text-lg">{{ $task->name }}</h3>
                        <p class="text-gray-700 mt-1">{{ $task->description }}</p>
                        <p class="text-sm mt-2 text-gray-500">
                            Completed: {{ $task->completed ? 'True' : 'False' }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 italic">No tasks found.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
