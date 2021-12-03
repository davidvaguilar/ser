<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\IntegracionSer;
use App\so_tsic_ser;
use App\gg_tespera;
use App\Oracle;

class HomeController extends Controller
{
    private $objConexion;
    
    private $objSer;
    private $conn;
    
    public function __construct()
    {
		  //solo se usa local
      $this->objSer = new IntegracionSer();
      //$this->objEspera = new gg_tespera();
	    $this->objConexion = new Oracle();
		  $this->conn = $this->objConexion->getInstancia(config('app.DB_USERNAME'),config('app.DB_PASSWORD'));        	
	  }

    public function index(Request $request)
    {     
      $login = $this->objSer->login();
        $inicio = array('22/07/2021', '25/07/2021', '01/08/2021', '08/08/2021', '15/08/2021', '22/08/2021');    // OK
        $fin = array('24/07/2021', '31/07/2021', '07/08/2021', '14/08/2021',  '21/08/2021', '28/08/2021');    // OK

        $inicio = array('22/08/2021', '29/08/2021', '05/09/2021', '12/09/2021', '19/09/2021', '26/09/2021');    // OK
        $fin = array('28/08/2021', '04/09/2021', '11/09/2021', '18/09/2021', '25/09/2021', '02/10/2021');     // OK

        $inicio = array('03/10/2021', '10/10/2021', '17/10/2021', '24/10/2021', '31/10/2021', '07/11/2021');  // OK
        $fin = array('09/10/2021', '16/10/2021', '23/10/2021', '30/10/2021', '06/11/2021', '13/11/2021');     // OK

        $inicio = array('14/11/2021', '21/11/2021', '28/11/2021', '05/12/2021', '12/12/2021', '19/12/2021', '26/12/2021');     // OK
        $fin = array('20/11/2021', '27/11/2021', '04/12/2021', '11/12/2021', '18/12/2021', '25/12/2021', '01/01/2022');  // OK

      // $inicio = array('02/01/2022');
      // $fin = array('08/01/2022');

      // $inicio = array('22/07/2021', '25/07/2021' );   // PRUEBA
      // $fin = array('24/07/2021', '31/07/2021' );     //PRUEBA  

      //$inicio = array('25/07/2021');   // PRUEBA
      //$fin = array('31/07/2021');     //PRUEBA  

     
      for ($a = 0; $a < count($inicio); $a++) { 
          $ser_integracion = $this->objSer->getPacienteFecha( $login['token'], $inicio[$a], $fin[$a]);
          try{
            $this->conn = $this->objConexion->getInstancia(config('app.DB_USERNAME'),config('app.DB_PASSWORD'));
            $contador_ciclos = 0;
            $contador_insertados = 0;
            $contador_nada = 0;
            $contador_finalizados = 0;
            $contador_errores = 0;
            for( $i=0 ; $i < count($ser_integracion) ; $i++ ) { 
              // dd($ser_integracion[$i]['pacienteEpisodios'][0]['idEspecialidad']);
              
              for( $j=0 ; $j < count($ser_integracion[$i]['pacienteEpisodios']) ; $j++ ) { 

                $id_paciente = NULL;
                  
                $nom_nombre = NULL;
                $nom_apellidos = NULL;
                $num_nficha = NULL;
                $cod_rutpac = NULL;
                $fec_ingreso = NULL;
                $ind_prevision = NULL;
                $ind_hospital = NULL;
                $ind_consultorio = NULL;

                $id_episodio =  NULL;
                $cod_especialidad =  NULL;
                $cod_cie10_inicial =  NULL;
                $cod_cie10_categoria = NULL;
                $fec_sic = NULL;
                $num_sic = NULL;
                $ind_auge = NULL;
                $ind_prioridad = NULL;
                $fec_ingreso_episodio = NULL;
                $ind_activo = NULL;
                $nom_historia_actual = NULL;
                $nom_examen_relevante = NULL;
                $nom_medicamento_actual = NULL;
                $nom_examen_fisico = NULL;
                $nom_examen_previo = NULL;
              
                $id_episodio_cierre = NULL;
                $nom_plan = NULL;
                $nom_cierre = NULL;
                $cod_cie10_cierre = NULL;
                $fec_egreso_episodio = NULL;

                try {
                  $id_paciente = $ser_integracion[$i]['idPaciente'];
                    
                  $nom_nombre = $ser_integracion[$i]['nombres'];
                  $nom_apellidos = $ser_integracion[$i]['apellidos'];
                  $num_nficha = $ser_integracion[$i]['numeroFicha'];
                  $cod_rutpac = $ser_integracion[$i]['rut'];
                  $fec_ingreso = $ser_integracion[$i]['fechaIngreso'];
                  $ind_prevision = $ser_integracion[$i]['idPrevision'];
                  $ind_hospital = $ser_integracion[$i]['idHospital'];
                  $ind_consultorio = $ser_integracion[$i]['idConsultorio'];

                  $id_episodio = $ser_integracion[$i]['pacienteEpisodios'][$j]['idPacienteEpisodio'];     //    "idPaciente": 1537
                  $cod_especialidad = $ser_integracion[$i]['pacienteEpisodios'][$j]['idEspecialidad'];     //    "nombres": "ELIZABETH "
                  $cod_cie10_inicial = $ser_integracion[$i]['pacienteEpisodios'][$j]['idCie10DiagnosticoInicial'];     //      "apellidos": "VILLABLANCA GARCIA",
                  $cod_cie10_categoria = $ser_integracion[$i]['pacienteEpisodios'][$j]['idCie10CategoriaDiagnosticoInicial'];     //  "numeroFicha": 0,
                  $fec_sic = $ser_integracion[$i]['pacienteEpisodios'][$j]['fechaSolicitudInterconsulta'];
                  $num_sic = $ser_integracion[$i]['pacienteEpisodios'][$j]['codigoSic'];
                  $ind_auge = $ser_integracion[$i]['pacienteEpisodios'][$j]['idAuge'];
                  $ind_prioridad = $ser_integracion[$i]['pacienteEpisodios'][$j]['idPrioridad'];
                  $fec_ingreso_episodio = $ser_integracion[$i]['pacienteEpisodios'][$j]['fechaIngreso'];
                  $ind_activo = $ser_integracion[$i]['pacienteEpisodios'][$j]['activo'];  
                  $nom_historia_actual = $ser_integracion[$i]['pacienteEpisodios'][$j]['historiaActual'];
                  $nom_examen_relevante = $ser_integracion[$i]['pacienteEpisodios'][$j]['examenesRelevantes'];
                  $nom_medicamento_actual = $ser_integracion[$i]['pacienteEpisodios'][$j]['medicamentosActuales'];
                  $nom_examen_fisico = $ser_integracion[$i]['pacienteEpisodios'][$j]['examenesFisicos'];
                  $nom_examen_previo = $ser_integracion[$i]['pacienteEpisodios'][$j]['examenPrevio'];

                  if( count($ser_integracion[$i]['pacienteEpisodios'][$j]['pacienteEpisodioCierres']) ){
                    $id_episodio_cierre = $ser_integracion[$i]['pacienteEpisodios'][$j]['pacienteEpisodioCierres'][0]['idPacienteEpisodioCierre'];    
                    $nom_plan = $ser_integracion[$i]['pacienteEpisodios'][$j]['pacienteEpisodioCierres'][0]['planEpisodio'];
                    $nom_cierre = $ser_integracion[$i]['pacienteEpisodios'][$j]['pacienteEpisodioCierres'][0]['cierreEpisodio'];
                    $cod_cie10_cierre = $ser_integracion[$i]['pacienteEpisodios'][$j]['pacienteEpisodioCierres'][0]['idCie10DiagnosticoCierre'];
                    $fec_egreso_episodio = $ser_integracion[$i]['pacienteEpisodios'][$j]['pacienteEpisodioCierres'][0]['fechaIngreso'];
                  }
                
                  $sql = "begin HETG_OPERATIVOSER.pau_mantenerAutomatico(:plsPacienteId, :plsPacienteNombre, :plsPacienteApellido,
                                                                  :plsPacienteFicha, :plsPacienteRun, :plsIngresoFecha,
                                                                  :plsPrevisionIndicador, :plsHospitalIndicador, :plsConsultorioCodigo,
                                                                  :plsEpisodioId, :plsEspecialidadCodigo, :plsDiagnosticoCodigo,
                                                                  :plsDiagnosticoCategoria, :plsInterconsultaFecha, :plsInterconsultaNumero,
                                                                  :plsAugeIndicador, :plsPrioridadIndicador, :plsEpisodioFecha,
                                                                  :plsActivoIndicador, :plsHistoriaActual, :plsExamenActual,
                                                                  :plsMedicamentoActual, :plsExamenFisico, :plsExamenPrevio,
                                                                  :plsCierreId, :plsCierrePlan, :plsCierreNombre,
                                                                  :plsCierreDiagnostico, :plsCierreFecha, :io_Error); end;";	
      
                  $stmt = oci_parse($this->conn, $sql);
                  oci_bind_by_name($stmt,":plsPacienteId", $id_paciente);
                  oci_bind_by_name($stmt,":plsPacienteNombre", $nom_nombre);
                  oci_bind_by_name($stmt,":plsPacienteApellido", $nom_apellidos);
                  oci_bind_by_name($stmt,":plsPacienteFicha", $num_nficha);
                  oci_bind_by_name($stmt,":plsPacienteRun", $cod_rutpac);
                  oci_bind_by_name($stmt,":plsIngresoFecha", $fec_ingreso);
                  oci_bind_by_name($stmt,":plsPrevisionIndicador", $ind_prevision);
                  oci_bind_by_name($stmt,":plsHospitalIndicador", $ind_hospital);
                  oci_bind_by_name($stmt,":plsConsultorioCodigo", $ind_consultorio);
                  oci_bind_by_name($stmt,":plsEpisodioId", $id_episodio);
                  oci_bind_by_name($stmt,":plsEspecialidadCodigo", $cod_especialidad);
                  oci_bind_by_name($stmt,":plsDiagnosticoCodigo", $cod_cie10_inicial);
                  oci_bind_by_name($stmt,":plsDiagnosticoCategoria", $cod_cie10_categoria);
                  oci_bind_by_name($stmt,":plsInterconsultaFecha", $fec_sic);
                  oci_bind_by_name($stmt,":plsInterconsultaNumero", $num_sic);
                  oci_bind_by_name($stmt,":plsAugeIndicador", $ind_auge);
                  oci_bind_by_name($stmt,":plsPrioridadIndicador", $ind_prioridad);
                  oci_bind_by_name($stmt,":plsEpisodioFecha", $fec_ingreso_episodio);
                  oci_bind_by_name($stmt,":plsActivoIndicador", $ind_activo);
                  oci_bind_by_name($stmt,":plsHistoriaActual", $nom_historia_actual);
                  oci_bind_by_name($stmt,":plsExamenActual", $nom_examen_relevante);
                  oci_bind_by_name($stmt,":plsMedicamentoActual", $nom_medicamento_actual);
                  oci_bind_by_name($stmt,":plsExamenFisico", $nom_examen_fisico);
                  oci_bind_by_name($stmt,":plsExamenPrevio", $nom_examen_previo);
                  oci_bind_by_name($stmt,":plsCierreId", $id_episodio_cierre);
                  oci_bind_by_name($stmt,":plsCierrePlan", $nom_plan);
                  oci_bind_by_name($stmt,":plsCierreNombre", $nom_cierre);
                  oci_bind_by_name($stmt,":plsCierreDiagnostico", $cod_cie10_cierre);
                  oci_bind_by_name($stmt,":plsCierreFecha", $fec_egreso_episodio);
                  oci_bind_by_name($stmt, ":io_Error", $io_Error, 200);
                  ociexecute($stmt);

                  $contador_ciclos  = $contador_ciclos + 1;
                  switch ($io_Error) {
                    case 'insert':
                        $contador_insertados = $contador_insertados + 1;
                      break;
                    case 'nothing':
                        $contador_nada = $contador_nada + 1;
                      break;
                    case 'finished':
                        $contador_finalizados = $contador_finalizados + 1;
                      break;
                    default:
                        $contador_errores = $contador_errores + 1;
                      break;
                  }
                  
                }
                catch(\Exception $e) {
                    Log::info($e->getMessage());           // Log::info($io_Error);
                }
              }  
            }
            if( $contador_ciclos > 0){
              echo  "<br><br>Fecha Carga : desde ".$inicio[$a]." - hasta ".$fin[$a].
                  "<br>Total de Pacientes ".$contador_ciclos.
                  "<br>Episodios solo ingresados ".$contador_insertados.
                  "<br>Episodios no registrados ".$contador_nada.
                  "<br>Episodios con egreso ".$contador_finalizados.
                  "<br>Episodios con errores ".$contador_errores;

              Log::info( "<br><br>Fecha Carga : desde ".$inicio[$a]." - hasta ".$fin[$a].
                  "<br>Total de Pacientes ".$contador_ciclos.
                  "<br>Episodios solo ingresados ".$contador_insertados.
                  "<br>Episodios no registrados ".$contador_nada.
                  "<br>Episodios con egreso ".$contador_finalizados.
                  "<br>Episodios con errores ".$contador_errores);
            }
          } catch(\Exception $e){
            Log::info($e->getMessage());
              
          }
      }
      echo "<br><br><br>Total de Ciclos: ".$a."<br>No se consideran ciclos que no tengan registros";
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
