@extends('admin.layouts.master')

@section('content')
    <main class="container mt-4">

        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="fw-bold">Create New Policy </h1>
                <a href="{{ route('policy-index') }}" class="btn btn-success"> <- Back button</a>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Add Policy</h5>
                        </div>
                        <div class="card-body p-4">
                            <form id="addPolicy" onsubmit="return false;">
                                @csrf
                                <input type="hidden" name="user_id" id="user_id">

                                <!-- Policy Number -->
                                <div class="mb-3">
                                    <label class="form-label">Policy Number</label>
                                    <input type="text" name="policy_number" class="form-control" required>
                                    <small class="text-muted">Unique policy identifier</small>
                                </div>

                                <!-- Type -->
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="policy_type" class="form-control" required>
                                        <option value="" disabled selected>Select Insurance Type</option>
                                        <option value="health">Health Insurance</option>
                                        <option value="vehicle">Vehicle Insurance</option>
                                        <option value="life">Life Insurance</option>
                                    </select>
                                    <small class="text-muted">Type of insurance (e.g., health, vehicle, life)</small>
                                </div>

                                <!-- Premium Amount -->
                                <div class="mb-3">
                                    <label class="form-label">Premium Amount</label>
                                    <input type="number" step="0.01" name="premium_amount" class="form-control"
                                        required>
                                    <small class="text-muted">Policy premium amount</small>
                                </div>

                                <!-- Coverage Details -->
                                <div class="mb-3">
                                    <label class="form-label">Coverage Details</label>
                                    <textarea name="coverage_details" class="form-control" rows="3" required></textarea>
                                    <small class="text-muted">Policy coverage description</small>
                                </div>

                                <!-- Start Date & End Date -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" name="start_date" class="form-control" required>
                                        <small class="text-muted">Policy start date</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" name="end_date" class="form-control" required>
                                        <small class="text-muted">Policy expiration date</small>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="active">Active</option>
                                        <option value="expired">Expired</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                    <small class="text-muted">Policy status</small>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary">Save Policy</button>
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
            let user = JSON.parse(localStorage.getItem("user"));
            console.log(user);
            if (user) {
                $("#user_id").val(user.id);
            }

            $('#addPolicy').validate({
                rules: {
                    policy_number: {
                        required: true
                    },
                    policy_type: {
                        required: true
                    },
                    premium_amount: {
                        required: true,
                        number: true
                    },
                    coverage_details: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    end_date: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    policy_number: {
                        required: "Please enter a policy number."
                    },
                    policy_type: {
                        required: "Please select a policy type."
                    },
                    premium_amount: {
                        required: "Enter the premium amount."
                    },
                    coverage_details: {
                        required: "Provide coverage details."
                    },
                    start_date: {
                        required: "Select a start date."
                    },
                    end_date: {
                        required: "Select an end date."
                    },
                    status: {
                        required: "Select the policy status."
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
                        url: "/api/store-policy",
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
                                toastr.success("Policy added successfully!", "Success", {
                                    closeButton: true,
                                    progressBar: true,
                                    timeOut: 3000
                                });

                                setTimeout(function() {
                                    window.location.href =
                                    '/policy';
                                }, 3000);
                            } else {
                                toastr.error("Error adding policy. Please try again.",
                                    "Error", {
                                        closeButton: true,
                                        progressBar: true
                                    });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, messages) {
                                    messages.forEach(function(message) {
                                        toastr.error(message,
                                            "Validation Error", {
                                                closeButton: true,
                                                progressBar: true
                                            });
                                    });
                                });
                            } else {
                                toastr.error("Something went wrong!", "Error", {
                                    closeButton: true,
                                    progressBar: true
                                });
                            }
                            console.error(xhr.responseText);
                        }
                    });

                }
            });
        });
    </script>
@endsection
