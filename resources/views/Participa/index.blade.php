@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Diagramas</h1>
@stop

@section('content')
<div class="container-fluid mt--7">
  <div class="row">
      <div class="col">
          <div class="card shadow">
              <div class="card-header border-0">
                  <div class="row align-items-center">
                      <div class="col-md-8">
                        <h3 class="mb-0"><b>Diagramas</b></h3>
                      </div>
                      <div align="right" class="col-md-4">
                          <button class="btn btn-primary btn-lg" data-toggle="modal"
                          data-target="#addModal" 
                          type="button"  name="button"> 
                         Crear Diagrama
                          </button>
                      </div>
                  </div>
              </div>
              
              <div class="table-responsive">
                @if ($participas)
                      <div class="card-group">
                          @foreach ($participas as $participa)
                          <div class="card ">
                            
                              <div class="card-body">
                              <i class="fas fa-pen-square fa-10x" style="color: rgb(86, 67, 131)" ></i>
                              {{-- <h5 class="card-title">Nombre: {{DB::table('documentos')->where('id',$participa->documentoId)->value('nombre')}}</h5> --}}
                            
                            {{--   <p class="card-text"> Cliente: {{DB::table('clientes')->where('id',$participa->idCliente)->value('nombre')}}</p> --}}
                           
                            <h5 class="card-title">ID: {{$participa->documentoId}} </h5>
                            <p class="card-text">Estado: {{$participa->usuarioId}}</p>
                         {{--    <p class="card-text">Fecha: {{DB::table('documentos')->where('id',$participa->documentoId)->value('fecha')}}</p>
                              <p class="card-text">Hora de inicio: {{DB::table('documentos')->where('id',$participa->documentoId)->value('estado')}}</p>
                              <p class="card-text">Estado: {{$documentos->estado}}</p>
                              <p class="card-text">Link: {{$documentos->link}}</p> --}}
                          
                              @csrf

                             {{--  <form  action="{{route('participas.destroy',$participa)}}" method="post">
                                  @csrf
                              @method('delete') --}}
                               
                               {{--  <a class="btn btn-info btn-sm" href="{{route('participas.edit',$participa)}}">Ver o Editar</a>  --}}
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿ESTA SEGURO DE  BORRAR?')" 
                                value="Borrar">Eliminar</button>
                            </form>
                              </div>
                          </div>
                          @endforeach
                      </div>
                      <div align="center" class="d-flex justify-content-center">
                          
                        {{--       {!! $participas->links("pagination::bootstrap-4") !!} --}}
                          
                      </div>

                 
                      @else
                  
                      @endif
                    
              </div>
              <div class="card-footer py-4">
                  <nav class="d-flex justify-content-end" aria-label="...">
                      
                  </nav>
              </div>
          </div>
      </div>
  </div>
      
</div>

<!--Agregue el formulario-->
<div class="modal fade" tabindex="-1" role="dialog" id="addModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo Diagrama</h4>
      </div>
      <div class="modal-body">
        <form  action="{{route('participas.store')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              
            </div>
            <div class="form-group">
              <label> Nombre: </label>
              <input type="text" class="form-control" name="nombre">
            </div>
           

            
               
                <div class="form-group">
                  <label> Estado: </label>
                  <input type="text" class="form-control" name="estado">
                </div>
                <div class="form-group">
                  <label> Link: </label>
                  <input type="text" class="form-control" name="link">
                </div>
              
                {{-- <div class="form-group">
              <h5>Cliente:</h5>
          <select name = "idCliente" id="idCliente" class="form-control">
              <option value="">Seleccione el cliente</option>
                  @foreach ($clientes as $cliente)
                      <option value="{{$cliente->id}}">
                          {{$cliente->nombre}}
                      </option>
                  @endforeach
          </select>
                </div> --}}

       {{--    <h5>Empleado:</h5>
          <select name = "idUser" id="idUser" class="form-control" onchange="habilitar()" >
              <option value="">Seleccione el empleado</option>
                  @foreach ($users as $user)
                      <option value="{{$user->id}}">
                          {{$user->name}}
                      </option>
                  @endforeach
          </select> --}}

                <input type="submit" name="submit" value="Guardar" class="btn btn-success">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </form>
      
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop