<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::create(
            [
                'name' => 'Daniel',
                'last_name' => 'Aguayo',
                'status' => true
            ]
        );

        Client::create(
            [
                'name' => 'Fabiola',
                'last_name' => 'Perez',
                'status' => true
            ]
        );

        Client::create(
            [
                'name' => 'Carlos',
                'last_name' => 'Sandoval',
                'status' => false
            ]
        );
    }
}
