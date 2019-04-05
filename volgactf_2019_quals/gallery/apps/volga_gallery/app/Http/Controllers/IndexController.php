<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use DirectoryIterator;

class IndexController extends Controller
{
    public function __construct()
    {
    }

    public function index() {
        return response("Welcome to volgactf gallery backend", 200)->header('Content-Type', 'text/plain');;
    }
}
