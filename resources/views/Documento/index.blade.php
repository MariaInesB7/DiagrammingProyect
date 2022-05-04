@extends('adminlte::page')

@section('title', 'Diagramming')

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
                  @if ($documentos)
                      <div class="card-group">
                          @foreach ($documentos as $documento)
                          <div class="card ">
                            
                              <div class="card-body">
                              <i class="fas fa-pen-square fa-10x" style="color: rgb(86, 67, 131)" ></i>
                              <h5 class="card-title">Nombre: {{$documento->nombre}} </h5>
                            
                           
                              <p class="card-text">Fecha: {{$documento->fecha}}</p>
                       
                              <p class="card-text">Link: {{$documento->link}}</p>
                              @csrf

                              <form  action="{{route('documentos.destroy',$documento)}}" method="post">
                                  @csrf
                              @method('delete')
                            
                                <button class="btn btn-danger btn-sm" onclick="return confirm('¿ESTA SEGURO DE  BORRAR?')" 
                                value="Borrar">Eliminar</button>
                            </form>
                               
                            <a class="btn btn-info btn-sm" href="{{route('documentos.edit',$documento)}}">Abrir</a>  
                              </div>
                          </div>
                          @endforeach
                      </div>
                      <div align="center" class="d-flex justify-content-center">
                          
                        {!! $documentos->links("pagination::bootstrap-4") !!}
                            
                          
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
        <h4 class="modal-title">Crear documento</h4>
      </div>
      <div class="modal-body">
        <form  action="{{route('documentos.store')}}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
              
            </div>
            <div class="form-group">
              <label> Nombre: </label>
              <input type="text" class="form-control" name="nombre">
            </div>
           

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