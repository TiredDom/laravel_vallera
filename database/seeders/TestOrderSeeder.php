<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;

class TestOrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::take(3)->get();

        if ($users->isEmpty()) {
            $this->command->error('No users found. Please seed users first.');
            return;
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $products = [
            ['id' => 1, 'name' => 'Luxe Comfort Sofa', 'price' => 74999, 'category' => 'Sofas'],
            ['id' => 2, 'name' => 'Minimalist Oak Desk', 'price' => 39999, 'category' => 'Tables'],
            ['id' => 3, 'name' => 'Velvet Dream Armchair', 'price' => 29999, 'category' => 'Chairs'],
            ['id' => 4, 'name' => 'Industrial Metal Bookshelf', 'price' => 44999, 'category' => 'Storage'],
            ['id' => 5, 'name' => 'Aether Minimalist Lamp', 'price' => 7999, 'category' => 'Lighting'],
        ];

        foreach ($users as $index => $user) {
            $orderCount = rand(1, 3);

            for ($i = 0; $i < $orderCount; $i++) {
                $itemsCount = rand(1, 3);
                $total = 0;

                $order = $user->orders()->create([
                    'total' => 0,
                    'status' => $statuses[array_rand($statuses)],
                ]);

                for ($j = 0; $j < $itemsCount; $j++) {
                    $product = $products[array_rand($products)];
                    $quantity = rand(1, 2);
                    $itemTotal = $product['price'] * $quantity;
                    $total += $itemTotal;

                    $order->items()->create([
                        'product_id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity,
                        'category' => $product['category'],
                    ]);
                }

                $order->update(['total' => $total + 500]);
            }

            $this->command->info("Created {$orderCount} order(s) for {$user->name}");
        }

        $this->command->info('Test orders created successfully!');
    }
}
