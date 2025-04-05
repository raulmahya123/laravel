<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="icon" href="{{ asset('assets/logo/Logo White BG@2x.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Add Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        @tailwind base;
        @tailwind components;
        @tailwind utilities;

        /* Apply Poppins font globally */
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="h-screen bg-gray-100 font-sans">
    <div class="grid grid-cols-1 md:grid-cols-2 h-full">
        <!-- Logo -->

        <!-- Form Section -->
        <div class="flex items-center justify-center bg-white">
            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Create Your Account</h2>
                <form method="POST" action="{{ route('register') }}" class="form-container">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Name')" style="color: black;" />
                        <x-text-input id="name" class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="text-red-500 text-sm mt-1" />
                    </div>

                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Email')" style="color: black;" />
                        <x-text-input id="email" class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            type="email" name="email" :value="old('email')" required autocomplete="email" />
                        <x-input-error :messages="$errors->get('email')" class="text-red-500 text-sm mt-1" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" style="color: black;" />
                        <x-text-input id="password" class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            type="password" name="password" required minlength="8" autocomplete="new-password" oninput="checkPasswordStrength()" />
                        <x-input-error :messages="$errors->get('password')" class="text-red-500 text-sm mt-1" />
                    </div>

                    <!-- Password Strength -->
                    <div id="password-strength" class="text-sm mt-1 text-gray-500"></div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" style="color: black;" />
                        <x-text-input id="password_confirmation" class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            type="password" name="password_confirmation" required autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="text-red-500 text-sm mt-1" />
                    </div>

                    <!-- Register Button -->
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm mt-5">
                        {{ __('Register') }}
                    </button>

                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">Already have an account?
                        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Log In</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Image Section -->
        <div
            class="hidden md:flex flex-col items-center justify-center bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-center px-8 py-12 rounded-tl-3xl rounded-bl-3xl">
            <img src="{{ asset('assets/icon/undraw_register.png') }}" alt="Illustration" class="w-3/4 mb-6">
        </div>
    </div>

    <script>
        // Password strength checking
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthDisplay = document.getElementById('password-strength');
            let strength = 'Weak';
            let color = 'red';

            if (password.length >= 8) {
                if (/[a-z]/.test(password) && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^a-zA-Z0-9]/.test(password)) {
                    strength = 'Very Strong';
                    color = 'green';
                } else if (/[a-zA-Z]/.test(password) && /[0-9]/.test(password)) {
                    strength = 'Strong';
                    color = 'orange';
                }
            }

            strengthDisplay.textContent = `Password Strength: ${strength}`;
            strengthDisplay.style.color = color;
        }
    </script>
</body>

</html>
