<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Hotel;
use App\Models\Room;

class ReservationController extends Controller
{
    /** Muestra la lista de reservas
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtiene todas las reservas
        // El metodo with() permite relacionar las tablas, es decir, obtener los datos de las tablas relacionadas
        // El metodo orderBy() ordena los resultados por el campo indicado
        // El metodo get() obtiene los resultados
        $reservations = Reservation::with('room', 'room.hotel')
            ->where('user_id', \Auth::user()->getUserInfo()['sub'])
            ->orderBy('arrival', 'asc')
            ->get();
        // El metodo with() de view() permite pasar datos a la vista

        // Retorna la vista con los datos y la lista de reservas
        return view('dashboard.reservations')->with('reservations', $reservations);
    }

    /**
     * Muestra el formulario para crear una nueva reserva
     *
     * @return \Illuminate\Http\Response
     */
    public function create($hotel_id)
    {
        // $hotelInfo: información del hotel
        // El metodo with() nos permite hacer consultas a la base de datos
        // El metodo get() obtiene los resultados como un objeto de tipo Collection
        // El metodo find() nos permite buscar un registro en la base de datos por un id
        $hotelInfo = Hotel::with('rooms')->get()->find($hotel_id);
        // El metodo compact() crea un array con los datos que se pasan como parámetros
        return view('dashboard.reservationCreate', compact('hotelInfo'));
    }

    /**
     * almacena una nueva reserva
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Set the user_id equal to the user's Auth0 sub id before
        // Will be similar to "auth0|123123123123123"
        $user_id = \Auth::user()->getUserInfo()['sub'];
        $request->request->add(['user_id' => $user_id]);

        // Create the request
        Reservation::create($request->all());

        return redirect('dashboard/reservations')->with('success', 'Reservation created!');
    }

    /**
     * Muestra una reserva especifica
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        $reservation = Reservation::with('room', 'room.hotel')
            ->get()
            ->find($reservation->id);

        if ($reservation->user_id === \Auth::user()->getUserInfo()['sub']) {
            $hotel_id = $reservation->room->hotel_id;
            $hotelInfo = Hotel::with('rooms')->get()->find($hotel_id);

            return view('dashboard.reservationSingle', compact('reservation', 'hotelInfo'));
        } else
            return redirect('dashboard/reservations')->with('error', 'You are not authorized to see that.');
    }

    /**
     * Muestra el formulario para editar una reserva
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        $reservation = Reservation::with('room', 'room.hotel')
            ->get()
            ->find($reservation->id);

        if ($reservation->user_id === \Auth::user()->getUserInfo()['sub']) {
            $hotel_id = $reservation->room->hotel_id;
            $hotelInfo = Hotel::with('rooms')->get()->find($hotel_id);

            return view('dashboard.reservationEdit', compact('reservation', 'hotelInfo'));
        } else
            return redirect('dashboard/reservations')->with('error', 'You are not authorized to do that');
    }

    /**
     * Actualiza una reserva especifica
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id != \Auth::user()->getUserInfo()['sub'])
            return redirect('dashboard/reservations')->with('error', 'You are not authorized to update this reservation');

        $user_id = \Auth::user()->getUserInfo()['sub'];
        $reservation->user_id = $user_id;
        $reservation->num_of_guests = $request->num_of_guests;
        $reservation->arrival = $request->arrival;
        $reservation->departure = $request->departure;
        $reservation->room_id = $request->room_id;

        $reservation->save();

        return redirect('dashboard/reservations')->with('success', 'Successfully updated your reservation!');
    }

    /**
     * Elimina una reserva especifica
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        $reservation = Reservation::find($reservation->id);

        if ($reservation->user_id === \Auth::user()->getUserInfo()['sub']) {
            $reservation->delete();

            return redirect('dashboard/reservations')->with('success', 'Successfully deleted your reservation!');
        } else
            return redirect('dashboard/reservations')->with('error', 'You are not authorized to delete this reservation');
    }
}
