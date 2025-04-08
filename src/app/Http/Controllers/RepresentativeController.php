<?php

// app/Http/Controllers/RepresentativeController.php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function representativeDashboard()
    {
        return view('representative.dashboard');
    }

    public function create()
    {
        return view('representative.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Restaurant::create($request->all());

        return redirect()->route('representative.dashboard');
    }

    public function index()
    {
        $reservations = Reservation::where('restaurant_id', auth()->user()->restaurant_id)
            ->orderBy('reservation_date', 'asc')
            ->get();

        return view('representative.reservations', compact('reservations'));
    }
}