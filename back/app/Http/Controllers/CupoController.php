<?php

namespace App\Http\Controllers;

use App\Models\Cupo;
use App\Http\Requests\StoreCupoRequest;
use App\Http\Requests\UpdateCupoRequest;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\ImageManagerStatic as Image;

class CupoController extends Controller
{
    public function index(){return Cupo::with('materials')->get();}
    public function validaCupon($ci){
        return Cupo::where('ci',$ci)->first();
    }
    public function store(StoreCupoRequest $request){return Cupo::create($request->all());}
    public function show($codigo){
        return Cupo::where('codigo', $codigo)->first();
    }
    public function update(UpdateCupoRequest $request, Cupo $cupo){
        $cupo->update($request->all());
        return $cupo;
    }
    public function updateRegistro(UpdateCupoRequest $request, Cupo $cupo){
        $request->validate([
            'ci' => 'required|unique:cupos,ci,'.$cupo->id,
//            'email' => 'required|unique:cupos,email,'.$cupo->id,
        ]);
        $query=Cupo::where('ci', $request->ci)->get();
        if (sizeof($query) > 0) {
            return response()->json(['message' => 'El CI ya se encuentra registrado'], 500);
        }else{
            $cupo->update($request->all());
            return $cupo;
        }

    }

    public function updateMaterial(UpdateCupoRequest $request, $ci){
        $cupo= Cupo::where('ci', $ci)->first();
        $cupo=Cupo::find($cupo->id);
        $cupo->update($request->all());
        return $cupo;
    }

    public function updateTipo(Request $reques){
        $cupo= Cupo::find($reques->id);
        $cupo->tipo=$reques->tipo;
        return $cupo->save();
    }

    public function destroy(Cupo $cupo){$cupo->delete();return $cupo;}
    public function cupoPdf(Request $request){
        $data=[];
        foreach ($request->all() as $value) {
 
            $png = QrCode::format('png')->size(250)->generate(env('URL_FRONT').'registro/'.$value['codigo']);
            $png = base64_encode($png);
            $value['qr']=$png;
            $data[]=$value;
        }
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('cupoPdf', ['cupos' => $data]);
        $pdf->setPaper('letter', 'landscape');
        $pdf->save('cupos.pdf')->stream();
    }
    public function certificadoPdf(Request $request){
        $data=[];
        foreach ($request->lista as $value) {
            $tip='';
            switch ($value['tipo']) {
                case 'PARTICIPANTE':
                    $tip='par';
                    $titulo='CERTIFICADO DE PARTICIPACION';
                    break;
                    case 'EXPOSITOR':
                        $tip='exp';
                        $titulo='CERTIFICADO DE RECONOCIMIENTO';
                        break;
                        case 'ORGANIZADOR':
                        $tip='org';
                        $titulo='CERTIFICADO DE RECONOCIMIENTO';
                        break;
                        case 'DOCENTE':
                        $tip='org';
                        $titulo='CERTIFICADO DE PARTICIPACION';
                    # code...
                            break;
                default:
                    # code...
                    $tip='par';
                    $titulo='CERTIFICADO DE PARTICIPACION';
                    break;
            }
            $png = QrCode::format('png')->size(250)->generate('https://certificados.sistemas.edu.bo/jtc2022'.$tip.'/'.$value['ci']);
            $png = base64_encode($png);
            $value['url']='https://certificados.sistemas.edu.bo/jtc2022'.$tip.'/'.$value['ci'];
            $value['titulo']=$titulo;
            $value['fondo']=$request->fondo;
            $value['qr']=$png;
            $data[]=$value;
        }
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('certificadoPdf', ['certificados' => $data]);
        $pdf->setPaper('letter', 'landscape');
        $pdf->save('certificados.pdf')->stream();
    }
    public function credencialPdf(Request $request){
        $data=[];
        $generator = new BarcodeGeneratorPNG();
        foreach ($request->lista as $value) {
//            $png = '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($value['ci'], $generator::TYPE_CODE_128)) . '">';
            $value['qr']=base64_encode($generator->getBarcode($value['ci']==null?'123':$value['ci'], $generator::TYPE_CODE_128));
            $value['fondo']=$request->fondo;
            $data[]=$value;
        }
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('credencialPdf', ['credencials' => $data]);
        $pdf->setPaper('letter', 'landscape');
        $pdf->save('credencials.pdf')->stream();
    }

    public function cupoFile(){
        return response()->file('cupos.pdf');
    }
    public function certificadoFile(){
        return response()->file('certificados.pdf');
    }
    public function credencialFile(){
        return response()->file('credencials.pdf');
    }
    public function buscarCupo(Request $request,$ci){
        $cupo=Cupo::with('materials')->where('ci',$ci)->first();
        if ($cupo==null){
            return response()->json(['message' => 'El CI no se encuentra registrado'], 500);
        }else{
            return $cupo;
        }
    }
    public function rotateFoto(Request $request){
        // create Image from file
// create Image from file
        $img = Image::make('imagenes/'.$request->foto);

// rotate image 45 degrees clockwise
        $name= time().".jpg";
        $img->rotate(-90)->save('imagenes/'.$name);
        $cupo=Cupo::find($request->id);
        $cupo->foto=$name;
        $cupo->save();
        return $cupo;
//        $file = public_path('/imagenes/'.$request->foto);
//        $img = Image::make($file);
//        $image = $request->file('foto');
//
//        $file_name = time().'_'.$image->getClientOriginalExtension();
//        $file_out = time().$image->getClientOriginalName();
//
//        $parts = pathinfo($file_name);
//
//        $extensions = $parts['extension'];
//
//        if($extensions == "jpeg"){
//
//            $degrees = 90;
//
//            $source = imagecreatefromjpeg($image);
//
//            $rotate = imagerotate($source, $degrees, 0);
//
//            imagejpeg($rotate, $file_out);
//            $cupo = Cupo::find($request->id);
//            $cupo->foto = $file_out;
//            $cupo->save();
//            return response()->file(public_path($file_out));
//
//
//        }elseif($extensions == "png"){
//
//            $degrees = 90;
//
//            $source = imagecreatefrompng($image);
//
//            $rotate = imagerotate($source, $degrees, 0);
//
//            imagepng($rotate, $file_out);
//            $cupo = Cupo::find($request->id);
//            $cupo->foto = $file_out;
//            $cupo->save();
//            return response()->file(public_path($file_out));
//
//        }else{
//            echo "Plz Select jpeg And Png File.";
//        }
    }
}
