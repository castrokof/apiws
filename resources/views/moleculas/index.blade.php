@extends("theme.$theme.layout")

@section('titulo') Administrar Moléculas @endsection

@section('styles')
<link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('contenido')
{{-- Header / Card con botón Atrás --}}
<div class="row">
  <div class="col-lg-12">
    <div id="card-drawel" class="card card-info">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">@yield('titulo')</h3>
        <div class="ml-auto d-flex">
          <a href="{{ route('moleculas.import.form') }}" class="btn btn-info mx-1">
            <i class="fa fa-file"></i> Cargar Proveedores
          </a>
          <a href="{{ route('submenu') }}" class="btn btn-danger mx-1">
            <i class="fa fa-arrow-left"></i> Atrás
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container mt-3">
  @include('includes.form-mensaje')
  @include('includes.form-error')

  {{-- Filtros --}}
  <form id="frmFiltros" method="GET" class="mb-3">
    <div class="form-row">
      <div class="col-md-3 mb-2">
        <label class="mb-0">Buscar</label>
        <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="Código o descripción">
      </div>
      <div class="col-md-2 mb-2">
        <label class="mb-0">Código</label>
        <input type="text" name="codigo" class="form-control form-control-sm" value="{{ request('codigo') }}">
      </div>
      <div class="col-md-3 mb-2">
        <label class="mb-0">Descripción</label>
        <input type="text" name="descripcion" class="form-control form-control-sm" value="{{ request('descripcion') }}">
      </div>
      <div class="col-md-2 mb-2">
        <label class="mb-0">Marca</label>
        <input type="text" name="marca" class="form-control form-control-sm" value="{{ request('marca') }}">
      </div>
      <div class="col-md-2 mb-2">
        <label class="mb-0">Presentación</label>
        <input type="text" name="presentacion" class="form-control form-control-sm" value="{{ request('presentacion') }}">
      </div>
  
      <div class="col-md-2 mb-2 d-flex align-items-end">
        <button class="btn btn-info btn-sm mr-2" id="btnBuscar" type="submit">
          <i class="fa fa-search"></i> Buscar
        </button>
        <a href="{{ route('moleculas.index') }}" class="btn btn-secondary btn-sm" id="btnLimpiar">Limpiar</a>
      </div>
    </div>
  </form>

  {{-- Contenedor de tabla y paginación (se actualizan por AJAX) --}}
  <div id="tablaWrap">
    @include('moleculas.partials.table', ['moleculas' => $moleculas])
  </div>
  <div id="paginacionWrap">
    @include('moleculas.partials.pagination', ['moleculas' => $moleculas])
  </div>
</div>
@endsection

@section('scripts')
<script>
(function(){
  const $form = $('#frmFiltros');
  const $tabla = $('#tablaWrap');
  const $pagi  = $('#paginacionWrap');

  // Mapa de alias del front → columnas reales del backend/BD
  const sortMap = {
    codigo_rfast: 'codigo',
    descripcion : 'nombre',
    presentacion: 'forma',
    activo     : 'estado',
    // permitir también los nuevos nombres tal cual:
    codigo     : 'codigo',
    nombre     : 'nombre',
    forma      : 'forma',
    estado     : 'estado',
  };

  function debounce(fn, delay=400){
    let t; return function(){ clearTimeout(t); t=setTimeout(()=>fn.apply(this, arguments), delay); };
  }

  // Construye querystring desde el form (y normaliza claves opcionalmente)
  function buildQuery(){
    // Puedes dejar serialize() si el controlador ya mapea.
    // Si quieres normalizar aquí, toma campos y ajusta claves:
    const params = new URLSearchParams($form.serialize());

    // Normalización opcional de nombres viejos → nuevos (si existen)
    if (params.has('descripcion') && !params.has('nombre')) {
      params.set('nombre', params.get('descripcion'));
    }
    if (params.has('presentacion') && !params.has('forma')) {
      params.set('forma', params.get('presentacion'));
    }
    if (params.has('activo') && !params.has('estado')) {
      params.set('estado', params.get('activo'));
    }

    // Normaliza sort si viene con alias viejo
    const sort = params.get('sort');
    if (sort && sortMap[sort]) {
      params.set('sort', sortMap[sort]);
    }

    return params.toString();
  }

  function cargarAjax(url){
    $.ajax({
      url,
      type: 'GET',
      dataType: 'json',
      beforeSend(){ $tabla.css('opacity','.6'); },
      complete(){  $tabla.css('opacity','1');  },
      success(resp){
        if(resp.table)      $tabla.html(resp.table);
        if(resp.pagination) $pagi.html(resp.pagination);
      },
      error(xhr){
        console.error('Error AJAX:', xhr.status, xhr.responseText);
      }
    });
  }

  // Submit → AJAX + pushState
  $form.on('submit', function(e){
    e.preventDefault();
    const base = "{{ route('moleculas.index') }}";
    const qs = buildQuery();
    const url = qs ? (base + '?' + qs) : base;
    history.pushState({url}, '', url);
    cargarAjax(url);
  });

  // Búsqueda “en vivo” (escucha ambos nombres: viejos y nuevos)
  $form
    .find([
      'input[name="q"]',
      'input[name="codigo"]',
      'input[name="codigo_rfast"]',
      'input[name="descripcion"]',
      'input[name="nombre"]',
      'input[name="marca"]',
      'input[name="presentacion"]',
      'input[name="forma"]'
    ].join(','))
    .on('input', debounce(function(){ $form.trigger('submit'); }, 500));

  // Cambio de combo Activo/Estado (escucha ambos)
  $form.find('select[name="activo"], select[name="estado"]').on('change', function(){
    $form.trigger('submit');
  });

  // Intercepta paginación
  $(document).on('click', '#paginacionWrap .pagination a', function(e){
    e.preventDefault();
    const url = $(this).attr('href');
    history.pushState({url}, '', url);
    cargarAjax(url);
  });

  // Intercepta ordenamiento por columnas (si tienes links con ?sort=&dir=)
  $(document).on('click', '.js-sort', function(e){
    e.preventDefault();
    const href = new URL($(this).attr('href'), window.location.origin);
    // Normaliza la columna sort si viene con alias viejo
    const s = href.searchParams.get('sort');
    if (s && sortMap[s]) href.searchParams.set('sort', sortMap[s]);

    const url = href.toString();
    history.pushState({url}, '', url);
    cargarAjax(url);
  });

  // Back/forward del navegador
  window.addEventListener('popstate', function(e){
    const url = (e.state && e.state.url) ? e.state.url : window.location.href;
    cargarAjax(url);
  });
})();
</script>
@endsection
