<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped table-sm mb-0">
        <thead class="bg-light">
          <tr>
            <th>Código RFAST</th>
            <th>Descripción</th>
            <th>Marca</th>
            <th>Presentación</th>
            <th style="width:200px" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($moleculas as $m)
            <tr>
              <td><strong>{{ $m->codigo_rfast }}</strong></td>
              <td>{{ $m->descripcion }}</td>
              <td>{{ $m->marca }}</td>
              <td>{{ $m->presentacion }}</td>
              <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                  <a href="{{ route('moleculas.codigos.index', $m) }}" class="btn btn-info" title="Ver Códigos">
                    <i class="fa fa-barcode"></i> Códigos
                  </a>
                  <a href="{{ route('moleculas.edit', $m) }}" class="btn btn-warning" title="Editar">
                    <i class="fa fa-edit"></i>
                  </a>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-3">No hay moléculas registradas</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
