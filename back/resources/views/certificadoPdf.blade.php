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
            font-family:"Calibri, sans-serif";
        }
        .page_break { page-break-before: always; }

    </style>
</head>
<body>
<?php
$cont=0;
?>
@foreach($certificados as $certificado)
    @if($certificado['fondo']=='SI')
        <img src="certificado.png" style=" position: absolute;top: 0px;left: 0px;width: 1056px;height: 816px" >
    @endif
{{--    <div style="border: 0px solid red;position: absolute;top: 255px;left: 110px;text-align: center;font-size: 2.8em; font-weight: bold;width: 870px;height: 35px;font-family: 'Nunito', sans-serif;color: #343c47">{{$certificado['titulo']}}</div>--}}
    <div style="border: 0px solid red;position: absolute;top: 325px;left: 90px;text-align: center;font-size: 1.8em; font-weight: bold;width: 900px;height: 35px;font-family: 'Nunito', sans-serif;color: #343c47">A: {{$certificado['nombres']}}</div>
    <div style="border: 0px solid red;position: absolute;top: 380px;left: 120px;text-align: justify;font-size: 1em;width: 850px;height: 85px;font-family: 'Nunito', sans-serif;color: #343c47">
        Por su participación en calidad de <span style="font-size: 1.5em;font-weight: bold;">{{$certificado['tipo']}}</span> en la competencia <span style="font-size: 1.5em;font-weight: bold;"> BATTLEBOT SUMO</span>
        demostrandoo habilidades en programación, mecánica y construcción de robots. Se le otorga el presente certificado. <br>

        Desarrollado el 26 de Marzo del 2023 en la ciudad de Oruro, Bolivia.
    </div>
    <div style="border: 0px solid red;position: absolute;top: 480px;left: 150px;text-align: left;font-size: 1em;width: 850px;height: 85px;font-family: 'Nunito', sans-serif;color: #343c47">
        Oruro, Noviembre de 2023
    </div>
    <div style="border: 0px solid red;position: absolute;top: 550px;left: 120px;text-align: center;font-size: 0.6em;width: 850px;height: 85px;font-family: 'Nunito', sans-serif;color: #343c47">
        <table style="width: 100%">
            <tr>
                <td style="text-align: center">Ing. Adimer Paul Chambi Ajata<br>INGENIERIO INFORMATICO</td>
                <td style="text-align: center">Ing. Marcelo Callapa Nicolas<br>GERENTE GENERAL<br>WALTYS YOGUR GRIEGO</td>
                <td style="text-align: center">Ing. Alejandro Fernando Lopez Gutierrez<br>INGENIERIO INFORMATICO</td>
            </tr>
        </table>
    </div>
    <div style="position: absolute; top: 500px;right: 700px;"><img src="firma3.png" width="200px" height="100px"></div>
    <div style="position: absolute; top: 480px;right: 450px;"><img src="firma2.png" width="200px" height="100px"></div>
    <div style="position: absolute; top: 480px;right: 150px;"><img src="firma1.png" width="180px" height="90px"></div>
    <img src="data:image/png;base64, {!! $certificado['qr'] !!}" style="border:2px solid white;position: absolute;top: 620px;right: 450px;width: 90px;height: 90px">
{{--    <div style="border: 0px solid red;position: absolute;top: 700px;left: 120px;text-align: center;font-size: 0.5em; font-weight: bold;width: 850px;height: 35px;font-family: 'Nunito', sans-serif;color: #343c47">{{$certificado['url']}}</div>--}}

    <?php $cont++;?>
    @if($cont!==sizeof($certificados))
        <div class="page_break"></div>
    @endif
{{--    @if($loop->index%2==0)--}}
{{--        <img src="folleto.jpg" style="  position: absolute;top: 5px;left: 5px;width: 518px;height: 800px" >--}}
{{--        <img src="data:image/png;base64, {!! $cupo['qr'] !!}" style="border:2px solid white;position: absolute;top: 575px;left: 420px;width: 90px;height: 90px">--}}
{{--        <div style="position: absolute;top: 753px;left: 490px; font-weight: bold;width: 85px;height: 85px;font-family: 'Nunito', sans-serif;color: #a0aec0">{{$cupo['id']}}</div>--}}
{{--    @else--}}
{{--        <img src="folleto.jpg" style="  position: absolute;top: 5px;right: 5px;width: 518px;height: 800px" >--}}
{{--        <img src="data:image/png;base64, {!! $cupo['qr'] !!}" style="border:2px solid white;position: absolute;top: 575px;right: 25px;width: 90px;height: 90px">--}}
{{--        <div style="position: absolute;top: 753px;right: -50px; font-weight: bold;width: 85px;height: 85px;font-family: 'Nunito', sans-serif;color: #a0aec0">{{$cupo['id']}}</div>--}}
{{--        <div class="page_break"></div>--}}
{{--    @endif--}}
@endforeach
</body>
</html>
