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

    </style>
</head>
<body>
@foreach($cupos as $cupo)
    @if($loop->index%2==0)
        <img src="folleto.jpg" style="  position: absolute;top: 5px;left: 5px;width: 518px;height: 800px" >
        <img src="data:image/png;base64, {!! $cupo['qr'] !!}" style="border:2px solid white;position: absolute;top: 530px;left: 420px;width: 90px;height: 90px">
        <div style="position: absolute;top: 753px;left: 490px; font-weight: bold;width: 85px;height: 85px;font-family: 'Nunito', sans-serif;color: #a0aec0">{{$cupo['id']}}</div>
{{--        <img src="nom.jpg" style="  position: absolute;top: 300px;left: 35px;width: 150px;height: 45px" >--}}
        <div style="position: absolute;top: 400px;left: 35px;width: 300px;height: 15px;font-family: 'Nunito', sans-serif;color: #000;padding: 5px;background: #f6f9fc ;border: 2px solid #22254b;border-radius: 5px">Nombre..............................</div>
    @else
        <img src="folleto.jpg" style="  position: absolute;top: 5px;right: 5px;width: 518px;height: 800px" >
        <img src="data:image/png;base64, {!! $cupo['qr'] !!}" style="border:2px solid white;position: absolute;top: 530px;right: 25px;width: 90px;height: 90px">
        <div style="position: absolute;top: 753px;right: -50px; font-weight: bold;width: 85px;height: 85px;font-family: 'Nunito', sans-serif;color: #a0aec0">{{$cupo['id']}}</div>
        <div style="position: absolute;top: 400px;left: 550px;width: 300px;height: 15px;font-family: 'Nunito', sans-serif;color: #000;padding: 5px;background: #f6f9fc ;border: 2px solid #22254b;border-radius: 5px">Nombre..............................</div>
{{--        @if (count($cupos) == $loop->index+1)--}}
            <div class="page_break"></div>
{{--        @endif--}}

    @endif
@endforeach
</body>
</html>
