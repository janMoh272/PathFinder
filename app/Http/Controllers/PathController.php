<?php

namespace App\Http\Controllers;

use App\Models\Path;
//use Illuminate\Http\Request;

class PathController extends Controller
{
         public function getpath()
    {

    $path=Path::all();
    return response()->json($path);
        
    }
}
