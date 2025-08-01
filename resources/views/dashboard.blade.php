@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between">
    <h1 class="mb-4">Dashboard</h1>
    <h3>{{auth()->user()->name}}</h3>
</div>

@endsection
