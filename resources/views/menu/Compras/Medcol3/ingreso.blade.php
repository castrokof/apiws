@extends("theme.$theme.layout")

@section('titulo', 'Ordenes de Compras')

{{-- ====== ESTILOS (fuera de contenido) ====== --}}
@section('styles')
<link href="{{ asset("assets/$theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

{{-- ====== CONTENIDO ====== --}}
@section('contenido')

<div class="row">
  <div class="col-lg-12">
    @include('includes.form-error')
    @include('includes.form-mensaje')
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(!empty($avisos))
  <div class="alert alert-warning js-alerta-mapeo">
    <strong>Atención:</strong>
    <ul class="mb-0">
      @foreach($avisos as $a)
        <li>{{ $a }}</li>
      @endforeach
    </ul>
  </div>
@endif

    <div id="card-drawel" class="card card-info">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
          Ingreso para Orden: {{ $infoOrden->orden_de_compra }} ({{ $infoOrden->num_orden_compra }})
        </h3>
        <div class="ml-auto d-flex">
          <a href="{{ route('ordenes.detalle', $infoOrden->num_orden_compra) }}" class="btn btn-danger mx-1">
            <i class="fa fa-arrow-left"></i> Atrás
          </a>
        </div>
      </div>

      <div class="card-body">
        <form method="POST" action="{{ route('ordenes.ingreso.excel', $infoOrden->num_orden_compra) }}" id="formIngreso">
          @csrf

          <div class="table-responsive">
            <table class="table table-sm table-bordered" id="tablaIngreso">
              <thead class="thead-light">
              <tr>
                <th style="width:70px;">Acciones</th>  {{-- NUEVO --}}
                <th>Alterno</th>
                <th>Artículo</th>
                <th>Presentación</th>
                <th>IVA</th>
                <th>Cantidad</th>
                <th>Valor Unitario Con IVA</th>
                <th>Valor Total Con IVA</th>
                <th>Total Base</th>
                <th>Total IVA</th>
                <th>Fecha Vencimiento (DD/MM/YYYY)</th>
                <th>Lote</th>
                <th>Invima</th>
              </tr>
            </thead>

            <tbody>
              @php $totalBaseSum = 0; @endphp

              @foreach($mols as $i => $m)
                @php
                  $cantidad = (float) $m->cantidad;   // delta
                  $iva      = (float) ($m->iva ?? 0);
                  $unit     = (float) ($m->unitario ?? 0);
                  $vt   = round($cantidad * $unit, 2);
                  $base = $iva > 0 ? round($vt / (1 + $iva/100), 2) : $vt;
                  $tiva = round($vt - $base, 2);
                  $totalBaseSum += $base;
                @endphp

                <tr
                  class="ingreso-row"
                  data-group-id="{{ $m->id }}"              {{-- agrupa clones por detalle --}}
                  data-max="{{ $cantidad }}"                {{-- delta total a repartir --}}
                  data-articulo="{{ e($m->nombre) }}"       {{-- para mensajes --}}
                >
                  {{-- HIDDENs para backend --}}
                  <input type="hidden" name="rows[{{ $i }}][detalle_id]" value="{{ $m->id }}">
                  <input type="hidden" name="rows[{{ $i }}][group_id]"   value="{{ $m->id }}">  {{-- por si lo quieres usar --}}
                  <input type="hidden" name="rows[{{ $i }}][max_delta]"  value="{{ $cantidad }}">

                  {{-- Acciones (+ / –) --}}
                  <td>
                    <button type="button" class="btn btn-xs btn-outline-primary btn-dup">+</button>
                    <button type="button" class="btn btn-xs btn-outline-secondary btn-del">−</button>
                  </td>

                  <td>
                    <input type="text" class="form-control form-control-sm"
                          name="rows[{{ $i }}][Alterno]" value="{{ $m->alterno }}" readonly>
                  </td>

                  <td>
                    {{ $m->nombre }}
                    <input type="hidden" name="rows[{{ $i }}][Articulo]" value="{{ $m->nombre }}" >
                  </td>

                  <td>
                    <input type="text" class="form-control form-control-sm"
                          name="rows[{{ $i }}][Presentacion]" value="{{ $m->presentacion }}" readonly>
                  </td>

                  <td>
                    <input type="number" step="0.01" class="form-control form-control-sm iva"
                          name="rows[{{ $i }}][IVA]" value="{{ $iva }}" readonly>
                  </td>

                  <td>
                    <input type="number" step="1" min="0" class="form-control form-control-sm cantidad"
                          name="rows[{{ $i }}][Cantidad]" value="{{ $cantidad }}">
                  </td>

                  <td>
                    <input type="number" step="0.01" class="form-control form-control-sm unitario"
                          name="rows[{{ $i }}][ValorUnitarioConIVA]" value="{{ $unit }}" readonly>
                  </td>

                  <td class="totalConIva">{{ number_format($vt, 2, '.', '') }}</td>
                  <td class="totalBase">{{ number_format($base, 2, '.', '') }}</td>
                  <td class="totalIva">{{ number_format($tiva, 2, '.', '') }}</td>

                  <input type="hidden" name="rows[{{ $i }}][ValorTotalConIVA]" value="{{ $vt }}" class="hTotalConIva">
                  <input type="hidden" name="rows[{{ $i }}][TotalBase]"        value="{{ $base }}" class="hTotalBase">
                  <input type="hidden" name="rows[{{ $i }}][TotalIVA]"         value="{{ $tiva }}" class="hTotalIva">

                  <td>
                    <input type="date" class="form-control form-control-sm vencimiento"
                          name="rows[{{ $i }}][FechaVencimiento]" placeholder="DD/MM/YYYY" require>
                  </td>
                  <td>
                    <input type="text" class="form-control form-control-sm" name="rows[{{ $i }}][Lote]" require>
                  </td>
                  <td>
                    <input type="text" class="form-control form-control-sm" name="rows[{{ $i }}][Invima]" value="{{ $m->invima }}" require>
                  </td>
                </tr>
              @endforeach
            </tbody>
            </table>
            <div class="mt-2 d-flex justify-content-end">
                <div>
                    <strong>Total Base a ingresar: </strong>
                    <span id="totalBaseSum">{{ number_format($totalBaseSum, 2, '.', ',') }}</span>
                </div>
            </div>
          </div>

          <div class="mt-3 d-flex justify-content-end">
            <button type="button" id="btnGenerarExcel" class="btn btn-success">
              <i class="fa fa-file-excel"></i> Generar Excel
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@endsection

{{-- ====== SCRIPTS (usa @section si tu layout NO tiene @stack) ====== --}}
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form  = document.getElementById('formIngreso');
  const table = document.getElementById('tablaIngreso');
  const totalBaseSumEl = document.getElementById('totalBaseSum');
  const btnExcel = document.getElementById('btnGenerarExcel');

  // Lleva el índice para los name="rows[IDX][...]"
  let nextIndex = (function(){
    let max = -1;
    document.querySelectorAll('#tablaIngreso tbody input[name^="rows["]').forEach(inp => {
      const m = inp.name.match(/^rows\[(\d+)\]/);
      if (m) max = Math.max(max, parseInt(m[1],10));
    });
    return max + 1;
  })();
  function hayAlertasDeMapeo() {
    // 1) Si pintas la alerta en pantalla, dale la clase .js-alerta-mapeo
    const alertas = document.querySelectorAll('.js-alerta-mapeo');
    console.log('Alertas de mapeo:', alertas.length);
    if (alertas.length > 0) return true;

    // 2) Asegura que TODOS los Alterno tengan valor (si está vacío = no mapeado)
    const alternos = document.querySelectorAll('input[name^="rows"][name$="[Alterno]"]');
    for (const inp of alternos) {
      if (!inp.value || inp.value.trim() === '') return true;
    }
    return false;
  }

  function aplicarEstadoBoton() {
    const bloquear = hayAlertasDeMapeo();
    btnGenerarExcel.disabled = bloquear;
    btnGenerarExcel.classList.toggle('disabled', bloquear);
    if (bloquear) {
      btnGenerarExcel.title = 'No puedes generar el Excel mientras existan moléculas sin mapeo (Alterno vacío).';
    } else {
      btnGenerarExcel.title = '';
    }
  }

  // Re-evaluar al cargar
  aplicarEstadoBoton();
  
  function round2(n){ return Math.round((Number(n)+Number.EPSILON)*100)/100; }

  function recalcRow(tr){
    const iva  = parseFloat((tr.querySelector('.iva')?.value || '0')) || 0;
    const cant = parseFloat((tr.querySelector('.cantidad')?.value || '0')) || 0;
    const unit = parseFloat((tr.querySelector('.unitario')?.value || '0')) || 0;

    const totalConIva = round2(cant * unit);
    const base = iva > 0 ? round2(totalConIva / (1 + iva/100)) : totalConIva;
    const tIva = round2(totalConIva - base);

    tr.querySelector('.totalConIva').textContent = totalConIva.toFixed(2);
    tr.querySelector('.totalBase').textContent   = base.toFixed(2);
    tr.querySelector('.totalIva').textContent    = tIva.toFixed(2);

    tr.querySelector('.hTotalConIva').value = totalConIva;
    tr.querySelector('.hTotalBase').value   = base;
    tr.querySelector('.hTotalIva').value    = tIva;
  }

  function recalcFooter(){
    let sum = 0;
    document.querySelectorAll('#tablaIngreso tbody .hTotalBase').forEach(h => {
      sum += parseFloat(h.value || '0') || 0;
    });
    if (totalBaseSumEl) {
      totalBaseSumEl.textContent = sum.toLocaleString('es-CO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
  }

  function groupRows(groupId){
    return Array.from(document.querySelectorAll(`#tablaIngreso tbody tr.ingreso-row[data-group-id="${groupId}"]`));
  }

  function groupMax(groupId){
    const row = document.querySelector(`#tablaIngreso tbody tr.ingreso-row[data-group-id="${groupId}"]`);
    return row ? parseFloat(row.getAttribute('data-max') || '0') || 0 : 0;
  }

  function groupArticulo(groupId){
    const row = document.querySelector(`#tablaIngreso tbody tr.ingreso-row[data-group-id="${groupId}"]`);
    return row ? (row.getAttribute('data-articulo') || 'Molécula') : 'Molécula';
  }

  function groupSum(groupId){
    return groupRows(groupId).reduce((acc, tr) => {
      const v = parseFloat(tr.querySelector('.cantidad')?.value || '0') || 0;
      return acc + v;
    }, 0);
  }

  function validateGroup(groupId){
    const max   = groupMax(groupId);
    const sum   = groupSum(groupId);
    const delta = round2(sum - max);

    // marca visual si quieres
    groupRows(groupId).forEach(tr => {
      tr.classList.toggle('table-danger', delta !== 0);
    });

    return { ok: delta === 0, sum, max, articulo: groupArticulo(groupId) };
  }

  function validateAllGroupsOrWarn() {
    // Retorna true si todo OK; si no, muestra un alert con mensajes por grupo
    const groups = new Set(Array.from(document.querySelectorAll('#tablaIngreso tbody tr.ingreso-row'))
                      .map(tr => tr.getAttribute('data-group-id')));

    const errores = [];
    groups.forEach(gid => {
      const res = validateGroup(gid);
      if (!res.ok) {
        errores.push(`La suma de lotes (${res.sum}) debe ser EXACTA al delta (${res.max}) en “${res.articulo}”.`);
      }
    });

    if (errores.length) {
      alert(errores.join('\n'));
      return false;
    }
    return true;
  }

  function renumberRow(tr, idx){
    // Reescribe todos los name="rows[old][...]" → rows[idx][...]
    tr.querySelectorAll('[name^="rows["]').forEach(inp => {
      inp.name = inp.name.replace(/^rows\[\d+\]/, `rows[${idx}]`);
    });
  }

  function cloneRow(tr){
    const groupId = tr.getAttribute('data-group-id');
    const max     = groupMax(groupId);

    // Crea clon
    const clone = tr.cloneNode(true);

    // Nuevo índice para los name=""
    const idx = nextIndex++;
    renumberRow(clone, idx);

    // Limpia campos que deben venir vacíos en un lote nuevo
    clone.querySelector('.cantidad').value = 0;              // empezar en 0
    const venc = clone.querySelector('.vencimiento'); if (venc) venc.value = '';
    const lote = clone.querySelector('input[name$="[Lote]"]'); if (lote) lote.value = '';

    // Recalcula y quita marca de error
    clone.classList.remove('table-danger');

    // Inserta debajo
    tr.parentNode.insertBefore(clone, tr.nextSibling);

    // Reengancha botones a este clon (listeners delegados abajo se encargan)
    recalcRow(clone);
    recalcFooter();
    validateGroup(groupId);
  }

  function deleteRow(tr){
    const groupId = tr.getAttribute('data-group-id');
    const rows = groupRows(groupId);

    if (rows.length === 1) {
      // Si es la única, no la borres: restablece a max y limpia lote/vencimiento
      const max = groupMax(groupId);
      tr.querySelector('.cantidad').value = max;
      const venc = tr.querySelector('.vencimiento'); if (venc) venc.value = '';
      const lote = tr.querySelector('input[name$="[Lote]"]'); if (lote) lote.value = '';
      recalcRow(tr);
    } else {
      tr.remove();
    }
    recalcFooter();
    validateGroup(groupId);
  }

  // Delegación de eventos para + / −
  table.addEventListener('click', function(e){
    const btn = e.target.closest('.btn-dup, .btn-del');
    if (!btn) return;
    const tr = e.target.closest('tr.ingreso-row');
    if (!tr) return;

    if (btn.classList.contains('btn-dup')) {
      cloneRow(tr);
    } else if (btn.classList.contains('btn-del')) {
      deleteRow(tr);
    }
  });

  // Recalcular al cambiar inputs y validar grupo
  table.addEventListener('input', function(e){
    const tr = e.target.closest('tr.ingreso-row');
    if (!tr) return;

    // Asegura no negativos
    if (e.target.classList.contains('cantidad')) {
      const v = parseFloat(e.target.value || '0') || 0;
      e.target.value = v < 0 ? 0 : v;
    }

    recalcRow(tr);
    recalcFooter();
    validateGroup(tr.getAttribute('data-group-id'));
  });


function validateRequiredInvimaLote() {
  const filas = document.querySelectorAll('#tablaIngreso tbody tr'); // o '.ingreso-row' si ya la usas
  const faltantesPorArticulo = [];
  
  filas.forEach(tr => {
    // inputs de la fila
    const invima = tr.querySelector('input[name^="rows"][name$="[Invima]"]');
    const lote   = tr.querySelector('input[name^="rows"][name$="[Lote]"]');
    const FVenci   = tr.querySelector('input[name^="rows"][name$="[FechaVencimiento]"]');

    // nombre del artículo (hidden en tu form) para el mensaje
    const articulo = tr.querySelector('input[name^="rows"][name$="[Articulo]"]')?.value
                  || tr.querySelector('.articulo-label')?.textContent
                  || 'Artículo sin nombre';

    let faltaAlgo = false;

    // Invima
    if (invima) {
      if (invima.value.trim() === '') {
        invima.classList.add('is-invalid');
        faltaAlgo = true;
      } else {
        invima.classList.remove('is-invalid');
      }
    }

    // Lote
    if (lote) {
      if (lote.value.trim() === '') {
        lote.classList.add('is-invalid');
        faltaAlgo = true;
      } else {
        lote.classList.remove('is-invalid');
      }
    }

    if (FVenci) {
      if (FVenci.value.trim() === '') {
        FVenci.classList.add('is-invalid');
        faltaAlgo = true;
      } else {
        FVenci.classList.remove('is-invalid');
      }
    }

    if (faltaAlgo) {
      faltantesPorArticulo.push(`• ${articulo}`);
    }
  });

  if (faltantesPorArticulo.length > 0) {
    const msg = `
      Debes diligenciar <b>Lote</b> , <b>Invima</b> y <b>Fecha Vencimiento</b>  en las siguientes líneas:<br><br>
      ${faltantesPorArticulo.join('<br>')}
    `;
    if (window.Swal) {
      Swal.fire({
        icon: 'warning',
        title: 'Campos obligatorios',
        html: msg,
        confirmButtonText: 'Entendido'
      });
    } else {
      alert('Debes diligenciar Lote, Invima y Fecha Vencimiento en:\n\n' + faltantesPorArticulo.join('\n'));
    }
    return false; // ❌ detiene el submit
  }

  return true; // ✅ todo bien
}
  // Validación final al enviar
  const btn = document.getElementById('btnGenerarExcel');
  if (btn && form) {
  btn.addEventListener('click', function (e) {
    // 1) Validación de grupos/lotes (tu lógica actual)
    if (!validateAllGroupsOrWarn()) return;

    // 2) Validación obligatoria Lote + Invima por cada fila
    if (!validateRequiredInvimaLote()) return;

    // 3) (Opcional) Evitar cantidades 0
    const bad = Array.from(document.querySelectorAll('#tablaIngreso tbody tr'))
      .some(tr => {
        const v = parseFloat(tr.querySelector('.cantidad')?.value || '0') || 0;
        return v <= 0;
      });
    if (bad) {
      if (window.Swal) {
        Swal.fire({
          icon: 'warning',
          title: 'Cantidades inválidas',
          text: 'Hay filas con cantidad 0. Ajusta las cantidades de los lotes.'
        });
      } else {
        alert('Hay filas con cantidad 0. Ajusta las cantidades de los lotes.');
      }
      return;
    }

    // 4) OK → Generar Excel
    form.submit();
    });
  }

  // Recalcula al cargar
  document.querySelectorAll('#tablaIngreso tbody tr.ingreso-row').forEach(recalcRow);
  recalcFooter();
  // valida todos una vez
  (function(){
    const groups = new Set(Array.from(document.querySelectorAll('#tablaIngreso tbody tr.ingreso-row'))
                      .map(tr => tr.getAttribute('data-group-id')));
    groups.forEach(gid => validateGroup(gid));
  })();

});
</script>
@endsection

