@extends('layouts.auth')

@section('content')
<form class="form-horizontal" role="form" method="POST" action="{{ action('API\v1\UserController@storeUserDt') }}">
    {{ csrf_field() }}
    <h3 class="font-green">Register</h3>
    

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

    <div style="margin-top: 10px;" class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
        <div>
            <label for="username" class="control-label" style="margin-bottom: 10px;">Username</label>
            <input id="username" type="text" class="form-control placeholder-no-fix" name="username" value="{{ $username or old('username') }}" required autofocus>

            @if ($errors->has('username'))
                <span class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div style="margin-top: 10px;" class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
        <div>
            <label for="name" class="control-label" style="margin-bottom: 10px;">Name</label>
            <input id="name" type="text" class="form-control placeholder-no-fix" name="name" value="{{ $name or old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
    </div>

    
    <div class="form-actions" style="text-align: center;padding: 0px;">
        <button type="submit" class="btn btn-primary" style="margin-bottom:20px;">
            Register
        </button>
    </div>
    <input name="line_user_id" type="hidden" value="{{ $line_user_id }}">
    <input name="dt_code" type="hidden" value="{{ $dt_code }}">
    <input name="is_dt" type="hidden" value="1">
</form>
@endsection


