<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepEd Portal - Account</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        body { font-family: 'Roboto', sans-serif; background-color: #fcfcfc; }
        .deped-box { border: 1px solid #e3e3e3; border-radius: 4px; background: white; margin-bottom: 20px; }
        .deped-box-header { background-color: #f5f5f5; border-bottom: 1px solid #e3e3e3; padding: 10px 15px; font-size: 14px; font-weight: 500; color: #333; }
        .deped-box-content { padding: 20px; font-size: 14px; }
        .btn-green { background-color: #5cb85c; color: white; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .btn-blue { background-color: #337ab7; color: white; padding: 8px 12px; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Top Header -->
    <header class="bg-white border-b border-gray-200 px-8 h-12 flex justify-between items-center text-xs text-gray-600">
        <div class="flex items-center">
             <div class="flex items-baseline mr-2">
                <span class="text-xl font-bold text-[#bf2126]">Dep</span>
                <span class="text-xl font-bold text-[#21438b]">ED</span>
            </div>
            <span class="text-gray-500 font-light ml-2">Account</span>
        </div>
        <div class="flex items-center space-x-6">
            <button class="flex items-center hover:text-gray-900">
                <span class="uppercase">JUAN DELA CRUZ</span>
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <a href="#" class="hover:underline">Help</a>
            <a href="index.php?page=logout" class="hover:underline">Sign out</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto w-full pt-8 px-4 flex-grow">
        <!-- Identity Section -->
        <div class="flex justify-between items-start mb-8">
            <div>
                <button class="bg-white border border-gray-300 rounded px-3 py-1.5 text-xs flex items-center shadow-sm">
                    301218_juandelacruz <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
            <div class="text-right">
                <div class="text-sm font-medium text-gray-700">301218 - Tanza National High School</div>
                <div class="text-xs text-gray-500 uppercase">SECONDARY, TANZA</div>
                <div class="mt-1">
                    <span class="bg-gray-700 text-white text-[10px] px-2 py-0.5 font-bold rounded">SCHOOL PERSONNEL</span>
                </div>
            </div>
        </div>

        <!-- Tab Bar -->
        <div class="mb-4">
            <div class="bg-[#f5f5f5] border border-gray-200 inline-block px-4 py-2 text-sm font-medium text-gray-700 border-b-0 rounded-t">
                My Account
            </div>
            <div class="border-t border-gray-200 -mt-px w-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column: Profile -->
            <div class="deped-box">
                <div class="deped-box-header flex justify-between items-center">
                    <span>Profile</span>
                    <button class="btn-green flex items-center">
                         <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                         View Detailed
                    </button>
                </div>
                <div class="deped-box-content">
                    <div class="flex items-center text-sm">
                        <span class="w-32 text-right font-bold text-gray-600 mr-4">Full name</span>
                        <span class="text-gray-800">Juan dela Cruz</span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Apps and Security -->
            <div>
                <!-- DepEd Apps -->
                <div class="deped-box">
                    <div class="deped-box-header">DepEd Apps</div>
                    <div class="deped-box-content p-0">
                        <ul class="text-blue-600 text-sm">
                            <li class="border-b px-4 py-2.5 hover:bg-gray-50"><a href="index.php?page=dashboard" class="hover:underline">Learner Information System</a></li>
                            <li class="border-b px-4 py-2.5 hover:bg-gray-50"><a href="#" class="hover:underline">Enhanced Basic Education Information System</a></li>
                            <li class="border-b px-4 py-2.5 hover:bg-gray-50"><a href="#" class="hover:underline">Basic Education Information System</a></li>
                            <li class="border-b px-4 py-2.5 hover:bg-gray-50"><a href="#" class="hover:underline">Bayanihan 2 Basic Education System</a></li>
                            <li class="border-b px-4 py-2.5 hover:bg-gray-50"><a href="#" class="hover:underline">National School Building Inventory System</a></li>
                            <li class="border-b px-4 py-2.5 hover:bg-gray-50"><a href="#" class="hover:underline">National Achievement Test Integration System</a></li>
                            <li class="border-b px-4 py-2.5 hover:bg-gray-50"><a href="#" class="hover:underline">WASH in Schools Online Monitoring System</a></li>
                            <li class="px-4 py-2.5 hover:bg-gray-50"><a href="#" class="hover:underline">DCP Portal</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Security -->
                <div class="deped-box">
                    <div class="deped-box-header">Security</div>
                    <div class="deped-box-content">
                        <div class="flex mb-4">
                            <button class="btn-blue mr-4">Password</button>
                            <button class="text-blue-600 hover:underline">Username</button>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Old password <span class="text-red-500">*</span></label>
                                <input type="password" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">New Password <span class="text-red-500">*</span></label>
                                <input type="password" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-1">Repeat Password <span class="text-red-500">*</span></label>
                                <input type="password" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                            </div>
                            <button class="bg-white border border-gray-300 rounded px-4 py-1.5 text-xs text-gray-700 hover:bg-gray-50 shadow-sm">Change Password</button>
                        </div>
                    </div>
                </div>

                <!-- Connected Accounts -->
                <div class="deped-box">
                    <div class="deped-box-header">Connected Accounts</div>
                    <div class="deped-box-content text-xs text-gray-500">
                        No connected account. Choose from one of the providers below.
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-8 text-center text-xs text-gray-400 border-t border-gray-100 bg-white">
        Department of Education
    </footer>
</body>
</html>