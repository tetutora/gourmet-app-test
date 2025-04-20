<?php

namespace App\Http\Controllers;

use App\Constants\Constants;
use App\Http\Requests\UpdateReservationRequest;
use App\Http\Requests\ReservationRequest;
use App\Models\Reservation;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * 新規予約を作成
     */
    public function store(ReservationRequest $request)
    {
        Reservation::createReservation($request);
        return redirect()->route('reservation.complete');
    }

    /**
     * 予約完了ページを表示
     */
    public function reservationComplete()
    {
        return view('reservation');
    }

    /**
     * 予約をキャンセル
     */
    public function cancel($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status_id = Constants::RESERVATION_STATUS_CANCELLED;
        $reservation->save();

        return response()->json(['success' => true]);
    }

    /**
     * 予約情報を更新
     */
    public function update(UpdateReservationRequest $request, $reservationId)
    {
        Reservation::updateReservation($reservationId, $request);
        return response()->json(['success' => true, 'message' => '予約を更新しました']);
    }

    /**
     * 予約のQRコードを生成
     */
    public function generateQrCode($reservation)
    {
        $qrCode = new QrCode("reservation/{$reservation->id}");
        $writer = new PngWriter();

        $path = "qrcodes/qr_{$reservation->id}.png";

        $writer->write($qrCode)->saveToFile(storage_path("app/public/{$path}"));

        return $path;
    }

    /**
     * QRコードを表示するためのURLを生成して表示
     */
    public function showQRCode($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);

        $qrCodePath = $this->generateQrCode($reservation);

        $qrCodeUrl = asset('storage/qrcodes/qr_' . $reservation->id . '.png');

        return view('qrcode', ['qrCodeUrl' => $qrCodeUrl]);
    }

    /**
     * 提供されたQRコードのデータが有効かどうかを検証
     */
    public function verifyQRCode(Request $request)
    {
        $qrCodeData = $request->input('qr_code_data');
        $reservation = Reservation::where('qr_code', $qrCodeData)->first();

        if (!$reservation) {
            return response()->json(['message' => '無効なQRコードです。'], 400);
        }

        if ($reservation->status_id === Constants::RESERVATION_STATUS_COMPLETED) {
            return response()->json(['message' => '来店済みの予約です。'], 200);
        }

        return response()->json(['message' => '予約情報が確認できません。'], 404);
    }

    /**
     * 予約に対するQRコードを表示するためのURLを生成して表示できるか
     */
    public function test_qr_code_url_is_generated_for_reservation()
    {
        $reservation = Reservation::create([
            'user_id' => $this->user->id,
            'restaurant_id' => $this->restaurant->id,
            'reservation_date' => Carbon::today(),
            'reservation_time' => '18:00:00',
            'num_people' => 2,
            'payment_method' => 'card',
            'status_id' => Constants::RESERVATION_STATUS_BOOKED,
        ]);

        $url = route('show.qrcode', ['reservation' => $reservation->id]);

        $response = $this->get($url);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }
}