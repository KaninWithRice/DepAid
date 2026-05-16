<?php include 'includes/header.php'; ?>

<?php
if (!$learner) {
    header('Location: index.php?page=search');
    exit();
}
?>

<main class="flex-grow py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
         <div class="bg-white border border-gray-200 shadow-sm p-6">
            <div class="text-sm text-gray-500 mb-8">
                <a href="index.php?page=masterlist" class="text-blue-600 hover:underline">Masterlist</a>
                <span class="mx-2">/</span>
                <a href="index.php?page=masterlist" class="text-blue-600 hover:underline"><?php echo $selectedClass; ?></a>
                <span class="mx-2">/</span>
                <span>Enrolment</span>
            </div>

            <div class="flex space-x-6">
                <!-- Learner Profile -->
                <div class="w-1/2 border rounded-sm p-4">
                     <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Learner <?php echo $learner['lrn']; ?></h3>
                     <dl class="grid grid-cols-3 gap-x-4 gap-y-3 text-sm">
                        <dt class="font-bold text-gray-700 col-span-1 text-right">Last name</dt>
                        <dd class="text-gray-800 col-span-2"><?php echo $learner['lastName']; ?></dd>
                        <dt class="font-bold text-gray-700 col-span-1 text-right">First name</dt>
                        <dd class="text-gray-800 col-span-2"><?php echo $learner['firstName']; ?></dd>
                        <dt class="font-bold text-gray-700 col-span-1 text-right">Middle name</dt>
                        <dd class="text-gray-800 col-span-2"><?php echo $learner['middleName']; ?></dd>
                        <dt class="font-bold text-gray-700 col-span-1 text-right">Birthdate</dt>
                        <dd class="text-gray-800 col-span-2"><?php echo str_replace('/', '-', $learner['birthdate']); ?></dd>
                        <dt class="font-bold text-gray-700 col-span-1 text-right">Gender</dt>
                        <dd class="text-gray-800 col-span-2"><?php echo $learner['gender'] === 'Female' ? 'F' : 'M'; ?></dd>
                     </dl>
                </div>
                <!-- Enrolment Form -->
                <div class="w-1/2 border rounded-sm p-4">
                    <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Enrolment</h3>
                     <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Date of First Attendance</label>
                        <div class="flex space-x-2">
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm">
                                <option>July</option>
                            </select>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm">
                                <option>02</option>
                            </select>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm">
                                <option>2025</option>
                            </select>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">The date of learner's first day of attendance in class or learning session.</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center mt-6 border-t pt-4">
                <a href="index.php?page=search" class="bg-gray-50 border border-gray-300 px-4 py-1.5 rounded-sm text-sm shadow-sm hover:bg-gray-200">Cancel</a>
                <a href="index.php?page=detailed_enrolment" class="px-4 py-1.5 bg-[#337ab7] text-white border border-[#2e6da4] rounded-sm hover:bg-[#286090] text-sm">Continue</a>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>