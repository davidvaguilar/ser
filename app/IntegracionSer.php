<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

class IntegracionSer extends Model
{
    private $jwt;
    private $payload;
    private $objConexion;
    private $conn;
	private $api_his='';
	private $token;	

    public function __construct()
    {
		//solo se usa local
	    //$this->objConexion = new Oracle();
		//$this->conn = $this->objConexion->getInstancia(config('app.DB_USERNAME'),config('app.DB_PASSWORD'));
		        	
		$this->api_his = 'https://www.corporacionser.com/api-resolute/';
		$this->token = 'Bearer eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJuYW1laWQiOiIxIiwidW5pcXVlX25hbWUiOiJoZXRnXzIwMjFfc2VyIiwibmJmIjoxNjMyNTI2MTkyLCJleHAiOjE2MzI2MTI1OTIsImlhdCI6MTYzMjUyNjE5Mn0.6HvLlVnicBmtU_7AcDpi5udc0MpRSFqByg9Bz60SMGDjYKHcavccEJZQkRgDcNbdsf26R02Gc-JroNwZkUaO0w';
	}

    public function login(){
		//set_time_limit(0);
		$body = array();
		$response = array();		
		$data = [];
		$dt = [];
		
		try{
			$client = new Client();

            $request_param = [
                'usuario'    => "HETG_2021_SER",
                'password'   => "Ser@HeTg"       
            ];	
            $request_data = json_encode($request_param);
            $res = $client->request(
                'POST',
                url($this->api_his.'api/Usuarios/login'),
                [
                    'headers' => [
                        'Content-Type'     => 'application/json',
                      //  'Authorization' => $this->token,    
                    ],
                    'body'   => $request_data
                ]
            );
          
            $data = json_decode($res->getBody()->getContents(), true);
            return $data;			
            

		}catch (BadResponseException $e) {
            
			$response = $e->getResponse();	
            dd($response->getStatusCode());
			// $data = [ "code"=>$response->getStatusCode(), "message"=>"Operacion fallida." ];		
		}

		return $data;
	}

	public function getPacienteFecha($token, $inicio, $fin){
		//set_time_limit(0);
		$body = array();
		$response = array();		
		$data = [];
		$dt = [];
		
		try{
			$client = new Client([
                'curl.options' =>[ 'CURLOPT_BUFFERSIZE' => 10485764]
                ]);

            $request_param = [
                'fechaInicio'    => $inicio,            //  $inicio[$i]
                'fechaFin'       => $fin,               // $fin[$i]
            ];	
            $request_data = json_encode($request_param);
            $res = $client->request(
                'POST',
                url($this->api_his.'api/Pacientes/fecha'),
                [
                    'headers' => [
                        'Content-Type'     => 'application/json',
                        'Authorization' => 'Bearer '.$token,    
                    ],
                    'body'   => $request_data
                ]
            );
            $data = json_decode($res->getBody()->getContents(), true);
            return $data;			
            

		}catch (BadResponseException $e) {
            
            /*$response = $client->post($this->api_his.'api/Pacientes/fecha', [
                'headers' => ['Content-type' => 'application/json',
							  'authorization'=>$this->token]
			]);	
			$data = json_decode($response->getBody(), true);
			$dt = data_get($data, 'data', 'default');  */
			$response = $e->getResponse();	

			$data = [ "code"=>$response->getStatusCode(), "message"=>"Operacion fallida." ];		
		}

		return $data;
	}


}
