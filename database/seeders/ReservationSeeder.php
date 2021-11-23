<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Hotel;
use App\Models\Room;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Arreglo de datos para insertar en la tabla de Reservaciones
        $reservations = [
            [
                'user_id' => '1',
                'room_id' => 1,
                'num_of_guests' => 4,
                'arrival' => '2020-05-18',
                'departure' => '2020-05-28'
            ],
            [
                'user_id' => '1',
                'room_id' => 2,
                'num_of_guests' => 1,
                'arrival' => '2020-05-10',
                'departure' => '2020-05-12'
            ],
            [
                'user_id' => '1',
                'room_id' => 3,
                'num_of_guests' => 3,
                'arrival' => '2020-05-06',
                'departure' => '2020-05-07'
            ],
            [
                'user_id' => '1',
                'room_id' => 4,
                'num_of_guests' => 2,
                'arrival' => '2020-05-12',
                'departure' => '2020-05-15'
            ],
            [
                'user_id' => '1',
                'room_id' => 2,
                'num_of_guests' => 2,
                'arrival' => '2020-05-20',
                'departure' => '2020-05-24'
            ]
        ];

        // ForEach para recorrer el arreglo de datos
        // Insertar datos en la tabla de Reservaciones
        foreach ($reservations as $reservation) {
            Reservation::create(array(
                'user_id' => $reservation['user_id'],
                'room_id' => $reservation['room_id'],
                'num_of_guests' => $reservation['num_of_guests'],
                'arrival' => $reservation['arrival'],
                'departure' => $reservation['departure']
            ));
        }
    }
}
