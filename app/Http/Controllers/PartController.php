<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parts = Part::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('article', 'like', '%' . $search . '%');
            })
            ->paginate(20);

        return view('parts.index', compact('parts'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Кастомные сообщения для валидации
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:parts,name',
                'article' => 'required|string|unique:parts,article',
            ], [
                'name.unique' => 'Запчасть с таким названием уже существует.', // Кастомное сообщение для названия
                'article.unique' => 'Запчасть с таким артикулом уже существует.', // Кастомное сообщение для артикула
            ]);

            // Создание новой запчасти
            Part::create([
                'name' => $validated['name'],
                'article' => $validated['article']
            ]);

            // Сообщение об успешном добавлении
            return redirect()->route('parts.index')->with('success', 'Запчасть добавлена!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Сообщение об ошибке, если запчасть уже существует
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'article' => 'required|string|unique:parts,article,' . $id,
        ]);

        $part = Part::findOrFail($id);
        $part->update($request->only('name', 'article'));

        return redirect()->route('parts.index')->with('success', 'Запчасть успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $part = Part::findOrFail($id);
        $part->delete();

        return redirect()->route('parts.index')->with('success', 'Запчасть успешно удалена.');
    }
}