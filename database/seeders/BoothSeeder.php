<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booth;

class BoothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $boothJsonFilePath = public_path('frontAssets/frontData/booths.json');
        $allBooths = json_decode(file_get_contents($boothJsonFilePath), true);
        $collection = collect($allBooths);

        $batchSize = 500;

        Booth::truncate();

        $collection->chunk($batchSize)->each(function ($chunk) {
            Booth::insert($chunk->toArray());
        });

        Booth::query()->update([
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
