<?php

namespace App\Http\Controllers;

use App\Models\Cupo;
use App\Models\Material;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMaterialRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function register($material, $request){

    }

    public function totalmaterial(Request $request){
        if ($request->user()->id== 1) {
            return DB::SELECT("SELECT
            (select count(*) from materials where nombre='CREDENCIAL Y PORTA CREDENCIAL' and estado=true) cantcred,
            (select count(*) from materials where nombre='FOLDER' and estado=true) cantfolder,
            (select count(*) from materials where nombre='BOLIGRAFO' and estado=true) cantbol,
            (select count(*) from materials where nombre='BARBIJO' and estado=true) cantbar,
            (select count(*) from materials where nombre='CERTIFICADO' and estado=true) cantcert,
            (select count(*) from materials where nombre='CD' and estado=true) cantcd");
        } else {
            return DB::SELECT("SELECT
            (select count(*) from materials where nombre='CREDENCIAL Y PORTA CREDENCIAL' and estado=true and user_id=" . $request->user()->id . " ) cantcred,
            (select count(*) from materials where nombre='FOLDER' and estado=true and user_id=" . $request->user()->id . " ) cantfolder,
            (select count(*) from materials where nombre='BOLIGRAFO' and estado=true and user_id=" . $request->user()->id . " ) cantbol,
            (select count(*) from materials where nombre='BARBIJO' and estado=true and user_id=" . $request->user()->id . " ) cantbar,
            (select count(*) from materials where nombre='CERTIFICADO' and estado=true and user_id=" . $request->user()->id . " ) cantcert,
            (select count(*) from materials where nombre='CD' and estado=true and user_id=" . $request->user()->id . " ) cantcd");
        }
    }

    public function listentrega(Request $request){
        if($request->user()->id==1){
            return DB::SELECT("SELECT DISTINCT(c.ci),c.nombres
            FROM cupos c inner join materials m on c.id=m.cupo_id
            where date(m.fecha)='$request->fecha'
            order by c.nombres asc;");
        }
        else
        {
            return DB::SELECT("SELECT DISTINCT(c.ci),c.nombres
            FROM cupos c inner join materials m on c.id=m.cupo_id
            where m.user_id=".$request->user()->id."
            and date(m.fecha)='$request->fecha'
            order by c.nombres asc;");
        }
    }


    public function reporteMat1(Request $request){
        return DB::SELECT("
        SELECT c.id,c.ci,c.nombres,
        (SELECT m.estado from materials m where  m.nombre='CREDENCIAL Y PORTA CREDENCIAL' and m.cupo_id=c.id ) as credencial ,
        (SELECT m.estado from materials m where  m.nombre='FOLDER' and m.cupo_id=c.id ) as folder ,
        (SELECT m.estado from materials m where  m.nombre='BOLIGRAFO' and m.cupo_id=c.id ) as boligrafo ,
        (SELECT m.estado from materials m where  m.nombre='BARBIJO' and m.cupo_id=c.id ) as barbijo, 
        (SELECT m.estado from materials m where  m.nombre='CERTIFICADO' and m.cupo_id=c.id ) as certificado, 
        (SELECT m.estado from materials m where  m.nombre='CD' and m.cupo_id=c.id ) as cd, 
        (SELECT m.fecha from materials m where  m.nombre='CREDENCIAL Y PORTA CREDENCIAL' and m.cupo_id=c.id ) as fecha
        
        FROM cupos c
        WHERE c.ci is not null;");
    }

    public function reporteMat2(Request $request){
        return DB::SELECT("
        SELECT c.id,c.ci,c.nombres,
        (SELECT m.estado from materials m where  m.nombre='CERTIFICADO' and m.cupo_id=c.id ) as certificado, 
        (SELECT m.estado from materials m where  m.nombre='CD' and m.cupo_id=c.id ) as cd, 
        (SELECT m.fecha from materials m where  m.nombre='CERTIFICADO' and m.cupo_id=c.id ) as fecha
        
        FROM cupos c
        WHERE c.ci is not null;");
    }

    public function store(StoreMaterialRequest $request)
    {


        $materiales= Material::where('cupo_id', $request->id)->get()->count();

        if ($materiales == 0) {
            $material = new Material();
            $material->nombre = "CREDENCIAL Y PORTA CREDENCIAL";
            $material->fecha=date('Y-m-d');
            $material->hora=date('H:i:s');
            $material->estado=$request->credencial;
            $material->cupo_id=$request->id;
            $material->user_id=$request->user()->id;
            $material->save();
            $material = new Material();
            $material->nombre = "FOLDER";
            $material->fecha=date('Y-m-d');
            $material->hora=date('H:i:s');
            $material->estado=$request->folder;
            $material->cupo_id=$request->id;
            $material->user_id=$request->user()->id;
            $material->save();
            $material = new Material();
            $material->nombre = "BOLIGRAFO";
            $material->fecha=date('Y-m-d');
            $material->hora=date('H:i:s');
            $material->estado=$request->boligrafo;
            $material->cupo_id=$request->id;
            $material->user_id=$request->user()->id;
            $material->save();
            $material = new Material();
            $material->nombre = "BARBIJO";
            $material->fecha=date('Y-m-d');
            $material->hora=date('H:i:s');
            $material->estado=$request->barbijo;
            $material->cupo_id=$request->id;
            $material->user_id=$request->user()->id;
            $material->save();
            $material = new Material();
            $material->nombre = "CERTIFICADO";
            $material->fecha=date('Y-m-d');
            $material->hora=date('H:i:s');
            $material->estado=$request->certificado;
            $material->cupo_id=$request->id;
            $material->user_id=$request->user()->id;
            $material->save();
            $material = new Material();
            $material->nombre = "CD";
            $material->fecha=date('Y-m-d');
            $material->hora=date('H:i:s');
            $material->estado=$request->cd;
            $material->cupo_id=$request->id;
            $material->user_id=$request->user()->id;
            $material->save();
        }else{
            $material= Material::whereCupo_id($request->id)->whereNombre("CREDENCIAL Y PORTA CREDENCIAL")->first();
            error_log($material);
            if ($material->estado==0 && $request->credencial) {
            $material->fecha=date('Y-m-d');
            $material->estado=true;
                $material->user_id=$request->user()->id;
                $material->save();
            }
            $material= Material::whereCupo_id($request->id)->whereNombre("FOLDER")->first();
            if ($material->estado==0 && $request->folder) {
            $material->fecha=date('Y-m-d');
            $material->estado=true;
            $material->user_id=$request->user()->id;
            $material->save();
            }
            $material= Material::whereCupo_id($request->id)->whereNombre("BOLIGRAFO")->first();
            if ($material->estado==0 && $request->boligrafo) {
            $material->fecha=date('Y-m-d');
            $material->estado=true;
            $material->user_id=$request->user()->id;
            $material->save();
            }
            $material= Material::whereCupo_id($request->id)->whereNombre("BARBIJO")->first();
            if ($material->estado==0 && $request->barbijo) {
            $material->fecha=date('Y-m-d');
            $material->estado=true;
            $material->user_id=$request->user()->id;
            $material->save();
            }
            $material= Material::whereCupo_id($request->id)->whereNombre("CERTIFICADO")->first();
            if ($material->estado==0 && $request->certificado) {
            $material->fecha=date('Y-m-d');
            $material->estado=true;
            $material->user_id=$request->user()->id;
            $material->save();
            }
            $material= Material::whereCupo_id($request->id)->whereNombre("CD")->first();
            if ($material->estado==0 && $request->cd) {
                $material->fecha=date('Y-m-d');
                $material->estado=true;
            $material->user_id=$request->user()->id;
            $material->save();
            }
        }



        //
//        $request->id;
//        $mat1=Material::where('cupo_id',$request->id)->where('nombre','CREDENCIAL')->first();
//        if(!$mat1){ $mat1=DB::table('materials')->insert(['nombre'=>'CREDENCIAL','fecha'=>$request->fecha,'hora'=>$request->hora,'cupo_id'=>$request->id]);}
//        $mat2=Material::where('cupo_id',$request->id)->where('nombre','BARBIJO')->first();
//        if(!$mat2){ $mat2=DB::table('materials')->insert(['nombre'=>'BARBIJO','fecha'=>$request->fecha,'hora'=>$request->hora,'cupo_id'=>$request->id]);}
//        $mat3=Material::where('cupo_id',$request->id)->where('nombre','FOLDER')->first();
//        if(!$mat3){ $mat3=DB::table('materials')->insert(['nombre'=>'FOLDER','fecha'=>$request->fecha,'hora'=>$request->hora,'cupo_id'=>$request->id]);}
//        $mat4=Material::where('cupo_id',$request->id)->where('nombre','CERTIFICADO')->first();
//        if(!$mat4){ $mat4=DB::table('materials')->insert(['nombre'=>'CERTIFICADO','fecha'=>$request->fecha,'hora'=>$request->hora,'cupo_id'=>$request->id]);}
//        $mat5=Material::where('cupo_id',$request->id)->where('nombre','CD')->first();
//        if(!$mat5){ $mat5=DB::table('materials')->insert(['nombre'=>'CD','fecha'=>$request->fecha,'hora'=>$request->hora,'cupo_id'=>$request->id]);}
//        $mat6=Material::where('cupo_id',$request->id)->where('nombre','BOLIGRAFO')->first();
//        if(!$mat6){ $mat6=DB::table('materials')->insert(['nombre'=>'BOLIGRAFO','fecha'=>$request->fecha,'hora'=>$request->hora,'cupo_id'=>$request->id]);}
//
//        if($request->credencial && $mat1->estado==0){
//            $mat1->fecha=$request->fecha;
//            $mat1->hora=$request->hora;
//            $mat1->estado=$request->credencial;
//            $mat1->save();
//        }
//        if($request->barbijo && $mat2->estado==0){
//            $mat2->fecha=$request->fecha;
//            $mat2->hora=$request->hora;
//            $mat2->estado=$request->barbijo;
//            $mat2->save();
//        }
//        if( $request->folder && $mat3->estado==0){
//            $mat3->fecha=$request->fecha;
//            $mat3->hora=$request->hora;
//            $mat3->estado=$request->folder;
//            $mat3->save();
//        }
//        if( $request->certificado && $mat4->estado==0){
//            $mat4->fecha=$request->fecha;
//            $mat4->hora=$request->hora;
//            $mat4->estado=$request->certificado;
//            $mat4->save();
//        }
//        if( $request->cd && $mat5->estado==0){
//            $mat5->fecha=$request->fecha;
//            $mat5->hora=$request->hora;
//            $mat5->estado=$request->cd;
//            $mat5->save();
//        }
//        if( $request->boligrafo && $mat6->estado==0){
//            $mat6->fecha=$request->fecha;
//            $mat6->hora=$request->hora;
//            $mat6->estado=$request->boligrafo;
//            $mat6->save();
//        }
        $cupo=Cupo::where('id',$request->id)->with('materials')->first();
        return $cupo;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function show(Material $material)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function edit(Material $material)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMaterialRequest  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMaterialRequest $request, Material $material)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\Response
     */
    public function destroy(Material $material)
    {
        //
    }
}
