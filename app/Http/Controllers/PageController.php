<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PageController extends Controller
{
    public function massOperations()
    {
        $files = \Storage::disk('images')->allFiles();
        return view('pages.mass_operations', ['files' => $files]);
    }
}
