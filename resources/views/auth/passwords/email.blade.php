@extends('layouts.auth')

@section('content')
  <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
      {{ csrf_field() }}
      <h3 class="font-green">Forget Password ?</h3>
      @if (session('status'))
          <div class="alert alert-success">
              {{ session('status') }}
          </div>
      @endif
      <p> Enter your e-mail address below to reset your password. </p>
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
          <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" value="{{ old('email') }}" required/>
          @if ($errors->has('email'))
              <span class="help-block">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
          @endif
      </div>
      <div class="form-actions text-center">
          <button type="submit" class="btn btn-success uppercase">Submit</button>
      </div>
  </form>

@endsection
