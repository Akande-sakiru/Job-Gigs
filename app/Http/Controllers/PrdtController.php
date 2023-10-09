<?php

namespace App\Http\Controllers;

use App\Models\prdt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PrdtController extends Controller
{
    //
    public function index(){
        $products =prdt::all();
        return view('index',compact('products'));
    }
    public function createPdrt(){
        return view('create');

    }
    public function storePdrt(Request $req){
        $number = mt_rand(1000000000,9999999999);
        if($this->productCodeExists($number)){
             $number = mt_rand(1000000000,9999999999);
        }
        $req['product_code']=$number;
        prdt::create($req->all());
        return Redirect('qrcode1');
    }
    public function productCodeExists($number){
        return prdt::whereProductCode($number)->exists();

    }
}
