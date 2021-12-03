@extends('layout')

@section('header')
  <h1>Sigte con observaciones
    <div class="btn-group  pull-right">
      <button type="button" class="btn btn bg-primary btn-flat" data-toggle="modal" data-target="#modal-sigte-ingreso">
        <i class="fa fa-fw fa-plus"></i> Cargar INGRESOS
      </button>
      <button type="button" class="btn btn-success btn-flat" data-toggle="modal" data-target="#modal-sigte-egreso">
        <i class="fa fa-fw fa-minus"></i> Cargar EGRESOS
      </button>
      <button type="button" class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-sigte-edicion">
        <i class="fa fa-fw fa-pencil"></i> EDITAR SIGTE
      </button>
    </div>
  </h1>   
@stop

@section('content')
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        
        <div class="box-body table-responsive">
          <table id="sigte-observadas-table" class="table table-bordered table-hover text-center">
            <thead>
              <tr>
                <th></th>
                <th>Local</th>
                <th>Sigte</th>
                <th style="min-width:90px">Run</th>
                <th style="min-width:180px">Nombre</th>
                <th>Lista Espera</th>
                <th>Fecha Entrada</th>
                <th>Fecha Salida</th>
                <th>Cod. Motivo</th>
                <th>Tipo</th>
                <th style="min-width:180px">Prestación</th>
                <th style="min-width:250px">Diagnostico</th>
                <th style="min-width:250px">Detalle</th>
                <th style="min-width:250px">Resultado</th>
                <th>Observacion</th>
              </tr>
            </thead>
            <tbody> 
              @foreach($sigte_observadas as $index => $fila)         
              <tr>
                <td>{{ $index+1 }}</td>
                <td>
                  <a href="{{ route('admin.listaespera.show', $fila->id_local) }}" 
                          class="btn btn-default" title="Editar Orden de Trabajo">
                      {{ $fila->id_local }}
                  </a>
                </td>
                <td>{{ $fila->sigte_id }}</td>
                <td>{{ $fila->run }}-{{ $fila->dv }}</td>
                <td>{{ $fila->nombres }} {{ $fila->primer_apellido }} {{ $fila->segundo_apellido }}</td>
                <td>
                  @if ($fila->tipo_prest == 1)
                       {{ 'C. NUEVA' }}
                  @elseif ($fila->tipo_prest == 2)
                     {{ 'C. CONTROL' }}
                  @elseif ($fila->tipo_prest == 3)
                     {{ 'PROCEDIMIENTO' }}
                  @elseif ($fila->tipo_prest == 4)
                     {{ 'QUIRURGICA' }}
                  @else
                     {{ $fila->tipo_prest }}
                  @endif
                </td>          
                <td>{{ $fila->f_entrada }}</td>
                <td>{{ $fila->f_salida }}</td>  
                <td>{{ $fila->c_salida }}</td> 
                <td><button type="button" 
                          title="{{ $fila->f_salida }}"
                          class="btn btn-xs  {{ $fila->c_salida == '' || $fila->c_salida == '3' ? 'btn-warning': 'btn-success' }} ">
                          {{ $fila->c_salida == '' || $fila->c_salida == '3' ? "INGRESO": "EGRESO" }}</button></td>                 
                            
                <td>{{ $fila->presta_est }}</td>     
                <td>{{ $fila->sospecha_diag }}</td>
                <td>{{ $fila->detalle }}</td>
                <td>{{ $fila->resultado }}</td>
                <td>{{ $fila->fec_observada->format('d/m/Y h:i') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
          {{-- $jobs->links() --}}
        </div>
      </div>
    </div>
  </div>
  
  @include('sigte.excel-ingreso')
  @include('sigte.excel-egreso')

@endsection

@push('styles')    
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
 
@endpush

@push('scripts')
    <!-- DataTables -->
    <script src="{{ asset('adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

  <script>

    /*function buscar_listaespera() {
      var url = "{{-- route('admin.branches.index') --}}";
      var enterprise_id = document.getElementById('enterprise_id').value;
   
      axios.get(url, {
        params: {
          'enterprise': enterprise_id,
        }
      }).then(function(response){
        var total_registro = response.data.length
        //console.log(response.data[0].name);
        var select = document.getElementById('branch_id');
        select.innerHTML = '';
        for (var indice = 0; indice < total_registro; indice++) {  
          var option = document.createElement("OPTION");
          option.setAttribute("value", response.data[indice].id);
          var text = document.createTextNode(response.data[indice].name);
          option.appendChild(text);
          select.appendChild(option);
        }
      })
      .catch(function (error){
        console.log(error);
      });
    }*/
  </script>
@endpush