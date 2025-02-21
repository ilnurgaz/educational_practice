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
            ->paginate(10);

        return view('parts.index', compact('parts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'article' => 'required|string|unique:parts,article',
        ]);

        Part::create($request->only('name', 'article'));

        return redirect()->route('parts.index')->with('success', 'Запчасть успешно добавлена.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $part = Part::findOrFail($id);
        return view('parts.show', compact('part'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $part = Part::findOrFail($id);
        return view('parts.edit', compact('part'));
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