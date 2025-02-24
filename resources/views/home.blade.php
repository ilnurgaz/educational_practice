<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная страница</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 p-5">
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


</body>
</html>