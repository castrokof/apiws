 <div class="row">
                    <div class="col-lg-12">
                        @include('includes.form-error')
                        @include('includes.form-mensaje')
                        <span id="form_result"></span>
                        <div id="card-drawel" class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Generar Ordenes</h3>
                                
                            </div>
                            <form id="form-general" class="form-horizontal" method="POST">
                                @csrf
                                <div class="card-body">
                                    @include('menu.Compras.Medcol4.tabs.tabsingresos')
                                </div>
                               
                                <button type="button" class="btn-flotante tooltipsC" id="guardar_entrada" title="Guardar orden"><i class="fas fa-save fa-2x"></i></button>
                            </form>

                        </div>
                    </div>
 </div>
