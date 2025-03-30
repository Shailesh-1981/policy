<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form id="loginForm" method="POST">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     $("#loginForm").submit(function(event) {
        //         event.preventDefault(); // Prevent default form submission
        //         let email = $("#email").val();
        //         let password = $("#password").val();
        //         let csrfToken = $('meta[name="csrf-token"]').attr(
        //         "content"); // Get CSRF token from meta tag

        //         $.ajax({
        //             url: "{{ route('login') }}",
        //             type: "POST",
        //             dataType: "json",
        //             headers: {
        //                 "X-CSRF-TOKEN": csrfToken, // Include CSRF token in headers
        //                 "Accept": "application/json"
        //             },
        //             data: {
        //                 email: email,
        //                 password: password,
        //                 _token: csrfToken // Include CSRF token in data as well
        //             },
        //             success: function(response) {
        //                 console.log("Login Success:", response);

        //                 if (response.status == 200) {
        //                     // Remove old token before storing a new one
        //                     localStorage.removeItem("jwt_token");
        //                     localStorage.removeItem("user");

        //                     // Store new token and user data
        //                     localStorage.setItem("jwt_token", response.token);
        //                     localStorage.setItem("user", JSON.stringify(response.user));

        //                     // Redirect to dashboard
        //                     window.location.href = "/dashboard";
        //                 } else {
        //                     $("#errorMessage").text(
        //                         "Login failed. Please check your credentials.");
        //                 }
        //             },
        //             error: function(xhr) {
        //                 console.error("Login Error:", xhr);
        //                 let errorMsg = xhr.responseJSON?.message ||
        //                 "Invalid login credentials.";
        //                 $("#errorMessage").text(errorMsg);
        //             }
        //         });
        //     });


        // });
        // $("#loginForm").submit(function(event) {
        //     event.preventDefault(); // Prevent default form submission

        //     let email = $("#email").val();
        //     let password = $("#password").val();
        //     let csrfToken = $('input[name="_token"]').val(); // Get CSRF token from form

        //     $.ajax({
        //         url: "{{ route('login') }}",
        //         type: "POST",
        //         dataType: "json",
        //         headers: {
        //             "X-CSRF-TOKEN": csrfToken, // Include CSRF token
        //             "Accept": "application/json"
        //         },
        //         data: {
        //             email: email,
        //             password: password,
        //         },
        //         success: function(response) {
        //             console.log("Login Success:", response);

        //             if (response.status == 200) {
        //                 // ✅ First, remove the old token
        //                 localStorage.removeItem("jwt_token");
        //                 localStorage.removeItem("user");

        //                 // ✅ Then, store the new token
        //                 localStorage.setItem("jwt_token", response.token);
        //                 localStorage.setItem("user", JSON.stringify(response.user));

        //                 window.location.href = "/dashboard"; // Redirect after login
        //             } else {
        //                 $("#errorMessage").text("Login failed. Please check your credentials.");
        //             }
        //         },
        //         error: function(xhr) {
        //             console.error("Login Error:", xhr);
        //             let errorMsg = xhr.responseJSON?.message || "Invalid login credentials.";
        //             $("#errorMessage").text(errorMsg);
        //         }
        //     });
        // });
        $("#loginForm").submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            let email = $("#email").val();
            let password = $("#password").val();
            let csrfToken = $('input[name="_token"]').val(); // Get CSRF token from form

            // ✅ Clear entire localStorage before making the request
            localStorage.clear();

            $.ajax({
                url: "{{ route('login') }}",
                type: "POST",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": csrfToken, // Include CSRF token
                    "Accept": "application/json"
                },
                data: {
                    email: email,
                    password: password,
                },
                success: function(response) {
                    console.log("Login Success:", response);

                    if (response.status == 200 && response.token) {
                        // ✅ First, clear local storage to remove old data
                        localStorage.clear();

                        // ✅ Then, store the new token and user data
                        localStorage.setItem("jwt_token", response.token);
                        localStorage.setItem("user", JSON.stringify(response.user));

                        // ✅ Retrieve token to verify it's stored correctly
                        let storedToken = localStorage.getItem("jwt_token");
                        console.log(localStorage.getItem("jwt_token")); // Should log the correct JWT token

                        console.log("Stored Token:",
                        storedToken); // Now this should log the correct token

                        window.location.href = "/dashboard";
                    } else {
                        $("#errorMessage").text("Login failed. Please check your credentials.");
                    }
                },
                // success: function(response) {
                //     console.log("Login Success:", response);

                //     if (response.status == 200) {
                //         // ✅ Store the new token and user data
                //         var token = localStorage.setItem("jwt_token", response.token);
                //         console.log(token);
                //         localStorage.setItem("user", JSON.stringify(response.user));

                //         window.location.href = "/dashboard"; // Redirect after login
                //     } else {
                //         $("#errorMessage").text("Login failed. Please check your credentials.");
                //     }
                // },
                // error: function(xhr) {
                //     console.error("Login Error:", xhr);
                //     let errorMsg = xhr.responseJSON?.message || "Invalid login credentials.";
                //     $("#errorMessage").text(errorMsg);
                // }
            });
        });
    </script>
</x-guest-layout>
