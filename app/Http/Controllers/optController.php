<?php

namespace App\Http\Controllers;

use Spatie\Newsletter\NewsletterFacade as Newsletter;
use Str;
use Auth;
use DB;
use Illuminate\Support\Facades\Http;
use App\Models\tokens;
use Illuminate\Support\Carbon;
use App\Models\User;

use Illuminate\Http\Request;

class optController extends Controller
{
    public function opt()
    {
        return view('opt');
    }
    public function optin(Request $request)
    {
        $registeredemail =  $request->input('email');
        $randomNumber = Str::random(8);
        $firstname = 'user' .   $randomNumber;


        $user = new User();

        $user->name =  $firstname;
        $user->email =  $request->input('email');
        $user->role =  'user';
        $user->password = bcrypt('password123'); // Make sure to hash the password for security

        $user->save();


        $token =   $request->input('token');
        $token_to_clear = tokens::where('token', $token)->get()->first();




        if ($token_to_clear) {
            // Update the user record

            if ($token_to_clear->status === 1) {

                $token_to_clear->status = 0;
                $token_to_clear->save();

          ///////////////////http///////////////
                $response = Http::get('https://zimpapers.pressreader.com/?token=' .  $user->password); // Replace with the actual external API URL

      
                if ($response->successful()) {


                    return view('success', compact('registeredemail'));
                } else {
                    return response()->json(['error' => 'Failed to fetch data from the external API'], $response->status());
                }
          ///////////////////http///////////////

            } else {

                return ('token is used');
            }


        } else {
            echo 'Token not found.';
        }
    }


    public function api(Request $request)
    {

        $token_pass = $request->input('token');
        $call = $request->input('call');
        $userid = $request->input('userid');

        $username = $request->input('username');
        $password = $request->input('password');



        $today = Carbon::now();
        $nextMonth = $today->addMonth();
        $nextMonthFormatted = $nextMonth->toDateString();



        if ($call == "get_user_by_userid") {




            $user = User::find($userid);





            if ($user->count() === 0) {
                $xmlResponse = '<?xml version="1.0" encoding="utf-8"?>
<error>
    <code>04</code>
    <message>User not found</message>
</error>';
            } else {
                $xmlResponse = '<?xml version="1.0" encoding="utf-8"?>
<member>';




                $xmlResponse .= '
    <userID>' . $user->id . '</userID>
    <loginName>' . $user->email . '</loginName>
    <email>' . $user->email . '</email>
    <firstname>' .  $user->name . '</firstname>
    <lastname>' .   $user->name  . '</lastname>
    <homeareacode></homeareacode>
    <homephone></homephone>
    <workareacode></workareacode>
    <workphone></workphone>
    <address></address>
    <apartment></apartment>
    <city></city>
    <province></province>
    <postalcode></postalcode>
    <country></country>
    <gender></gender>
    <nickname>' .  $user->name . '</nickname>
    <products>
        <product>
            <productID>BundleA</productID>
            <startdate>' .    $today . '</startdate>
            <enddate>' . $nextMonthFormatted . '</enddate>
        </product>
    </products></member>';
            }

            return response($xmlResponse)->header('Content-Type', 'application/xml');
        } elseif ($call == "authenticate") {

            $credentials = [
                'email' => $request->input('username'),
                'password' => $request->input('password'),
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();




                if (empty($user)) {
                    $xmlResponse = '<?xml version="1.0" encoding="utf-8"?>
<error>
    <code>04</code>
    <message>User not found</message>
</error>';
                } else {
                    $xmlResponse = '<?xml version="1.0" encoding="utf-8"?>
<member>';




                    $xmlResponse .= '
    <userID>' . $user->id . '</userID>
    <loginName>' . $user->email . '</loginName>
    <email>' . $user->email . '</email>
    <firstname>' .  $user->name . '</firstname>
    <lastname>' .   $user->name  . '</lastname>
    <homeareacode></homeareacode>
    <homephone></homephone>
    <workareacode></workareacode>
    <workphone></workphone>
    <address></address>
    <apartment></apartment>
    <city></city>
    <province></province>
    <postalcode></postalcode>
    <country></country>
    <gender></gender>
    <nickname>' .  $user->name . '</nickname>
    <products>
        <product>
            <productID>BundleA</productID>
            <startdate>' .    $today . '</startdate>
            <enddate>' . $nextMonthFormatted . '</enddate>
        </product>
    </products></member>';
                }
            } else {

                $xmlResponse = '<?xml version="1.0" encoding="utf-8"?>
<error>
    <code>04</code>
    <message>Wrong Credentials</message>
</error>';
            }

            return response($xmlResponse)->header('Content-Type', 'application/xml');
        } elseif ($call == "get_user_by_token") {



            $user = DB::table('users')->where('password', '=', $token_pass)->get()->first();





            if (empty($user)) {
                $xmlResponse = '<?xml version="1.0" encoding="utf-8"?>
<error>
    <code>04</code>
    <message>User not found</message>
</error>';
            } else {
                $xmlResponse = '<?xml version="1.0" encoding="utf-8"?>
<member>';




                $xmlResponse .= '
    <userID>' . $user->id . '</userID>
    <loginName>' . $user->email . '</loginName>
    <email>' . $user->email . '</email>
    <firstname>' .  $user->name . '</firstname>
    <lastname>' .   $user->name  . '</lastname>
    <homeareacode></homeareacode>
    <homephone></homephone>
    <workareacode></workareacode>
    <workphone></workphone>
    <address></address>
    <apartment></apartment>
    <city></city>
    <province></province>
    <postalcode></postalcode>
    <country></country>
    <gender></gender>
    <nickname>' .  $user->name . '</nickname>
    <products>
        <product>
            <productID>BundleA</productID>
            <startdate>' .    $today . '</startdate>
            <enddate>' . $nextMonthFormatted . '</enddate>
        </product>
    </products></member>';
            }



            return response($xmlResponse)->header('Content-Type', 'application/xml');
        } else {


            $xmlResponse = '<error>
 <code>04</code>
 <message>User not found</message>
</error> ';
            return response($xmlResponse)->header('Content-Type', 'application/xml');
        }
    }
}
