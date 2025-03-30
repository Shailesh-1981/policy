@extends('admin.layouts.master')

@section('content')
    <main class="container mt-4">

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="fw-bold">Edit Policy </h1>
                <a href="{{ route('employe-index') }}" class="btn btn-success"> <- Back button</a>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Edit Policy</h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="editUser" onsubmit="return false;">
                                @csrf
                                <input type="hidden" name="user_id" id="user_id" value="{{ $data->id }}">

                                <!-- User Name -->
                                <div class="mb-3">
                                    <label class="form-label">User Name</label>
                                    <input type="text" name="user_name" class="form-control" value="{{ $data->name }}"
                                        required>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $data->email }}"
                                        required>
                                </div>

                                <!-- Password (Optional) -->
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control">
                                    <small class="text-muted">Leave blank to keep the current password.</small>
                                </div>

                                <!-- User Role -->
                                <div class="mb-3">
                                    <label class="form-label">User Role</label>
                                    <select name="user_type" class="form-control" required>
                                        <option value="" disabled>Select Role</option>
                                        <option value="1" @selected($data->userRole->role_id == 1)>Admin</option>
                                        <option value="2" @selected($data->userRole->role_id == 2)>Agent</option>
                                        <option value="3" @selected($data->userRole->role_id == 3)>Manager</option>
                                        <option value="4" @selected($data->userRole->role_id == 4)>User</option>
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#editUser').validate({
                rules: {
                    user_name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 6
                    },
                    user_type: {
                        required: true
                    }
                },
                messages: {
                    user_name: {
                        required: "Please enter a user name."
                    },
                    email: {
                        required: "Please enter an email.",
                        email: "Please enter a valid email."
                    },
                    password: {
                        minlength: "Password must be at least 6 characters."
                    },
                    user_type: {
                        required: "Please select a user role."
                    }
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    let token = localStorage.getItem("jwt_token");

                    if (!token) {
                        toastr.error("Authentication error. Please log in again.", "Error", {
                            closeButton: true,
                            progressBar: true
                        });
                        return;
                    }

                    $.ajax({
                        url: "/api/update-user",
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            "Authorization": "Bearer " + token,
                            "Accept": "application/json"
                        },
                        success: function(response) {
                            if (response.status === 200) {
                                toastr.success("User updated successfully!", "Success", {
                                    closeButton: true,
                                    progressBar: true,
                                    timeOut: 3000
                                });

                                setTimeout(function() {
                                    window.location.href =
                                        "{{ route('employe-index') }}"; 
                                }, 2000);
                            } else {
                                toastr.error("Error updating user. Please try again.",
                                    "Error", {
                                        closeButton: true,
                                        progressBar: true
                                    });
                            }
                        },
                        error: function(xhr) {
                            toastr.error("Something went wrong!", "Error", {
                                closeButton: true,
                                progressBar: true
                            });
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
