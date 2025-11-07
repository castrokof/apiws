<div class="form-group">
    <label>Código RFAST</label>
    <input type="text" name="codigo_rfast" class="form-control"
           value="{{ old('codigo_rfast', $molecula->codigo_rfast) }}"
           {{ $molecula->exists ? 'readonly' : '' }} required>
</div>

<div class="form-group">
    <label>Descripción</label>
    <textarea name="descripcion" class="form-control" required>{{ old('descripcion', $molecula->descripcion) }}</textarea>
</div>

<div class="form-group">
    <label>Marca</label>
    <input type="text" name="marca" class="form-control"
           value="{{ old('marca', $molecula->marca) }}">
</div>

<div class="form-group">
    <label>Presentación</label>
    <input type="text" name="presentacion" class="form-control"
           value="{{ old('presentacion', $molecula->presentacion) }}">
</div>

<div class="form-group form-check">
    <input type="checkbox" name="activo" value="1"
           class="form-check-input" id="activo"
           {{ old('activo', $molecula->activo) ? 'checked' : '' }}>
    <label for="activo" class="form-check-label">Activo</label>
</div>
