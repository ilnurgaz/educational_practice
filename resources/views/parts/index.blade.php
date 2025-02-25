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

    <div class="container mx-auto bg-white shadow p-4 rounded-lg">

        <h1 class="text-3xl font-bold mb-5">Запчасти</h1>
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


        <div class="flex flex-wrap justify-between items-center">
            <form action="{{ route('parts.index') }}" method="GET"
                class="w-full sm:w-auto flex flex-col space-x-2 gap-3">
                <div class="flex gap-3 flex-col sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Поиск..."
                        class="w-full sm:w-80 p-2 border rounded sm:min-w-[200px]">

                    <select name="supplier_id" class="p-2 border rounded w-[100%] sm:w-[200px] sm:w-[100%] !ml-0">
                        <option value="">Все поставщики</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 flex-col sm:flex-row !ml-0">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded !ml-0">Фильтровать</button>
                    @if (request()->has('search') || request()->has('supplier_id'))
                    <a href="{{ route('parts.index') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded !ml-0 text-center">Сбросить</a>
                    @endif
                </div>
            </form>

            <div class="flex justify-start w-full ml-0 mb-5 mt-3">
                <button class="bg-blue-500 text-white px-4 py-2 rounded w-full sm:w-auto" id="addPartButton">Добавить
                    запчасть</button>
            </div>
        </div>

        <div class="overflow-y-hidden mb-5">
            <table class="w-full overflow-y-scroll table-auto bg-white shadow-lg rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Название</th>
                        <th class="px-4 py-2 text-left">Артикул</th>
                        <th class="px-4 py-2 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parts as $part)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $part->id }}</td>
                        <td class="px-4 py-2">{{ $part->name }}</td>
                        <td class="px-4 py-2">{{ $part->article }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('parts.show', $part->id) }}" class="text-green-500 hover:text-green-700">
                                Посмотреть
                            </a>
                            <a href="{{ route('parts.edit', $part->id) }}" class="text-blue-500 hover:text-blue-700">
                                Редактировать
                            </a>
                            <form action="{{ route('parts.destroy', $part->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">Удалить</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        @if($parts->isEmpty())
        <p class="mt-4 text-red-500">Запчасти не найдены.</p>
        @endif

        {{ $parts->appends(request()->input())->links() }}

    </div>

    <div id="modal-add-part"
        class="fixed flex items-center justify-center inset-0 bg-gray-800 bg-opacity-50 hidden z-50 px-5">
        <div class="bg-white p-6 rounded-lg w-full sm:w-[100%] md:w-2/3 lg:w-5/12">
            <h2 class="text-2xl font-bold mb-4">Добавить запчасть</h2>
            <form action="{{ route('parts.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block mb-2">Название</label>
                    <input type="text" id="name" name="name"
                        class="w-full p-2 border rounded @error('name') border-red-500 @enderror" required>
                </div>

                <div class="mb-4">
                    <label for="article" class="block mb-2">Артикул</label>
                    <input type="text" id="article" name="article"
                        class="w-full p-2 border rounded @error('article') border-red-500 @enderror" required>
                </div>
                <label class="block mb-2">Выберите поставщиков и укажите цену</label>
                <div class="mb-4 px-3 overflow-y-scroll" style='max-height: 200px;' id="supplierList">
                    @foreach($suppliers as $supplier)
                    <div class="flex justify-between mb-2">
                        <div class="">
                            <input type="checkbox" id="supplier_{{ $supplier->id }}"
                                name="suppliers[{{ $supplier->id }}][selected]" value="1" class="mr-2">
                            <label for="supplier_{{ $supplier->id }}" class="mr-4">{{ $supplier->name }}</label>
                        </div>
                        <input type="number" step="0.01" min="0" name="suppliers[{{ $supplier->id }}][price]"
                            placeholder="Цена" class="p-2 border rounded w-32">
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Добавить</button>
                    <button type="button" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded"
                        id="closeModalButton">Отмена</button>
                </div>
            </form>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addPartButton = document.getElementById('addPartButton');
            const modal = document.getElementById('modal-add-part');
            const closeModalButton = document.getElementById('closeModalButton');

            addPartButton.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });

            closeModalButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });

        const editModal = document.getElementById('editModal');
        const editForm = document.getElementById('editForm');
        const editNameInput = document.getElementById('editName');
        const editArticleInput = document.getElementById('editArticle');
        const supplierContainer = document.getElementById('supplierList');
        const closeModalButton = document.getElementById('closeModal');

    </script>

</body>

</html>
