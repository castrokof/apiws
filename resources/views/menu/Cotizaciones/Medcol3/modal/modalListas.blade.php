<div class="modal fade" tabindex="-1" id="modal-listas" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog  modal-xl" role="document">
        <div class="modal-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-primary">
                        <div class="card-header with-border">
                            <h3 class="card-title">Listas</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" data-dismiss="modal">
                                    <i class="fas fa-times"></i>
                                </button>


                            </div>
                        </div>
                        <div class="card-body">
                            <form action="" id="Form" name="Form"class="form-horizontal" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="loader"> <img src="{{ asset("assets/$theme/dist/img/loader6.gif") }}"
                                        class="" /> </div>

                                <div class="card-body">
                                   @include('menu.Cotizaciones.Medcol3.form.form')
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">

                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        @include('includes.boton-form-crear-empresa-empleado-usuario')

                                    </div>
                                </div>
                                <!-- /.card-footer -->
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
