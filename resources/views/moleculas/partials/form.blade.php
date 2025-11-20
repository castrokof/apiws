{{-- Código RFAST --}}
<div class="form-group">
  <label for="codigo_rfast">
    Código RFAST <span class="text-danger">*</span>
  </label>
  <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fas fa-barcode"></i></span>
    </div>
    <input
      type="text"
      name="codigo_rfast"
      id="codigo_rfast"
      class="form-control @error('codigo_rfast') is-invalid @enderror"
      value="{{ old('codigo_rfast', $molecula->codigo_rfast) }}"
      placeholder="Ingrese el código RFAST"
      {{ $molecula->exists ? 'readonly' : '' }}
      required>
    @error('codigo_rfast')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>
  @if($molecula->exists)
    <small class="form-text text-muted">
      <i class="fas fa-info-circle"></i> El código no puede ser modificado
    </small>
  @endif
</div>

{{-- Descripción --}}
<div class="form-group">
  <label for="descripcion">
    Descripción <span class="text-danger">*</span>
  </label>
  <div class="input-group">
    <div class="input-group-prepend">
      <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
    </div>
    <textarea
      name="descripcion"
      id="descripcion"
      class="form-control @error('descripcion') is-invalid @enderror"
      rows="3"
      placeholder="Ingrese la descripción del medicamento"
      required>{{ old('descripcion', $molecula->descripcion) }}</textarea>
    @error('descripcion')
      <span class="invalid-feedback">{{ $message }}</span>
    @enderror
  </div>
</div>

{{-- Marca y Presentación en la misma fila --}}
<div class="row">
  {{-- Marca --}}
  <div class="col-md-6">
    <div class="form-group">
      <label for="marca">
        Marca
      </label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-tag"></i></span>
        </div>
        <input
          type="text"
          name="marca"
          id="marca"
          class="form-control @error('marca') is-invalid @enderror"
          value="{{ old('marca', $molecula->marca) }}"
          placeholder="Marca del medicamento">
        @error('marca')
          <span class="invalid-feedback">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  {{-- Presentación --}}
  <div class="col-md-6">
    <div class="form-group">
      <label for="presentacion">
        Presentación
      </label>
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><i class="fas fa-pills"></i></span>
        </div>
        <input
          type="text"
          name="presentacion"
          id="presentacion"
          class="form-control @error('presentacion') is-invalid @enderror"
          value="{{ old('presentacion', $molecula->presentacion) }}"
          placeholder="Ej: Tabletas, Jarabe, etc.">
        @error('presentacion')
          <span class="invalid-feedback">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>
</div>

{{-- Estado Activo --}}
<div class="form-group">
  <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
    <input
      type="checkbox"
      name="activo"
      value="1"
      class="custom-control-input"
      id="activo"
      {{ old('activo', $molecula->activo ?? true) ? 'checked' : '' }}>
    <label for="activo" class="custom-control-label">
      <strong>Estado Activo</strong>
      <small class="d-block text-muted">Indica si la molécula está disponible para uso</small>
    </label>
  </div>
</div>

{{-- Nota informativa --}}
<div class="alert alert-info alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h5><i class="icon fas fa-info"></i> Información</h5>
  Los campos marcados con <span class="text-danger">*</span> son obligatorios.
</div>
