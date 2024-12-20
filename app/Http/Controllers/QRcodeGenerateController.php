<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Str;
use App\Models\tokens;
use Illuminate\Http\Request;

class QRcodeGenerateController extends Controller
{
    public function qrcode()
    {

        $qrCodes = [];


        $i = 0;



        do {
            $randomNumber = Str::random(8);

     
            $link =  route('register-email', ['serial' => $randomNumber]);

            $qrCodes[$randomNumber] = QrCode::size(150)->generate($link);



            $post = new tokens();
            $post->token = $randomNumber;
            $post->status = 1;
          
    
            $post->save();



            $i++;
        } while ($i < 6);


        
        return view('qrcodes', compact('qrCodes'));
    }
}
