@extends('layouts.auth')

@section('content')
<form class="form-horizontal" role="form" method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{ $token }}">
    <h3 class="font-green">Reset Password</h3>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div style="margin-top: 10px;" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <div>
            <label for="email" class="control-label" style="margin-bottom: 10px;">E-Mail Address</label>
            <input id="email" type="email" class="form-control placeholder-no-fix" name="email" value="{{ $email or old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        

        <div>
            <label for="password" class="control-label" style="margin-bottom: 10px;">Password</label>
            <input id="password" type="password" class="form-control" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        
        <div >
            <label for="password-confirm" class="control-label" style="margin-bottom: 10px;">Confirm Password</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

            @if ($errors->has('password_confirmation'))
                <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="form-actions" style="text-align: center;padding: 0px;">
        <button type="submit" class="btn btn-primary" style="margin-bottom:20px;">
            Reset Password
        </button>
    </div>
</form>
@endsection
