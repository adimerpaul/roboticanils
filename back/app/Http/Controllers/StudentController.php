<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller{
    public function index(){
        return Student::all();
    }

    public function create(){
    }

    public function buscarStudent($ci){
        return Student::where('ci',$ci)->first();
    }

    public function store(StoreStudentRequest $request){
        $student= new Student;
        $student->ci=$request->ci;
        $student->nombres=strtoupper($request->nombres);
        $student->carrera=strtoupper($request->carrera);
        $student->celular=$request->celular;
        $student->direccion=$request->direccion;
        $student->save();
    }


    public function show($ci){
        $student = Student::where('ci', $ci)->first();
        return $student;
    }


    public function edit(Student $student){
    }



    public function update(UpdateStudentRequest $request, Student $student){
        $student= Student::find($request->id);
        $student->nombres=strtoupper($request->nombres);
        $student->carrera=strtoupper($request->carrera);
        $student->celular=$request->celular;
        $student->direccion=$request->direccion;
        $student->save();
    }


    public function destroy(Student $student){
        $student->delete();
    }
}
