<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\so_tsic_eventos;
use App\so_tpacte;
use App\gg_tprofesional;
use App\gg_tespecialidad;
use App\so_tecitas;
use App\gg_tdiagno;
use App\ss_tusuarios;
use Illuminate\Support\Facades\Log;
use App\Oracle;

class SoapController extends BaseSoapController
{
    private $objConexion;
    private $conn;

    private $service;
    private $url_desarrollo = "https://prepplataformadeintegraciones.saludenred.cl:8292/WSNotificacionesInterconsulta/Derivacion.svc?wsdl";
    private $url_produccion = "https://esb.saludenred.cl:8292/WSNotificacionesInterconsulta/Derivacion.svc?wsdl";  
    private $tipoMensaje = '1';
    private $idSoftwareInforma = "14";
    private $versionSoftwareInforma = 'SISTEMA DE GESTIÓN CLÍNICA HETG Ver 1.0';
    private $idSitioInforma = "14";
    private $codigoEstablecimientoInforma = "02-100";

    function __construct(Request $request)
    {
        $this->objConexion = new Oracle();
	}

    public function index()
    {
        Log::info('Integracion Rayen iniciado: '.date("d/m/Y H:i:s")); 
        try{
            $this->conn = $this->objConexion->getInstancia(config('app.DB_USERNAME'),config('app.DB_PASSWORD'));
   
                     //$this->solicitudAceptada(3137);
            //$this->solicitudEgresada(3148);
            // $this->solicitudObservada(3308);
            //$this->citaAgendada(3313);


            $sql = "begin HETG_SIC.pau_listarEvento(:io_Cursor); end;";	

            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);

            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);

            ociexecute($stmt);
            ociexecute($curs);
  
            while($fila = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS)){
                switch( $fila["EVENTO_NUMERO"] ){
                    case '1':
                            $this->solicitudAceptada($fila["EVENTO_CODIGO"]);
                        break;
                    case '2':
                            $this->solicitudEgresada($fila["EVENTO_CODIGO"]);
                        break;
                    case '3':
                            $this->solicitudObservada($fila["EVENTO_CODIGO"]);  // HAY ALGO QUE NO FUNCIONA
                        break;
                    case '4':
                            $this->citaAgendada($fila["EVENTO_CODIGO"]);
                        break;   
                    case '5':
                            $this->llegadaDePaciente($fila["EVENTO_CODIGO"]);
                        break;      
                    case '6':
                            $this->ausenciaDePaciente($fila["EVENTO_CODIGO"]);
                        break;   
                    case '7':
                            $this->confirmacionDiagnostica($fila["EVENTO_CODIGO"]);
                        break;  
                    case '8':
                            $this->responderDerivacion($fila["EVENTO_CODIGO"]);
                        break;          
                    case '9':
                            $this->altaDePaciente($fila["EVENTO_CODIGO"]);
                        break;              
                    case '10':
                            $this->citaCancelada($fila["EVENTO_CODIGO"]);
                        break;
                }
            }
        }catch( \Exception $e ){
            Log::info($e->getMessage());   
        }
        Log::info('Integracion Rayen finalizado: '.date("d/m/Y H:i:s")); 
        return back();
    }

    public function solicitudAceptada($num_correl){
        
        try {
            $sql = "begin HETG_SIC.pau_eventoAceptada(:plsCorrel, :io_Cursor); end;";	
            
            $curs = oci_new_cursor($this->conn);
            
            $stmt = oci_parse($this->conn, $sql);
            
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            
            
            ociexecute($stmt);
            ociexecute($curs);
            
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();            // dd($this->service->__getTypes());
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"               =>  $resultado["SIC_CORRELATIVO"]           // $resultado["SIC_NUMERO"],   // $sic->num_sic,
                ]
            ];

            $response = $this->service->NotificarSolicitudAceptada($params);      // dd($this->service->__getLastRequest());

            //dd($this->service->__getLastRequest());

            $nom_descripcion = $response->NotificarSolicitudAceptadaResult->Descripcion;
            $ind_condicion = $response->NotificarSolicitudAceptadaResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarSolicitudAceptadaResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);									
            ociexecute($stmt);
        }
        catch(\Exception $e) {
            dd($e->getMessage());
            Log::info($e->getMessage());           // Log::info($io_Error);
        }
    }

    public function solicitudEgresada($num_correl){
        
        try {
            $sql = "begin HETG_SIC.pau_eventoEgresada(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);
            

            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();  
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"               => $resultado["SIC_CORRELATIVO"],           // $resultado["SIC_NUMERO"],
                    "FechaYHoraEgreso"          => $resultado["EVENTO_FECHA"], //$sic->fec_evento->format('Ymd H:i'),     //date("Ymd H:i"),
                    "CausalDeEgreso"            => $resultado["EVENTO_CAUSAL"],   // 1-15
                    "Observacion"               => $resultado["EVENTO_OBSERVACION"],   //OPCIONAL
                ]
            ];
            $response = $this->service->NotificarSolicitudEgresada($params);
            
            // dd($this->service->__getLastRequest());

            $nom_descripcion = $response->NotificarSolicitudEgresadaResult->Descripcion;
            $ind_condicion = $response->NotificarSolicitudEgresadaResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarSolicitudEgresadaResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);		
            ociexecute($stmt);
            //Log::info($io_Error);
        }
        catch(\Exception $e) {
            dd($e->getMessage());
            Log::info($e->getMessage());
        }
    }

    public function solicitudObservada($num_correl){
        try {
            $sql = "begin HETG_SIC.pau_eventoObservada(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();
            $params = array(
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"               => $resultado["SIC_CORRELATIVO"],     //$resultado["SIC_NUMERO"],
                    "ObservacionSICDestino"     => array(
                        "IdSolicitud"	        => $resultado["SIC_CORRELATIVO"],           // $resultado["SIC_NUMERO"],
                        "FechaObservacion"		=> $resultado["EVENTO_FECHA"],
                        "FuncionarioGenerador"	=>
                        array(
                            "Run" 		    => $resultado["FUNCIONARIO_RUT"],   //OPCIONAL
                            "Nombres"       => $resultado["FUNCIONARIO_NOMBRE"],  //OPCION
                            "PrimerApellido"	=> $resultado["FUNCIONARIO_PATERNO"],     //OPCION
                            "SegundoApellido"	=> $resultado["FUNCIONARIO_MATERNO"],    //OPCION
                            //"Contacto"      => $pacte->num_telefo1,  //OPCION
                        ),                       
                        "Observacion"   => $resultado["EVENTO_OBSERVACION"],   
                    ),
                ]
            );            
            $response = $this->service->NotificarSolicitudObservada($params);

            // dd($this->service->__getLastRequest());

            $nom_descripcion = $response->NotificarSolicitudObservadaResult->Descripcion;
            $ind_condicion = $response->NotificarSolicitudObservadaResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarSolicitudObservadaResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);			
            ociexecute($stmt);
            //Log::info($io_Error);
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /* METODO 4 */
    public function citaAgendada($num_correl){
        try {
            $sql = "begin HETG_SIC.pau_eventoCitaAgendada(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);
    
            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"               => $resultado["SIC_CORRELATIVO"],       // $resultado["SIC_NUMERO"],
                    "EsReserva"			        => "SI",    // SI = contactabilidad desde APS, NO = contactabilidad Hospital
                    "Confirmada"  			=> "NO",                 //$confirmada,   
                    "IdCita"          		=> $resultado["CITA_CODIGO"],
                    "FechaYHoraCita"        => $resultado["EVENTO_FECHA"],                              // OPCIONAL
                    "RunProfesional"        => $resultado["PROFESIONAL_RUT"],                           // OPCIONAL
                    "NombresProfesional"    => $resultado["PROFESIONAL_NOMBRE"],                        // OPCIONAL max 30 Char
                    "PrimerApellidoProfesional"		=> $resultado["PROFESIONAL_PATERNO"],               // OPCIONAL max 30 Char
                    "SegundoApellidoProfesional"    => $resultado["PROFESIONAL_MATERNO"],               // OPCIONAL max 30 Char
                    "CodigoEstablecimiento" 	=> "02-100",                                            //OPCIONAL   == CodigoEstablecimientoInforma
                    "FechaYHoraEvento"          => $resultado["EVENTO_FECHA"],  
                    "CodigoEspecialidadEstablecimiento" 	=> $resultado["ESPECIALIDAD_CODIGO"],       // OPCIONAL
                    "NombreEspecialidadEstablecimiento"		=> $resultado["ESPECIALIDAD_NOMBRE"],       // OPCIONAL
                    "TipoDeConsulta"        => $resultado["CITA_TIPO"],
                    "LugarAtencion"         => $resultado["AGENDA_UBICACION"],
                    "Observaciones"         => $resultado["EVENTO_OBSERVACION"],        //OPCIONAL
                ]
            ];
           
            $response = $this->service->NotificarCitaAgendada($params);

            // dd($this->service->__getLastRequest());

            $nom_descripcion = $response->NotificarCitaAgendadaResult->Descripcion;
            $ind_condicion = $response->NotificarCitaAgendadaResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarCitaAgendadaResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);
            ociexecute($stmt);
            //echo $io_Error;
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());    //print_r($e->getMessage());
        }
    }

    /* METODO 5  */
    public function llegadaDePaciente($num_correl){    
        try {
            $sql = "begin HETG_SIC.pau_eventoLlegadaPaciente(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"           => $resultado["SIC_CORRELATIVO"],       // $resultado["SIC_NUMERO"],
                    "IdCita"                => $resultado["CITA_CODIGO"],
                    "FechaYHoraEvento"		=> $resultado["EVENTO_FECHA"],
                    // "PacienteFueAtendido"	=> 1        //$pacientefueatendido,    // OPCIONAL  boolean=1,0
                ]
            ];                  

            $response = $this->service->NotificarLlegadaDePaciente($params);
            $nom_descripcion = $response->NotificarLlegadaDePacienteResult->Descripcion;
            $ind_condicion = $response->NotificarLlegadaDePacienteResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarLlegadaDePacienteResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);
            ociexecute($stmt);
            //Log::info($io_Error);
        }
        catch(\Exception $e) {           
            Log::info($e->getMessage());    //  print_r($e->getMessage());
        }
    }

    /* METODO 6 */
    public function ausenciaDePaciente($num_correl){    
        try {
            $sql = "begin HETG_SIC.pau_eventoAusenciaPaciente(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"           => $resultado["SIC_CORRELATIVO"],
                    "IdCita"                => $resultado["CITA_CODIGO"],
                    "FechaYHoraEvento"		=> $resultado["EVENTO_FECHA"],
                ]
            ]; 

            $response = $this->service->NotificarAusenciaDePaciente($params);
            $nom_descripcion = $response->NotificarAusenciaDePacienteResult->Descripcion;
            $ind_condicion = $response->NotificarAusenciaDePacienteResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarAusenciaDePacienteResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);									
            ociexecute($stmt);
            //Log::info($io_Error);
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /* METODO 7  */
    public function confirmacionDiagnostica($num_correl){    
        try {
            $sql = "begin HETG_SIC.pau_eventoConfirmacionDiag(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            $evento_codigo = $resultado["EVENTO_CODIGO"];
            $sic_correlativo = $resultado["SIC_CORRELATIVO"];
            $evento_fecha = $resultado["EVENTO_FECHA"];
            $diagnosis = array("Diagnostico" => array()); 
            $diagnosis["Diagnostico"][] =  array(
                "CodigoDiagnostico" => $resultado["DIAGNOSTICO_CODIGO"],
                "SistemaCodificacionDiagnostica" => 'CIE-10',
                "DescripcionDiagnostica" => $resultado["DIAGNOSTICO_NOMBRE"],                
                //"CodigoAlternativoDiagnostico" => $resultado["DIAGNOSTICO_CODIGO"],       // $resultado["DIAGNOSTICO2_CODIGO"],
                //"SistemaCodificacionAlternativo" => 'CIE-10',
                //"DescripcionDiagnosticoAlternativo" =>  $resultado["DIAGNOSTICO_NOMBRE"], // $resultado["DIAGNOSTICO2_NOMBRE"],
                //"FundamentoDiagnostico" => $fundamento,                                   // OPCIONAL STRING
                "TipoDiagnostico" => '0',     // 0=NINGUNO, 1=CONFIRMADO, 2=SOSPECHA
            );

            while($resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS)){
                $diagnosis["Diagnostico"][] =  array(
                    "CodigoDiagnostico" => $resultado["DIAGNOSTICO_CODIGO"],
                    "SistemaCodificacionDiagnostica" => "CIE-10",
                    "DescripcionDiagnostica" => $resultado["DIAGNOSTICO_NOMBRE"], 
                    "TipoDiagnostico" => '0',                       // 0=NINGUNO, 1=CONFIRMADO, 2=SOSPECHA
                );
            }

            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();            
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"                       => $sic_correlativo,   
                    "FechaYHoraConfirmacionDiagnostica" => $evento_fecha,
                    "DiagnosticosConfirmados"           => $diagnosis     //$params['parametro']['DiagnosticosConfirmados']
                ]
            ];
                //   $params['parametro']['DiagnosticosConfirmados'] = $diagnosis;   //COLOCARLO POR FUERA otra forma

            //dd($params);
            $response = $this->service->NotificarConfirmacionDiagnostica($params);
            // dd($this->service->__getLastRequest());

            $nom_descripcion = $response->NotificarConfirmacionDiagnosticaResult->Descripcion;
            $ind_condicion = $response->NotificarConfirmacionDiagnosticaResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarConfirmacionDiagnosticaResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);
        
            oci_bind_by_name($stmt,":plsCorrel", $evento_codigo);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);									
            ociexecute($stmt);
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());                 // Log::info($io_Error);
        }
    }


    /* METODO 8 */
    public function responderDerivacion($num_correl){    
        try {
            $sql = "begin HETG_SIC.pau_eventoResponderDerivacion(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            self::setWsdl($this->url_desarrollo);
            $this->service = InstanceSoapClient::init();
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "ApellidoMaterno"   => $resultado["PACIENTE_APELLIDO_MATERNO"],
                    "ApellidoPaterno"   => $resultado["PACIENTE_APELLIDO_PATERNO"],
                    "CodEspEgreso"      => $resultado["ESPECIALIDAD_CODIGO"],           // CODIGO DE 820
                    "Comuna"            => $resultado["PACIENTE_COMUNA"],
                    "ContinuaControl"   => 1,                  //  1=SI, 2=NO
                    "DiagCie10"         => $resultado["DIAGNOSTICO_CODIGO"],
                    "Direccion"         => $resultado["PACIENTE_DIRECCION"],
                    "Run"               => $resultado["PACIENTE_RUT"],
                    "Dv"                => $resultado["PACIENTE_VERIFICADOR"],
                    "RunProfesional"    => $resultado["PROFESIONAL_RUT"],
                    "DvProfesional"     => $resultado["PROFESIONAL_VERIFICADOR"],
            //      "Email" => $numsic,                                                 // OPCIONAL
                    "EstablecimientoOrigen"     => $this->codigoEstablecimientoInforma,     // Código DEIS del establecimiento de origen
                    "EstablecimientoOtorga"     => $this->codigoEstablecimientoInforma,     // Código DEIS del establecimiento que otorga "02-306"
                    "FechaCR"           => '20200309',                                  //  FALTA  FECHA=YYYYMMDD
                    "FechaNacimiento"   => $resultado["PACIENTE_NACIMIENTO"],
                    "FolioInternoAps"   => $resultado["SIC_NUMERO"],               // Aca es el numero SIC Rayen
            //      "Indicaciones" => $this->idsoftwareinforma,                         // opcional Indicaciones alta del paciente
                    "LugarControl"      => 1,          // Integer, Lugar del Control  1=APS 2= Secundaria
                    "AltaClinica"       => 1,                                         //  OPCIONAL  ALTA CLÍNICA 1=SI, 2=NO
                    "MotivoAltaClinica" => 1,                                         // 1=Alta Medica 2=Deriv Interna, 3=Deriv APS, 4 Fallecido, 5=Administrativo
                    "MotivoPertinencia" => 1,                                         // 1=No se ajusta a guia clinica, 2=Mapa derivacion
                    "NombrePaciente"    => $resultado["PACIENTE_NOMBRE"],
                    "NombreProfesional" => $resultado["PROFESIONAL_NOMBRE"],
                    "OtroDiagnostico"   => "OTRO DIAGNOSTICO",
                    "Pertinencia"   => 1,                                             // 1=SI, 2=NO
                    "Prevision"     => 1,                                             // 1=FONASA, 2=ISAPRE, 3=PARTICULAR
                    "Sexo"          => $resultado["PACIENTE_SEXO"],                     // Segun DECRETO 820
            //      "TelefonoFijo" => "572767207",                                      //  OPCIONAL
                    "TelefonoMovil" => $resultado["PACIENTE_TELEFONO"],                 //  OPCIONAL
            //      "DescMotivoNoPertinencia" => "TEXTO DE PRUEBA MOTIVO DE NO PERTINENCIA",                // OPCIONAL
            //      "CodSubEspecialidadInterna" => $this->idsoftwareinforma,            //  OPCIONAL
                "TipoContraReferencia" => 2,   //  OPCIONAL *IMPORTANTE 1=APS con atencion, 2=SECU, 3=APS, 4=SECU, 5=SECU, 6 TELEMEDICINA, 7=8=TELEMEDICINA
            //      "Proveedor" => $this->idsoftwareinforma,                            //  OPCIONAL
                "PrioridadDestino" => 1,                     //  OPCIONAL * IMPORTANTE 0=N/A, 1=ALTA, 2=MEDIA, 3=baja     
            //      "TratamientoRealizado" => $this->idsoftwareinforma,                 // OPCIONAL Texto indicando tratamiento realizado el paciente
                ]
            ]; 

            $response = $this->service->ResponderDerivacion($params);

            //dd($this->service->__getLastRequest());
            $nom_descripcion = $response->ResponderDerivacionResult->Descripcion;
            $ind_condicion = $response->ResponderDerivacionResult->Status;
            $ind_estado = 'V';
            if ( $response->ResponderDerivacionResult->Status == 0 ){
                $ind_estado = 'E';
            }    
 
            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);			
            ociexecute($stmt);
            //Log::info($io_Error);
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }


    /* METODO 9 NotificarAltaDePaciente */
    public function altaDePaciente($num_correl){    
        try {
            $sql = "begin HETG_SIC.pau_eventoAltaPaciente(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            $evento_codigo = $resultado["EVENTO_CODIGO"];
            $sic_correlativo = $resultado["SIC_CORRELATIVO"];
            $evento_fecha = $resultado["EVENTO_FECHA"];
            $evento_observacion = $resultado["EVENTO_OBSERVACION"];
            $diagnosis = array("Diagnostico" => array()); 
            $diagnosis["Diagnostico"][] =  array(
                "CodigoDiagnostico"             => $resultado["DIAGNOSTICO_CODIGO"],
                "SistemaCodificacionDiagnostica" => 'CIE-10',
                "DescripcionDiagnostica"        => $resultado["DIAGNOSTICO_NOMBRE"],                
                //"CodigoAlternativoDiagnostico" => $resultado["DIAGNOSTICO_CODIGO"],       // $resultado["DIAGNOSTICO2_CODIGO"],
                //"SistemaCodificacionAlternativo" => 'CIE-10',
                //"DescripcionDiagnosticoAlternativo" =>  $resultado["DIAGNOSTICO_NOMBRE"], // $resultado["DIAGNOSTICO2_NOMBRE"],
                //"FundamentoDiagnostico" => $fundamento,                                   // OPCIONAL STRING
                "TipoDiagnostico"               => '0',     // 0=NINGUNO, 1=CONFIRMADO, 2=SOSPECHA
            );

            while($resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS + OCI_RETURN_LOBS)){
                $diagnosis["Diagnostico"][] =  array(
                    "CodigoDiagnostico" => $resultado["DIAGNOSTICO_CODIGO"],
                    "SistemaCodificacionDiagnostica" => "CIE-10",
                    "DescripcionDiagnostica" => $resultado["DIAGNOSTICO_NOMBRE"], 
                    "TipoDiagnostico" => '0',                       // 0=NINGUNO, 1=CONFIRMADO, 2=SOSPECHA
                );
            }

            self::setWsdl($this->url_produccion);
            $this->service = InstanceSoapClient::init();
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"       => $sic_correlativo,                               //  $resultado["SIC_NUMERO"],   
                    "FechaYHoraAlta"    => $evento_fecha,
                    "Diagnosticos"      => $diagnosis,
                    "DestinoAlta"       => '0',                             // OPCIONAL 0=N/A, 1=DOMICILIO
                    "Observacion"       =>  $evento_observacion,                           // OPCIONAL
                    "MotivoAlta"        => '1',                  // 1=FUGA, 2=URGENCIA, 3=ESPECIALIDAD, 5=HOSPITALARIA, 6=VIGILADA, 7=VALUNTARIA, 8=OTRO, 9=PROTESIS, 10=NO PERTINENCIA
                //  "DetalleAlta"       => 'DETALLES TEXTO LIBRE',                                      // OPCIONAL
                //  "TratamientoRealizado"  => 'TRATAMIENTO DESCRIPCION',                               // OPCIONAL
                ]
            ]; 

            //dd($params );
            $response = $this->service->NotificarAltaDePaciente($params);
            $nom_descripcion = $response->NotificarAltaDePacienteResult->Descripcion;
            $ind_condicion = $response->NotificarAltaDePacienteResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarAltaDePacienteResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $evento_codigo);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);									
            ociexecute($stmt);
            //Log::info($io_Error);
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /* METODO 10 NotificarCitaCancelada  */
    public function citaCancelada($num_correl){    
        try {
            $sql = "begin HETG_SIC.pau_eventoCitaCancelada(:plsCorrel, :io_Cursor); end;";	
            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);
            oci_bind_by_name($stmt,":plsCorrel", $num_correl);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);
            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            self::setWsdl($this->url_desarrollo);
            $this->service = InstanceSoapClient::init();            
            $params = [
                'parametro' => [
                    "TipoMensaje"               => $this->tipoMensaje,
                    "FechaHoraMensaje"          => date("Ymd H:i"),
                    "IdSoftwareInforma"         => $this->idSoftwareInforma,
                    "VersionSoftwareInforma"    => $this->versionSoftwareInforma,
                    "IdSitioInforma"            => $this->idSitioInforma,
                    "CodigoEstablecimientoInforma" => $this->codigoEstablecimientoInforma,
                    "IdSolicitud"           => $resultado["SIC_CORRELATIVO"],                       // $resultado["SIC_NUMERO"],  
                    "IdCita"                => $resultado["CITA_CODIGO"],    
                    "FechaYHoraEvento"		=> $resultado["EVENTO_FECHA"], 
                    "MotivoCancelacionCita" => '',       //  $sic->nom_motivocancelacion
                    "Observacion"           => $resultado["EVENTO_OBSERVACION"],                    // OPCIONAL
                ]
            ]; 

            $response = $this->service->NotificarCitaCancelada($params);

       //     dd($this->service->__getLastRequest());
            $nom_descripcion = $response->NotificarCitaCanceladaResult->Descripcion;
            $ind_condicion = $response->NotificarCitaCanceladaResult->Status;
            $ind_estado = 'V';
            if ( $response->NotificarCitaCanceladaResult->Status == 0 ){
                $ind_estado = 'E';
            }    

            $sql = "begin HETG_SIC.pau_actualizarEvento(:plsCorrel, :plsDescripcion, :plsCondicion, :plsEstado, :io_Error); end;";	
            $stmt = oci_parse($this->conn, $sql);	
			oci_bind_by_name($stmt,":plsCorrel", $resultado["EVENTO_CODIGO"]);
			oci_bind_by_name($stmt,":plsDescripcion", $nom_descripcion);
			oci_bind_by_name($stmt,":plsCondicion", $ind_condicion);
			oci_bind_by_name($stmt,":plsEstado", $ind_estado);
			oci_bind_by_name($stmt,":io_Error", $io_Error, 10);									
            ociexecute($stmt);
            //Log::info($io_Error);
        }
        catch(\Exception $e) {
            Log::info($e->getMessage());
        }
    }

}