@extends('layouts.guest')

@section('content')
<style>
    .home-link {
    position: absolute;
    top: 20px;
    left: 20px;
    font-size: 24px;
    color: #007bff;
}
</style>

@if ($errors->has('inactive'))
    <div class="modal" tabindex="-1" id="inactive-modal" style="display:block;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account Deactivated</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ $errors->first('inactive') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="closeModal()">OK</button>
                </div>
            </div>
        </div>
    </div>
@endif
<a href="/" class="home-link"><i class="fas fa-home"></i></a>
    <div class="card-body login-card-body">
        <p class="login-box-msg">{{ __('Login') }}</p>

        <form action="{{ route('signin') }}" method="post">
            @csrf

            <div class="input-group mb-3">
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email') }}" required autofocus>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
                @error('email')
                <span class="error invalid-feedback">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="input-group mb-3">
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
                @error('password')
                <span class="error invalid-feedback">
                    {{ $message }}
                </span>
                @enderror
            </div>

            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Login') }}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        @if (Route::has('password.request'))
            <p class="mb-1">
                <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
            </p>
        @endif

        @if (Route::has('register'))
            <p class="mb-0">
                <a href="{{ route('register') }}" class="text-center">{{ __('Register') }}</a>
            </p>
        @endif
    
    </div>
    <!-- /.login-card-body -->
    <script>
        function closeModal() {
            document.getElementById("inactive-modal").style.display = "none";
        }
    </script>
@endsection
