

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" disabled autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="drogueria" class="col-md-4 col-form-label text-md-right">{{ __('drogueria ') }}</label>

                            <div class="col-md-6">
                                <select id="drogueria" type="drogueria" class="form-control @error('drogueria') is-invalid @enderror" name="drogueria" value="{{ old('drogueria') }}" required autocomplete="drogueria">
                                    <option>-Seleccione-</option>
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
                                    
                                </select>    

                                @error('drogueria')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                                                <div class="form-group row">
                            <label for="rol" class="col-md-4 col-form-label text-md-right">{{ __('E-rol') }}</label>

                            <div class="col-md-6">
                                <select id="rol" type="rol" class="form-control @error('rol') is-invalid @enderror" name="rol" value="{{ old('rol') }}" required autocomplete="rol">
                                    <option >-Seleccione-</option>
                                    <option value="1">Administrador</option>
                                    <option value="2">Supervisor</option>
                                    <option value="3">Consultor</option>
                                    <option value="4">Scann</option>
                                    
                                </select>
                                
                                
                                @error('rol')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"  autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="email_verified_at	" class="col-md-4 col-form-label text-md-right">{{ __('Activar_usuario') }}</label>

                            <div class="col-md-6">
                                <input id="email_verified_at" type="date" class="form-control @error('email_verified_at	') is-invalid @enderror" name="email_verified_at"  autocomplete="email_verified_at">

                                @error('email_verified_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

          