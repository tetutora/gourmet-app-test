<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Region;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepresentativeController extends Controller
{
    public function representativeDashboard()
    {
        $user = auth()->user();

        $reservations = Reservation::where('restaurant_id', $user->restaurant_id)
            ->orderBy('reservation_date', 'asc')
            ->get();

        return view('representative.dashboard', ['reservations' => $reservations]);
    }

    public function create()
    {
        $regions = Region::all();
        $genres = Genre::all();

        return view('representative.create', compact('regions', 'genres'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'region_id' => 'required|exists:regions,id',
            'genre_ids' => 'nullable|array',
            'genre_ids.*' => 'exists:genres,id',
            'new_genres' => 'nullable|string',
            'image_url' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('image_url')) {
                $imagePath = $request->file('image_url')->store('restaurants', 'public');
            } else {
                $imagePath = null;
            }

            // 店舗の作成
            $restaurant = Restaurant::create([
                'name' => $request->name,
                'description' => $request->description,
                'region_id' => $request->region_id,
                'user_id' => auth()->id(),
                'image_url' => $imagePath,
            ]);

            // 既存のジャンルIDを取得
            $genreIds = $request->input('genre_ids', []);

            // 新しいジャンルを追加する場合
            if ($request->filled('new_genres')) {
                $newGenres = array_map('trim', explode(',', $request->new_genres));
                foreach ($newGenres as $genreName) {
                    $genre = Genre::firstOrCreate(['name' => $genreName]);
                    $genreIds[] = $genre->id; // 新しいジャンルをIDリストに追加
                }
            }

            // 多対多リレーションを使ってジャンルを関連付け
            $restaurant->genres()->sync($genreIds);

            DB::commit();

            return redirect()->route('index');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '店舗の登録中にエラーが発生しました。']);
        }
    }
}