<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
    <div class="card card-info">
        <div class="card-header with-border">
          <h3 class="card-title">Lista de pendientes</h3>
          <div class="card-tools pull-right">
            {{-- <button type="button" class="btn create_evolution btn-default" name="create_evolution" id="create_evolution" ><i class="fa fa-fw fa-plus-circle"></i>Nueva evolución</button> --}}
           </div>
        </div>
      <div class="card-body table-responsive p-2">

      <table id="pendientes" class="table table-hover  text-nowrap">
        {{-- class="table table-hover table-bordered text-nowrap" --}}
        <thead>
        <tr>
              <th>Acciones</th>
              <th>Id</th>
              <th>Primer apellido</th>
              <th>Segundo apellido</th>
              <th>Primer nombre</th>
              <th>Segundo nombre</th>
              <th>Tipo Documento</th>
              <th>Documento</th>
              <th>Fecha de nacimiento</th>
              <th>Municipio</th>
              <th>Otro</th>
              <th>Direccion</th>
              <th>Celular</th>
              <th>Telefono</th>
              <th>Correo</th>
              <th>Sexo</th>
              <th>Eapb</th>
              <th>Motivo de consulta</th>
              <th>Consulta</th>
              <th>Diagnostico Psiqui</th>
              <th>Fecha de creacion</th>


        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </form>
    <!-- /.card-body -->
</div>
</div>
</div>
