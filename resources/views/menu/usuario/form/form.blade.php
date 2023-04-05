<div class="form-group row">
    <div class="col-lg-3">
        <label for="surname" class="col-xs-4 control-label requerido">Primer nombre</label>
        <input type="text" name="surname" id="surname" class="form-control UpperCase" value="{{old('surname')}}" required >
    </div>
    <div class="col-lg-3">
        <label for="ssurname" class="col-xs-4 control-label ">Segundo nombre</label>
        <input type="text" name="ssurname" id="ssurname" class="form-control UpperCase" value="{{old('ssurname')}}"  >
    </div>
    <div class="col-lg-3">
        <label for="fname" class="col-xs-4 control-label requerido">Primer apellido</label>
        <input type="text" name="fname" id="fname" class="form-control UpperCase" value="{{old('fname')}}" required >
    </div>
    <div class="col-lg-3">
        <label for="sname" class="col-xs-4 control-label ">Segundo apellido</label>
        <input type="text" name="sname" id="sname" class="form-control UpperCase" value="{{old('sname')}}"  >
    </div>
    </div>
    <div class="form-group row">
    <div class="col-lg-2">
        <label for="type_document" class="col-xs-4 control-label requerido">Tipo de documento</label>
        <select name="type_document" id="type_document" class="form-control select2bs4" style="width: 100%;" required>
            <option value="">---seleccione---</option>
            <option value="AS">AS</option>
            <option value="CC">CC</option>
            <option value="CE">CE</option>
            <option value="MS">MS</option>
            <option value="NI">NI</option>
            <option value="NU">NU</option>
            <option value="PE">PE</option>
            <option value="RC">RC</option>
            <option value="TI">TI</option>
        </select>
    </div>
    <div class="col-lg-3">
        <label for="document" class="col-xs-4 control-label requerido">Documento</label>
        <input type="number" name="document" id="document" class="form-control" value="{{old('document')}}" minlength="5"  required >
    </div>

    <div class="col-lg-2">
        <label for="date_birth" class="col-xs-4 control-label requerido">Fecha nacimiento</label>
        <input type="date" name="date_birth" id="date_birth" class="form-control" value="{{old('date_birth')}}" required >
        </div>
        <div class="col-lg-1">
        <label for="edad" class="col-xs-4 control-label ">Edad</label>
        <input type="number" name="edad" id="edad" class="form-control" value="{{old('edad')}}" placeholder = "00" readonly >
    </div>

    <div class="col-lg-2">
        <label for="municipality" class="col-xs-4 control-label requerido">Municipio</label>
        <select name="municipality" id="municipality" class="form-control select2bs4" style="width: 100%;" required>
        <option value="">---seleccione---</option>
        <option value="Cali">Cali</option>
        <option value="Palmira">Palmira</option>
        <option value="Buenaventura">Buenaventura</option>
        <option value="Jamundi">Jamundi</option>
        <option value="Yumbo">Yumbo</option>
        <option value="Otro">Otro</option>
    </select>
    </div>

    <div class="col-lg-2" id="municipio_otras" style="display: none;">
        <label for="other" class="col-xs-4 control-label requerido">Otro</label>
        <input type="text" name="other" id="other" class="form-control UpperCase" value="{{old('other')}}" >
    </div>

</div>
    <div class="form-group row">
    <div class="col-lg-3">
    <label for="address" class="col-xs-4 control-label requerido">Direccion</label>
    <input type="text" name="address" id="address" class="form-control UpperCase" value="{{old('address')}}" minlength="6" required >
    </div>
    <div class="col-lg-3">
        <label for="celular" class="col-xs-4 control-label requerido">Celular</label>
        <input type="number" name="celular" id="celular" class="form-control" value="{{old('celular')}}" required>
    </div>
    <div class="col-lg-3">
        <label for="telefono" class="col-xs-4 control-label requerido">Telefono</label>
        <input type="number" name="phone" id="phone" class="form-control" value="{{old('phone')}}" required>
    </div>

    <div class="col-lg-3">
        <label for="correo" class="col-xs-4 control-label ">E-mail</label>
        <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}">
    </div>

    </div>
    <div class="form-group row">
        <div class="col-lg-3">
            <label for="sexo" class="col-xs-4 control-label requerido">Sexo</label>
            <select name="sex" id="sex" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
                <option value="M">MASCULINO</option>
                <option value="F">FEMENINO</option>
            </select>
        </div>
        <div class="col-lg-3">
            <label for="eps" class="col-xs-4 control-label requerido">EAPB</label>
            <select name="eapb" id="eapb" class="form-control select2bs4" style="width: 100%;" required>
            <option value="">---seleccione---</option>
            <option value="COMFENALCO">COMFENALCO</option>

        </select>
        </div>

            <div class="col-lg-3">
                <label for="eps" class="col-xs-4 control-label requerido">Motivo de la llamada</label>
                <select name="consultation" id="consultation" class="form-control select2bs4" style="width: 100%;" required>
                <option value="">---seleccione---</option>
                <option value="1">Orientación Psicológica</option>
                <option value="2">Redireccionar llamada al call center</option>
            </select>
            </div>
            <div class="form-group col-lg-3 clearfix" id="radio_button" style="display: none;">
                <label for="radio_button">Paciente tiene diagnóstico psiquiátrico previo</label>
                <div class="icheck-primary d-inline">
                    <label for="radioPrimary1">SI
                    </label>
                    <input type="radio" id="radioPrimary1" name="r1" >

                </div>
                <div class="icheck-primary d-inline">
                    <label for="radioPrimary2">NO
                    </label>
                    <input type="radio" id="radioPrimary2" name="r1" >

                </div>
            </div>
            <input type="hidden" name="diagnosis" id="diagnosis" class="form-control" value="">



    </div>
    <div class="form-group row">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <label for="motivo_consulta" class="col-xs-8 control-label requerido">Motivo de consulta</label>
            <textarea name="reason_consultation" id="reason_consultation" class="form-control UpperCase" rows="3" placeholder="Ingrese el motivo de consulta ..." value="{{old('reason_consultation')}}" required></textarea>
        </div>
    </div>

    <input type="hidden" name="user_id" id="user_id" class="form-control" value="{{Session()->get('usuario_id')}}" >





