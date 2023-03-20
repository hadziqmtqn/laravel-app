<?php

namespace App\Http\Controllers\API;

use App\Helpers\DTO;
use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index_old(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        if ($validator->fails()) {
            return DTO::ResponseDTO('Search User Failed', null, $validator->errors(), null, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            // $data = User::where('name','LIKE',"%$request->name%")
            // ->orWhere('email','LIKE',"%$request->email%")
            // ->get();

            $response = Http::get('http://localhost:8000/api/user');
            // dd($response);
            // die();
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return DTO::ResponseDTO('List User Failed',  null, 'Oops, error', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return DTO::ResponseDTO('List User Succesfully', null, null, $response, Response::HTTP_OK);
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid input', 'errors' => $validator->errors()], 422);
        }

        try {
            $response = Http::get('http://localhost:8000/api/user');
            
            if ($response->successful()) {
                $data = $response->json();
                return response()->json($data);
            } else {
                return response()->json(['message' => 'Failed to retrieve data from external link'], 500);
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(['message' => 'Error occurred while retrieving data from external link'], 500);
        }
    }

    public function list()
    {
        $users = User::get();

        return response()->json($users);
    }

}
