<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\License;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    public function index(Request $request)
{
    $validator = Validator::make($request->all(), [
        'license_key' => 'required',
        'domain' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors(),
        ], 422);
    }

    $license = License::where('license_key', $request->input('license_key'))
        ->where('domain', $request->input('domain'))
        ->first();

    if (!$license) {
        return response()->json([
            'message' => 'License not found.',
        ], 404);
    }

    $encryptedData = Crypt::encrypt(json_encode($license->license_key));
    $decrypted_data = Crypt::decryptString($encryptedData);

    // Kirim data ke server Laravel B
    $client = new Client();
    $response = $client->request('POST', 'http://laravelb.com/api/license', [
        'form_params' => [
            'data' => $encryptedData
        ]
    ]);

    $responseData = json_decode($response->getBody(), true);

    return response()->json([
        'data' => $responseData['data'],
    ]);
}

}
