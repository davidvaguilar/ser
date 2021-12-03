<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\so_tsic_eventos;
use App\so_tsic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Oracle;


class SicController extends Controller
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
            $this->conn = $this->objConexion->getInstancia(config('app.DB_USERNAME'),config('app.DB_PASSWORD'));
            #print_r($this->conn);exit;
            //$sql = "SELECT * FROM so_tsic_eventos WHERE IND_ESTADO ='V'";
            $sql = "begin HETG_SIC.pau_listarEvento(:io_Cursor); end;";	

            $curs = oci_new_cursor($this->conn);
            $stmt = oci_parse($this->conn, $sql);

            #oci_bind_by_name($stmt,":plsEjem1",$usuario);
            oci_bind_by_name($stmt, ":io_Cursor", $curs, -1, OCI_B_CURSOR);

            ociexecute($stmt);
            ociexecute($curs);
            $resultado = oci_fetch_array($curs, OCI_ASSOC + OCI_RETURN_NULLS);

            #print_r($row);exit;            
            //$this->objConexion->desconectar(); 
        }catch(\Exception $e){
            Log::info($e->getMessage());
            
        }
        #$sic = so_tsic_eventos::where('ind_estado', 'V')->orderBy('fec_evento')->get();
        #print_r($resultado);
        #exit;
        /*$sic_integracion = so_tsic_eventos::where('IND_ESTADO', 'V')
                    ->orderBy('fec_usrcrea', 'asc')->get();

        return view('sic.integracion', compact('sic_integracion'));  */

        $sic_integracion = so_tsic_eventos::where('IND_ESTADO', 'V')
                    ->orderBy('fec_usrcrea', 'asc')->get(['nom_evento','num_sic', 'nom_descripcion', 'ind_condicion', 'cod_rutpac', 'cod_digver']);       
        $fecha_actual = \DB::select("select SYSDATE from dual");
        //dd($fecha_actual[0]->sysdate);
        $fecha = Carbon::createFromFormat('Y-m-d H:i:s', $fecha_actual[0]->sysdate);        

        return [
            'fecha_actual' => $fecha->format('d/m/Y H:i'),
            'sic' => $sic_integracion
        ];
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    /*public function show($id)
    {
        $sic = so_tsic::where('cod_rutpac', $id)
                ->with([
                    'sic_eventos' => function($query){
                        $query->select('num_sic', 'num_evento', 'nom_evento');
                    }
                ])
                ->orderBy('fec_solic', 'desc')
                ->get(['num_sic', 'fec_solic']);
        
        return [
            'sic' => $sic
        ];
    }*/


    public function show(Request $request)
    {
        $rut = $request->get('rut');
        $sic = so_tsic::where('cod_rutpac', $rut)
                ->with([
                    'sic_eventos' => function($query){
                        $query->select('num_sic', 'num_evento', 'nom_evento');
                    }
                ])
                ->orderBy('fec_solic', 'desc')
                ->get(['num_sic', 'fec_solic']);

        return view('sic.integracion')->with(compact('sic'));
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
