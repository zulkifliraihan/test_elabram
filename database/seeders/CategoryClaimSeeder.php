<?php

namespace Database\Seeders;

use App\Models\CategoryClaim;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryClaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $claimCategories = [
            [
                'name' => 'Travel Expenses',
                'description' => 'Expenses related to travel, including airfare, hotel, local transportation, and meals during business trips.'
            ],
            [
                'name' => 'Meal Expenses',
                'description' => 'Expenses related to meals incurred during business trips or meetings.'
            ],
            [
                'name' => 'Transportation Expenses',
                'description' => 'Expenses related to transportation, such as taxi fares, public transportation, or parking fees.'
            ],
            [
                'name' => 'Lodging Expenses',
                'description' => 'Expenses related to lodging in hotels or other accommodations.'
            ],
            [
                'name' => 'Office Supplies Expenses',
                'description' => 'Expenses related to the purchase or replacement of office supplies and work equipment.'
            ],
            [
                'name' => 'Training Expenses',
                'description' => 'Expenses related to training or educational courses required for the job.'
            ],
            [
                'name' => 'Vehicle Expenses',
                'description' => 'Expenses related to vehicle maintenance, fuel, and repairs for work-related use.'
            ],
            [
                'name' => 'Entertainment Expenses',
                'description' => 'Expenses related to entertainment, meals, or business meetings with clients or business partners.'
            ],
            [
                'name' => 'Medical Expenses',
                'description' => 'Expenses related to medical care or prescription medications.'
            ],
            [
                'name' => 'Communication Expenses',
                'description' => 'Expenses related to telephone, internet, or other communication costs for work.'
            ],
            [
                'name' => 'Other Expenses',
                'description' => 'Other miscellaneous expenses that do not fit into the above categories, such as shipping costs or conference fees.'
            ]
        ];

        foreach ($claimCategories as $value) {
            CategoryClaim::create([
                'name' => $value['name'],
                'description' => $value['description'],
            ]);
        }
        
    }
}
