@extends('welcome')

@section('title', 'Home')

@section('content')
    @php
        use Illuminate\Support\Facades\Route;

        $routes = collect(Route::getRoutes())->map(function ($route) {
            return [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'action' => $route->getActionName(),
                'method' => implode('|', $route->methods()),
            ];
        });
    @endphp

    <h1 class="text-2xl font-bold mb-6">Список маршрутов</h1>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">URI</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Имя</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Метод</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Action</th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
            @foreach ($routes as $route)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2">
                        <a href="{{ url($route['uri']) }}" class="text-blue-600 hover:underline">
                            {{ $route['uri'] }}
                        </a>
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $route['name'] ?? '-' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $route['method'] }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $route['action'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
