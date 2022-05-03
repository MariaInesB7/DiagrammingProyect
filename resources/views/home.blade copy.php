@extends('adminlte::page')




@section('content')

 <div id="ud_diagram_div" class="ud_diagram_div" style="width: 800px; height:500px">
               
                   <canvas class="ud_diagram_canvas" width="700" height="400" >
                      
                   </canvas>
                   <canvas class="ud_diagram_canvas" width="700" height="400" >
                      
                </canvas>
               
                   
 </div>
          
@endsection

@section('css')

<link type=‘text/css’ rel=‘stylesheet’ href="{{asset('/css/UDStyle.css') }}" />
@stop

@section('js')
<script type=‘text/javascript’ src="{{ asset('/UDCore.js') }}" ></script>
<script type=‘text/javascript’ src="{{ asset('/UDModules.js') }}" ></script>

<script>
 
    var div = document.getElementById( "ud_diagram_div" );

   div.setAttribute( "class", "ud_diagram_div" );

   div.style.width = width + "50 px";

   div.style.height = height + "50 px";
  
   var canvas = document.createElement("canvas");

   canvas.setAttribute( "class", "ud_diagram_canvas");

   canvas.width = 300;

   canvas.height = 500;

   var mainContext = canvas.getContext("2d");

   div.appendChild( canvas );

   canvas = document.createElement("canvas");

    canvas.setAttribute( "class", "ud_diagram_canvas");

    canvas.width = this.width;

    canvas.height = this.height;

    canvas.onmousedown = function () { return false; }

    var motionContext = canvas.getContext("2d");

   var d1 = new UMLUseCaseDiagram({backgroundNodes: "#ff9900"});

   d1.initialize( 0, div, mainContext, motionContext, width, height ); 
   
   var d2 = new UMLClassDiagram({backgroundNodes: "#ff9900"});

d2.initialize( 1, div, mainContext, motionContext, width, height );
   </script>

@stop