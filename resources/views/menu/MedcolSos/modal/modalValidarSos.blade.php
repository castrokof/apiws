<div class="row">
    <div class="col-lg-12">
        @include('includes.form-error')
        @include('includes.form-mensaje')
        <span id="form_result"></span>
        <div id="card-drawel" class="card card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Validar Derechos SOS</h3>
                <div class="ml-auto d-flex">
                    
                </div>
            </div>
            <form id="form-general" class="form-horizontal" method="POST">
                @csrf
                <div class="card-body">
                    @include('menu.MedcolSos.tabs.tabsValidarSos')
                </div>
                <button type="button" class="btn-flotante tooltipsC" id="guardar_entrada" title="Guardar ordenes"><i class="fas fa-save fa-2x"></i></button>
            </form>
        </div>
    </div>
</div>

