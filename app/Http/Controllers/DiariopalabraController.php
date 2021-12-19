<?php

namespace App\Http\Controllers;

use App\Models\Diariopalabra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DiariopalabraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diariopalabras = Diariopalabra::orderByDesc('orden')->get();
        return view('diariopalabras.index', compact('diariopalabras'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $diariopalabras = Diariopalabra::all();
        return view('diariopalabras.crear', compact('diariopalabras'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'imagen' => 'image|mimes:jpeg,png,svg,webp|max:1024',
            'audio' => 'file|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        $diariopalabra = $request->all();
        if ($imagen = $request->file('imagen')) {
            $rutaGuardarImg = 'imagen/';
            $imagenDiariopalabra = date('YmdHis') . "." . $imagen->getClientOriginalExtension();
            $imagen->move($rutaGuardarImg, $imagenDiariopalabra);
            $diariopalabra['imagen'] = "$imagenDiariopalabra";
        }

        if ($audio = $request->file('audio')) {
            $rutaGuardarAud = 'audio/';
            $audioDiariopalabra = date('YmdHis') . "." . $audio->getClientOriginalExtension();
            $audio->move($rutaGuardarAud, $audioDiariopalabra);
            $diariopalabra['audio'] = "$audioDiariopalabra";
        }

        Diariopalabra::create($diariopalabra);
        return redirect()->route('diariopalabras.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Diariopalabra $diariopalabra)
    {
        return view('diariopalabras.editar', compact('diariopalabra'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diariopalabra $diariopalabra)
    {
        $request->validate([
            'imagen' => 'image|mimes:jpeg,png,svg,webp|max:1024',
            'audio' => 'file|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        $palabra = $request->all();
        if ($imagen = $request->file('imagen')) {
            $rutaGuardarImg = 'imagen/';
            $imagenDiariopalabra = date('YmdHis') . "." . $imagen->getClientOriginalExtension();
            $imagen->move($rutaGuardarImg, $imagenDiariopalabra);
            $palabra['imagen'] = "$imagenDiariopalabra";
        } else {
            unset($palabra['imagen']);
        }

        if ($audio = $request->file('audio')) {
            $rutaGuardarAud = 'audio/';
            $audioDiariopalabra = date('YmdHis') . "." . $audio->getClientOriginalExtension();
            $audio->move($rutaGuardarAud, $audioDiariopalabra);
            $palabra['audio'] = "$audioDiariopalabra";
        } else {
            unset($palabra['imagen']);
        }

        $diariopalabra->update($palabra);
        return redirect()->route('diariopalabras.index');
    }

    public function updateOrden(Request $request)
    {

        $palabras = Diariopalabra::all();

        foreach ($palabras as $pal) {
            foreach ($request->orden as $orden) {
                if ($orden['id'] == $pal->id) {
                    $pal->update(['orden' => $orden['posicion']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diariopalabra $diariopalabra)
    {
        $diariopalabra->delete();
        File::delete("audio/$diariopalabra->audio");
        File::delete("imagen/$diariopalabra->imagen");
        return redirect()->route('diariopalabras.index');
    }

    // API
    public function getPalabras()
    {
        $dia = date('d');
        // $dia = 3;
        $mes = date('m');
        // $mes = 1;
        $palabras = Diariopalabra::orderByDesc('orden')
            ->where('mes', $mes)->take($dia)
            ->get(['id', 'mes', 'imagen', 'audio']);
        $json['palabras'] = $palabras;
        return $json;
    }
}
