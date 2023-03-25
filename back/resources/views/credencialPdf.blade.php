<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        * {
            /*font-family: Arial, Helvetica, sans-serif;*/
            padding: 0px;
            margin: 0px;
            border: 0px;
        }
        .page_break { page-break-before: always; }
        @page { size: 8cm 10cm; }
    </style>
</head>
<body>
<?php
$cont=0;
?>
@foreach($credencials as $credencial)
@if($credencial['fondo']=='SI')
<img src="credencial.jpeg" style="opacity: 0.5;position: absolute;top: 0px;left: 0px;width: 8cm;height: 10cm">
@endif
<img src="imagenes/{{$credencial['foto']}}" style="position: absolute;top: 95px;left: 120px;width: 80px;height: 80px;border-radius: 40px" >
<div style="text-align: center;position: absolute;top: 200px;left: 53px;font-size: 14px; font-weight: bold;width: 210px;height: 40px;font-family: 'Nunito', sans-serif;color: #373e48;text-align: center;background: white;border: 1px solid black ;border-radius: 5px">{{$credencial['nombres']}}</div>
<div style="text-align: center;position: absolute;top: 245px;left: 53px;font-size: 14px; font-weight: bold;width: 210px;height: 40px;font-family: 'Nunito', sans-serif;color: #373e48;text-align: center;background: white;border: 1px solid black ;border-radius: 5px">{{$credencial['tipo']}}</div>
<img src="data:image/png;base64, {!! $credencial['qr'] !!}" style="background : white ; border:2px solid white;position: absolute;top: 295px;left: 67px;width: 170px;height: 50px">
<div style="color: #a0aec0;position: absolute;top: 350px;left: 915px;width: 10px;height: 10px">{{$credencial['id']}}</div>
<?php $cont++;?>
@if($cont!==sizeof($credencials))
<div class="page_break"></div>
@endif
@endforeach
</body>
</html>
