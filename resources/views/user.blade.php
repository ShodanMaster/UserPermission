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
            <form id="addUserForm">
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
                        <div class="col-md-6 form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="password_confirmation" placeholder="Confirm Password" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
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
<script>

    const modals = ['#addUserModal', '#editUserModal'];

    modals.forEach(modalId => {
        const modalEl = document.querySelector(modalId);
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function () {
                const form = modalEl.querySelector('form');
                if (form) form.reset();

            });
        }
    });

    function passwordConfirm(password, confirm_password){
        return password === confirm_password && password.length > 0;
    }

    document.getElementById('addUserForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('name').value.trim();
        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const confirm_password = document.getElementById('confirm_password').value;

        if (!passwordConfirm(password, confirm_password)) {
            Swal.fire({
                title: 'Password Mismatch',
                text: 'Password and confirmation do not match.',
                icon: 'warning'
            });
            return;
        }

        Swal.fire({
            title: 'Confirm Submission',
            text: "Are you sure you want to add this user?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, add user',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post('{{ route('user.store') }}', {
                    name: name,
                    username: username,
                    password: password,
                    password_confirmation: confirm_password
                })
                .then(function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response.data.message || 'User added successfully.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                    bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
                    document.getElementById('addUserForm').reset();
                })
                .catch(function(error) {
                    if (error.response && error.response.status === 422) {

                        const errors = error.response.data.errors;
                        let messages = '';
                        for (const field in errors) {
                            messages += `${errors[field].join(', ')}\n`;
                        }

                        Swal.fire({
                            title: 'Validation Error',
                            text: messages,
                            icon: 'warning'

                        });

                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: error.response?.data?.message || 'Something went wrong.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });

</script>
@endpush
