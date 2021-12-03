<div class="modal fade" id="modal-sigte-ingreso">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header" style="color:#fff; background-color: #00c0ef;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Importar Sigte INGRESO</h4>
        </div>
        <form method="POST" action="{{ route('admin.sigte.importar-ingreso') }}" class="form-horizontal" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="modal-body">
  
            <div class="form-group">
              <label for="file" class="col-xs-3 control-label">Excel de INGRESO</label>
              <div class="col-xs-9">
                <input name="file" 
                    type="file"
                    class="form-control"
                    accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
              </div>
            </div>
    
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Subir INGRESO</button>
          </div>
        </form>
  
      </div>
    </div>
  </div>