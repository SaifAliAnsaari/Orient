<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

class JsonApiResponse{

    public $status;
    public $data;
    public $code;

    public function __construct($status, $code, $data){
        $this->status = $status;
        $this->code = $code;
        $this->data = $data;
    }

    function apiResponse(){
        return array('status' => $this->status, 'code' => $this->code, 'data' => $this->data);
    }

}
