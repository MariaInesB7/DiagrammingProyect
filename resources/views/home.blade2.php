<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Canvas</title>
    </head>
    <body>
        <h1>Hola</h1>
        <canvas width="320" height="160" style="border: 1px solid #000000"> </canvas>

        <script>
        var lienzo = document.querySelector("canvas");
        var context=lienzo.getContext("2d");
        context.fillStyle = "cyan";
        context.fillRect(20,20,100,50);
        </script>
    </body>
</html>