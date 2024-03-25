<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveDocRequest;
use App\Models\DocumentsModel;

class DocumentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except'=> ['']]);
    }

    public function save(SaveDocRequest $request)
    {
        
    }
}