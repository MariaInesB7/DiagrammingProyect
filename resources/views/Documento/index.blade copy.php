@extends('adminlte::page')




@section('content')
<div id="todo" >
 <div id="myDiagramDiv" style="width: 300 px; height:300px; border :1px solid black" >
               
               
                   
 </div>
</div>         
@endsection

@section('css')

{{-- <link type=‘text/css’ rel=‘stylesheet’ href="{{asset('/css/UDStyle.css') }}" />
@stop --}}

@section('js')
<script src="https://unpkg.com/gojs/release/go.js" ></script>

<script>
 const myDiagram = new go.Diagram("myDiagramDiv",
    { // enable Ctrl-Z to undo and Ctrl-Y to redo
      "undoManager.isEnabled": true
    });

myDiagram.model = new go.Model(
  [ // for each object in this Array, the Diagram creates a Node to represent it
    { key: "Alpha" },
    { key: "Beta" },
    { key: "Gamma" }
  ]);
 </script>
{{-- <script>
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
   
   </script> --}}

@stop