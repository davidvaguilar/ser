<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\gg_tsigte_observadas;
use App\so_tsic_eventos;
use App\Oracle;

use Illuminate\Support\Facades\Log;

class SigteController extends Controller
{
    private $objConexion;
    private $conn;

    function __construct(Request $request)
    {
        $this->objConexion = new Oracle();
	}

    public function index()
    {
        
        try{
            $this->conn = $this->objConexion->getInstancia(config('app.DB_USERNAME'), config('app.DB_PASSWORD'));
            #print_r($this->conn);exit;
          //  $sql = "SELECT * FROM so_tsic_eventos WHERE IND_ESTADO ='V'";
            $sql = "begin HETG_SIC.pau_listarEvento(:io_Cursor); end;";	
            
            #$curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn,$sql);

            #oci_bind_by_name($stmt,":plsEjem1",$usuario);
            #oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);

            ociexecute($stmt);
            #ociexecute($curs);
            
            $sic = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS);

            #print_r($row);exit;            
            //$this->objConexion->desconectar(); 
        }catch(\Exception $e){
            Log::info($e->getMessage());
        }

        #$sic = so_tsic_eventos::where('ind_estado', 'V')->orderBy('fec_evento')->get();
        print_r($sic);
        exit;



        $sigte_observadas = gg_tsigte_observadas::where('IND_ESTADO', 'V')
            ->orderBy('fec_observada', 'desc')                                
            ->get(['run', 'dv', 'nombres', 'primer_apellido', 'segundo_apellido',
                    'presta_est', 'tipo_prest', 'f_entrada', 'f_salida', 'c_salida', 'sospecha_diag', 
                    'id_local','resultado', 'sigte_id', 'detalle', 'fec_observada']);

                    
        return view('sigte.observacion', compact('sigte_observadas'));
    }

    public function create()
    {
        /*$sigte_observadas = gg_tsigte_observadas::where('IND_ESTADO', 'V')
            ->orderBy('fec_observada', 'desc')                                
            ->get(['run', 'dv', 'nombres', 'primer_apellido', 'segundo_apellido',
                    'presta_est', 'tipo_prest', 'f_entrada', 'f_salida', 'c_salida', 'sospecha_diag', 
                    'id_local','resultado', 'sigte_id', 'detalle', 'fec_observada']);*/
        
        //$sic = so_tsic_eventos::where('num_evento', '1')->get();
        $sic = so_tsic_eventos::all();
        
        foreach ($sic as $fila){
            //echo "El valor de $clave es: $valor";
            Log::info($fila->fec_evento);
            
        }


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
