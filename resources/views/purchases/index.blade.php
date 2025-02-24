<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Закупки</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<div class="container mx-auto p-6 rounded-lg shadow-md bg-white mb-4"> <!-- Изменен фон на белый -->
    <nav class="flex justify-between items-center"> <!-- Добавлено выравнивание по краям -->
        <ul class="flex space-x-8"> <!-- Увеличен отступ между элементами -->
            <li><a href="{{ route('home') }}" class="text-gray-800 text-lg hover:text-blue-600 transition duration-200 ml-4">Главная</a></li>
            <li><a href="{{ route('suppliers.index') }}" class="text-gray-800 text-lg hover:text-blue-600 transition duration-200 ml-4">Поставщики</a></li>
            <li><a href="{{ route('parts.index') }}" class="text-gray-800 text-lg hover:text-blue-600 transition duration-200 ml-4">Запчасти</a></li>
            <li><a href="{{ route('purchases.index') }}" class="text-gray-800 text-lg hover:text-blue-600 transition duration-200 ml-4">Закупки</a></li>
        </ul>
        <div class="flex items-center space-x-4"> <!-- Контейнер для кнопок профиля и выхода -->
            <form action="{{ route('logout') }}" method="POST" class="inline-block"> <!-- Форма для выхода -->
                @csrf
                <button type="submit" class="text-gray-800 text-lg hover:text-blue-600 transition duration-200 ml-4">Выход</button>
            </form>
        </div>
    </nav>
    @yield('content')
</div>

<body class="bg-gray-100 p-5">
    <div class="container mx-auto p-5 rounded shadow bg-white">
        <h1 class="text-3xl font-bold mb-5">Список закупок</h1>

        <!-- Форма фильтрации -->
        <form method="GET" action="{{ route('purchases.index') }}" class="mb-5">
            <div class="flex flex-wrap gap-4">
                <div class="flex flex-col w-full sm:w-1/4">
                    <label for="supplier_id" class="mb-1 font-medium">Поставщик</label>
                    <select name="supplier_id" id="supplier_id" class="p-2 border rounded">
                        <option value="">Все</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col w-full sm:w-1/4">
                    <label for="date_from" class="mb-1 font-medium">Дата с</label>
                    <input type="date" name="date_from" class="p-2 border rounded" value="{{ request('date_from') }}">
                </div>

                <div class="flex flex-col w-full sm:w-1/4">
                    <label for="date_to" class="mb-1 font-medium">Дата до</label>
                    <input type="date" name="date_to" class="p-2 border rounded" value="{{ request('date_to') }}">
                </div>

               
                <div class="flex items-end w-full sm:w-1/4 gap-3">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full">Фильтровать</button>
                    </div>

                    @if(request('supplier_id') || request('date_from') || request('date_to'))
                        <div class="flex items-end w-full sm:w-1/4">
                            <a href="{{ route('purchases.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded w-full text-center">
                                Сбросить
                            </a>
                        </div>
                    @endif

                <div class="flex justify-end w-full sm:w-1/4">
                    <a href="{{ route('purchases.selectSupplier') }}" class="bg-blue-600 hover:bg-blue-700 w-full text-white px-4 py-2 rounded text-center transition duration-300">
                        Добавить закупку
                    </a>
                </div>
            </div>
        </form>

        <!-- Таблица закупок -->
        <div class="overflow-y-auto bg-white shadow-lg rounded-lg">
            <table class="w-full table-auto">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">Дата</th>
                        <th class="px-4 py-2 text-left">Поставщик</th>
                        <th class="px-4 py-2 text-left">Статус</th>
                        <th class="px-4 py-2 text-left">Стоимость</th>
                        <th class="px-4 py-2 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchases as $purchase)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $purchase->purchase_date }}</td>
                        <td class="px-4 py-2">{{ $purchase->supplier->name }}</td>
                        <td class="px-4 py-2">{{ $purchase->status_text }}</td>
                        <td class="px-4 py-2">{{ $purchase->total_amount ? number_format($purchase->total_amount, 2) . ' ₽' : 'Не указано' }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('purchases.edit', $purchase->id) }}" class="text-blue-500 hover:text-blue-700">
                                Перейти
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-2 text-center text-red-500">Закупки не найдены.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Пагинация -->
        <div class="mt-5">
            {{ $purchases->withQueryString()->links() }}
        </div>
    </div>
</body>

</html>
