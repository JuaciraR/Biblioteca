<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
    public function run(): void
    {
        $publishers = [
            ['name' => 'Penguin Random House', 'logo' => 'logos/penguin.png'],
            ['name' => 'HarperCollins', 'logo' => 'logos/harpercollins.png'],
            ['name' => 'Macmillan Publishers', 'logo' => 'logos/macmillan.png'],
            ['name' => 'Simon & Schuster', 'logo' => 'logos/simon.png'],
            ['name' => 'Hachette Livre', 'logo' => 'logos/hachette.png'],
        ];

        foreach ($publishers as $p) {
            Publisher::create($p);
        }
    }
}
