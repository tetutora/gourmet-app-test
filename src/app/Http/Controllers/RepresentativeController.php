<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function create()
    {
        return view('representative.create');
    }

    public function store(Request $request)
    {
        Restaurant::create($request->all());

        return redirect()->route('representative.index');
    }

    public function edit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('representative.edit', compact('restaurant'));
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->update($request->all());

        return redirect()->route('representative.index');
    }

    public function index()
    {
        $restaurants = Restaurant::all();
        return view('representative.index', compact('restaurants'));
    }
}
