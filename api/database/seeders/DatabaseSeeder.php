<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('document_types')->insert([
            [
                'id' => 1,
                'description' => 'resolución',
            ],
            [
                'id' => 2,
                'description' => 'declaración',
            ],
            [
                'id' => 3,
                'description' => 'disposición',
            ],
            [
                'id' => 4,
                'description' => 'acta',
            ],
            [
                'id' => 5,
                'description' => 'memo',
            ],
            [
                'id' => 6,
                'description' => 'nota',
            ],
        ]);

        DB::table('issuers')->insert([
            [
                'id' => 1,
                'description' => 'decanato',
            ],
            [
                'id' => 2,
                'description' => 'consejo directivo',
            ]
        ]);
    }
}
