<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запчасти</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 p-5">

    @include('partials.navbar')

    <div class="container mx-auto bg-white p-5 rounded shadow">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
            <a href="{{ url()->previous() }}"
                class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                ← Назад
            </a>
            <a href="{{ route('parts.edit', $part->id) }}"
                class="inline-block px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                Изменить запчасть
            </a>
        </div>

        <h1 class="text-3xl font-bold mb-4">{{ $part->name }}</h1>
        <p class="mb-2"><strong>Артикул:</strong> {{ $part->article }}</p>

        <h2 class="text-2xl font-bold mt-6 mb-3">Поставщики</h2>
        <div class="overflow-y-hidden mb-5">
            <table class="w-full table-auto bg-gray-100 shadow-md rounded-lg">
                <thead class="bg-gray-300">
                    <tr>
                        <th class="px-4 py-2 text-left">Название поставщика</th>
                        <th class="px-4 py-2 text-left">Адрес</th>
                        <th class="px-4 py-2 text-left">Телефон</th>
                        <th class="px-4 py-2 text-left">Цена</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($part->suppliers as $supplier)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $supplier->name }}</td>
                        <td class="px-4 py-2">{{ $supplier->address }}</td>
                        <td class="px-4 py-2">{{ $supplier->phone }}</td>
                        <td class="px-4 py-2">{{ $supplier->pivot->price }} ₽</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Нет поставщиков для этой запчасти</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
