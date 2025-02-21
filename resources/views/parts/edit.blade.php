@extends('parts.index')

@section('content')
    <div class="container mx-auto">
        <h2 class="text-2xl font-bold mb-4">Редактировать запчасть</h2>
        <form action="{{ route('parts.update', $part->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block mb-2">Название</label>
                <input type="text" id="name" name="name" value="{{ $part->name }}" class="w-full p-2 border rounded @error('name') border-red-500 @enderror" required>
            </div>

            <div class="mb-4">
                <label for="article" class="block mb-2">Артикул</label>
                <input type="text" id="article" name="article" value="{{ $part->article }}" class="w-full p-2 border rounded @error('article') border-red-500 @enderror" required>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Сохранить</button>
                <a href="{{ route('parts.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">Отмена</a>
            </div>
        </form>
    </div>
@endsection