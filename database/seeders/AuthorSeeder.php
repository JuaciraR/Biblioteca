<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
    {
        $authors = [
            ['name' => 'J.K. Rowling', 'photo' => 'https://upload.wikimedia.org/wikipedia/commons/5/5d/J._K._Rowling_2010.jpg'],
            ['name' => 'George R.R. Martin', 'photo' => 'https://upload.wikimedia.org/wikipedia/commons/1/16/George_R._R._Martin_by_Gage_Skidmore_2.jpg'],
            ['name' => 'Stephen King', 'photo' => 'https://upload.wikimedia.org/wikipedia/commons/e/e3/Stephen_King%2C_Comicon.jpg'],
            ['name' => 'Agatha Christie', 'photo' => 'https://hips.hearstapps.com/hmg-prod/images/gettyimages-517399194.jpg'],
            ['name' => 'Isabel Allende', 'photo' => 'https://tse3.mm.bing.net/th/id/OIP.4ZN-6uV9je-Q7YKXx83UggHaEK?rs=1&pid=ImgDetMain&o=7&rm=3'],
            ['name' => 'Paulo Coelho', 'photo' => 'https://cdn.britannica.com/67/126567-050-A5C3A312/Paulo-Coelho-departure-themes-thriller-serial-killer-2008.jpg'],
            ['name' => 'Chimamanda Ngozi Adichie', 'photo' => 'https://th.bing.com/th/id/OSK.HEROiKc5ZoBTIw-E41AO2W32ch8pN5o1-teeCvABAeTMw1I?o=7rm=3&rs=1&pid=ImgDetMain&o=7&rm=3'],
            ['name' => 'Khaled Hosseini', 'photo' => 'https://upload.wikimedia.org/wikipedia/commons/8/85/Khaled_Hosseini%2C_2013_%28cropped%29.jpg'],
            ['name' => 'Haruki Murakami', 'photo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Conversatorio_Haruki_Murakami_%2812_de_12%29_%2845747009452%29_%28cropped%29.jpg/375px-Conversatorio_Haruki_Murakami_%2812_de_12%29_%2845747009452%29_%28cropped%29.jpg'],
            ['name' => 'Margaret Atwood', 'photo' => 'https://th.bing.com/th/id/R.ded7eec90f1b101c0fa92710f4016f9e?rik=oK6tf5mVjlyNPA&pid=ImgRaw&r=0'],
        ];

        foreach ($authors as $a) {
            Author::create($a);
        }
    }
}
