@extends("cm.layouts.app")

@section("content")
<h3 class="center-align">Password Reset Request</h3>
<div class="row">
    <div class="col s12 m4 offset-m4">
      <form method="POST" action="{{ route('c.auth.password.reset.request.submit') }}" aria-label="{{ __('Password Reset') }}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="input-field col s12">
                <i class="fa fa-envelope prefix"></i>
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus/>
                <label for="email">E-Mail Address</label>
                @if ($errors->has('email'))
                    <span class="helper-text" data-error="wrong" data-success="right">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="input-field col s11 offset-s1">
                <button type="submit" class="waves-effect waves-light btn blue darken-2">
                    {{ __('Send Reset Password Link') }}
                </button>
            </div>
        </div>
      </form>
  </div>
</div>
@endsection