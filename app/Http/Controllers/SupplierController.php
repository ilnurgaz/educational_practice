<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Part;
use App\Models\SupplierPart;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('search') && $request->input('search') !== '') {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('part_id') && $request->input('part_id') !== '') {
            $query->whereHas('supplierParts', function ($q) use ($request) {
                $q->where('part_id', $request->input('part_id'));
            });
        }

        $suppliers = $query->paginate(20);

        $parts = \App\Models\Part::all();

        return view('suppliers.index', compact('suppliers', 'parts'));
    }

    public function getParts(Supplier $supplier)
    {
        return $supplier->parts()->withPivot('price')->get();
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|unique:suppliers,name|max:255',
                'address' => 'required|max:255',
                'phone' => 'nullable|max:15',
            ], [
                'name.unique' => 'Такой поставщик уже существует', 
            ]);

            Supplier::create([
                'name' => $validated['name'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
            ]);

            return redirect()->route('suppliers.index')->with('success', 'Поставщик добавлен!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::with('supplierParts')->findOrFail($id);
        $parts = \App\Models\Part::all();
        return view('suppliers.edit', compact('supplier', 'parts'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'parts' => 'array',
            'parts.*.price' => 'nullable|numeric|min:0',
        ], [
            'name.unique' => 'Поставщик с таким названием уже существует.',
        ]);

        $supplier = Supplier::findOrFail($id);

        foreach ($request->parts ?? [] as $partId => $data) {
            if (isset($data['selected']) && (empty($data['price']) || $data['price'] <= 0)) {
                return redirect()->back()->withErrors(['parts.' . $partId . '.price' => 'Для выбранной запчасти должна быть указана цена'])->withInput();
            }
        }

        $supplier->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        $syncData = [];
        foreach ($request->parts ?? [] as $partId => $data) {
            if (isset($data['selected'])) {
                $syncData[$partId] = ['price' => $data['price']];
            }
        }

        $supplier->parts()->sync($syncData);

        return redirect()->route('suppliers.edit', $supplier->id)->with('success', 'Поставщик успешно обновлен');
    }

    public function show($id)
    {
        $supplier = Supplier::with(['parts' => function ($query) {
            $query->paginate(20);
        }])->findOrFail($id);

        $parts = $supplier->parts()->paginate(20);

        return view('suppliers.show', compact('supplier', 'parts'));
    }

    public function storePart(Request $request, $supplierId)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:parts,name',
            'article' => 'required|string|max:255|unique:parts,article',
            'price' => 'required|numeric|min:0',
        ], [
            'name.unique' => 'Запчасть с таким названием уже существует.',
            'article.unique' => 'Запчасть с таким артикулом уже существует.',
            'price.required' => 'Цена обязательна для заполнения.',
        ]);

        $supplier = Supplier::findOrFail($supplierId);

        $part = Part::create([
            'name' => $request->name,
            'article' => $request->article,
        ]);

        $supplier->parts()->attach($part->id, ['price' => $request->price]);

        return redirect()->route('suppliers.edit', $supplier->id)
            ->with('success', 'Запчасть успешно добавлена и связана с поставщиком.');
    }


    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Поставщик удалён!');
    }

}
