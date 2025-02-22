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
    <div class="container mx-auto">

        <h1 class="text-3xl font-bold mb-5">Поставщики</h1>
        @if (session('success'))
        <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4 fade-out">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function () {
                document.getElementById('success-message').style.display = 'none';
            }, 3000); // Скрыть сообщение через 3 секунды

        </script>
        @endif

        @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-4 fade-out">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block mb-2">Название</label>
                <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="address" class="block mb-2">Адрес</label>
                <input type="text" name="address" value="{{ old('address', $supplier->address) }}"
                    class="w-full p-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label for="phone" class="block mb-2">Телефон</label>
                <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                    class="w-full p-2 border rounded">
            </div>

            <div class="mb-4">
                <label class="block mb-2">Запчасти</label>
                @foreach ($parts as $part)
                <div class="flex items-center gap-2 mb-2">
                    <input type="checkbox" name="parts[{{ $part->id }}][selected]" value="1"
                        {{ $supplier->parts->contains($part->id) ? 'checked' : '' }}>
                    <label>{{ $part->name }}</label>

                    <input type="number" name="parts[{{ $part->id }}][price]" placeholder="Цена"
                        value="{{ $supplier->parts->find($part->id)?->pivot->price ?? '' }}"
                        class="p-1 border rounded w-24 ml-2">
                </div>
                @endforeach
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Сохранить</button>
                <a href="{{ route('suppliers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Отмена</a>
            </div>
        </form>
    </div>

</body>

</html>
