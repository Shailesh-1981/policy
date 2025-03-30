@extends('admin.layouts.master')

@section('content')
    <main class="container mt-4">
        <div class="row">


            <!-- Right Side: Add Button & Table -->
            <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="fw-bold">Employe Management</h1>
                    <a href="{{ route('add-employe') }}" class="btn btn-success">+ Add Employe</a>
                </div>

                <div class="container mt-4">
                    <div class="d-flex justify-content-end gap-2">

                        <div class="d-flex justify-content-end">
                            <input type="text" class="form-control w-200" placeholder="Search Policies..."
                                oninput="searchEmploye(this)">
                        </div>

                    </div>
                </div>

                <div class="card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Employe List</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="searchEmploye">
                                @foreach ($user as $users)
                                    <tr>
                                        <td> {{ $users->name ?? '' }}</td>
                                        <td>{{ $users->email ?? '' }}</td>
                                        <td>
                                            @if (isset($users->userRole->role_id))
                                                @if ($users->userRole->role_id == 1)
                                                    Admin
                                                @elseif($users->userRole->role_id == 2)
                                                    Agent
                                                @elseif($users->userRole->role_id == 3)
                                                    Manager
                                                @elseif($users->userRole->role_id == 4)
                                                    User
                                                @else
                                                    Unknown Role
                                                @endif
                                            @else
                                                Unknown Role
                                            @endif
                                        </td>


                                        <td>
                                            <button class="btn btn-primary view-employee"
                                                data-id="{{ $users->id }}">View</button>


                                            <a href="{{ route('employe-show', ['id' => $users->id]) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <button class="btn btn-danger deleteBtns" data-id="{{ $users->id }}"
                                                data-name="{{ $users->name }}">
                                                Delete
                                            </button>

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div style="display: flex; justify-content: flex-end; gap: 10px; list-style: none; margin-top: 10px;">
                            @for ($i = 1; $i <= $count; $i++)
                                <li>
                                    <a class="btn btn-primary" href="javascript:void(0);" onclick="searchEmployePagination({{ $i }})">
                                        {{ $i }}
                                    </a>
                                </li>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="policyName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <form id="deleteForm">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="employeeViewModal" tabindex="-1" role="dialog"
            aria-labelledby="employeeViewModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="employeeViewModalLabel">Employee Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <tr>
                                <th>User Name:</th>
                                <td id="view_user_name"></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td id="view_email"></td>
                            </tr>
                            <tr>
                                <th>User Role:</th>
                                <td id="view_user_type"></td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>



    </main>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            let deleteUrl = '';
            let token = localStorage.getItem("jwt_token");

            $(document).on('click', '.deleteBtns', function() {
                let policyId = $(this).data('id');
                let policyName = $(this).data('name');

                $('#policyName').text(policyName);
                deleteUrl = `/api/destroy-employe/${policyId}`;
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
                        $('#deleteModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert('Failed to delete policy. Please try again.');
                    }
                });
            });
        });

        function searchEmploye(element) {
            let searchValue = element.value.trim();
            console.log("Search Value:", searchValue);

            if (searchValue.length === 0) {
                loadAllEmployees();
                return;
            }

            if (searchValue.length < 2) return;

            let token = localStorage.getItem("jwt_token");

            if (!token) {
                console.error("Token is missing! User might not be authenticated.");
                return;
            }

            $.ajax({
                url: "/api/employe/search",
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
                    let employees = response.data;

                    $("#searchEmploye").html('');

                    if (!employees || employees.length === 0) {
                        $("#searchEmploye").html(
                            "<tr><td colspan='4' class='text-center'>No matching employees found</td></tr>"
                        );
                        return;
                    }

                    employees.forEach((employee) => {
                        let roleName = "Unknown Role";
                        switch (employee.role_id) {
                            case 1:
                                roleName = "Admin";
                                break;
                            case 2:
                                roleName = "Agent";
                                break;
                            case 3:
                                roleName = "Manager";
                                break;
                            case 4:
                                roleName = "User";
                                break;
                        }

                        $("#searchEmploye").append(`
                    <tr>
                        <td>${employee.name ?? ''}</td>
                        <td>${employee.email ?? ''}</td>
                        <td>${roleName}</td>
                        <td>
                                 <button class="btn btn-primary view-employee"
                                                data-id="${employee.id}">View</button>
                            <a href="/employe/${employee.id}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-danger deleteBtns" data-id="${employee.id}" data-name="${employee.name}">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);
                    });
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.status, xhr.statusText, xhr.responseText);
                    $("#searchEmploye").html(
                        "<tr><td colspan='4' class='text-center text-danger'>Error fetching data</td></tr>"
                    );
                }
            });
        }

        $(document).on("click", ".view-employee", function() {
            let employeeId = $(this).data("id");
            let token = localStorage.getItem("jwt_token"); // JWT authentication

            $.ajax({
                url: `/api/view-employe/${employeeId}`,
                type: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Content-Type": "application/json"
                },
                success: function(response) {
                    let employee = response.data;

                    if (employee) {
                        $("#view_user_name").text(employee.name);
                        $("#view_email").text(employee.email);
                        $("#view_user_type").text(getUserRole(employee.user_type));

                        $("#employeeViewModal").modal("show");
                    } else {
                        alert("Employee not found.");
                    }
                },
                error: function(xhr) {
                    alert("Error fetching employee details!");
                }
            });
        });

        function getUserRole(type) {
            switch (type) {
                case 1:
                    return "Admin";
                case 2:
                    return "Agent";
                case 3:
                    return "Manager";
                default:
                    return "Unknown";
            }
        }

        function searchEmployePagination(skipValue) {
            console.log("Skip Value:", skipValue); // Debugging

            let token = localStorage.getItem("jwt_token");

            if (!token) {
                console.error("Token is missing! User might not be authenticated.");
                return;
            }

            $.ajax({
                url: "/api/employe/searchPagination",
                method: "GET",
                headers: {
                    "Authorization": `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
                data: {
                    skip: (skipValue - 1) * 5 // Corrected pagination logic
                },
                success: function(response) {
                    console.log("AJAX Success:", response); // Debugging
                    let employees = response.data;

                    $("#searchEmploye").html('');

                    if (!employees || employees.length === 0) {
                        $("#searchEmploye").html(
                            "<tr><td colspan='4' class='text-center'>No matching employees found</td></tr>"
                        );
                        return;
                    }

                    employees.forEach((employee) => {
                        let roleName = "Unknown Role";
                        if (employee.user_role && employee.user_role.role_id) {
                            switch (employee.user_role.role_id) {
                                case 1:
                                    roleName = "Admin";
                                    break;
                                case 2:
                                    roleName = "Agent";
                                    break;
                                case 3:
                                    roleName = "Manager";
                                    break;
                                case 4:
                                    roleName = "User";
                                    break;
                            }
                        }

                        $("#searchEmploye").append(`
                    <tr>
                        <td>${employee.name ?? ''}</td>
                        <td>${employee.email ?? ''}</td>
                        <td>${roleName}</td>
                        <td>
                            <button class="btn btn-primary view-employee"
                                                data-id="${employee.id}">View</button>
                            <a href="/employe/${employee.id}" class="btn btn-sm btn-warning">Edit</a>
                            <button class="btn btn-danger deleteBtns" data-id="${employee.id}" data-name="${employee.name}">
                                Delete
                            </button>
                        </td>
                    </tr>
                `);
                    });
                },
                error: function(xhr) {
                    console.error("AJAX Error:", xhr.status, xhr.statusText, xhr.responseText);
                    $("#searchEmploye").html(
                        "<tr><td colspan='4' class='text-center text-danger'>Error fetching data</td></tr>"
                    );
                }
            });
        }
    </script>
@endsection
