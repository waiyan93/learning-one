<?php

use App\Ebook;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EbooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ebooks = [
            [
                'id' => 1,
                'title' => 'First PDF',
                'original' => 'original-ebooks/KM 11-8-2018.pdf',
                'edited' => 'edited-ebooks/KM 11-8-2018.pdf',
                'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'title' => 'Second PDF',
                'original' => 'original-ebooks/KM 13.8.2018.pdf',
                'edited' => 'edited-ebooks/KM 13.8.2018.pdf',
                'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'title' => 'Third PDF',
                'original' => 'original-ebooks/KM 14.8.2018.pdf',
                'edited' => 'edited-ebooks/KM 14.8.2018.pdf',
                'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];
        Ebook::insert($ebooks);
    }
}
