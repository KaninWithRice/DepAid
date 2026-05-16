<?php include 'includes/header.php'; ?>

<main class="flex-grow py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="index.php?page=masterlist" class="flex space-x-2 mb-4">
            <select name="selected_class" onchange="this.form.submit()" class="bg-white border border-gray-300 px-3 py-2 rounded-md text-sm shadow-sm hover:bg-gray-50 w-80">
                <?php foreach ($classOptions as $option): ?>
                    <option value="<?php echo $option; ?>" <?php echo $selectedClass === $option ? 'selected' : ''; ?>>
                        <?php echo $option; ?> (SY 2025–2026)
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" name="set_class" value="1">
            <button type="button" class="bg-white border border-gray-300 px-4 py-1.5 rounded-md text-sm flex items-center shadow-sm hover:bg-gray-50">
                Select Tagging 
                <svg class="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>
        </form>

        <div class="bg-white border border-gray-200 shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-light text-gray-700">Masterlist</h2>
                <a href="index.php?page=enrolment" class="bg-[#5cb85c] text-white px-4 py-2 rounded-md hover:bg-[#4cae4c] font-medium text-sm">Enrol Learner</a>
            </div>

            <!-- Overview Box (Matching 100% Zoom) -->
            <div class="border rounded-sm mb-6">
                <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Overview</h3>
                <div class="p-4">
                    <div class="flex justify-between items-center text-sm p-3 bg-gray-50 rounded-sm">
                        <span><strong class="font-semibold">Adviser</strong> DELA CRUZ, JUAN</span>
                        <span class="text-gray-600"><?php echo $selectedClass; ?> / SY 2025-2026</span>
                    </div>
                    <div class="mt-4 bg-[#fcf8e3] border border-[#faebcc] text-[#8a6d3b] p-4 rounded-sm text-sm">
                        <p><strong class="font-semibold">Warning</strong> The following requires immediate attention.</p>
                        <p class="mt-1">2 pending enrolment</p>
                    </div>
                </div>
            </div>

            <!-- Summary Section (Matching 100% Zoom) -->
            <div class="border rounded-sm mb-6">
                <h3 class="text-lg font-normal text-gray-800 mb-4 px-4 pt-4">Summary</h3>
                <div class="flex px-4 pb-4">
                    <!-- No of learners -->
                    <div class="w-1/4 text-center border-r pr-8">
                        <p class="font-semibold text-gray-800">No of learners</p>
                        <p class="text-6xl font-light my-2 text-gray-800"><?php echo count($learners); ?></p>
                        <div class="flex justify-center space-x-6 text-sm">
                            <?php
                            $males = array_filter($learners, fn($l) => (substr($l['gender'] ?? 'M', 0, 1) === 'M'));
                            $females = array_filter($learners, fn($l) => (substr($l['gender'] ?? 'F', 0, 1) === 'F'));
                            ?>
                            <div><span class="text-gray-600">Male</span> <p class="font-semibold text-gray-800"><?php echo count($males); ?></p></div>
                            <div><span class="text-gray-600">Female</span> <p class="font-semibold text-gray-800"><?php echo count($females); ?></p></div>
                        </div>
                    </div>
                    <!-- Stats Tables -->
                    <div class="w-3/4 flex pl-8 text-sm">
                         <table class="w-1/2">
                            <thead>
                                <tr class="text-left text-gray-500 text-xs">
                                    <th></th><th class="text-center">Male</th><th class="text-center">Female</th><th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b"><td class="py-1">Transfer-in</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">0</td></tr>
                                <tr class="border-b"><td class="py-1">Balik-aral</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">0</td></tr>
                                <tr><td class="py-1">Repeater</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">0</td></tr>
                            </tbody>
                        </table>
                        <table class="w-1/2 ml-8">
                            <thead>
                                <tr class="text-left text-gray-500 text-xs">
                                    <th></th><th class="text-center">Male</th><th class="text-center">Female</th><th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b"><td class="py-1 text-blue-600 border-b border-dotted border-blue-600">CCT Recipient</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">0</td></tr>
                                <tr class="border-b"><td class="py-1 text-blue-600 border-b border-dotted border-blue-600">ALIVE</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">0</td></tr>
                                <tr><td class="py-1 text-blue-600 border-b border-dotted border-blue-600">ADM</td><td class="text-center">0</td><td class="text-center">0</td><td class="text-center">0</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mt-4 border-t px-4">
                    <div class="flex justify-between items-center text-sm py-2.5 border-b"><span>Transferred out</span><span class="bg-gray-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">0</span></div>
                    <div class="flex justify-between items-center text-sm py-2.5 border-b"><span>Dropped out</span><span class="bg-gray-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">0</span></div>
                    <div class="flex justify-between items-center text-sm py-2.5"><span>No longer in school</span><span class="bg-gray-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs">0</span></div>
                </div>
            </div>

            <!-- Enrolment Table (Matching 50% Zoom) -->
            <div class="border rounded-sm">
                <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Enrolment</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-800">
                        <thead class="bg-gray-50 font-semibold border-b">
                            <tr>
                                <th class="p-3 w-10">#</th>
                                <th class="p-3">Learner</th>
                                <th class="p-3">Gender</th>
                                <th class="p-3">Date of First Attendance</th>
                                <th class="p-3">Grading</th>
                                <th class="p-3">Status</th>
                                <th class="p-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($learners as $index => $learner): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3"><?php echo $index + 1; ?></td>
                                    <td class="p-3">
                                        <div class="font-medium text-gray-900"><?php echo $learner['lrn']; ?></div>
                                        <div class="text-xs text-gray-600 uppercase"><?php echo "{$learner['lastName']}, {$learner['firstName']} " . ($learner['middleName'] ?? ''); ?></div>
                                    </td>
                                    <td class="p-3"><?php echo (substr($learner['gender'] ?? 'M', 0, 1) === 'F') ? 'F' : 'M'; ?></td>
                                    <td class="p-3">06/16/25</td>
                                    <td class="p-3 text-center">
                                        <button class="text-gray-400 hover:text-blue-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z" /></svg>
                                        </button>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center space-x-4 text-xs">
                                            <button class="flex items-center text-blue-600 hover:text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                Report
                                            </button>
                                            <button class="flex items-center text-gray-500 hover:text-blue-600">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z" /></svg>
                                                No status
                                            </button>
                                        </div>
                                    </td>
                                    <td class="p-3 text-right">
                                        <button class="bg-white border border-gray-300 px-4 py-1 rounded-md text-xs shadow-sm hover:bg-gray-50 font-semibold text-gray-700">Profile</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>