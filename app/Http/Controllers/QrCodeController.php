<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    //
    public function homeView(){
        return view('qrcode');
    }
}
