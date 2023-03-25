<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;


class UploadController extends Controller
{
    public function upload(Request $request){
        $this->validate($request, [
            'file'=>'required',
        ]);
        if ($request->hasFile('file')){
            $file    = $request->file('file');
            $nombre     = time().".".$file->getClientOriginalExtension();
            $ruta=public_path('/imagenes/'.$nombre);
            Image::make($file->getRealPath())
                ->resize(300,300
                    ,function ($constraint){
                        $constraint->aspectRatio();
                    }
                )
                ->save($ruta,72);
        }
        return $nombre;

    }
}
