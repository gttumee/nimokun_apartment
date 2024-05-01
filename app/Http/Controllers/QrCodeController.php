<?php

namespace App\Http\Controllers;

use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    public function generateQRCode()
    {
        $id = Auth::id();
        dd($id);
        $url = 'https://mtche.jp';
        $qrCode = QrCode::size(300)->generate($url);
        return view('qrcode', compact('qrCode'));
    }
}