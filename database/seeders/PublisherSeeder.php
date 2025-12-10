<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Publisher;

class PublisherSeeder extends Seeder
{
   public function run(): void
    {
        $publishers = [
            [
                'key' => 'penguin',
                'name' => 'Penguin Random House',
                'logo' => 'https://i0.wp.com/fontlot.com/wp-content/uploads/2022/06/3.jpg?fit=801%2C421&ssl=1',
            ],
            [
                'key' => 'harper',
                'name' => 'HarperCollins',
                'logo' => 'https://images.seeklogo.com/logo-png/40/1/harpercollins-publishers-logo-png_seeklogo-401077.png',
            ],
            [
                 'key' => 'macmillan',
                'name' => 'Macmillan Publishers',
                'logo' => 'https://th.bing.com/th/id/R.6d7b655758cb8526917a6295349094c1?rik=oq2fUIhWuV8%2ffg&pid=ImgRaw&r=0',
            ],
            [
                 'key' => 'hachette',
                'name' => 'Hachette Livre',
                'logo' => 'https://tse4.mm.bing.net/th/id/OIP.l1WV9X8r_M74eDFVz_5WpwHaHa?rs=1&pid=ImgDetMain&o=7&rm=3',
            ],
            [
                 'key' => 'simon',
                'name' => 'Simon & Schuster',
                'logo' => 'https://tse2.mm.bing.net/th/id/OIP.UnXzmooP_vswHEN2e-rEtQHaFz?rs=1&pid=ImgDetMain&o=7&rm=3',
            ],
        ];

        foreach ($publishers as $publisher) {
            Publisher::create($publisher);
        }
    }
}
