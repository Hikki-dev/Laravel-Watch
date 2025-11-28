<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

class LegacyDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Users (Admin, Seller, Customer)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $seller = User::firstOrCreate(
            ['email' => 'seller@example.com'],
            [
                'name' => 'John Seller',
                'password' => bcrypt('password'),
                'role' => 'seller',
                'is_active' => true,
                'phone' => '123-456-7890',
                'address' => '123 Watch St',
                'city' => 'New York',
                'country' => 'USA',
                'postal_code' => '10001'
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Jane Customer',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_active' => true,
                'phone' => '987-654-3210',
                'address' => '456 Buyer Ln',
                'city' => 'Los Angeles',
                'country' => 'USA',
                'postal_code' => '90001'
            ]
        );

        // 2. Create Categories (Brands)
        $brands = [
            [
                'name' => 'Rolex',
                'description' => 'Rolex watches are crafted from the finest raw materials and assembled with scrupulous attention to detail.',
                'image' => 'rolex-logo.jpg'
            ],
            [
                'name' => 'Omega',
                'description' => 'Omega has been the official timekeeper of the Olympic Games since 1932.',
                'image' => 'omega-logo.jpg'
            ],
            [
                'name' => 'Patek Philippe',
                'description' => 'Patek Philippe is widely considered to be one of the most prestigious watch manufacturers in the world.',
                'image' => 'patek-philippe-logo.jpg'
            ],
            [
                'name' => 'Audemars Piguet',
                'description' => 'Audemars Piguet is the oldest fine watchmaking manufacturer still in the hands of its founding families.',
                'image' => 'audemars-piguet-logo.jpg'
            ],
            [
                'name' => 'Richard Mille',
                'description' => 'Richard Mille watches are known for their technical innovation, architectural design, and use of high-tech materials.',
                'image' => 'richard-mille-logo.jpg'
            ],
            [
                'name' => 'Swatch',
                'description' => 'Swatch is a Swiss watchmaker founded in 1983, known for its colorful, affordable, and plastic watches.',
                'image' => 'swatch-logo.jpg'
            ]
        ];

        foreach ($brands as $brandData) {
            $category = Category::firstOrCreate(
                ['name' => $brandData['name']],
                [
                    'slug' => Str::slug($brandData['name']),
                    'description' => $brandData['description'],
                    'image' => $brandData['image'] ?? null
                ]
            );

            // Create Dummy Products for each Brand
            $this->createProductsForBrand($category, $seller); // Assign products to Seller
        }
    }

    private function createProductsForBrand($category, $seller)
    {
        $products = [];

        switch ($category->name) {
             case 'Rolex':
                $products = [
                    [
                        'name' => 'Submariner Date',
                        'price' => 14500.00,
                        'model' => '126610LN',
                        'description' => 'The Oyster Perpetual Submariner Date in Oystersteel with a Cerachrom bezel insert in black ceramic and a black dial with large luminescent hour markers.',
                        'image_url' => 'images/products/rolex-submariner-1.jpg'
                    ],
                    [
                        'name' => 'Submariner "Hulk"',
                        'price' => 18500.00,
                        'model' => '116610LV',
                        'description' => 'The Submariner Date with a green bezel and green dial, affectionately known as the "Hulk".',
                        'image_url' => 'images/products/rolex-submariner-2.jpg'
                    ],
                    [
                        'name' => 'Daytona Panda',
                        'price' => 35000.00,
                        'model' => '116500LN',
                        'description' => 'The Oyster Perpetual Cosmograph Daytona in Oystersteel with a white dial and black Cerachrom bezel.',
                        'image_url' => 'images/products/rolex-submariner-2.jpg' // Placeholder
                    ],
                    [
                        'name' => 'GMT-Master II Pepsi',
                        'price' => 22000.00,
                        'model' => '126710BLRO',
                        'description' => 'Designed to show the time in two different time zones simultaneously, the GMT-Master II, launched in 1955, was originally developed as a navigation instrument for professionals criss-crossing the globe.',
                        'image_url' => 'images/products/rolex-gmt-1.jpg'
                    ]
                ];
                break;
            case 'Omega':
                $products = [
                    [
                        'name' => 'Speedmaster Moonwatch',
                        'price' => 7600.00,
                        'model' => '310.30.42.50.01.001',
                        'description' => 'The Speedmaster Moonwatch is one of the world’s most iconic timepieces. Having been a part of all six lunar missions, the legendary chronograph is an impressive representation of the brand’s adventurous pioneering spirit.',
                        'image_url' => 'images/products/omega-speedmaster-1.jpg'
                    ],
                    [
                        'name' => 'Speedmaster \'57',
                        'price' => 9500.00,
                        'model' => '332.10.41.51.01.001',
                        'description' => 'The Speedmaster \'57 is a modern interpretation of the original 1957 Speedmaster, featuring a broad arrow hand and a manual-winding movement.',
                        'image_url' => 'images/products/omega-speedmaster-2.jpg'
                    ],
                    [
                        'name' => 'Seamaster Diver 300M',
                        'price' => 5600.00,
                        'model' => '210.30.42.20.03.001',
                        'description' => 'Since 1993, the Seamaster Professional Diver 300M has enjoyed a legendary following. Today’s modern collection has embraced that famous ocean heritage and updated it with OMEGA’s best innovation and design.',
                        'image_url' => 'images/products/omega-seamaster-1.jpg'
                    ]
                ];
                break;
            case 'Patek Philippe':
                $products = [
                    [
                        'name' => 'Nautilus',
                        'price' => 120000.00,
                        'model' => '5711/1A',
                        'description' => 'With the rounded octagonal shape of its bezel, the ingenious porthole construction of its case, and its horizontally embossed dial, the Nautilus has epitomized the elegant sports watch since 1976.',
                        'image_url' => 'images/products/patek-nautilus-1.jpg'
                    ],
                    [
                        'name' => 'Nautilus Rose Gold',
                        'price' => 180000.00,
                        'model' => '5711/1R',
                        'description' => 'The Nautilus in rose gold combines the sporty elegance of the model with the warmth and luxury of the precious metal.',
                        'image_url' => 'images/products/patek-nautilus-2.jpg'
                    ],
                    [
                        'name' => 'Calatrava',
                        'price' => 32000.00,
                        'model' => '6119R',
                        'description' => 'The Calatrava is the essence of the round wristwatch and one of the finest symbols of the Patek Philippe style.',
                        'image_url' => 'images/products/patek-calatrava-1.jpg'
                    ]
                ];
                break;
             case 'Audemars Piguet':
                $products = [
                    [
                        'name' => 'Royal Oak',
                        'price' => 45000.00,
                        'model' => '15500ST',
                        'description' => 'Experience the iconic Royal Oak, whose pioneering design and craftsmanship embody Audemars Piguet\'s uncompromising vision of luxury.',
                        'image_url' => 'images/products/ap-royal-oak-1.jpg'
                    ],
                    [
                        'name' => 'Royal Oak Offshore',
                        'price' => 55000.00,
                        'model' => '26420SO',
                        'description' => 'The Royal Oak Offshore collection defies conventions with its larger, more robust case and sporty aesthetic.',
                        'image_url' => 'images/products/ap-royal-oak-2.jpg'
                    ]
                ];
                break;
             case 'Richard Mille':
                $products = [
                    [
                        'name' => 'RM 11-03',
                        'price' => 350000.00,
                        'model' => 'RM 11-03',
                        'description' => 'The RM 11-03 Automatic Flyback Chronograph represents the perfect synthesis of the brand\'s philosophy.',
                        'image_url' => 'images/products/richard-mille-1.jpg'
                    ],
                    [
                        'name' => 'RM 35-02 Rafael Nadal',
                        'price' => 420000.00,
                        'model' => 'RM 35-02',
                        'description' => 'Inspired by the RM 27-02 tourbillon, the RM 35-02 Rafael Nadal is the first piece in the Nadal collection to feature an automatic calibre.',
                        'image_url' => 'images/products/richard-mille-2.jpg'
                    ]
                ];
                break;
             case 'Swatch':
                $products = [
                    [
                        'name' => 'Big Bold Chrono',
                        'price' => 150.00,
                        'model' => 'SB02B400',
                        'description' => 'The BIG BOLD CHRONO CHECKPOINT BLACK features a matte black dial with contrasting white and colored elements.',
                        'image_url' => 'images/products/swatch-big-bold-chrono-1.jpg'
                    ],
                    [
                        'name' => 'Sistem51 Irony',
                        'price' => 215.00,
                        'model' => 'YIS401G',
                        'description' => 'SISTEM51 IRONY is the automatic watch for those who value clean, classic design.',
                        'image_url' => 'images/products/swatch-sistem51-irony-1.jpg'
                    ],
                    [
                        'name' => 'Skin Classic',
                        'price' => 125.00,
                        'model' => 'SFK361',
                        'description' => 'The ultra-thin SKIN CLASSIC watch features a transparent plastic case and a minimalist dial.',
                        'image_url' => 'images/products/swatch-skin-classic-1.jpg'
                    ]
                ];
                break;
        }

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['name' => $productData['name']], // Check by name to update if exists
                [
                    'seller_id' => $seller->id,
                    'category_id' => $category->id,
                    'slug' => Str::slug($productData['name']),
                    'description' => $productData['description'],
                    'brand' => $category->name,
                    'model' => $productData['model'],
                    'price' => $productData['price'],
                    'original_price' => $productData['price'] * 1.1,
                    'condition_type' => 'new',
                    'stock_quantity' => 10,
                    'is_featured' => true,
                    'is_active' => true,
                    'status' => 'approved',
                    'image_url' => $productData['image_url'], // Update image URL
                    'movement_type' => 'Automatic',
                    'case_material' => 'Steel',
                    'dial_color' => 'Black',
                    'strap_material' => 'Steel',
                    'water_resistance' => '100m',
                    'year_manufactured' => 2023,
                    'warranty_info' => '5 Years',
                ]
            );
        }
    }
}
