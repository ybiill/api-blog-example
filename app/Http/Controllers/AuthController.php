<?php

namespace App\Http\Controllers;

use App\Models\MPasswordreset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $post_data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8'
        ]);
        try {
            $user = User::create([
                'name' => $post_data['name'],
                'email' => $post_data['email'],
                'password' => Hash::make($post_data['password']),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Akun Berhasil Terdaftar',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!\Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Invalid login details'
                ], 401);
            }
            $user = User::where('email', $request['email'])->firstOrFail();
            $token = $user->createToken('authToken')->plainTextToken;
            return response()->json([
                'message' => true,
                'data' => $user,
                'role' => $user->role,
                'access_token' => Crypt::encrypt($token),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }
    public function sendForgotpassword(Request $request)
    {
        require base_path("vendor/autoload.php");

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }
        $validatordata = $validator->validated();
        MPasswordreset::where('email', $validatordata['email'])->delete();

        $tokenrandom = Str::random(70);
        $linkreset = "<a href='http://127.0.0.1:1234/api/forgotpassword/" . $tokenrandom . "'>Reset Password</a>";

        try {
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = 'mail.dinta.co.id';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'dtc@dinta.co.id';
            $mail->Password   = '@dtcdinta800';
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = '465';

            $mail->IsHTML(TRUE);
            $mail->setFrom('dtc@dinta.co.id', 'CS Dinta Indonesia');
            $mail->addAddress($validatordata['email']);

            $mail->Subject = 'Password Reset';
            $mail->Body    = 'A request for forgot password has been made. If you have not made this request, please ignore this email. If you have made this request, please click on the link below to reset your password. <br>' . $linkreset;
            $mail->AltBody = 'reset password';

            if (!$mail->send()) {
                return response()->json([
                    'status' => false,
                    'message' => "Data Tidak terikirim",
                ]);
            } else {
                $emailToken = MPasswordreset::create([
                    'email' => $validatordata['email'],
                    'token' => $tokenrandom,
                ]);
                return response()->json([
                    'message' => true,
                    'data' => $emailToken,
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }
    public function Forgotpassword(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [],
                'message' => $validator->errors(),
                'success' => false
            ]);
        }
        $validatordata = $validator->validated();
        //Cek-token
        $user = MPasswordreset::where('token', $token)
            ->orderBy('created_at', 'desc')->first();

        try {
            if (!$user) {
                return response()->json([
                    'message' => 'Link Reset Expired.',
                    'success' => false
                ]);
            } else {
                $user = User::where('email', $user['email'])->firstOrFail();
                $user->update([
                    'password' => Hash::make($validatordata['password'])
                ]);
                MPasswordreset::where('email', $user['email'])->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Password Berhasil DiUpdate.',
                    'data' => $user
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal',
                'success' => true,
                'data' => $e
            ]);
        }
    }
}
