 <div class="content-wrapper col-mb-12" style="min-height: 543px;" >
                <!-- Content Header (Page header) -->
            <div class="row">
                <div class="content-header">
                  <div class="container-fluid">
                    <div class="row mb-12">
                      <div class="col-sm-12">
                        <h1 class="m-0 text-dark">Informes </h1>
                      </div><!-- /.col -->

                      @csrf
                      <div class="card-body">
                      <div class="row col-lg-12">

                        <div class="form-group row col-lg-12">
                            <div class="col-md-6">
                                   <label for="fechaini" class="col-xs-2 control-label requerido">Fecha de
                                    Informes</label>
                                    <div class="form-group row">
                                    <input type="date" name="fechaini" id="fechaini" class="form-control col-md-6"
                                        value="">
                                    <input type="date" name="fechafin" id="fechafin" class="form-control col-md-6"
                                        value="">
                                    </div>
                            </div>

                            <div class="col-md-6">
                                <label>&nbsp;</label>
                                <div class="form-group row">
                                    <button type="submit" name="reset" id="reset"  class="btn btn-default btn-xl col-md-6">Limpiar</button>
                                    <button type="submit" name="buscar" id="buscar" class="btn btn-success btn-xl col-md-6">Buscar</button>
                                </div>
                        </div>

                        </div>


                      </tr>
                      </td>
                      </div>
                    </div><!-- /.row -->
                  </div><!-- /.container-fluid -->
                </div>
              </div>
            </div>
                <!-- /.content-header -->

                <!-- Main content -->
            <section class="content">



                  <div class="row">
                    <div class="col-12">
                      <div class="card shadow-lg p-3 mb-5 card-success card-tabs">
                        <div class="card-header p-0 pt-1">
                          <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                              <a class="nav-link active"
                              id="custom-tabs-one-datos-del-pago-tab"
                              data-toggle="pill"
                              href="#custom-tabs-one-datos-del-pago"
                              role="tab"
                              aria-controls="custom-tabs-one-datos-del-pago"
                              aria-selected="false">Lista de Evoluciones</a>
                            </li>
                          </ul>
                        </div>


                          <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-one-datos-del-pago" role="tabpanel" aria-labelledby="custom-tabs-one-datos-del-pago-tab">


                                  @csrf
                                  @include('lineaPsicologica.tablas.tablaIndexInforme')

                            </div>
                           </div>

                        <!-- /.card -->
                      </div>
                    </div>


                  </div>


            </section>
                <!-- /.content -->

            </div>
