<?php include 'includes/header.php'; ?>

<main class="flex-grow py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-4">
            <button class="bg-white border border-gray-300 px-4 py-1.5 rounded-md text-sm flex items-center shadow-sm hover:bg-gray-50">
                Explore 
                <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
        </div>

        <div class="bg-white border border-gray-200 shadow-sm">
            <div class="p-6">
                <div class="flex justify-between items-start border-b pb-4 mb-6">
                    <div>
                        <p class="text-sm text-gray-500">Today</p>
                        <p class="text-lg text-gray-800">Oct 23, SY 2025-2026</p>
                    </div>
                    <button class="border border-gray-300 px-3 py-1.5 rounded-md text-sm flex items-center bg-gray-50 shadow-sm hover:bg-gray-100">
                        Oct 23, SY 2025-2026 
                        <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>
                
                <div class="border border-gray-200 max-w-lg">
                    <div class="flex justify-between items-center bg-gray-100 px-4 py-2 border-b">
                        <h2 class="text-md font-semibold text-gray-700">Enrolment</h2>
                        <div class="text-sm">
                            <button class="text-gray-800 border-b-2 border-gray-700 pb-1 mr-4">Overview</button>
                            <button class="text-gray-500 hover:text-gray-800">Summary</button>
                        </div>
                    </div>
                    <div class="bg-white p-8 text-center">
                        <p class="text-md text-gray-600">Total Enrolment</p>
                        <p class="text-6xl font-light text-gray-800 my-4">1,847</p>
                        <div class="flex justify-center space-x-12 text-md text-gray-600">
                            <div>
                                <span>Male</span>
                                <p class="text-xl text-gray-800 font-medium">889</p>
                            </div>
                            <div>
                                <span>Female</span>
                                <p class="text-xl text-gray-800 font-medium">958</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>