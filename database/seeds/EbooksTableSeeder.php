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
                'path' => asset('ebooks/1.pdf'),
                'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'title' => 'Second PDF',
                'path' => asset('ebooks/2.pdf'),
                'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'title' => 'Third PDF',
                'path' => asset('ebooks/3.pdf'),
                'created_at' =>  Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' =>  Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];
        
        Ebook::insert($ebooks);
    }
}
