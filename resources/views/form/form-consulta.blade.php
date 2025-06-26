<div class="form-group row">
    <div class="col-lg-3">
        <label for="fecha" class="col-xs-4 control-label ">Fecha inicial</label>
        <input type="date" name="fechaini" id="fechaini" class="form-control" value="{{old('fechaini')}}" >
    </div>
    <div class="col-lg-3">
        <label for="snombre" class="col-xs-4 control-label ">Fecha final</label>
        <input type="date" name="fechafin" id="fechafin" class="form-control" value="{{old('fechafin')}}" >
    </div>
    <div class="col-lg-6">
        <label for="prescripcion" class="col-xs-4 control-label ">Prescripcion</label>
        <textarea name="prescripcion" id="prescripcion" class="textarea form-control" rows="3" placeholder="Para consultar varias prescripciones separelas por , máximo 400 " ></textarea>
       
    </div>
</div>
<div class="form-group row">
<div class="col-lg-10">
<label for="fecha" class="col-xs-4 control-label ">Filtrar por Id o iddireccionamiento</label>
<input type="text" id="filtro-numeros" placeholder="Filtrar por ID" class="form-control">
</div>
</div>




 