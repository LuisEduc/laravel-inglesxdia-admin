<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tipos de lecciones (Bloques de inicio)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-3 py-3">

                <a class="btn btn-primary my-2 ms-2" href="{{ route('tipos.create') }}" role="button">Crear</a>
                <div class="table-responsive-xl">
                    <table id="tipos" class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="d-none">ID</th>
                                <th scope="col">SLUG</th>
                                <th scope="col">POSICIÓN</th>
                                <th scope="col">TITULO</th>
                                <th scope="col">ICONO</th>
                                <th scope="col">COLOR</th>
                                <th scope="col">FONDO</th>
                                <th scope="col">ESTADO</th>
                                <th scope="col">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tipos as $tipo)
                            <tr>
                                <td class="d-none">{{$tipo->id}}</td>
                                <td>{{$tipo->slug}}</td>
                                <td>{{$tipo->posicion}}</td>
                                <td>{{$tipo->titulo}}</td>
                                <td>{{$tipo->icono}}</td>
                                <td>{{$tipo->color}}</td>
                                <td>{{$tipo->bg}}</td>
                                <td>{{$tipo->estado}}</td>
                                <td>
                                    <div class="d-flex justify-content-center" role="group">
                                        <!-- botón editar -->
                                        <a href="{{ route('tipos.edit', $tipo->id) }}" class="btn btn-info btn-sm"><i class="far fa-edit"></i></a>
                                        <!-- botón borrar -->
                                        <form action="{{ route('tipos.destroy', $tipo->id)}}" method="POST" class="formEliminar">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm ml-2"><i class="far fa-trash-alt"></i></button>
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
        var forms = document.querySelectorAll('.formEliminar')
        Array.prototype.slice.call(forms)
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
    })()
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#tipos').DataTable({
            "lengthMenu": [
                [5, 10, 50, -1],
                [5, 10, 50, "All"]
            ]
        });
    });
</script>