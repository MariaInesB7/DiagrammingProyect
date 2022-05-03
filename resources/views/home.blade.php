@extends('adminlte::page')




@section('content')
<div id="todo" onload="init()">
 <div id="pizarra" style="width: 300 px; height:300px; border :1px solid black" >
               
                   <canvas class="ud_diagram_canvas" width="700" height="400" >
                      
                   </canvas>
                   <canvas class="ud_diagram_canvas" width="700" height="400" >
                      
                </canvas>
               
                   
 </div>
</div>         
@endsection

@section('css')

{{-- <link type=‘text/css’ rel=‘stylesheet’ href="{{asset('/css/UDStyle.css') }}" />
@stop --}}

@section('js')
<script src="go.js" ></script>


<script>
 function init(){
    var$ go.GraphObject.make;
    myDiagram=$(go.Diagram,"pizarra");
    var nodeDataAray=[
        {key:"Alpha"},
        {key:"Beta"}
    ];
    var linkDataArray=[
    {to: "Beta", from: "Alpha"}
    ];
    myDiagram.model = new go.GraphLinksModel(nodeDataAray,link);
 }
   
   </script>

@stop