<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать поставщиков</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-100 p-5">
    <div class="container mx-auto bg-white p-5 rounded shadow">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-3">
            <a href="{{ url()->previous() }}" class="inline-block px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                ← Назад
            </a>
            <div class="flex gap-3 flex-col sm:flex-row w-full sm:w-auto">
                <a href="{{ route('purchases.createWithSupplier', ['supplier_id' => $supplier->id]) }}" 
                class="inline-block w-full sm:w-auto px-4 py-2 text-center bg-green-500 text-white rounded hover:bg-green-600">
                    Добавить закупку
                </a>
                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="inline-block w-full sm:w-auto px-4 text-center py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Изменить поставщика</a>
            </div>
        </div>

        <h1 class="text-3xl font-bold mb-4">{{ $supplier->name }}</h1>
        <p><strong>Адрес:</strong> {{ $supplier->address }}</p>
        <p><strong>Телефон:</strong> {{ $supplier->phone }}</p>
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

        <h2 class="text-2xl font-bold mt-6 mb-3">Запчасти</h2>
        <div class="overflow-y-hidden mb-5">
            <table class="w-full table-auto bg-gray-100 shadow-md rounded-lg">
                <thead class='bg-gray-300'>
                    <tr>
                        <th class="px-4 py-2 text-left">Название</th>
                        <th class="px-4 py-2 text-left">Цена</th>
                        <th class="px-4 py-2 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($parts as $part)
                    <tr class='border-t'>
                        <td class="px-4 py-2">{{ $part->name }}</td>
                        <td class="px-4 py-2">{{ $part->pivot->price }} ₽</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('parts.edit', $part->id) }}" class="text-blue-500 hover:text-blue-700">
                                Изменить
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $parts->links() }}
        </div>
    </div>
</body>

</html>
