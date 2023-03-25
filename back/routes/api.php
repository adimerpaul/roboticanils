<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('login', [\App\Http\Controllers\UserController::class, 'login']);
Route::get('cupoFile', [\App\Http\Controllers\CupoController::class, 'cupoFile']);
Route::get('certificadoFile', [\App\Http\Controllers\CupoController::class, 'certificadoFile']);
Route::get('credencialFile', [\App\Http\Controllers\CupoController::class, 'credencialFile']);
Route::post('upload', [\App\Http\Controllers\UploadController::class, 'upload']);
Route::apiResource('cupo', \App\Http\Controllers\CupoController::class);
Route::apiResource('student', \App\Http\Controllers\StudentController::class);
Route::put('updateRegistro/{cupo}', [\App\Http\Controllers\CupoController::class, 'updateRegistro']);
Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::post('me', [\App\Http\Controllers\UserController::class, 'me']);
    Route::post('logout', [\App\Http\Controllers\UserController::class, 'logout']);
    Route::put('updatePassword/{user}', [\App\Http\Controllers\UserController::class, 'updatePassword']);

    Route::apiResource('user', \App\Http\Controllers\UserController::class);
    Route::apiResource('refrigerio', \App\Http\Controllers\RefrigerioController::class);
    Route::post('totalreg', [\App\Http\Controllers\RefrigerioController::class, 'totalreg']);
    Route::post('printRefri', [\App\Http\Controllers\RefrigerioController::class, 'printRefri']);

    Route::apiResource('material', \App\Http\Controllers\MaterialController::class);
    Route::post('cupoPdf', [\App\Http\Controllers\CupoController::class, 'cupoPdf']);
    Route::post('certificadoPdf', [\App\Http\Controllers\CupoController::class, 'certificadoPdf']);
    Route::post('credencialPdf', [\App\Http\Controllers\CupoController::class, 'credencialPdf']);
    Route::post('updateMaterial/{ci}', [\App\Http\Controllers\CupoController::class, 'updateMaterial']);
    Route::post('buscarStudent/{ci}', [\App\Http\Controllers\StudentController::class, 'buscarStudent']);
    Route::post('buscarCupo/{ci}', [\App\Http\Controllers\CupoController::class, 'buscarCupo']);
    Route::post('validaCupon/{ci}', [\App\Http\Controllers\CupoController::class, 'validaCupon']);
    Route::post('rotateFoto', [\App\Http\Controllers\CupoController::class, 'rotateFoto']);
    Route::post('updateTipo', [\App\Http\Controllers\CupoController::class, 'updateTipo']);
    Route::post('totalmaterial', [\App\Http\Controllers\MaterialController::class, 'totalmaterial']);
    Route::post('listentrega', [\App\Http\Controllers\MaterialController::class, 'listentrega']);
    


});
