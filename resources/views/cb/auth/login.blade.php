@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 text-center">
      <h3>Login</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <form method="POST" action="{{ url('login') }}" aria-label="{{ __('Login') }}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="form-group row">
            <div class="col-md-4">
                <label for="email">E-Mail Address</label>
            </div>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus/>
                @if ($errors->has('email'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-4">
                <label for="password">Password</label>
            </div>
            <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required />
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-4"></div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('Login') }}
                </button>

                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-4"></div>
            <div class="col-md-6">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                    </label>
                </div>
            </div>
        </div>
      </form>
      @if(false)
      <hr>
      <div class="row" style="text-align: center;">
        <a class="btn btn-default" href="http://localhost:8003/login/google"><i class="fa fa-google-plus"></i> Google</a>
        <a class="btn btn-default" href="http://localhost:8003/login/facebook"><i class="fa fa-facebook"></i> Facebook</a>
        <a class="btn btn-default"><i class="fa fa-twitter"></i> Twitter</a>
        <a class="btn btn-default"><i class="fa fa-youtube"></i> Youtube</a>
        <a class="btn btn-default" onclick="login()"><i class="fa fa-github"></i> Github</a>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection