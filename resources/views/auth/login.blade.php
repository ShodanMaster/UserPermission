@extends('layouts.app')
@section('app-content')
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-center text-white fs-4"> Login</div>
            <form id="loginForm" action="{{route('loginin')}}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
