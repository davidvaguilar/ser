<?php

use Illuminate\Database\Seeder;
use App\so_tsic_eventos;
use Carbon\Carbon;

class SicEventosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
/*
        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarSolicitudAceptada";
        $sic_eventos->num_evento = '1';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarSolicitudAceptada";
        $sic_eventos->num_evento = '1';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarSolicitudEgresada";
        $sic_eventos->num_evento = '2';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8917;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->nom_causal = '2';
        $sic_eventos->nom_observacion = "nueva observacion 1";
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarSolicitudEgresada";
        $sic_eventos->num_evento = '2';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10689;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->nom_causal = '2';
        $sic_eventos->nom_observacion = "nueva observacion 2";
        $sic_eventos->save();

*/

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarSolicitudObservada";
        $sic_eventos->num_evento = '3';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->nom_observacion = "observaciones";
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarSolicitudObservada";
        $sic_eventos->num_evento = '3';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->nom_observacion = "nueva observaciones";
        $sic_eventos->save();


/*        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarCitaAgendada";
        $sic_eventos->num_evento = '4';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->cod_promed = 'BFT';
        $sic_eventos->cod_especi = 'PUI';
        $sic_eventos->num_cita = '832537';
        $sic_eventos->nom_observacion = "observaciones";
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarCitaAgendada";
        $sic_eventos->num_evento = '4';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->cod_promed = 'BFT';
        $sic_eventos->cod_especi = 'PUI';
        $sic_eventos->num_cita = '832537';
        $sic_eventos->nom_observacion = "nueva observaciones";
        $sic_eventos->save();


        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarLlegadaDePaciente";
        $sic_eventos->num_evento = '5';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '832537';
        //PacienteFueAtendido  NO SE PUEDE DETERMINAR
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarLlegadaDePaciente";
        $sic_eventos->num_evento = '5';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '832537';
        //PacienteFueAtendido  NO SE PUEDE DETERMINAR
        $sic_eventos->save();


        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarAusenciaDePaciente";
        $sic_eventos->num_evento = '6';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '832537';
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarAusenciaDePaciente";
        $sic_eventos->num_evento = '6';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '832537';
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarConfirmacionDiagnostica";
        $sic_eventos->num_evento = '7';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15128068';
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarConfirmacionDiagnostica";
        $sic_eventos->num_evento = '7';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15125250';
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "ResponderDerivacion";
        $sic_eventos->num_evento = '8';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15128068';
        $sic_eventos->cod_promed = 'BFT';
        $sic_eventos->cod_especi = 'PUI';
        $sic_eventos->save();


        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "ResponderDerivacion";
        $sic_eventos->num_evento = '8';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15125250';
        $sic_eventos->cod_promed = 'BFT';
        $sic_eventos->cod_especi = 'PUI';
        $sic_eventos->save();*/

  /*      $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarAltaDePaciente";
        $sic_eventos->num_evento = '9';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15128068';
        $sic_eventos->nom_observacion = "nueva observaciones";
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarAltaDePaciente";
        $sic_eventos->num_evento = '9';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15125250';
        $sic_eventos->nom_observacion = "nueva observaciones";
        $sic_eventos->save();

        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarCitaCancelada";
        $sic_eventos->num_evento = '10';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 8732;
        $sic_eventos->cod_rutpac = "14453883";
        $sic_eventos->cod_digver = '8';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15128068';
        $sic_eventos->nom_observacion = "nueva observaciones";
        $sic_eventos->save();


        $sic_eventos = new so_tsic_eventos;
        $sic_eventos->nom_evento = "NotificarCitaCancelada";
        $sic_eventos->num_evento = '10';
        $sic_eventos->fec_evento = Carbon::now();
        $sic_eventos->num_sic = 10244;
        $sic_eventos->cod_rutpac = "5518858";
        $sic_eventos->cod_digver = '0';
        $sic_eventos->ind_estado = "V";
        $sic_eventos->num_cita = '15125250';
        $sic_eventos->nom_observacion = "nueva observaciones";
        $sic_eventos->save();
    */
    }
}
