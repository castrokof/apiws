

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   @if(Auth::user()->drogueria == '4')
                    <h1 class="m-0">Pendientes Medcol PCE</h1>
                   @elseif(Auth::user()->drogueria == '5')  
                   <h1 class="m-0">Pendientes Medcol Huerfanas</h1>
                   @elseif(Auth::user()->drogueria == '6') 
                   <h1 class="m-0">Pendientes Medcol BIOLOGICOS</h1>
                   @endif
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pendientes v1</li>
                    </ol>
                </div>
            </div>
            @csrf
            {{-- <div class="card-body">
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
                                <button type="submit" name="reset" id="reset"
                                    class="btn btn-default btn-xl col-md-6">Limpiar</button>
                                <button type="submit" name="buscar" id="buscar"
                                    class="btn btn-success btn-xl col-md-6">Buscar</button>
                            </div>
                        </div>

                    </div>


                    </tr>
                    </td>
                </div>
            </div> --}}

        </div>
    </div>

<section class="content">
    <div class="container-fluid">

        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-6" id="detalle">
                    </div>
                    <div class="col-lg-3 col-6" id="detalle1">
                    </div>
                    <div class="col-lg-3 col-6" id="detalle2">
                    </div>
                    <div class="col-lg-3 col-6" id="detalle3">
                    </div>
                </div>
            </div>


        </div>



    </div>
</section>

