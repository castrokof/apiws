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
                   @elseif(Auth::user()->drogueria == '8') 
                   <h1 class="m-0">Pendientes Medcol EMCALI</h1>
                   @endif
                
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item active">Pendientes v1</li>
                </ol>
            </div>
        </div>
        @csrf
        @include('menu.Medcol5.tablas.tablaIndexInformemedicamentos')

    </div>
</div>
