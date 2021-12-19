<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Pushuser;
use Illuminate\Http\Request;

class PushuserController extends Controller
{
    public function index(Request $request)
    {
        $pushusers = Pushuser::all();
        return view('pushusers.index', compact('pushusers'));
    }

    public function saveToken(Request $request)
    {
        if (Pushuser::where('device_id', $request->device_id)->exists()) {
            $device_id = Pushuser::where('device_id', $request->device_id);
            $device_id->update(['device_token' => $request->device_token]);
            return response()->json(['token actualizado correctamente']);
        } else {
            Pushuser::create($request->all());
            return response()->json(['token guardado correctamente']);
        }
    }

    public function sendNotification(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        $firebaseToken = Pushuser::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAAn_DFlvQ:APA91bHz_TQ4xD_hOPwLGilct_CJDIHA4W5pk1LnJ7-ApaAejkWb2wUbjUBzBc1E8gVFpdSlXqFzZNCuh7UDVD_J0spOR-b4SBOHi7ZnRO_EK8Ai1fCba0haqryTo5JTT_Gm00cfnvGQ';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $dataChunk = array_chunk($firebaseToken, 900);

        foreach ($dataChunk as $dataMil) {

            $data = [
                "registration_ids" => $dataMil,
                "notification" => [
                    "title" => 'Lección de hoy 🔊',
                    "body" => $lesson->titulo,
                    "content_available" => true,
                    "priority" => "high",
                    "icon" => "/favicon.png",
                ],
            ];

            $dataString = json_encode($data);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
        }
        return redirect()->route('lessons.index');
    }
}
