@extends('layouts.master')

@section('content')

<!--Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addUserModalLabel">Add User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="username" class="form-label">User Name</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 form-control">
                        <label for="password" class="form-label"></label>
                        <input type="text" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <div class="col-md-6 form-control">
                        <label for="confirm-password" class="form-label"></label>
                        <input type="text" class="form-control" id="confirm-password" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="d-flex justify-content-between">
    <h1 class="mb-4">User</h1>

    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        Add User
    </button>
</div>

@endsection
@push('custom-scripts')
    
@endpush
