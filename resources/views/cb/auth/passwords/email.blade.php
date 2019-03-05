@extends("cb.layouts.app")

@section("content")
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 text-center">
      <h3>Password Reset Request</h3>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Password Reset') }}">
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
            <div class="col-md-4"></div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    {{ __('Send Reset Password Link') }}
                </button>
            </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection