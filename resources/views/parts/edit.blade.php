<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Запчасти</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="bg-gray-100 p-5">
    <div class="container mx-auto">

        <h1 class="text-3xl font-bold mb-5">Редактировать запчасть</h1>

        @if (session('success'))
            <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4 fade-out">
                {{ session('success') }}
            </div>
            <script>
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                }, 3000); // Скрыть сообщение через 3 секунды
            </script>
        @endif

        {{-- Вывод ошибок валидации --}}
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4 fade-out">
                <ul>
                @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('parts.update', $part->id) }}" method="POST" class='sm:max-w-[500px]'>
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block mb-2">Название</label>
                <input type="text" id="name" name="name" value="{{ $part->name }}" class="w-full p-2 border rounded"
                    required>
            </div>

            <div class="mb-4">
                <label for="article" class="block mb-2">Артикул</label>
                <input type="text" id="article" name="article" value="{{ $part->article }}"
                    class="w-full p-2 border rounded" required>
            </div>

            <label class="block mb-2">Поставщики и цена</label>
            <div class="mb-4 overflow-y-scroll sm:max-w-[500px] max-h-[300px]">
                @php
                    $selectedSuppliers = $suppliers->filter(fn($supplier) => $part->suppliers->contains($supplier->id));
                    $unselectedSuppliers = $suppliers->reject(fn($supplier) => $part->suppliers->contains($supplier->id));
                @endphp

                {{-- Отображаем выбранных поставщиков сначала --}}
                @foreach($selectedSuppliers as $supplier)
                <div class="flex justify-between mb-2">
                    <div>
                        <input type="checkbox" name="suppliers[{{ $supplier->id }}][selected]" value="1" checked>
                        <label>{{ $supplier->name }}</label>
                    </div>
                    <input type="number" step="0.01" min="0" name="suppliers[{{ $supplier->id }}][price]"
                        value="{{ $part->suppliers->find($supplier->id)?->pivot->price ?? '' }}"
                        class="p-1 border rounded w-24 ml-2">
                </div>
                @endforeach

                {{-- Отображаем остальных поставщиков --}}
                @foreach($unselectedSuppliers as $supplier)
                <div class="flex justify-between mb-2">
                    <div>
                        <input type="checkbox" name="suppliers[{{ $supplier->id }}][selected]" value="1">
                        <label>{{ $supplier->name }}</label>
                    </div>
                    <input type="number" step="0.01" min="0" name="suppliers[{{ $supplier->id }}][price]" value=""
                        class="p-1 border rounded w-24 ml-2">
                </div>
                @endforeach
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Сохранить изменения</button>
                <a href="{{ route('parts.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">Отмена</a>
            </div>
        </form>
    </div>
</body>

</html>
