<?php
namespace App\Http\Controllers;
use SoapClient;

class InstanceSoapClient extends BaseSoapController implements InterfaceInstanceSoap
{
    public static function init(){
        $usuario_desarrollo = "DerivIquique";
        $clave_desarrollo = "DerivIquiquePRO";
        $usuario_produccion = "SSDerivIquique";
        $clave_produccion = "SS2020quique";

        $wsdlUrl = self::getWsdl();
        $soapClientOptions = [
            'stream_context' => self::generateContext(),
            'login'          => $usuario_produccion,
            'password'       => $clave_produccion,
            'trace' => 1
        ];
        return new SoapClient($wsdlUrl, $soapClientOptions);
    }
}