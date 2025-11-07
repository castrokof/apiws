<div class="table-responsive">
  <table class="table table-bordered table-sm">
    <thead class="thead-light">
      <tr>
        <th>Código RFAST</th>
        <th>Descripción</th>
        <th>Marca</th>
        <th>Presentación</th>
        <th style="width:160px">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @forelse($moleculas as $m)
        <tr>
          <td>{{ $m->codigo_rfast }}</td>
          <td>{{ $m->descripcion }}</td>
          <td>{{ $m->marca }}</td>
          <td>{{ $m->presentacion }}</td>
          <td>
            <a href="{{ route('moleculas.codigos.index', $m) }}" class="btn btn-info btn-sm">
              <i class="fa fa-barcode"></i> Códigos
            </a>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center">No hay moléculas registradas</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
