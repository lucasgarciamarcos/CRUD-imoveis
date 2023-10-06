<?php

namespace Database\Seeders;

use App\Models\Transacao;
use Illuminate\Database\Seeder;

class ImoveisTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Imovel::factory()->create();
    }
}
