<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LIS - Learner Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        body { font-family: 'Roboto', sans-serif; }
        /* Select2 Tailwind styling overrides */
        .select2-container--default .select2-selection--single {
            height: 32px;
            border: 1px solid #d1d5db;
            border-radius: 0.125rem;
            padding-top: 1px;
            font-size: 0.8rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 30px;
        }
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.125rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            font-size: 0.8rem;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db;
            border-radius: 0.125rem;
            padding: 4px 8px;
            font-size: 0.8rem;
        }
        .select2-results__option {
            padding: 4px 8px;
        }
    </style>
</head>
<body class="bg-[#f5f5f5] min-h-screen flex flex-col font-sans text-gray-800">
    <header class="bg-white shadow-sm w-full sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="flex items-baseline">
                        <span class="text-2xl font-bold text-[#bf2126]">Dep</span>
                        <span class="text-2xl font-bold text-[#21438b]">ED</span>
                    </div>
                    <span class="text-xl text-gray-700">Learner Information System</span>
                </div>
                <div class="flex items-center space-x-6 text-sm text-gray-600">
                    <button class="flex items-center hover:text-gray-800">
                        <span><?php echo $_SESSION['email']; ?></span>
                        <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <a href="#" class="hover:text-gray-800">Help</a>
                    <a href="index.php?page=logout" class="hover:text-gray-800">Sign out</a>
                </div>
            </div>
        </div>
    </header>

    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end py-4">
                <div>
                    <?php 
                        $displayPage = ucfirst($page);
                        if (strpos($page, 'enrolment') !== false) {
                            $displayPage = 'Enrolment';
                        }
                    ?>
                    <h1 class="text-2xl font-light text-gray-700"><?php echo $displayPage; ?></h1>
                    <a href="#" class="text-blue-600 hover:underline text-sm">305460 - Taguig National High School</a>
                </div>
                <nav class="flex space-x-8 text-sm font-medium text-gray-500">
                    <a href="index.php?page=dashboard" class="<?php echo $page === 'dashboard' ? 'text-gray-900 border-b-2 border-red-500 pb-1' : 'hover:text-gray-900'; ?>">Dashboard</a>
                    <a href="index.php?page=masterlist" class="<?php echo $page === 'masterlist' ? 'text-gray-900 border-b-2 border-red-500 pb-1' : 'hover:text-gray-900'; ?>">Masterlist</a>
                    <a href="#" class="hover:text-gray-900">School Forms</a>
                    <a href="#" class="flex items-center hover:text-gray-900">
                        Data Corrections <span class="ml-1.5 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">86</span>
                    </a>
                    <a href="index.php?page=support" class="<?php echo $page === 'support' ? 'text-gray-900 border-b-2 border-red-500 pb-1' : 'hover:text-gray-900'; ?>">Support</a>
                </nav>
            </div>
        </div>
    </div>
