@extends('layouts.master')

@section('content')
    <h1>Permission</h1>

    <div class="card">
        <div class="card-header">
            <select name="user" id="user" class="form-control" onchange="permissions()" required>
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
                        <input type="checkbox" name="route_ids[]" id="route_id_{{$route->id}}" value="{{$route->id}}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end">
            <button class="btn btn-dark" onclick="submit()">Grand</button>
        </div>
    </div>
@endsection
@push('custom-scripts')
    <script>
        function permissions(){

            let userId = document.getElementById('user').value;

            axios.post('{{url('getPermissions')}}', {
                user_id: userId
            })
            .then(function (response){
                const result = response.data;
                console.log(response.data.status);

                if(result.status === 200){
                    let permissions = result.permissions;

                    document.querySelectorAll('input[type="checkbox"][name="route_ids[]"]').forEach(cb => cb.checked = false);

                    permissions.forEach(id => {
                        const checkbox = document.getElementById('route_id_' + id);
                        if (checkbox) checkbox.checked = true;
                    });
                }else{
                    document.querySelectorAll('input[type="checkbox"][name="route_ids[]"]').forEach(cb => cb.checked = false);
                }

            }).catch(function (error) {
                // In case of actual request failure
                document.querySelectorAll('input[type="checkbox"][name="route_ids[]"]').forEach(cb => cb.checked = false);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something Went Wrong',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProhressBar: true,
                });
                console.error(error);
            });

        }

        function submit() {
            const userId = document.getElementById('user').value;

            if (!userId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select a User',
                    text: 'Please select a user before submitting permissions.',
                    timer: 2000,
                    showConfirmButton: false,
                });
                return;
            }

            const selectedRoutes = [];
            document.querySelectorAll('input[type="checkbox"][name="route_ids[]"]:checked').forEach(cb => {
                selectedRoutes.push(cb.value);
            });

            axios.post('{{ route('permission.store') }}', {
                user_id: userId,
                route_ids: selectedRoutes
            })
            .then(function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved!',
                    text: 'Permissions have been Granted successfully.',
                    timer: 2000,
                    showConfirmButton: false,
                });
            })
            .catch(function(error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Something went wrong while saving permissions.',
                    timer: 2000,
                    showConfirmButton: false,
                });
                console.error(error);
            });
        }

    </script>
@endpush
