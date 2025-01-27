<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\State;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            [
                "id" => 33,
                "name" => "Andaman and Nicobar Islands"
            ],
            [
                "id" => 1,
                "name" => "Andhra Pradesh"
            ],
            [
                "id" => 2,
                "name" => "Arunachal Pradesh"
            ],
            [
                "id" => 3,
                "name" => "Assam"
            ],
            [
                "id" => 4,
                "name" => "Bihar"
            ],
            [
                "id" => 34,
                "name" => "Chandigarh"
            ],
            [
                "id" => 5,
                "name" => "Chhattisgarh"
            ],
            [
                "id" => 32,
                "name" => "Dadra Nagar Haveli & Daman-Diu"
            ],
            [
                "id" => 30,
                "name" => "Delhi"
            ],
            [
                "id" => 6,
                "name" => "Goa"
            ],
            [
                "id" => 7,
                "name" => "Gujarat"
            ],
            [
                "id" => 8,
                "name" => "Haryana"
            ],
            [
                "id" => 9,
                "name" => "Himachal Pradesh"
            ],
            [
                "id" => 10,
                "name" => "Jammu and Kashmir"
            ],
            [
                "id" => 11,
                "name" => "Jharkhand"
            ],
            [
                "id" => 12,
                "name" => "Karnataka"
            ],
            [
                "id" => 13,
                "name" => "Kerala"
            ],
            [
                "id" => 38,
                "name" => "Ladakh"
            ],
            [
                "id" => 36,
                "name" => "Lakshadweep"
            ],
            [
                "id" => 14,
                "name" => "Madhya Pradesh"
            ],
            [
                "id" => 15,
                "name" => "Maharashtra"
            ],
            [
                "id" => 16,
                "name" => "Manipur"
            ],
            [
                "id" => 17,
                "name" => "Meghalaya"
            ],
            [
                "id" => 18,
                "name" => "Mizoram"
            ],
            [
                "id" => 19,
                "name" => "Nagaland"
            ],
            [
                "id" => 20,
                "name" => "Odisha"
            ],
            [
                "id" => 31,
                "name" => "Puducherry"
            ],
            [
                "id" => 21,
                "name" => "Punjab"
            ],
            [
                "id" => 22,
                "name" => "Rajasthan"
            ],
            [
                "id" => 23,
                "name" => "Sikkim"
            ],
            [
                "id" => 24,
                "name" => "Tamil Nadu"
            ],
            [
                "id" => 25,
                "name" => "Telangana"
            ],
            [
                "id" => 26,
                "name" => "Tripura"
            ],
            [
                "id" => 27,
                "name" => "Uttarakhand"
            ],
            [
                "id" => 28,
                "name" => "Uttar Pradesh"
            ],
            [
                "id" => 29,
                "name" => "West Bengal"
            ]
        ];

        State::truncate();

        foreach ($states as $state) {
            $exist = State::where('name', $state['name'])->first();

            if (!$exist) {
                $newState = new State();
                $newState->id = $state['id'];
                $newState->name = $state['name'];
                $newState->save();
            }
        }
    }
}
