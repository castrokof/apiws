    <div class="container-fluid">
    <div class="row">
    <div class="col-md-3">

    <div class="card card-primary card-outline">
    <div class="card-body box-profile">
    <div class="text-center">
    <img class="profile-user-img img-fluid img-circle" src="{{asset("assets/$theme/dist/img/user_default.jpg  ")}}" alt="User profile picture">
    </div>

    <h2 id="namesadd" class="profile-username text-center text-muted"></h2>
    <p id="documentsadd" class="text-muted text-center"></p>
    </div>
    </div>
    </div>

    <div class="col-md-9">
    <div class="card card-primary card-outline">
    <div class="card-header p-2">
    <ul class="nav nav-pills">
    <li class="nav-item"><a class="nav-link active" data-toggle="tab">Observación</a>

    </li>
    </ul>
    </div>
    <div class="card-body">

    <div class="tab-pane active" id="activity">
    <div class="post">

        <div class="col-lg-12 col-md-12 col-xs-12 p-0">
               <textarea name="addobservacion" id="addobservacion" class="form-control UpperCase" rows="5" placeholder="Ingrese la observación ..." value="{{old('addobservacion')}}" ></textarea>
        </div>

        <input type="hidden" name="evo_id" id="evo_id" class="form-control" value="">
        <input type="hidden" name="user_id" id="user_id" class="form-control" value="">


    </div>

    <div class="card-footer p-2">
        <span class="float-right">@include('includes.boton-form-crear-empresa-empleado-usuario')</span>
    </div>

    </div>





    </div>


    </div>
    </div>
    </div>
    </div>

