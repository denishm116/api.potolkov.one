<?php

use Illuminate\Database\Seeder;
use App\Models\Catalog;

class CatalogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        factory(Catalog::class, 10)->create()->each(function(Catalog $catalog) {
            $counts = [0, random_int(3, 7)];
            $catalog->children()->saveMany(factory(Catalog::class, $counts[array_rand($counts)])->create()->each(function(Catalog $catalog) {
                $counts = [0, random_int(3, 7)];
                $catalog->children()->saveMany(factory(Catalog::class, $counts[array_rand($counts)])->create());
            }));
        });
    }
}
