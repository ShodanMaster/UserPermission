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
<div class="input-group mb-3 w-50">
    <input type="text" class="form-control" id="searchInput" placeholder="Search by name or username">
</div>
<div class="table-responsive">
    <table class="table table-striped" id="usersTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Username</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="users-body"></tbody>
    </table>
</div>

<nav>
    <ul class="pagination" id="pagination"></ul>
</nav>

@endsection
@push('custom-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        let currentSearch = '';

        loadUsers();

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

        function debounce(func, delay) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }


        document.getElementById('searchInput').addEventListener('input', debounce(function (e) {
            currentSearch = e.target.value.trim();
            loadUsers(1, currentSearch);
        }, 300));

        function renderPagination(current, total) {
            const maxVisible = 7;
            let html = '';

            if (current > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadUsers(${current - 1})">Previous</a></li>`;
            }

            let startPage = Math.max(1, current - Math.floor(maxVisible / 2));
            let endPage = startPage + maxVisible - 1;

            if (endPage > total) {
                endPage = total;
                startPage = Math.max(1, endPage - maxVisible + 1);
            }

            if (startPage > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadUsers(1)">1</a></li>`;
                if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            for (let i = startPage; i <= endPage; i++) {
                html += `<li class="page-item ${i === current ? 'active' : ''}">
                            <a class="page-link" href="javascript:void(0)" onclick="loadUsers(${i})">${i}</a>
                        </li>`;
            }

            if (endPage < total) {
                if (endPage < total - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadUsers(${total})">${total}</a></li>`;
            }

            if (current < total) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="loadUsers(${current + 1})">Next</a></li>`;
            }

            document.getElementById('pagination').innerHTML = html;
        }



        function loadUsers(page = 1, search = '') {
            console.log('called');

            currentPage = page;
            currentSearch = search;

            axios.get(`/users?page=${page}&per_page=10&search=${encodeURIComponent(search)}`)
                .then(response => {
                    const res = response.data;
                    const tbody = document.getElementById('users-body');
                    tbody.innerHTML = '';

                    if (!res.data || res.data.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="9" class="text-center text-muted">No users found.</td>
                            </tr>`;
                        document.getElementById('pagination').innerHTML = '';
                        return;
                    }

                    res.data.forEach((user, index) => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${(res.current_page - 1) * res.per_page + index + 1}</td>
                                <td>${user.name || ''}</td>
                                <td>${user.username}</td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-primary me-2" onclick="editUser(${user.id})" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>`;
                    });

                    renderPagination(res.current_page, res.last_page);
                })
                .catch(error => {
                    console.error("Error loading users:", error);
                    document.getElementById('users-body').innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center text-danger">Failed to load users.</td>
                        </tr>`;
                    document.getElementById('pagination').innerHTML = '';
                });
        }

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
                        loadUsers(1);
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

        window.loadUsers = loadUsers;
    });

</script>
@endpush
