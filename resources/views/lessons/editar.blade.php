<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Lección') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">

                <form action="{{ route('lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">slug:</label>
                            <input name="slug" class="form-control rounded" type="text" value="{{ $lesson->slug }}" />
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">título:</label>
                            <input name="titulo" class="form-control rounded" type="text" value="{{ $lesson->titulo }}" />
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">descripción:</label>
                            <input name="descripcion" class="form-control rounded" type="text" value="{{ $lesson->descripcion }}" />
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">categoría:</label>
                            <select name="id_categoria" class="form-control rounded" type="number">
                                @foreach($categorias as $key => $value)
                                @if ($value->id == $lesson->id_categoria)
                                <option value="{{ $value->id }}" selected>{{ $value->titulo }}</option>
                                @else
                                <option value="{{ $value->id }}">{{ $value->titulo }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">estado:</label>
                            <select name="estado" class="form-control rounded" type="text">
                                <option value="publica" @if($lesson->estado=='publica') selected='selected' @endif>Publica</option>
                                <option value="privada" @if($lesson->estado=='privada') selected='selected' @endif>Privada</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">tipo:</label>
                            <select name="id_tipo" class="form-control rounded" type="number">
                                @foreach($tipos as $key => $value)
                                @if ($value->id == $lesson->id_tipo)
                                <option value="{{ $value->id }}" selected>{{ $value->slug }}</option>
                                @else
                                <option value="{{ $value->id }}">{{ $value->slug }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Para ver la imagen seleccionada, de lo contrario no se -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 my-4">
                        <div class="grid grid-cols-1">
                            <img src="/imagen/{{ $lesson->imagen }}" class="border rounded shadow" id="imagenSeleccionada" style="max-height: 200px;">
                        </div>
                        <div class="grid grid-cols-1">
                            <audio controls src="/audio/{{ $lesson->audio }}" id="audioSeleccionado"></audio>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8">
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">Actualizar Imagen</label>
                            <div class='flex items-center justify-center w-full'>
                                <label class='flex flex-col border-4 border-dashed w-full h-32 hover:bg-gray-100 hover:border-purple-300 group'>
                                    <div class='flex flex-col items-center justify-center py-2'>
                                        <svg class="w-10 h-10 text-purple-400 group-hover:text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class='text-sm text-gray-400 group-hover:text-purple-600 py-2 tracking-wider'>Seleccione la imagen</p>
                                    </div>
                                    <input name="imagen" id="imagen" type='file' class="hidden" />
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-1">
                            <label class="form-label text-uppercase">Actualizar Audio</label>
                            <div class='flex items-center justify-center w-full'>
                                <label class='flex flex-col border-4 border-dashed w-full h-32 hover:bg-gray-100 hover:border-purple-300 group'>
                                    <div class='flex flex-col items-center justify-center py-2'>
                                        <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M6 13c0 1.105-1.12 2-2.5 2S1 14.105 1 13c0-1.104 1.12-2 2.5-2s2.5.896 2.5 2zm9-2c0 1.105-1.12 2-2.5 2s-2.5-.895-2.5-2 1.12-2 2.5-2 2.5.895 2.5 2z" />
                                            <path fill-rule="evenodd" d="M14 11V2h1v9h-1zM6 3v10H5V3h1z" />
                                            <path d="M5 2.905a1 1 0 0 1 .9-.995l8-.8a1 1 0 0 1 1.1.995V3L5 4V2.905z" />
                                        </svg>
                                        <p class='text-sm text-gray-400 group-hover:text-purple-600 py-2 tracking-wider'>Seleccione el audio</p>
                                    </div>
                                    <input name="audio" id="audio" type='file' class="hidden" />
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class='flex items-center justify-center  md:gap-8 gap-4 pt-4'>
                        <a class="btn btn-secondary my-2 ms-2 btn-sm" onclick="submitForms()">Agregar pregunta {{ count($preguntas) + 1 }}</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 mt-4">
                        @foreach($preguntas as $key => $value)
                        <div class="grid grid-cols-1">
                            <div class="mb-1">
                                <label class="form-label text-uppercase fw-bold">pregunta {{$key+1}}:</label>
                                <a href="{{ route('preguntas.eliminar', $value->id) }}" class="btn btn-danger btn-sm formEliminar">Eliminar</a>
                            </div>
                            <input name="id_lesson[]" value="{{ $value->id_lesson }}" type="hidden" />
                            <input name="id_pregunta[]" value="{{ $value->id_pregunta }}" type="hidden" />
                            <input name="pregunta[]" class="form-control rounded mb-1" type="text" value="{{ $value->pregunta }}" />
                            <small>Opciones</small>
                            <input name="opcion1[]" class="form-control rounded mb-1" type="text" value="{{ $value->opcion1 }}" />
                            <input name="opcion2[]" class="form-control rounded mb-1" type="text" value="{{ $value->opcion2 }}" />
                            <input name="opcion3[]" class="form-control rounded mb-1" type="text" value="{{ $value->opcion3 }}" />
                            <small>Respuesta</small>
                            <input name="respuesta[]" class="form-control rounded" type="number" value="{{ $value->respuesta }}" />
                        </div>
                        @endforeach
                    </div>

                    <div class='flex items-center justify-center  md:gap-8 gap-4 pt-4'>
                        <a href="{{ route('lessons.index') }}" class="btn btn-danger">Cancelar</a>
                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </div>

                </form>

                <form id="crearpregunta" action="{{ route('preguntas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input style="display: none;" name="individual" value="1" type="hidden" />
                    <input style="display: none;" name="id_lesson[]" value="{{$lesson->id}}" type="hidden" />
                    <input style="display: none;" name="id_pregunta[]" value="{{ count($preguntas) }}" type="hidden" />
                    <input style="display: none;" name="pregunta[]" class="form-control rounded mb-3" type="text" />
                    <input style="display: none;" name="opcion1[]" class="form-control rounded mb-1" type="text" />
                    <input style="display: none;" name="opcion2[]" class="form-control rounded mb-1" type="text" />
                    <input style="display: none;" name="opcion3[]" class="form-control rounded mb-1" type="text" />
                    <input style="display: none;" name="respuesta[]" class="form-control rounded mb-1" type="number" />
                </form>

            </div>
        </div>
    </div>
</x-app-layout>

<script>
    (function() {
        'use strict'
        //debemos crear la clase formEliminar dentro del form del boton borrar
        //recordar que cada registro a eliminar esta contenido en un form  
        var forms = document.querySelectorAll('.formEliminar')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('click', function(event) {
                    event.preventDefault()
                    event.stopPropagation()
                    Swal.fire({
                        title: '¿Confirma la eliminación del registro?',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Confirmar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('preguntas.eliminar', $value->id) }}";
                            Swal.fire('¡Eliminado!', 'El registro ha sido eliminado exitosamente.', 'success');
                        }
                    })
                }, false)
            })
    })()
</script>

<!-- Script para ver la imagen antes de CREAR UN NUEVO PRODUCTO -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    function submitForms() {
        document.getElementById("crearpregunta").submit();
    }

    $(document).ready(function(e) {
        $('#imagen').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#imagenSeleccionada').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
        $('#audio').change(function() {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#audioSeleccionado').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });
    });
</script>