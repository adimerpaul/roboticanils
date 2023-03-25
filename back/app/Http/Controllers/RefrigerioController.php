<?php

namespace App\Http\Controllers;

use App\Models\Cupo;
use App\Models\Refrigerio;
use App\Http\Requests\StoreRefrigerioRequest;
use App\Http\Requests\UpdateRefrigerioRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RefrigerioController extends Controller
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
     * @param  \App\Http\Requests\StoreRefrigerioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function totalreg(Request $request){
//        return DB::select("SELECT turno,count(*) total FROM `refrigerios` WHERE fecha='$request->fecha' GROUP BY turno;");
        return DB::SELECT("SELECT (select count(*) from cupos where ci is not null) total,
         (select COUNT(*) from refrigerios where turno='MAÑANA' and date(fecha)='$request->fecha' AND user_id=".$request->user()->id.") manana,
        (select COUNT(*) from refrigerios where turno='TARDE' and date(fecha)='$request->fecha' AND user_id=".$request->user()->id.") tarde");

    }
    public function store(StoreRefrigerioRequest $request)
    {
        //
        $cupo=Cupo::where('ci',$request->ci)->first();
        $refrigerio=Refrigerio::where('cupo_id',$cupo->id)->where('turno',$request->turno)->where('fecha',$request->fecha)->get();
        if(sizeof($refrigerio)==0){
            $refri=new Refrigerio;
            $refri->fecha=$request->fecha;
            $refri->hora=$request->hora;
            $refri->turno=$request->turno;
            $refri->cupo_id=$cupo->id;
            $refri->user_id=$request->user()->id;
            $refri->save();
            $refu=Refrigerio::where('id',$refri->id)->with('cupo')->with('user')->first();
            return $refu;
        }
        else{
            return response()->json(['message' => 'Se encuentra registrado'], 500);

        }
    }

    public function printRefri(Request $request)
    {
        //
        $cupo=Cupo::where('ci',$request->ci)->first();
        $refrigerio=Refrigerio::with('cupo')->with('user')->where('cupo_id',$cupo->id)->where('turno',$request->turno)->where('fecha',$request->fecha)->get();
        if(sizeof($refrigerio)>0){
            return $refrigerio[0];
        }
        else{
            return response()->json(['message' => 'No se encuentra registrado'], 500);

        }
    }

    public function reporteDia(Request $request){
        return DB::SELECT("SELECT c.id,c.ci,c.nombres,m.fecha,m.hora 
        FROM cupos c inner join refrigerios m on c.id=m.cupo_id
        WHERE  c.ci is not null 
        and  m.fecha='$request->fecha' and m.turno=$request->turno");
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Refrigerio  $refrigerio
     * @return \Illuminate\Http\Response
     */
    public function show(Refrigerio $refrigerio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Refrigerio  $refrigerio
     * @return \Illuminate\Http\Response
     */
    public function edit(Refrigerio $refrigerio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRefrigerioRequest  $request
     * @param  \App\Models\Refrigerio  $refrigerio
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRefrigerioRequest $request, Refrigerio $refrigerio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Refrigerio  $refrigerio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Refrigerio $refrigerio)
    {
        //
    }
}
