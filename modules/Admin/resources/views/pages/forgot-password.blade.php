@extends('admin::layouts.guest')

@section('title', 'Forgot Password')

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{ url('/') }}"><b>Admin</b>LTE</a>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.password.email') }}" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    />
                    <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Request New Password</button>
                        </div>
                    </div>
                </div>
            </form>

            <p class="mt-3 mb-1">
                <a href="{{ route('admin.login') }}">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
