
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                   @if(Auth::user()->drogueria == '1')
                    <h1 class="m-0">Pendientes Medcol SOS - JAMUNDI</h1>
                   @elseif(Auth::user()->drogueria == '4')  
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



