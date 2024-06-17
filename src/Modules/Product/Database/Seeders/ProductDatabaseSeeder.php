<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Product\Entities\ActiveIngredient;
use Modules\Product\Entities\IngredientFamily;
use Modules\Product\Entities\Manufacturer;
use Modules\Product\Entities\Offer;
use Modules\Product\Entities\Product;
use Modules\Product\Enums\OfferType;
use Modules\Product\Enums\ProductType;
use Modules\Product\Enums\SlatType;

class ProductDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Manufacturer::create([
            'name' => [
                'en' => 'Ramix pharma company',
                'ar' => 'شركة رامكس فارما',
            ],
        ]);

        Manufacturer::create([
            'name' => [
                'en' => 'Alpha Cure Medical',
                'ar' => 'ألفا كير',
            ],
        ]);

        Manufacturer::create([
            'name' => [
                'en' => 'Misr pharma company',
                'ar' => 'شركة مصر فارما',
            ],
        ]);

        IngredientFamily::create([
            'name' => 'amol',
        ]);

        IngredientFamily::create([
            'name' => 'statin',
        ]);

        ActiveIngredient::create([
            'name' => 'Paracetamol',
            'description' => 'pain relief medicines',
            'ingredient_families_id' => rand(1, 2),
        ]);

        ActiveIngredient::create([
            'name' => 'apamamol',
            'description' => 'pain relief medicines',
            'ingredient_families_id' => rand(1, 2),
        ]);

        ActiveIngredient::create([
            'name' => 'Paracetvastatin',
            'description' => 'pain relief medicines',
            'ingredient_families_id' => rand(1, 2),
        ]);

        ActiveIngredient::create([
            'name' => 'Atorvastatin',
            'description' => 'cholesterol-lowering medicines',
            'ingredient_families_id' => rand(1, 2),
        ]);

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APROVEL 150 MG TAB',
                'ar' => 'ابروفال 150مجم 14قرص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 10,
            'is_limited' => 1,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'ABILIFY 15 MG TAB',
                'ar' => 'ابليفاى 15 مجم اقراص ',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 20,
            'is_limited' => 1,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'IBIDROXIL 1 G TABLETS',
                'ar' => 'ابيدروكسيل 1 جرام كبسول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 30,
            'is_limited' => 1,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'IBIDROXIL 1 G TABLETS',
                'ar' => 'اوجمنتين  اكياس انتبه',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 40,
            'is_limited' => 1,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'AUGMENTIN  DROPS',
                'ar' => 'اوجمنتين نقط اطفال',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 50,
            'is_limited' => 1,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'ORACIN MW 120ML',
                'ar' => 'اوراسين غرغرة120 ملى س ج',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'TANTUM  GEL',
                'ar' => 'تانتم جيل س ج',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'TEGRETOL 200 SR TAB',
                'ar' => 'تجريتول 200 سى ار اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'TEARS NATURAL DPS',
                'ar' => 'تيرز  ناتشورال قطره',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'GYNOZOL 400 SUPP',
                'ar' => 'جينوزول 400 لبوس',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'DAPABLIX',
                'ar' => 'دابابليكس 10/1000م 30قرص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 2),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'A VITON TAB',
                'ar' => 'ا فيتون اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'A VITON TAB',
                'ar' => 'ابياموكس 200مجم شراب',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'IBIAMOX 400 MG',
                'ar' => 'ابياموكس 400 مجم شراب',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'IBIAMOX 500M,G CAP',
                'ar' => 'ابياموكس 500مجم كبسول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPITENS TAB',
                'ar' => 'ابيتنس اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APETOID',
                'ar' => 'ابيتويد 20مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIGONAL-AMPOL',
                'ar' => 'ابيجونال امبول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::INJECTIONS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIGENT 80MG AMP',
                'ar' => 'ابيجينت 80مجم امبول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::INJECTIONS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'IBIDROXIL 1 G TABLETS',
                'ar' => 'ابيدروكسيل 1 جرام كبسول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::INJECTIONS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'IBIDROXIL 250 SYP',
                'ar' => 'ابيدروكسيل 250مجم شراب',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::INJECTIONS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'IBIDROXIL 500 MG CAPSULES',
                'ar' => 'ابيدروكسيل 500مجم كبسول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::CAPSULES,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIDRON VIAL',
                'ar' => 'ابيدرون فيال',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::CAPSULES,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIDEXONE DEXAMETH',
                'ar' => 'ابيدكسون قطره',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::DROPS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APIDONE 125ML SYP',
                'ar' => 'ابيدون شراب',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIRAZOLE 20 MG CAPSULES',
                'ar' => 'ابيرازول 20مجم',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::LIQUID,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIRIZINE 10MG TAB',
                'ar' => 'ابيريزين 10مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APIFORTYL 30 CAPSULES',
                'ar' => 'ابيفورتيل كبسول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::CAPSULES,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APIFORTYL 30 CAPSULES',
                'ar' => 'ابيفورتيل كبسول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::CAPSULES,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIFENAC 25 MG',
                'ar' => 'ابيفيناك 25مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIFNAC 5 SUPPOSITORIES',
                'ar' => 'ابيفيناك 100م لبوس',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::SUPPOSITORY,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EOIFEAC 25 MG SUPP',
                'ar' => 'ابيفيناك 25مجم لبوس',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::SUPPOSITORY,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIFEAC 50MG TAB',
                'ar' => 'ابيفيناك 50مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIFEAC AMP',
                'ar' => 'ابيفيناك امبول',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::INJECTIONS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPIFENAC EYE DROPS',
                'ar' => 'ابيفيناك قطره',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::DROPS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'EPICROM 4 DROPS',
                'ar' => 'ابيكروم قطره',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::DROPS,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APEXIDONE .5 MG',
                'ar' => 'ابيكسيدون .5مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APEXDONE 1MG TAB',
                'ar' => 'ابيكسيدون 1مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APEXDONE 2MG TAB',
                'ar' => 'ابيكسيدون 2مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APEXDONE 3MG TAB',
                'ar' => 'ابيكسيدون 3مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $product = Product::create([
            'created_by' => 1,
            'name' => [
                'en' => 'APEXDONE 4MG TAB',
                'ar' => 'ابيكسيدون 4مجم اقراص',
            ],
            'description' => Str::random(20),
            'sku' => Str::uuid(),
            'barcode' => random_int(100000, 999999),
            'limited_quantity' => 0,
            'is_limited' => 0,
            'manufacturer_id' => rand(1, 3),
            'type' => ProductType::TABLET,
            'price' => rand(50, 300),
            'taxes' => rand(5, 15),
            'normal_discount' => rand(5, 15),
            'items_number_in_packet' => rand(100, 200),
            'packets_number_in_package' => rand(100, 200),
            'manufacturing_type' => rand(0, 3),
            'note' => 'ملاحظات علي المنتج',
            'selling_status' => rand(0, 2),
            'buying_status' => rand(0, 2),
        ]);
        $product->activeIngredients()->attach(rand(1, 2));

        $offer = Offer::create([
            'created_by' => 1,
            'updated_by' => 1,
            'type' => OfferType::PERCENTAGE,
            'slat_type' => SlatType::SECOND_SLAT,
            'quantity_for_offer' => 40,
            'offer_value' => 4,
        ]);
        $offer->products()->attach([21, 22, 23, 24, 25, 26, 27, 28, 29]);

        $offer = Offer::create([
            'created_by' => 1,
            'updated_by' => 1,
            'type' => OfferType::PERCENTAGE,
            'slat_type' => SlatType::SECOND_SLAT,
            'quantity_for_offer' => 30,
            'offer_value' => 15,
        ]);
        $offer->products()->attach([30, 31, 32, 33, 34, 35, 36, 37, 38]);

        $offer = Offer::create([
            'created_by' => 1,
            'updated_by' => 1,
            'type' => OfferType::QUANTITY,
            'slat_type' => SlatType::FIRST_SLAT,
            'quantity_for_offer' => 10,
            'offer_value' => 1,
        ]);
        $offer->products()->attach([6, 7, 8, 9, 10]);

        $offer = Offer::create([
            'created_by' => 1,
            'updated_by' => 1,
            'type' => OfferType::QUANTITY,
            'slat_type' => SlatType::FIRST_SLAT,
            'quantity_for_offer' => 20,
            'offer_value' => 2,
        ]);
        $offer->products()->attach([11, 12, 13, 14, 15, 16, 17, 18, 19, 20]);

        $offer = Offer::create([
            'created_by' => 1,
            'updated_by' => 1,
            'type' => OfferType::PERCENTAGE,
            'slat_type' => SlatType::FIRST_SLAT,
            'quantity_for_offer' => 10,
            'offer_value' => 10,
        ]);
        $offer->products()->attach([30, 31, 32, 33, 34, 35, 36, 37, 38]);

        $offer = Offer::create([
            'created_by' => 1,
            'updated_by' => 1,
            'type' => OfferType::PERCENTAGE,
            'slat_type' => SlatType::FIRST_SLAT,
            'quantity_for_offer' => 20,
            'offer_value' => 20,
        ]);
    }
}
