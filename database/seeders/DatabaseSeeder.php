<?php

use Illuminate\Database\Seeder;
use App\Models\Supplier;
use App\Models\Part;
use App\Models\SupplierPart;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Создаём поставщиков
        $supplier1 = Supplier::create([
            'name' => 'AutoParts Ltd',
            'address' => 'ул. Ленина, 10',
            'phone' => '123-456-789'
        ]);

        $supplier2 = Supplier::create([
            'name' => 'CarService',
            'address' => 'пр. Победы, 25',
            'phone' => '987-654-321'
        ]);

        // Создаём запчасти
        $part1 = Part::create(['name' => 'Фильтр масляный', 'article' => 'F123']);
        $part2 = Part::create(['name' => 'Тормозные колодки', 'article' => 'B456']);

        // Добавляем связь поставщик → запчасть с ценой
        SupplierPart::create([
            'supplier_id' => $supplier1->id,
            'part_id' => $part1->id,
            'price' => 500
        ]);

        SupplierPart::create([
            'supplier_id' => $supplier2->id,
            'part_id' => $part2->id,
            'price' => 1200
        ]);
    }
}
