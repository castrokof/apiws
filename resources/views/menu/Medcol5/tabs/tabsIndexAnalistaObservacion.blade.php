<div class="row">
    <div class="col-12">
      <div class="card card-primary card-tabs">
<div class="card-header p-0 pt-1">
    <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active"
        id="custom-tabs-one-datos-del-paciente-tab"
        data-toggle="pill"
        href="#custom-tabs-one-datos-del-paciente"
        role="tab"
        aria-controls="custom-tabs-one-datos-del-paciente"
        aria-selected="false">Linea Psicologica</a>
      </li>
      <li class="nav-item">
        <a class="nav-link"
        id="custom-tabs-one-datos-agendados-tab"
        data-toggle="pill"
        href="#custom-tabs-one-datos-agendados"
        role="tab"
        aria-controls="custom-tabs-one-datos-agendados"
        aria-selected="false">Citas agendadas</a>
      </li>
    </ul>
</div>
  <div class="card-body">
   <div class="tab-content" id="custom-tabs-one-tabContent">
      <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-paciente" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-paciente-tab">
        <div class="card-body">
       @include('lineaPsicologica.tablas.tablaIndexPsicologica')
      </div>
      </div>

      <div class="tab-pane fade " id="custom-tabs-one-datos-agendados" role="tabpanel" aria-labelledby="custom-tabs-one-datos-agendados-tab">
        <div class="card-body">
       @include('lineaPsicologica.tablas.tablaIndexPsicologicaAgendado')
      </div>

    </div>
  </div>
  </div>
</div>
</div>
</div>
