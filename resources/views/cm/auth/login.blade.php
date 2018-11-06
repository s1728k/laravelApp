@extends("cm.layouts.app")

@section("content")
<h3 class="center-align">Login</h3>
<div class="row">
    <div class="col s12 m4 offset-m4">
        <form method="POST" action="{{ url('login') }}" aria-label="{{ __('Login') }}">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="fa fa-envelope prefix"></i>
                        <input id="email" type="email" class="validate{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus/>
                        <label for="email">E-Mail Address</label>
                        @if ($errors->has('email'))
                            <span class="helper-text" data-error="wrong" data-success="right">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                    <div class="input-field col s12">
                        <i class="fa fa-key prefix"></i>
                        <input id="password" type="password" class="validate{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" value="{{ old('password') }}" required />
                        <label for="password">Password</label>
                        @if ($errors->has('password'))
                            <span class="helper-text" data-error="wrong" data-success="right">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                    <div class="input-field col s11 offset-s1">
                        <button type="submit" class="waves-effect waves-light btn blue darken-2">
                            <i class="fa fa-sign-in left"></i>{{ __('Login') }}</button>
                        <a href="{{ route('c.auth.password.reset.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    </div>
                    <div class="input-field col s12 offset-s1">
                        <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>{{ __('Remember Me') }}</span>
                        </label>
                    </div>
                </div>
        </form>
    </div>
</div>
@endsection