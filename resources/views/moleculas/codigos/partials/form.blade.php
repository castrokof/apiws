<div class="form-group">
    <label>Nombre Proveedor</label>
    <input type="text" name="nombre_proveedor" class="form-control"
           value="{{ old('nombre_proveedor', $codigo->nombre_proveedor) }}">
</div>

<div class="form-group">
    <label>Código Proveedor</label>
    <input type="text" name="codigo_proveedor" class="form-control"
           value="{{ old('codigo_proveedor', $codigo->codigo_proveedor) }}" required>
</div>

<div class="form-group form-check">
    <input type="checkbox" name="activo" value="1"
           class="form-check-input" id="activo"
           {{ old('activo', $codigo->activo ?? true) ? 'checked' : '' }}>
    <label for="activo" class="form-check-label">Activo</label>
</div>
