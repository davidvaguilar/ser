@extends('layout')

@section('header')
  <h1>Integracion Sic - Rayen
    <div class="btn-group pull-right">
      <a href="{{ route('admin.sic.integracion') }}" class="btn btn bg-primary btn-flat disabled">
        <i class="fa fa-fw fa-plus"></i> Cargar Sic
      </a>      
    </div>
  </h1>   
@stop

@section('content')
  <div class="row">
    <div class="col-xs-12">


      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs nav-justified">
          <li class="active"><a href="#tab_1" data-toggle="tab">Monitor</a></li>
          <li><a href="#tab_2" data-toggle="tab">Busqueda</a></li>       
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab_1">
            
            
            <div class="table-responsive">
              <table class="table table-bordered table-hover text-center">
                <thead>
                  <tr>
                    <th></th>
                    <th>Evento</th>
                    <th>Sic</th>
                    <th>Rut</th>
                    <th>Resultado</th>             
                    <th>Estado</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="table_body-sic_integracion-index"> 
                
                </tbody>
              </table>
            </div>

          </div>
          <div class="tab-pane" id="tab_2">


            <!--<div class="form-group">
              <label for="rut">Rut Paciente:</label>
              <input type="text" class="form-control" id="rut" name="rut">
            </div>
            <div class="form-group">
              <button onclick="listar_sic();"
                    type="button" 
                    class="btn btn-success">Buscar</button>
            </div>-->


            <form class="form-horizontal" method="GET" action="{{ route('admin.sic.show') }}">
              <div class="box-body">
                <div class="form-group">
                  <label for="rut" class="col-sm-2 control-label">Rut Paciente</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="rut" name="rut" placeholder="Rut del Paciente">
                  </div>
                </div>
                <button type="submit" class="btn btn-info pull-right">Buscar</button>
              </div>
              
            </form>
            @if ( isset($sic) )
              <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                  <thead>
                    <tr>
                      <th>Fecha Sol.</th>
                      <th>Sic</th>
                      <th>Envio</th>             
                      <th>Respuesta</th>
                      <th></th>
                    </tr>
                  </thead>
                  
                    @foreach ( $sic as $fila )
                      <tbody id="table_body-sic_estado-index"> 
                      
                        <tr data-toggle="collapse" data-target="#accordion" class="clickable collapse-row collapsed">
                          <td>{{ $fila->sic_eventos }}</td>
                          <td>Some more stuff</td>
                          <td>And some more</td>
                        </tr>
                        <tr>
                          <td colspan="3">
                              <div id="accordion" class="collapse">Detalles</div>
                          </td>
                        </tr>
                        <tr data-toggle="collapse" data-target="#accordion2" class="clickable  collapse-row collapsed">
                          <td>Some Stuff</td>
                          <td>Some more stuff</td>
                          <td>And some more</td>
                        </tr>
                        <tr>
                          <td colspan="3">
                              <div id="accordion2" class="collapse">Hidden default</div>
                          </td>
                        </tr>
                        <tr data-toggle="collapse" data-target="#accordion3" class="clickable collapse-row collapsed">
                          <td>Some Stuff</td>
                          <td>Some more stuff</td>
                          <td>And some more</td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <div id="accordion3" class="collapse">Hidden by default</div>
                            </td>
                        </tr>
                      </tbody>
                    @endforeach
                
                </table>
              </div>
            @endif
          </div>
        </div>
        <!-- /.tab-content -->
      </div>

      
    </div>
  </div>
  

@endsection

@push('styles')    
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <style>

.collapse-row.collapsed + tr {
  display: none;
}
      </style>
@endpush

@push('scripts')

  <script>
    listar_integracion_sic()
    setInterval('listar_integracion_sic()', 300000);   //60000 = 1 min

    function listar_integracion_sic() {
      var url = "{{ route('admin.sic.index') }}";

      axios.get(url)
      .then(function(response){
        var total_registro = response.data.sic.length
        console.log(response.data);
        document.getElementById("table_body-sic_integracion-index").innerHTML = '';
        var table_body = document.getElementById("table_body-sic_integracion-index");

        document.getElementById('spn-hora_actual').innerHTML = response.data.fecha_actual;
        for (var i = 0; i < total_registro; i++) {  
          var row = document.createElement("tr");
        
          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(i+1);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(response.data.sic[i].nom_evento);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(response.data.sic[i].num_sic);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(response.data.sic[i].cod_rutpac+'-'+response.data.sic[i].cod_digver);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(response.data.sic[i].nom_descripcion);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(response.data.sic[i].ind_condicion);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          btn_show = `<button title="Detalles de la Sic" 
                          data-sic="${response.data.sic[i].num_sic}"
                          class="btn btn-success">
                        <i class="fa fa-fw fa-list"></i>
                      </button>`;  
          var celda = document.createElement("td");
          celda.innerHTML = btn_show; 
          row.appendChild(celda);

          table_body.appendChild(row);
        }
       /* var select = document.getElementById('branch_id');
        select.innerHTML = '';
        for (var indice = 0; indice < total_registro; indice++) {  
          var option = document.createElement("OPTION");
          option.setAttribute("value", response.data[indice].id);
          var text = document.createTextNode(response.data[indice].name);
          option.appendChild(text);
          select.appendChild(option);
        }*/
      })
      .catch(function (error){
        console.log(error);
      });
    }


    function listar_sic(){
      var rut = document.getElementById("rut").value;
      var url = window.location+"/"+rut;

      axios.get(url)
      .then(function(response){
        var total_registro = response.data.sic.length
        console.log(response.data);
       /* document.getElementById("table_body-sic_integracion-index").innerHTML = '';
        var table_body = document.getElementById("table_body-sic_integracion-index");

        document.getElementById('spn-hora_actual').innerHTML = response.data.fecha_actual;
        for (var i = 0; i < total_registro; i++) {  
          var row = document.createElement("tr");
        
          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(i+1);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(response.data.sic[i].nom_evento);
          celda.appendChild(textoCelda);
          row.appendChild(celda);

          var celda = document.createElement("td");
          var textoCelda = document.createTextNode(response.data.sic[i].num_sic);
          celda.appendChild(textoCelda);
          row.appendChild(celda);



          table_body.appendChild(row);
        }*/
      })
      .catch(function (error){
        console.log(error);
      });
    }


  </script>
@endpush