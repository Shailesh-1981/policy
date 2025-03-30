@extends('admin.layouts.master')

@section('content')
    <main class="container mt-4">

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="fw-bold">Add Employe </h1>
                <a href="{{ route('employe-index') }}" class="btn btn-success"> <- Back button</a>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Add Employe</h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="addemploye" onsubmit="return false;">
                                @csrf
                                <input type="hidden" name="user_id" id="user_id">

                                <!-- Policy Number -->
                                <div class="mb-3">
                                    <label class="form-label">User Name</label>
                                    <input type="text" name="user_name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <!-- Type -->
                                <div class="mb-3">
                                    <label class="form-label">User Role</label>
                                    <select name="user_type" class="form-control" required>
                                        <option value="" disabled selected>Select Insurance Type</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Agent</option>
                                        <option value="3">Manager</option>
                                    </select>
                                </div>
                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Submit </button>
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
            // Get the authenticated user from localStorage
            let user = JSON.parse(localStorage.getItem("user"));
            if (user) {
                $("#user_id").val(user.id); // Set user_id in form
            }

            // Validate and Submit Form
            $('#addemploye').validate({
                rules: {
                    user_name: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    user_type: {
                        required: true
                    }
                },
                messages: {
                    user_name: {
                        required: "Please enter the user's name."
                    },
                    email: {
                        required: "Please enter an email address.",
                        email: "Please enter a valid email."
                    },
                    password: {
                        required: "Please enter a password.",
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
                        url: "/api/store-employe", 
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
                                toastr.success("Employee added successfully!", "Success", {
                                    closeButton: true,
                                    progressBar: true,
                                    timeOut: 3000
                                });

                                setTimeout(function() {
                                    window.location.href = "/employe";
                                }, 2000);
                            } else {
                                toastr.error("Error adding employee. Please try again.",
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
