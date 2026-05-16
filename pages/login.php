<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Learner Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        body { font-family: 'Roboto', sans-serif; }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-white text-gray-800 items-center">
    <header class="border-b border-gray-200 w-full">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-baseline">
                    <span class="text-2xl font-bold text-[#bf2126]">Dep</span>
                    <span class="text-2xl font-bold text-[#21438b]">ED</span>
                    <span class="ml-4 text-xl text-gray-600 font-light">Single Sign On</span>
                </div>
                <a href="#" class="text-sm text-gray-600 hover:text-gray-800">Help</a>
            </div>
        </div>
    </header>

    <main class="flex-grow w-full flex flex-col items-center justify-start pt-20 px-4">
        <div class="w-full max-w-sm">
            <h1 class="text-3xl font-light text-gray-700 mb-6">Please sign in</h1>
            <?php if (isset($loginError)): ?>
                <p class="bg-red-100 text-red-700 p-3 rounded-md mb-4 text-sm"><?php echo $loginError; ?></p>
            <?php endif; ?>
            <form method="POST" action="index.php">
                <div class="mb-3">
                    <input
                        type="email"
                        name="email"
                        required
                        class="w-full px-3 py-2 border border-[#BEDAEC] rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-[#EAF3FA] text-gray-700"
                        placeholder="Email"
                    />
                </div>
                <div class="mb-4">
                    <input
                        type="password"
                        name="password"
                        required
                        class="w-full px-3 py-2 border border-[#BEDAEC] rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-[#EAF3FA] text-gray-700"
                        placeholder="Password"
                    />
                </div>
                <button
                    type="submit"
                    name="login"
                    class="px-5 py-2 bg-[#337ab7] text-white rounded-md hover:bg-[#286090] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#286090] transition-colors font-medium"
                >
                    Sign in
                </button>
            </form>
        </div>

        <div class="w-full max-w-sm mt-8 bg-[#f5f5f5] border border-[#e3e3e3] p-5 rounded-md">
            <h2 class="text-lg font-medium text-gray-700 mb-2">Forgot password?</h2>
            <p class="text-sm text-gray-600 leading-relaxed">
                For class advisers, request School Head or designated school system administrator to reset password. For school heads, request Division Planning Officer to reset password.
            </p>
        </div>
    </main>

    <footer class="bg-gray-100 border-t border-gray-200 w-full mt-auto">
        <div class="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
            Department of Education
        </div>
    </footer>
</body>
</html>