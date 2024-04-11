<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dispensado MedCol Dolor</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Pendientes v1</li>
                </ol>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h2 class="m-0 text-dark">Ingrese Rango de Fechas</h2>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="fecha" class="col-form-label">Fecha inicial</label>
                                <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{ old('fechaini') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="fechafin" class="col-form-label">Fecha final</label>
                                <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{ old('fechafin') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="reset" id="reset" class="btn btn-warning btn-block">Limpiar</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" name="buscar" id="buscar" class="btn btn-success btn-block">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-4" id="detalle"></div>
                    <div class="col-md-4" id="detalle1"></div>
                    <div class="col-md-4" id="detalle2"></div>
                </div>
            </div>
        </div>
    </div>
</div>