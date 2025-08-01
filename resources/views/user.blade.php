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
                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input" id="show-password" onchange="togglePassword('password')">
                                <label class="form-check-label" for="show-password">Show Password</label>
                            </div>
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

<!--Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editUserModalLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm">
                <input type="hidden" id="edit-id" name="id">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="edit-name" name="name" placeholder="Enter Full Name" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="username" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="edit-username" name="username" placeholder="Enter Username" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 form-group mb-2">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="enable-password" onchange="toggleEditPasswordFields()">
                                <label class="form-check-label" for="enable-password">Change Password</label>
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="edit-password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit-password" name="password" placeholder="Password" disabled>
                            <div class="form-check mt-1">
                                <input type="checkbox" class="form-check-input" id="show-edit-password" onchange="togglePassword('edit-password')" disabled>
                                <label class="form-check-label" for="show-edit-password">Show Password</label>
                            </div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="edit-confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="edit-confirm_password" name="password_confirmation" placeholder="Confirm Password" disabled>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update changes</button>
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

            currentPage = page;
            currentSearch = search;

            axios.post('{{url('getUsers')}}', {
                page: page,
                per_page: 10,
                search: search
            })
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
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                    </svg>
                                </button>

                                <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                        <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                    </svg>
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

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }

        document.getElementById('addUserForm').addEventListener('submit', function (e) {
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
                    .then(function (response) {
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
                    .catch(function (error) {
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

        function toggleEditPasswordFields() {
            const enabled = document.getElementById('enable-password').checked;
            document.getElementById('edit-password').disabled = !enabled;
            document.getElementById('edit-confirm_password').disabled = !enabled;
            document.getElementById('show-edit-password').disabled = !enabled;

            if (!enabled) {
                document.getElementById('edit-password').value = '';
                document.getElementById('edit-confirm_password').value = '';
                document.getElementById('show-edit-password').checked = false;
                document.getElementById('edit-password').type = 'password';
            }
        }

        function editUser(id) {
            axios.get(`/user/${id}`)
                .then(response => {
                    const user = response.data.data;

                    document.getElementById('edit-id').value = user.id;
                    document.getElementById('edit-name').value = user.name || '';
                    document.getElementById('edit-username').value = user.username;

                    const modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching user:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch user details.',
                    });
                });
        }

        document.getElementById('editUserForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const id = document.getElementById('edit-id').value;
            const name = document.getElementById('edit-name').value.trim();
            const username = document.getElementById('edit-username').value.trim();

            const enablePassword = document.getElementById('enable-password').checked;
            const password = document.getElementById('edit-password').value;
            const confirm_password = document.getElementById('edit-confirm_password').value;

            if (enablePassword && (password !== confirm_password || password.length === 0)) {
                Swal.fire({
                    title: 'Password Mismatch',
                    text: 'Password and confirmation do not match or are empty.',
                    icon: 'warning'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Update',
                text: "Are you sure you want to update this user?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, update',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = {
                        name: name,
                        username: username
                    };

                    if (enablePassword) {
                        formData.password = password;
                        formData.password_confirmation = confirm_password;
                    }

                    axios.put(`/user/${id}`, formData)
                        .then(function (response) {
                            Swal.fire({
                                title: 'Updated!',
                                text: response.data.message || 'User updated successfully.',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });

                            loadUsers(currentPage);
                            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
                        })
                        .catch(function (error) {
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

        function deleteUser(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/user/${id}`)
                        .then(response => {
                            const data = response.data;
                            if (data.status == 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: data.message || 'User has been deleted.',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                                loadUsers(currentPage);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to delete user.',
                                });
                            }
                        })
                        .catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while deleting the user.',
                            });
                        });
                }
            });
        }

        window.loadUsers = loadUsers;
        window.togglePassword = togglePassword;
        window.editUser = editUser;
        window.toggleEditPasswordFields = toggleEditPasswordFields;
        window.deleteUser = deleteUser;
    });

</script>
@endpush
