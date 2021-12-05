<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lecciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-3 py-3">

                <a class="btn btn-primary my-2 ms-2" href="{{ route('lessons.create') }}" role="button">Crear</a>
                <div class="table-responsive-xl">
                    <table id="lessons" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none">ID</th>
                                <th scope="col">SLUG</th>
                                <th scope="col">TITULO</th>
                                <th scope="col">DESCRIPCION</th>
                                <th scope="col">CAT</th>
                                <th scope="col">ESTADO</th>
                                <th scope="col">TIPO</th>
                                <th scope="col">IMAGEN</th>
                                <th scope="col">AUDIO</th>
                                <th scope="col">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lessons as $lesson)
                            <tr>
                                <td class="d-none">{{$lesson->id}}</td>
                                <td>{{$lesson->slug}}</td>
                                <td>{{$lesson->titulo}}</td>
                                <td>{{$lesson->descripcion}}</td>
                                @if(isset($lesson->categorias->slug))
                                <td>{{$lesson->categorias->slug}}</td>
                                @else
                                <td>Vacio</td>
                                @endif
                                <td>{{$lesson->estado}}</td>
                                @if(isset($lesson->tipos->slug))
                                <td>{{$lesson->tipos->slug}}</td>
                                @else
                                <td>Vacio</td>
                                @endif
                                <td>
                                    <img src="/imagen/{{$lesson->imagen}}" width="100px">
                                </td>
                                <td>{{$lesson->audio}}</td>
                                <td>
                                    <div class="d-flex justify-content-center" role="group">
                                        <form action="{{ route('send.notification', $lesson->id) }}" method="POST" class="formNotificar">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Notificar</button>
                                        </form>

                                        <!-- botón editar -->
                                        <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-info btn-sm mx-2"><i class="far fa-edit"></i></a>

                                        <!-- botón borrar -->
                                        <form action="{{ route('lessons.destroy', $lesson->id)}}" method="POST" class="formEliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    (function() {
        'use strict'
        //debemos crear la clase formEliminar dentro del form del boton borrar
        //recordar que cada registro a eliminar esta contenido en un form  
        var formEliminar = document.querySelectorAll('.formEliminar')
        Array.prototype.slice.call(formEliminar)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
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
                            this.submit();
                            Swal.fire('¡Eliminado!', 'El registro ha sido eliminado exitosamente.', 'success');
                        }
                    })
                }, false)
            })

        var formNotificar = document.querySelectorAll('.formNotificar')
        Array.prototype.slice.call(formNotificar)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault()
                    event.stopPropagation()
                    Swal.fire({
                        title: '¿Confirma el envio de la notificación?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#20c997',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Confirmar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.submit();
                            Swal.fire('¡Enviado!', 'La notificación se ha enviando exitosamente.', 'success');
                        }
                    })
                }, false)
            })
    })()
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#lessons').DataTable({
            "lengthMenu": [
                [5, 10, 50, -1],
                [5, 10, 50, "All"]
            ],
            "order": []
        });
    });
</script>