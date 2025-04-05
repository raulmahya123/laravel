<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" href="{{ asset('assets/logo/Logo White BG@2x.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @tailwind base;
        @tailwind components;
        @tailwind utilities;

        /* Apply Poppins font globally */
        body {
            font-family: 'Poppins', sans-serif;
        }

        @layer utilities {
            .bg-gradient-custom {
                background: linear-gradient(135deg, rgba(47, 69, 150, 1) 0%, rgba(77, 114, 249, 1) 100%);
            }
        }

    </style>
</head>

<body class="h-screen bg-gray-100 font-sans">
    <div class="grid grid-cols-1 md:grid-cols-2 h-full">
        <!-- Logo -->
        <!-- Form Section -->
        <div class="flex items-center justify-center bg-white">
            <div class="w-full max-w-md p-6 bg-white rounded shadow-lg">
                <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Welcome Back!</h2>
                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" style="color: black;" />
                        <x-text-input id="email" class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="text-red-500 text-sm mt-1" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" style="color: black;" />
                        <x-text-input id="password" class="w-full mt-1 px-4 py-2 border rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="text-red-500 text-sm mt-1" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                        <label for="remember_me" class="ml-2 text-sm text-gray-700">{{ __('Remember me') }}</label>
                    </div>

                    <!-- Forgot Password -->
                    @if (Route::has('password.request'))
                        <div class="text-right">
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:underline">Forgot Password?</a>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ __('Log in') }}
                    </button>
                </form>

                <!-- Register Link -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">Don't have an account?
                        <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Sign Up</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Image Section -->
        <div
            class="hidden md:flex flex-col items-center justify-center bg-gradient-custom text-white text-center px-8 py-12 rounded-tl-3xl rounded-bl-3xl">
            <img src="{{ asset('assets/icon/undraw_login.png') }}" alt="Illustration" class="w-3/4 mb-6">
        </div>
    </div>
</body>

</html>
