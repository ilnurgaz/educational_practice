<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поставщики</title>
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
                setTimeout(function() {
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


        <!-- Контейнер для поиска и формы добавления -->
        <div class="mb-4 flex flex-wrap justify-between items-center">
            <!-- Форма поиска -->
            <form action="{{ route('suppliers.index') }}" method="GET" class="w-full sm:w-auto mb-4 sm:mb-0">
                <input type="text" name="search" value="{{ request('search') }}" placeholder='Поиск...' class="w-full sm:w-80 p-2 border rounded" placeholder="Поиск по названию">
            </form>

            <!-- Форма добавления -->
            <div class="flex sm:justify-start justify-start sm:justify-end  w-full mt-5 sm:mt-0 w-[100%] sm:sm:w-auto">
                <button class="bg-blue-500 text-white px-4 py-2 rounded" id="addSupplierButton">Добавить поставщика</button>
            </div>
        </div>

        <div class="overflow-y-hidden my-5">
            <table class="w-full overflow-y-scroll table-auto bg-white shadow-lg rounded-lg">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Название</th>
                        <th class="px-4 py-2 text-left">Адрес</th>
                        <th class="px-4 py-2 text-left">Телефон</th>
                        <th class="px-4 py-2 text-left">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($suppliers as $supplier)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $supplier->id }}</td>
                            <td class="px-4 py-2">{{ $supplier->name }}</td>
                            <td class="px-4 py-2">{{ $supplier->address }}</td>
                            <td class="px-4 py-2">{{ $supplier->phone }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
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

        @if($suppliers->isEmpty())
            <p class="mt-4 text-red-500">Поставщики не найдены.</p>
        @endif

        <!-- Пагинация -->
        {{ $suppliers->appends(request()->input())->links() }}        

    </div>

    <!-- Модальное окно для добавления поставщика -->
    <div id="modal-add-supplier" class="fixed flex items-center justify-center inset-0 bg-gray-800 bg-opacity-50 hidden z-50 px-5">
    <div class="bg-white p-6 rounded-lg w-full sm:w-[100%] md:w-2/3 lg:w-5/12">
        <h2 class="text-2xl font-bold mb-4">Добавить поставщика</h2>
        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block mb-2">Название</label>
                <input type="text" id="name" name="name" class="w-full p-2 border rounded @error('name') border-red-500 @enderror" required>
            </div>

            <div class="mb-4">
                <label for="address" class="block mb-2">Адрес</label>
                <input type="text" id="address" name="address" class="w-full p-2 border rounded @error('address') border-red-500 @enderror" required>
            </div>

            <div class="mb-4">
                <label for="phone" class="block mb-2">Телефон</label>
                <input type="text" id="phone" name="phone" class="w-full p-2 border rounded @error('phone') border-red-500 @enderror" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Добавить</button>
                <button type="button" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded" id="closeModalButton">Отмена</button>
            </div>
        </form>
    </div>
</div>
</div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const addSupplierButton = document.getElementById('addSupplierButton');
        const modal = document.getElementById('modal-add-supplier');
        const closeModalButton = document.getElementById('closeModalButton');
        const form = modal.querySelector('form');
        const nameInput = document.getElementById('name');

        addSupplierButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModalButton.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Валидация перед отправкой формы
        form.addEventListener('submit', function (event) {
            const name = nameInput.value.trim();
            const address = document.getElementById('address').value.trim();
            const phone = document.getElementById('phone').value.trim();

            // Проверка на пустые поля
            if (!name || !address || !phone) {
                event.preventDefault();
                alert("Все поля должны быть заполнены!");
                return;
            }

            // Проверка уникальности названия на стороне клиента
            // Примерно можно посылать AJAX-запрос или выполнять проверку локально
            // Но для реальной валидации уникальности нужно использовать серверную логику (показано ниже)
            });
        });
    </script>
</body>
</html>
