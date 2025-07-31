@extends('layouts.master')

@section('content')
    <h1>Permission</h1>

    <div class="card">
        <div class="card-header">
            <select name="user" id="user" class="form-control">
                <option value="" disabled selected>Select User</option>

                @forelse ($users as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                @empty
                    <option value="" disabled>No Users</option>
                @endforelse
            </select>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach ($routes as $route)
                    <div class="col-sm-6">
                        <label for="route_id" class="form-label">{{$route->title}}</label>
                        <input type="checkbox" name="route_id" id="route_id" value="{{$route->id}}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
