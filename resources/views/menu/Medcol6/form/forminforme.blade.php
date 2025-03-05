
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                  <div class="col-sm-6">
                @if(Auth::user()->drogueria == '1')
                <h1 class="m-0">Pendientes Medcol All</h1>
                @elseif(Auth::user()->drogueria == '4')
                <h1 class="m-0">Pendientes Medcol PCE</h1>
                @elseif(Auth::user()->drogueria == '5')
                <h1 class="m-0">Pendientes Medcol Huerfanas</h1>
                @elseif(Auth::user()->drogueria == '6')
                <h1 class="m-0">Pendientes Medcol BIOLOGICOS</h1>
                @elseif(Auth::user()->drogueria == '2')
                <h1 class="m-0">Pendientes Medcol COMFENALCO SALUD MENTAL</h1>
                @elseif(Auth::user()->drogueria == '3')
                <h1 class="m-0">Pendientes Medcol COMFENALCO DOLOR Y PALIATIVOS</h1>
                @elseif(Auth::user()->drogueria == '8')
                <h1 class="m-0">Pendientes Medcol SOS PAC AUTOPISTA</h1>
                @elseif(Auth::user()->drogueria == '12')
                <h1 class="m-0">Pendientes Medcol SOS EVENTO</h1>
                @elseif(Auth::user()->drogueria == '13')
                <h1 class="m-0">Pendientes Medcol JAMUNDI COMFENALCO</h1>
                @endif
            </div>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Pendientes v1</li>
                    </ol>
                </div>
            </div>
            @csrf
            

        </div>
    </div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-2 col-3" id="detalle">
                    </div>
                    <div class="col-lg-2 col-3" id="detalle1">
                    </div>
                    <div class="col-lg-2 col-3" id="detalle2">
                    </div>
                    <div class="col-lg-2 col-3" id="detalle3">
                    </div>
                    
                    <div class="col-lg-2 col-3" id="detalle5">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



