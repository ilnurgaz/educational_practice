<div class="container mx-auto p-6 rounded-lg shadow-md bg-white mb-4">
    <nav class="flex justify-between items-center flex-wrap">

        <ul id="menu" class="hidden md:flex flex-col md:flex-row md:space-x-8 w-full md:w-auto mt-4 md:mt-0"> <!-- Скрыть на мобилках -->
            <li><a href="{{ route('home') }}" class="block text-gray-800 text-lg hover:text-blue-600 transition duration-200 py-2">Главная</a></li>
            <li><a href="{{ route('suppliers.index') }}" class="block text-gray-800 text-lg hover:text-blue-600 transition duration-200 py-2">Поставщики</a></li>
            <li><a href="{{ route('parts.index') }}" class="block text-gray-800 text-lg hover:text-blue-600 transition duration-200 py-2">Запчасти</a></li>
            <li><a href="{{ route('purchases.index') }}" class="block text-gray-800 text-lg hover:text-blue-600 transition duration-200 py-2">Закупки</a></li>

            <li class="md:flex hidden items-center">
                <form action="{{ route('logout') }}" method="POST" class="flex">
                    @csrf
                    <button type="submit" class="text-gray-800 text-lg hover:text-blue-600 transition duration-200">Выход</button>
                </form>
            </li>
        </ul>

        <div id="mobile-exit" class="hidden md:hidden mt-2 w-full">
            <form action="{{ route('logout') }}" method="POST" class="flex w-full">
                @csrf
                <button type="submit" class="text-gray-800 text-lg hover:text-blue-600 transition duration-200">Выход</button>
            </form>
        </div>
    </nav>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('menu');
        const mobileExit = document.getElementById('mobile-exit');
        menu.classList.toggle('hidden');
        mobileExit.classList.toggle('hidden');
    });
</script> 
