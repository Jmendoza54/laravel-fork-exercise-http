<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class ExerciseController extends Controller
{
    public function getData(){

        $response = rescue(function () {

            return Http::retry(3, 100)->post('https://atomic.incfile.com/fakepost');
        
        }, function ($e) {
            
            return $e->response;
        
        });

        if($response->successful()){
            return $this->msg('Listo', $response->status(), $response->body());
        }

        if($response->failed()){
            return $this->msg('Error', $response->status());
        }

        if($response->clientError())
            return $this->msg('Error', $response->status());

        if($response->serverError())
            return $this->msg('Error', $response->status());
    }

    public function msg($msg, $code, $data = null){
        return response()->json([
            'msg' => $msg,
            'code' => $code,
            'data' => $data
        ]);
    }
}
