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
            }, 3000);
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

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class='max-w-[500px]'>
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

            <label class="block mb-2">Запчасти</label>
            <div class="my-3">
                <button type="button" class="bg-green-500 text-white px-4 py-2 rounded" onclick="openModal()">Добавить запчасть</button>
            </div>
            <div class="mb-4 overflow-y-scroll max-h-[300px]">
                @php
                    $sortedParts = $parts->sortByDesc(function($part) use ($supplier) {
                        return $supplier->parts->contains($part->id) ? 1 : 0;
                    });
                @endphp
                @foreach ($sortedParts as $part)
                <div class="flex items-center gap-2 mb-2 justify-between">
                    <div class="flex flex-row flex-nowrap items-center gap-2">
                        <input type="checkbox" name="parts[{{ $part->id }}][selected]" value="1"
                            {{ $supplier->parts->contains($part->id) ? 'checked' : '' }}>
                        <label>{{ $part->name }}</label>
                    </div>
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

        <!-- Модальное окно -->
        <div id="addPartModal" class="fixed flex items-center justify-center inset-0 bg-gray-800 bg-opacity-50 hidden z-50 px-5">
            <div class="bg-white p-6 rounded-lg w-full sm:w-[100%] md:w-2/3 lg:w-5/12">
                <h2 class="text-xl font-bold mb-4">Добавить запчасть</h2>
                <form action="{{ route('suppliers.storePart', $supplier->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                    <div class="mb-4">
                        <label for="part_name" class="block mb-2">Название запчасти</label>
                        <input type="text" name="name" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="article" class="block mb-2">Артикул</label>
                        <input type="text" name="article" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block mb-2">Цена</label>
                        <input type="number" name="price" class="w-full p-2 border rounded" required>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Создать</button>
                        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded" onclick="closeModal()">Отмена</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openModal() {
                document.getElementById('addPartModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('addPartModal').classList.add('hidden');
            }
        </script>
    </div>
</body>
</html>