<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Lesson;
use App\Models\Pregunta;
use App\Models\Tipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $lessons = Lesson::paginate(5); //paginacion sin usar dataTables
        $lessons = Lesson::orderByDesc('id')->get();
        return view('lessons.index', compact('lessons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categorias = Categoria::all();
        $tipos = Tipo::all();
        return view('lessons.crear', compact('categorias', 'tipos'));
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
            // 'slug' => 'required',
            // 'titulo' => 'required',
            // 'descripcion' => 'required',
            // 'id_categoria' => 'required',
            // 'estado' => 'required',
            // 'tipo' => 'required',
            'imagen' => 'image|mimes:jpeg,png,svg,webp|max:1024',
            'audio' => 'file|mimes:audio/mpeg,mpga,mp3,wav,aac'
        ]);

        $lesson = $request->all();
        if ($imagen = $request->file('imagen')) {
            $rutaGuardarImg = 'imagen/';
            $imagenLesson = date('YmdHis') . "." . $imagen->getClientOriginalExtension();
            $imagen->move($rutaGuardarImg, $imagenLesson);
            $lesson['imagen'] = "$imagenLesson";
        }

        if ($audio = $request->file('audio')) {
            $rutaGuardarAud = 'audio/';
            $audioLesson = date('YmdHis') . "." . $audio->getClientOriginalExtension();
            $audio->move($rutaGuardarAud, $audioLesson);
            $lesson['audio'] = "$audioLesson";
        }

        Lesson::create($lesson);
        $id_lesson = Lesson::get('id')->last()->id;
        $preguntas = Lesson::get('preguntas')->last()->preguntas;
        return view('lessons.crearpregunta', compact('id_lesson', 'preguntas'));
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
    public function edit(Lesson $lesson)
    {
        $categorias = Categoria::all();
        $tipos = Tipo::all();
        $preguntas = Pregunta::where('id_lesson', $lesson->id)->get();
        return view('lessons.editar', compact('lesson', 'categorias', 'tipos', 'preguntas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {

        $request->validate([
            'imagen' => 'image|mimes:jpeg,jpg,png,svg,webp|max:1024',
            'audio' => 'file|mimes:audio/mpeg,mpga,mp3,wav,aac,ogg'
        ]);

        $id_preguntas = $request->id_pregunta;
        $id_lessons = $request->id_lesson;
        $preguntas = $request->pregunta;
        $opciones_1 = $request->opcion1;
        $opciones_2 = $request->opcion2;
        $opciones_3 = $request->opcion3;
        $respuestas = $request->respuesta;
        foreach ($id_preguntas as $key => $id_pregunta_value) {
            $data = [
                'id_pregunta' => $id_pregunta_value,
                'id_lesson' => $id_lessons[$key],
                'pregunta' => $preguntas[$key],
                'opcion1' => $opciones_1[$key],
                'opcion2' => $opciones_2[$key],
                'opcion3' => $opciones_3[$key],
                'respuesta' => $respuestas[$key]
            ];

            DB::table('preguntas')
                ->where('id_lesson', $id_lessons[$key])
                ->where('id_pregunta', $id_pregunta_value)
                ->update($data);
        }

        $leccion = $request->except(['id_pregunta', 'id_lesson', 'pregunta', 'opcion1', 'opcion2', 'opcion3', 'respuesta']);
        if ($imagen = $request->file('imagen')) {
            $oldImg = 'imagen/' . $lesson->imagen;
            if (File::exists($oldImg)) {
                File::delete($oldImg);
            }
            $rutaGuardarImg = 'imagen/';
            $imagenLeccion = date('YmdHis') . "." . $imagen->getClientOriginalExtension();
            $imagen->move($rutaGuardarImg, $imagenLeccion);
            $leccion['imagen'] = "$imagenLeccion";
        } else {
            unset($leccion['imagen']);
        }

        if ($audio = $request->file('audio')) {
            $oldAud = 'audio/' . $lesson->audio;
            if (File::exists($oldAud)) {
                File::delete($oldAud);
            }
            $rutaGuardarAud = 'audio/';
            $audioLeccion = date('YmdHis') . "." . $audio->getClientOriginalExtension();
            $audio->move($rutaGuardarAud, $audioLeccion);
            $leccion['audio'] = "$audioLeccion";
        } else {
            unset($leccion['audio']);
        }
        $lesson->update($leccion);
        return redirect()->route('lessons.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        File::delete("audio/$lesson->audio");
        File::delete("imagen/$lesson->imagen");
        return redirect()->route('lessons.index');
    }


    // API
    public function getLecciones()
    {
        $lecciones = DB::table('lessons')
            ->select('lessons.id','lessons.slug', 'lessons.titulo','lessons.descripcion','lessons.imagen','lessons.audio', 'categorias.slug as slug_cat')
            ->join('categorias', 'categorias.id', '=', 'lessons.id_categoria')
            ->where('lessons.estado', 'publica')
            ->orderBy('lessons.id', 'desc')
            ->get();

        return $lecciones;
    }

    public function getLeccionesCat($id_categoria)
    {

        $lecciones = DB::table('lessons')
            ->select('lessons.id','lessons.slug', 'lessons.titulo','lessons.descripcion','lessons.imagen','lessons.audio', 'categorias.slug')
            ->join('categorias', 'categorias.id', '=', 'lessons.id_categoria')
            ->where('id_categoria', $id_categoria)
            ->get();
        $json['lecciones'] = $lecciones;
        return $json;
    }

    public function getLeccionesCatSlug($slug_cat, $slug)
    {
        $preguntas = DB::table('lessons')
            ->select('preguntas.id_pregunta', 'preguntas.pregunta','preguntas.opcion1','preguntas.opcion2','preguntas.opcion3','preguntas.respuesta')
            ->join('preguntas', 'preguntas.id_lesson', '=', 'lessons.id')
            ->where('lessons.slug', $slug)
            ->get();

        $lecciones = DB::table('lessons')
            ->select('lessons.id','lessons.slug', 'lessons.titulo','lessons.descripcion','lessons.imagen','lessons.audio', 'categorias.slug as slug_cat')
            ->join('categorias', 'categorias.id', '=', 'lessons.id_categoria')
            ->where('categorias.slug', $slug_cat)
            ->where('lessons.slug', $slug)
            ->get();

        $json['leccion'] = $lecciones;
        $json['preguntas'] = $preguntas;
        return $json;
    }

    public function getAudio($audio)
    {
        return response()->file(public_path("audio/$audio"));
    }

    public function getInicio()
    {

        $tipos = Tipo::all()
            ->where('estado', 'publica')
            ->where('slug', '!=', 'normal')
            ->sortBy('posicion')->values()->all();

        $lecciones = DB::table('lessons')
            ->select('lessons.id','lessons.slug', 'lessons.titulo','lessons.descripcion','lessons.imagen','lessons.audio','lessons.id_categoria','lessons.id_tipo', 'categorias.slug as slug_cat')
            ->join('categorias', 'categorias.id', '=', 'lessons.id_categoria')
            ->where('lessons.estado', 'publica')
            ->get();

        foreach ($tipos as $i => $tipo) {
            $data[$i] = ['id' => $tipo->id, 'titulo' => $tipo->titulo, 'icono' => $tipo->icono];
            foreach ($lecciones as $leccion) {
                if ($leccion->id_tipo == $tipo->id) {
                    $data[$i]['data'][] = $leccion;
                }
            }
        }

        $json['secciones'] = $data;
        return $json;
    }

    public function buscarTermino($termino)
    {
        $lecciones = Lesson::where('titulo', 'like', '%' . $termino . '%')->get(['id', 'titulo']);
        $json['results'] = $lecciones;
        return $json;
    }

    public function buscar()
    {
        $lecciones = DB::table('lessons')
            ->select('lessons.id', 'lessons.titulo', 'lessons.slug', 'categorias.slug as slug_cat')
            ->join('categorias', 'categorias.id', '=', 'lessons.id_categoria')
            ->where('lessons.estado', 'publica')
            ->get();

        $json['results'] = $lecciones;
        return $json;
    }
}
