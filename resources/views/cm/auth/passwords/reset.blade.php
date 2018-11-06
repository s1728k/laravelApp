@extends("cm.layouts.app")

@section("content")
<h3 class="center-align">Reset Password</h3>
<div class="row">
    <div class="col s12 m4 offset-m4">
      <form method="POST" action="{{ route('c.auth.password.reset.submit', ['rtype' => $rtype, 'id' => $id]) }}" aria-label="{{ __('Reset Password') }}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <div class="row">
            <div class="input-field col s12">
                <i class="fa fa-key prefix"></i>
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" name="password" required>
                <label for="password">New Password</label>
                @if ($errors->has('password'))
                    <span class="helper-text" data-error="wrong" data-success="right">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="input-field s11 offset-s1">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s11 offset-s1">
                <button type="submit" class="waves-effect waves-light btn blue darken-2">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </div>
      </form>
    </div>
</div>
@endsection