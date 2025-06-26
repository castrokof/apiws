@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4>{{ __('Register') }}</h4>
                </div>

                <div class="card-body p-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group position-relative">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            <i class="fas fa-user form-icon"></i>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            <i class="fas fa-envelope form-icon"></i>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="drogueria" class="form-label">{{ __('Drogueria') }}</label>
                            <select id="drogueria" class="form-control form-control-lg @error('drogueria') is-invalid @enderror" name="drogueria" value="{{ old('drogueria') }}" required autocomplete="drogueria">
                                <option value="" disabled selected>-Seleccione-</option>
                                <option value="1">All</option>
                                <option value="2">Salud Mental</option>
                                <option value="3">Dolor y Paliativos</option>
                                <option value="4">Plan complementario</option>
                                <option value="5">Huerfanas</option>
                                <option value="6">Biologicos</option>
                                <option value="7">Cliente</option>
                                <option value="8">Emcali</option>
                                <option value="9">SOS_IDEO</option>
                                <option value="10">SOS_PASOANCHO</option>
                                <option value="11">SOS_PAC</option>
                                <option value="12">SOS_EVE</option>
                                <option value="13">Jamundi</option>
                                <option value="14">Comfe_IDEO</option>
                            </select>
                            <i class="fas fa-clinic-medical form-icon"></i>
                            @error('drogueria')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="rol" class="form-label">{{ __('Rol') }}</label>
                            <select id="rol" class="form-control form-control-lg @error('rol') is-invalid @enderror" name="rol" value="{{ old('rol') }}" required autocomplete="rol">
                                <option value="" disabled selected>-Seleccione-</option>
                                <option value="1">Administrador</option>
                                <option value="2">Supervisor</option>
                                <option value="3">Consultor</option>
                                <option value="4">Scann</option>
                                <option value="5">Compras</option>
                                <option value="6">Almacen</option>
                            </select>
                            <i class="fas fa-user-tag form-icon"></i>
                            @error('rol')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            <i class="fas fa-lock form-icon"></i>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group position-relative">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password">
                            <i class="fas fa-lock form-icon"></i>
                        </div>

                        <div class="form-group mb-0 text-center">
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
