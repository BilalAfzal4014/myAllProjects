<?php

use Illuminate\Database\Seeder;

class AttributeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $disk = \Storage::disk('seeders');
        $items = \GuzzleHttp\json_decode(
            $disk->get('attributes.json'),
            true
        );

        foreach ($items as $item) {
            $item['company_id'] = 1;
            \App\Attribute::create($item);
        }
    }
}
