@extends('layouts.admin')

@section('title', 'Administrar Moléculas')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Administrar Moléculas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
          <li class="breadcrumb-item"><a href="{{ route('compras.medcol3') }}">Órdenes de Compra</a></li>
          <li class="breadcrumb-item active">Moléculas</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    {{-- Botones de acción --}}
    <div class="row mb-3">
      <div class="col-12">
        <div class="btn-group">
          <a href="{{ route('moleculas.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Nueva Molécula
          </a>
          <a href="{{ route('moleculas.import.form') }}" class="btn btn-info">
            <i class="fa fa-file-excel"></i> Cargar Proveedores
          </a>
          <a href="{{ route('compras.medcol3') }}" class="btn btn-danger">
            <i class="fa fa-arrow-left"></i> Atrás
          </a>
        </div>
      </div>
    </div>

  {{-- Filtros --}}
  <div class="card card-secondary collapsed-card mb-3">
    <div class="card-header">
      <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-plus"></i>
        </button>
      </div>
    </div>
    <div class="card-body" style="display: none;">
      <form id="frmFiltros" method="GET">
        <div class="row">
          <div class="col-md-3 mb-2">
            <label class="mb-0 small">Buscar</label>
            <input type="text" name="q" class="form-control form-control-sm" value="{{ request('q') }}" placeholder="Código o descripción">
          </div>
          <div class="col-md-2 mb-2">
            <label class="mb-0 small">Código</label>
            <input type="text" name="codigo" class="form-control form-control-sm" value="{{ request('codigo') }}">
          </div>
          <div class="col-md-3 mb-2">
            <label class="mb-0 small">Descripción</label>
            <input type="text" name="descripcion" class="form-control form-control-sm" value="{{ request('descripcion') }}">
          </div>
          <div class="col-md-2 mb-2">
            <label class="mb-0 small">Marca</label>
            <input type="text" name="marca" class="form-control form-control-sm" value="{{ request('marca') }}">
          </div>
          <div class="col-md-2 mb-2">
            <label class="mb-0 small">Presentación</label>
            <input type="text" name="presentacion" class="form-control form-control-sm" value="{{ request('presentacion') }}">
          </div>

          <div class="col-md-12 mb-2 d-flex align-items-end justify-content-end">
            <button class="btn btn-info btn-sm mr-2" id="btnBuscar" type="submit">
              <i class="fa fa-search"></i> Buscar
            </button>
            <a href="{{ route('moleculas.index') }}" class="btn btn-secondary btn-sm" id="btnLimpiar">
              <i class="fa fa-eraser"></i> Limpiar
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

    {{-- Contenedor de tabla y paginación (se actualizan por AJAX) --}}
    <div id="tablaWrap">
      @include('moleculas.partials.table', ['moleculas' => $moleculas])
    </div>
    <div id="paginacionWrap">
      @include('moleculas.partials.pagination', ['moleculas' => $moleculas])
    </div>
  </div>{{-- /container-fluid --}}
</section>{{-- /.content --}}
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
