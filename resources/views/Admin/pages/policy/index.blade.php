@extends('admin.layouts.master')

@section('content')
    <main class="container mt-4">

        <div class="row">


            <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="fw-bold">Policy Management</h1>
                    <a href="{{ route('add-policy') }}" class="btn btn-success">+ Add Policy</a>
                </div>
                <div class="container mt-4">
                    <div class="d-flex justify-content-end gap-2">

                        <div class="d-flex justify-content-end">
                            <input type="text" class="form-control w-200" placeholder="Search Policies..."
                                oninput="searchPolicy(this)">
                        </div>
                    </div>
                </div>

                <!-- Table to Display Data -->
                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Policy List</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr no</th>
                                    <th>Policy Number</th>
                                    {{-- <th>Type</th> --}}
                                    <th>Premium Amount</th>
                                    {{-- <th>Coverage Details</th> --}}
                                    <th>Start Date</th>
                                    {{-- <th>End Date</th> --}}
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="searchContainer">
                                @foreach ($data as $datas)
                                    <tr>
                                        <td>{{ $loop->iteration ?? '' }}</td>
                                        <td>{{ $datas->policy_number ?? '' }}</td>
                                        {{-- <td>{{ $datas->type ?? '' }}</td> --}}
                                        <td>{{ $datas->premium_amount ?? '' }}</td>
                                        {{-- <td>{{ $datas->coverage_details ?? '' }}</td> --}}
                                        <td>{{ $datas->start_date ?? '' }}</td>
                                        {{-- <td>{{ $datas->end_date ?? '' }}</td> --}}
                                        <td>{{ $datas->status ?? '' }}</td>
                                        <td>
                                            {{-- <a href="#" class="btn btn-sm btn-info">View</a> --}}
                                            <button class="btn btn-primary view-policy" data-id="{{ $datas->id }}">View
                                                Policy</button>

                                            <a href="{{ route('policy.show', ['id' => $datas->id]) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            {{-- <button type="button" class="btn btn-sm btn-danger">Delete</button> --}}
                                            <!-- Delete Button -->
                                            {{-- <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $datas->id }}"
                                                data-name="{{ $datas->policy_number }}">
                                                Delete
                                            </button> --}}

                                            <button class="btn btn-danger deleteBtn" data-id="{{ $datas->id }}"
                                                data-name="{{ $datas->policy_number }}">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: flex-end; gap: 10px; list-style: none;">
                            @for($i = 1; $i <= $count; $i++)
                                <li>
                                    <a class="btn btn-primary" href="javascript:void(0);" onclick="searchPolicyPagination({{ $i }})">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            Are you sure you want to delete the policy "<span id="policyName"></span>"?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel">Policy Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="policyForm">
                            <div class="mb-3">
                                <label for="policyId" class="form-label">Policy Number</label>
                                <textarea type="text" class="form-control" id="policyNumber" readonly></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="policyName" class="form-label">Policy Type</label>
                                <textarea type="text" class="form-control" id="policyType" readonly><textarea>
                            </div>
                            <div class="mb-3">
                                <label for="policyDescription" class="form-label">Premium Amount</label>
                                <textarea class="form-control" id="policyPremium" rows="3" readonly></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="policyDescription" class="form-label">Coverage Details</label>
                                <textarea class="form-control" id="policyCoverage" rows="3" readonly></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <textarea type="date" id="policyStart" class="form-control" required></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <textarea class="form-control" id="policyEnd" rows="3" readonly></textarea>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="policyDescription" class="form-label">Status</label>
                                <textarea class="form-control" id="policyStatus" rows="3" readonly></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('scripts')
    <script>
        $('#deleteModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var policyId = button.data('id');
            var policyName = button.data('name');
            var modal = $(this);
            var form = modal.find('#deleteForm');

            modal.find('#policyName').text(policyName);
            form.attr('action', '/policies/' + policyId);
        });

        $(document).ready(function() {
            let deleteUrl = '';
            let token = localStorage.getItem("jwt_token");


            $(document).on('click', '.deleteBtn', function() {
                let policyId = $(this).data('id');
                let policyName = $(this).data('name');

                $('#policyName').text(policyName);
                deleteUrl = `/api/destroy-policy/${policyId}`;
                $('#deleteModal').modal('show');
            });

            $('#deleteForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: deleteUrl,
                    type: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteModal').modal('hide'); // Hide modal
                        location.reload(); // Refresh the page or update UI dynamically
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Failed to delete policy. Please try again.');
                    }
                });
            });
        });

        $(document).on("click", ".view-policy", function() {
            let policyId = $(this).data("id");
            let token = localStorage.getItem("jwt_token"); // Retrieve JWT token from local storage
            console.log(token);
            $.ajax({
                url: `/api/view-policies/${policyId}`, // API call with ID
                type: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`, // Pass JWT Token
                    "Content-Type": "application/json"
                },
                success: function(policy) {
                    console.log(policy.data);
                    $("#policyNumber").val(policy.data.policy_number);
                    $("#policyType").val(policy.data.type);
                    $("#policyPremium").val(policy.data.premium_amount);
                    $("#policyCoverage").val(policy.data.coverage_details);
                    $("#policyStart").val(policy.data.start_date);
                    $("#policyEnd").val(policy.data.end_date);
                    $("#policyStatus").val(policy.data.status);

                    // Show the modal
                    $("#viewModal").modal("show");
                },
                error: function(xhr) {
                    console.error("Error fetching policy:", xhr.responseText);
                    alert("Unauthorized or Error Fetching Data!");
                }
            });
        });

        function searchPolicy(element) {
            let searchValue = element.value.trim();
            console.log("Search Value:", searchValue); // Debugging

            if (searchValue.length === 0) {
                loadAllPolicies();
                return;
            }

            if (searchValue.length < 2) return;

            // ✅ Retrieve token from localStorage
            let token = localStorage.getItem("jwt_token");

            if (!token) {
                console.error("Token is missing! User might not be authenticated.");
                return;
            }

            $.ajax({
                url: "/api/search",
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
                data: {
                    query: searchValue
                },
                success: function(response) {
                    console.log("AJAX Success:", response);
                    let policies = response.data;

                    $("#searchContainer").html('');

                    if (!policies || policies.length === 0) {
                        $("#searchContainer").html(
                            "<tr><td colspan='6' class='text-center'>No matching policies found</td></tr>"
                        );
                        return;
                    }

                    policies.forEach((policy, index) => {
                        $("#searchContainer").append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${policy.policy_number ?? ''}</td>
                        <td>${policy.premium_amount ?? ''}</td>
                        <td>${policy.start_date ?? ''}</td>
                        <td>${policy.status ?? ''}</td>
                        <td>
                            <button class="btn btn-primary view-policy" data-id="${policy.id}">View Policy</button>
                            <a href="/policy/${policy.id}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-danger deleteBtn" data-id="${policy.id}" data-name="${policy.policy_number}">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);
                    });
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.status, xhr.statusText, xhr.responseText);
                    $("#searchContainer").html(
                        "<tr><td colspan='6' class='text-center text-danger'>Error fetching data</td></tr>"
                    );
                }
            });
        }

        function searchPolicyPagination(skipValue) {
            console.log("Skip Value:", skipValue); // Debugging

            // ✅ Retrieve token from localStorage
            let token = localStorage.getItem("jwt_token");

            if (!token) {
                console.error("Token is missing! User might not be authenticated.");
                return;
            }

            $.ajax({
                url: "/api/search/pagination",
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
                data: {
                    skip: (skipValue - 1) * 5 // Adjusting pagination logic
                },
                success: function(response) {
                    console.log("AJAX Success:", response); // Debugging
                    let policies = response.data;

                    $("#searchContainer").html('');

                    if (!policies || policies.length === 0) {
                        $("#searchContainer").html(
                            "<tr><td colspan='6' class='text-center'>No matching policies found</td></tr>"
                        );
                        return;
                    }

                    policies.forEach((policy, index) => {
                        $("#searchContainer").append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${policy.policy_number ?? ''}</td>
                        <td>${policy.premium_amount ?? ''}</td>
                        <td>${policy.start_date ?? ''}</td>
                        <td>${policy.status ?? ''}</td>
                        <td>
                            <button class="btn btn-primary view-policy" data-id="${policy.id}">View Policy</button>
                            <a href="/policy/${policy.id}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-danger deleteBtn" data-id="${policy.id}" data-name="${policy.policy_number}">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);
                    });
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.status, xhr.statusText, xhr.responseText);
                    $("#searchContainer").html(
                        "<tr><td colspan='6' class='text-center text-danger'>Error fetching data</td></tr>"
                    );
                }
            });
        }
    </script>
@endsection
