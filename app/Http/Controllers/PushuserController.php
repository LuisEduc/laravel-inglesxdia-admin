<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Lessonimage;
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
        $imagen = Lessonimage::where('id_lesson', $id)->first();
        $firebaseToken = Pushuser::whereNotNull('device_token')->pluck('device_token')->all();

        $SERVER_API_KEY = 'AAAAn_DFlvQ:APA91bHz_TQ4xD_hOPwLGilct_CJDIHA4W5pk1LnJ7-ApaAejkWb2wUbjUBzBc1E8gVFpdSlXqFzZNCuh7UDVD_J0spOR-b4SBOHi7ZnRO_EK8Ai1fCba0haqryTo5JTT_Gm00cfnvGQ';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $dataChunk = array_chunk($firebaseToken, 900);

        foreach ($dataChunk as $dataMil) {

            if (is_null($lesson->titulo)) {

                $data = [
                    "registration_ids" => $dataMil,
                    "notification" => [
                        "title" => 'Recordatorio',
                        "body" => '¡Tómate 5 minutos para practicar!',
                        "content_available" => true,
                        "priority" => "high",
                        "icon" => "/favicon.png",
                    ],
                ];
            } else {

                $data = [
                    "registration_ids" => $dataMil,
                    "notification" => [
                        "title" => 'Lección de hoy 🔊',
                        "body" => $lesson->titulo,
                        "content_available" => true,
                        "priority" => "high",
                        "icon" => "/favicon.png",
                        "image" => 'https://admin.inglesxdia.com/imagen/' . $imagen->imagen,
                    ],
                    "data" => [
                        "cat" => $lesson->categorias->slug,
                        "lec" => $lesson->slug,
                    ],
                ];
            }


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

    public function customNotification(Request $request)
    {
        if ($request->users == "todos") {
            $firebaseToken = Pushuser::whereNotNull('device_token')->pluck('device_token')->all();
        } else {
            $firebaseToken = array();
            $firebaseToken[] = $request->users;
        }

        $SERVER_API_KEY = 'AAAAn_DFlvQ:APA91bHz_TQ4xD_hOPwLGilct_CJDIHA4W5pk1LnJ7-ApaAejkWb2wUbjUBzBc1E8gVFpdSlXqFzZNCuh7UDVD_J0spOR-b4SBOHi7ZnRO_EK8Ai1fCba0haqryTo5JTT_Gm00cfnvGQ';

        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $dataChunk = array_chunk($firebaseToken, 900);

        foreach ($dataChunk as $dataMil) {

            if (is_null($request->titulo) || is_null($request->descripcion)) {

                $data = [
                    "registration_ids" => $dataMil,
                    "notification" => [
                        "title" => 'Recordatorio',
                        "body" => '¡Tómate 5 minutos para practicar!',
                        "content_available" => true,
                        "priority" => "high",
                        "icon" => "/favicon.png",
                    ],
                ];
            } else {

                $data = [
                    "registration_ids" => $dataMil,
                    "notification" => [
                        "title" => $request->titulo,
                        "body" => $request->descripcion,
                        "content_available" => true,
                        "priority" => "high",
                        "icon" => "/favicon.png",
                    ],
                    "data" => [
                        "cat" => $request->cat,
                        "lec" => $request->lec,
                    ],
                ];
            }

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
