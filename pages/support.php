<?php include 'includes/header.php'; ?>

<main class="flex-grow py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white border border-gray-200 shadow-sm p-6">
            <h2 class="text-2xl font-light text-gray-700 mb-6">Support & Database Tools</h2>

            <?php if (isset($_SESSION['support_message'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    <?php echo $_SESSION['support_message']; unset($_SESSION['support_message']); ?>
                </div>
            <?php endif; ?>

            <!-- Comprehensive Searchable Learner Form -->
            <div class="border rounded-sm mb-8">
                <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Register Searchable Learner</h3>
                <form method="POST" action="index.php?page=support&action=add" class="p-6 space-y-6">
                    
                    <!-- Basic Information -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-gray-700 border-b pb-1">Basic Information</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">LRN (Will be Doc ID) <span class="text-red-500">*</span></label>
                                <input type="text" name="lrn" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm" placeholder="006346555172">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Birthdate (YYYY-MM-DD) <span class="text-red-500">*</span></label>
                                <input type="text" name="birthdate" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm" placeholder="2008-08-24">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Gender <span class="text-red-500">*</span></label>
                                <select name="gender" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                                    <option value="male">male</option>
                                    <option value="female">female</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Last Name</label>
                                <input type="text" name="lastName" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">First Name</label>
                                <input type="text" name="firstName" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Middle Name</label>
                                <input type="text" name="middleName" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Ext Name</label>
                                <input type="text" name="extName" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Current Address -->
                    <div class="space-y-4 pt-4 border-t">
                        <h4 class="text-sm font-bold text-gray-700 border-b pb-1">Current Address</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Province</label>
                                <input type="text" name="currentProvince" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">City/Municipality</label>
                                <input type="text" name="currentCity" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Barangay</label>
                                <input type="text" name="currentBarangay" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Parents Information -->
                    <div class="space-y-4 pt-4 border-t">
                        <h4 class="text-sm font-bold text-gray-700 border-b pb-1">Father's Information</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <input type="text" name="fatherLastName" placeholder="Last Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            <input type="text" name="fatherFirstName" placeholder="First Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            <input type="text" name="fatherMiddleName" placeholder="Middle Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            <input type="text" name="fatherExtName" placeholder="Ext (e.g. Sr.)" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                        </div>

                        <h4 class="text-sm font-bold text-gray-700 border-b pb-1 pt-2">Mother's Maiden Information</h4>
                        <div class="grid grid-cols-4 gap-4">
                            <input type="text" name="motherMaidenLastName" placeholder="Last Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            <input type="text" name="motherMaidenFirstName" placeholder="First Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            <input type="text" name="motherMaidenMiddleName" placeholder="Middle Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                            <input type="text" name="motherMaidenExtName" placeholder="Ext" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm">
                        </div>
                    </div>

                    <!-- Guardian Information -->
                    <div class="space-y-4 pt-4 border-t">
                        <h4 class="text-sm font-bold text-gray-700 border-b pb-1">Guardian Information</h4>
                        <div class="grid grid-cols-5 gap-4">
                            <input type="text" name="guardianLastName" placeholder="Last Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm col-span-1">
                            <input type="text" name="guardianFirstName" placeholder="First Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm col-span-1">
                            <input type="text" name="guardianMiddleName" placeholder="Middle Name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm col-span-1">
                            <input type="text" name="guardianExtName" placeholder="Ext" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm col-span-1">
                            <input type="text" name="guardianRelationship" placeholder="Relationship" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm col-span-1">
                        </div>
                    </div>

                    <!-- Academic History -->
                    <div class="space-y-4 pt-4 border-t">
                        <h4 class="text-sm font-bold text-gray-700 border-b pb-1">Academic History</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Previous School Name</label>
                                <input type="text" name="previousSchoolName" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm" placeholder="301218 - Tanza National High School">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Previous Enrolment Record</label>
                                <input type="text" name="previousEnrolmentRecord" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm" placeholder="SY 2021 - 2022 / Grade 10 / Completer">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-[#337ab7] text-white px-8 py-2 rounded-md hover:bg-[#286090] font-medium text-sm">
                            Register & Save to Database
                        </button>
                    </div>
                </form>
            </div>

            <!-- Database Seeding -->
            <div class="border rounded-sm mb-8">
                <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Quick Seed</h3>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Clicking this button will add the sample learner (Tyrique Brakus) and others to the database using their LRN as the ID.</p>
                    <form method="POST" action="index.php?page=support&action=seed">
                        <button type="submit" class="bg-[#5cb85c] text-white px-6 py-2 rounded-md hover:bg-[#4cae4c] font-medium text-sm">
                            Seed with Tyrique Brakus & Others
                        </button>
                    </form>
                </div>
            </div>

            <!-- Section Creation -->
            <div class="border rounded-sm">
                <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Section Management (Grade 11 & 12)</h3>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Create new sections for Grade 11 and Grade 12. These will appear in the enrollment dropdowns.</p>
                    <form method="POST" action="index.php?page=support&action=create_section" class="flex items-end space-x-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Grade Level</label>
                            <select name="grade" required class="px-3 py-2 border border-gray-300 rounded-sm text-sm">
                                <option value="Grade 11">Grade 11</option>
                                <option value="Grade 12">Grade 12</option>
                            </select>
                        </div>
                        <div class="flex-grow">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Section Name</label>
                            <input type="text" name="sectionName" required class="w-full px-3 py-2 border border-gray-300 rounded-sm text-sm" placeholder="e.g. Einstein">
                        </div>
                        <button type="submit" class="bg-[#337ab7] text-white px-6 py-2 rounded-md hover:bg-[#286090] font-medium text-sm">
                            Create Section
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>