<?php

namespace App\Http\Controllers\Patient;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Illuminate\Support\Facades\QRCode;

class QrCodeController extends BaseController
{
    
    public function index(){
        $qr_code = new QrCode();
        $code = $qr_code->setText("Sample Text")
            ->setSize(300)
            ->setPadding(10)
            ->setErrorCorrection('high');
    
        return response($code->render());
    }
}
