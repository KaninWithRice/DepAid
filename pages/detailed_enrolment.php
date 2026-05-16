<?php include 'includes/header.php'; ?>

<?php
if (!$learner) {
    header('Location: index.php?page=search');
    exit();
}
?>

<main class="flex-grow py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form method="POST" action="index.php?page=detailed_enrolment" class="bg-white border border-gray-200 shadow-sm p-6">
            <div class="text-sm text-gray-500 mb-8">
                <a href="index.php?page=masterlist" class="text-blue-600 hover:underline">Masterlist <?php echo $selectedClass; ?></a>
                <span class="mx-2">/</span>
                <span>Enrolment</span>
            </div>

            <!-- Top Row: Learner and Enrolment Info -->
            <div class="grid grid-cols-2 gap-6 mb-6">
                <!-- Learner Profile Summary -->
                <div class="border rounded-sm p-4 bg-white shadow-sm">
                     <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Learner <?php echo $learner['lrn']; ?></h3>
                     <dl class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                        <dt class="font-bold text-gray-700 text-right">Last name</dt><dd class="text-gray-800"><?php echo strtoupper($learner['lastName']); ?></dd>
                        <dt class="font-bold text-gray-700 text-right">First name</dt><dd class="text-gray-800"><?php echo strtoupper($learner['firstName']); ?></dd>
                        <dt class="font-bold text-gray-700 text-right">Middle name</dt><dd class="text-gray-800"><?php echo strtoupper($learner['middleName'] ?? ''); ?></dd>
                        <dt class="font-bold text-gray-700 text-right">Birthdate</dt><dd class="text-gray-800"><?php echo str_replace('/', '-', $learner['birthdate']); ?></dd>
                        <dt class="font-bold text-gray-700 text-right">Gender</dt><dd class="text-gray-800"><?php echo $learner['gender'] === 'Female' ? 'F' : 'M'; ?></dd>
                     </dl>
                </div>

                <!-- Enrolment Details -->
                <div class="border rounded-sm p-4 bg-white shadow-sm">
                    <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Enrolment</h3>
                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                        <dt class="font-bold text-gray-700 text-right">School year</dt><dd class="text-gray-800">2025 - 2026</dd>
                        <dt class="font-bold text-gray-700 text-right">Grade & Section</dt><dd class="text-gray-800"><?php echo $selectedClass; ?></dd>
                        <dt class="font-bold text-gray-700 text-right">Date of First Attendance</dt><dd class="text-gray-800">2025-07-07</dd>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="text-xs font-bold text-gray-600 mb-2 uppercase">Credentials</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-1">Reason</label>
                                <select name="credentialReason" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm bg-white text-sm">
                                    <option value="">-- select --</option>
                                    <option>From accredited or recognized school</option>
                                    <option>From not accredited local school</option>
                                    <option>From foreign school abroad</option>
                                    <option>From Philippine school abroad</option>
                                    <option>From International School based in the Philippines</option>
                                    <option>From ALS</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="flex items-center text-xs text-gray-700">
                                    <input type="checkbox" class="mr-2"> Arabic Language and Islamic Values Education
                                </label>
                                <label class="flex items-center text-xs text-gray-700">
                                    <input type="checkbox" class="mr-2"> Alternative delivery mode
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Info (3 columns) -->
            <div class="grid grid-cols-3 gap-6 mb-6">
                <!-- Guardian -->
                <div class="border rounded-sm p-4 bg-white shadow-sm">
                    <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Guardian</h3>
                    <div class="space-y-3">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Last name</label><input type="text" name="guardianLastName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['guardianLastName'] ?? ''; ?>"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">First name</label><input type="text" name="guardianFirstName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['guardianFirstName'] ?? ''; ?>"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Middle name</label><input type="text" name="guardianMiddleName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['guardianMiddleName'] ?? ''; ?>">
                            <label class="flex items-center text-xs text-gray-600 mt-1"><input type="checkbox" class="mr-1"> No middle name</label>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Extension name</label><input type="text" name="guardianExtName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['guardianExtName'] ?? ''; ?>"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Relationship</label>
                            <select name="guardianRelationship" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm">
                                <option value="">-- select --</option>
                                <option value="Relative" selected>Relative</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Mother's Maiden Name -->
                <div class="border rounded-sm p-4 bg-white shadow-sm">
                    <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Mother's maiden name</h3>
                    <div class="space-y-3">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Last name</label><input type="text" name="motherLastName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['motherMaidenLastName'] ?? ''; ?>"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">First name</label><input type="text" name="motherFirstName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['motherMaidenFirstName'] ?? ''; ?>"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Middle name</label><input type="text" name="motherMiddleName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['motherMaidenMiddleName'] ?? ''; ?>">
                            <label class="flex items-center text-xs text-gray-600 mt-1"><input type="checkbox" class="mr-1"> No middle name</label>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Extension name</label><input type="text" name="motherExtName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm"></div>
                        
                        <div class="pt-2">
                            <label class="flex items-center text-xs text-gray-600 mb-2"><input type="checkbox" class="mr-1"> Reason for not specifying mother's maiden name</label>
                            <div class="flex space-x-4 text-xs text-gray-700">
                                <label class="flex items-center"><input type="radio" name="motherReason" class="mr-1"> No mother</label>
                                <label class="flex items-center"><input type="radio" name="motherReason" class="mr-1"> Not disclosed</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Father -->
                <div class="border rounded-sm p-4 bg-white shadow-sm">
                    <h3 class="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Father</h3>
                    <div class="space-y-3">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Last name</label><input type="text" name="fatherLastName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['fatherLastName'] ?? ''; ?>"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">First name</label><input type="text" name="fatherFirstName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['fatherFirstName'] ?? ''; ?>"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Middle name</label><input type="text" name="fatherMiddleName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['fatherMiddleName'] ?? ''; ?>">
                            <label class="flex items-center text-xs text-gray-600 mt-1"><input type="checkbox" class="mr-1" checked> No middle name</label>
                        </div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Extension name</label><input type="text" name="fatherExtName" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm" value="<?php echo $learner['fatherExtName'] ?? ''; ?>"></div>
                    </div>
                </div>
            </div>

            <!-- Indigenous, Language, Religion, Email, Citizenship, CCT -->
            <div class="grid grid-cols-3 gap-6 mb-8 border-b pb-8">
                <div class="space-y-4">
                    <div>
                        <h4 class="text-sm font-bold text-gray-700 mb-1">Indigenous Peoples</h4>
                        <p class="text-xs text-gray-500 mb-2">Is this learner a member of Indigenous Cultural Communities/Indigenous Peoples?</p>
                        <div class="flex space-x-4 text-sm">
                            <label class="flex items-center"><input type="radio" name="isIP" class="mr-1"> Yes</label>
                            <label class="flex items-center"><input type="radio" name="isIP" class="mr-1" checked> No</label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Select Ethnicity</label>
                        <input type="text" name="ethnicity" placeholder="Type ethnicity..." class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Mother tongue</label>
                        <input type="text" name="motherTongue" placeholder="Type mother tongue..." class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Other spoken languages</label>
                        <input type="text" name="otherLanguages" placeholder="Type other languages..." class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm">
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Religion</label>
                        <input type="text" name="religion" placeholder="Type religion..." class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
                        <input type="email" placeholder="example@email.com" class="w-full px-3 py-1.5 border border-gray-300 rounded-sm text-sm">
                    </div>
                </div>
            </div>

            <!-- Residence, Citizenship, CCT (Aligned according to format.png) -->
            <div class="grid grid-cols-3 gap-8 mb-8 pt-4">
                <!-- Current Residence -->
                <div>
                    <h3 class="text-xl text-gray-800 mb-6">Current Residence</h3>
                    <div class="space-y-4">
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Province</label>
                            <select id="currentProvince" name="currentProvince" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">City/Municipality</label>
                            <select id="currentCity" name="currentCity" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Zip Code</label>
                            <select id="currentZip" name="currentZip" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Barangay</label>
                            <select id="currentBarangay" name="currentBarangay" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Permanent Residence -->
                <div>
                    <h3 class="text-xl text-gray-800 mb-6">Permanent Residence</h3>
                    <div class="mb-4 h-6">
                        <label class="flex items-center text-sm text-gray-700"><input type="checkbox" id="sameAsCurrent" class="mr-2"> Same as current address</label>
                    </div>
                    <div class="space-y-4">
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Province</label>
                            <select id="permanentProvince" name="permanentProvince" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">City/Municipality</label>
                            <select id="permanentCity" name="permanentCity" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Zip Code</label>
                            <select id="permanentZip" name="permanentZip" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-gray-700 mb-1">Barangay</label>
                            <select id="permanentBarangay" name="permanentBarangay" class="w-full">
                                <option value="">--select--</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Country of Citizenship & CCT -->
                <div>
                    <div class="mb-10">
                        <h3 class="text-xl text-gray-800 border-b pb-1 mb-6">Country of Citizenship</h3>
                        <select id="citizenship" name="citizenship" class="w-full">
                            <option value="Philippines">Philippines</option>
                        </select>
                    </div>

                    <div>
                        <h3 class="text-xl text-gray-800 border-b pb-1 mb-6">Conditional Cash Transfer (CCT)</h3>
                        <label class="flex items-center text-sm text-gray-700"><input type="checkbox" name="isCCT" class="mr-2"> Is this learner CCT recipient?</label>
                    </div>
                </div>
            </div>

            <script src="addressData.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (typeof PH_ADDRESS_DATA === 'undefined') {
                        console.error('PH_ADDRESS_DATA is not loaded!');
                        return;
                    }

                    const currentSelectors = {
                        province: document.getElementById('currentProvince'),
                        city: document.getElementById('currentCity'),
                        zip: document.getElementById('currentZip'),
                        barangay: document.getElementById('currentBarangay')
                    };
                    const permanentSelectors = {
                        province: document.getElementById('permanentProvince'),
                        city: document.getElementById('permanentCity'),
                        zip: document.getElementById('permanentZip'),
                        barangay: document.getElementById('permanentBarangay')
                    };
                    const sameAsCurrent = document.getElementById('sameAsCurrent');

                    // Country List
                    const countries = [
                        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan",
                        "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi",
                        "Cabo Verde", "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)", "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia",
                        "Democratic Republic of the Congo", "Denmark", "Djibouti", "Dominica", "Dominican Republic",
                        "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia",
                        "Fiji", "Finland", "France",
                        "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana",
                        "Haiti", "Holy See", "Honduras", "Hungary",
                        "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Ivory Coast",
                        "Jamaica", "Japan", "Jordan",
                        "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan",
                        "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg",
                        "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", "Mozambique", "Myanmar",
                        "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", "North Macedonia", "Norway",
                        "Oman",
                        "Pakistan", "Palau", "Palestine State", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal",
                        "Qatar",
                        "Romania", "Russia", "Rwanda",
                        "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", "Sweden", "Switzerland", "Syria",
                        "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu",
                        "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan",
                        "Vanuatu", "Venezuela", "Vietnam",
                        "Yemen",
                        "Zambia", "Zimbabwe"
                    ];

                    const citizenshipSelect = document.getElementById('citizenship');
                    if (citizenshipSelect) {
                        citizenshipSelect.innerHTML = '';
                        countries.forEach(country => {
                            const option = document.createElement('option');
                            option.value = country;
                            option.textContent = country;
                            if (country === 'Philippines') option.selected = true;
                            citizenshipSelect.appendChild(option);
                        });
                    }

                    function populateDropdown(dropdown, options, selectedValue = null) {
                        if (!dropdown) return;
                        dropdown.innerHTML = '<option value="">--select--</option>';
                        options.forEach(opt => {
                            if (!opt) return;
                            const option = document.createElement('option');
                            option.value = opt;
                            option.textContent = opt;
                            if (opt === selectedValue) option.selected = true;
                            dropdown.appendChild(option);
                        });
                        $(dropdown).trigger('change.select2');
                    }

                    function updateCities(province, selectors) {
                        const cities = (province && PH_ADDRESS_DATA[province]) ? Object.keys(PH_ADDRESS_DATA[province]) : [];
                        populateDropdown(selectors.city, cities.filter(c => c !== '--select--'));
                        populateDropdown(selectors.zip, []);
                        populateDropdown(selectors.barangay, []);
                    }

                    function updateZipAndBarangay(province, city, selectors) {
                        const data = (province && city && PH_ADDRESS_DATA[province] && PH_ADDRESS_DATA[province][city]) ? PH_ADDRESS_DATA[province][city] : null;
                        
                        let zipOptions = [];
                        if (data && data.zip) {
                            zipOptions = Array.isArray(data.zip) ? data.zip : [data.zip];
                        }
                        
                        populateDropdown(selectors.zip, zipOptions);
                        populateDropdown(selectors.barangay, data ? data.barangays : []);
                    }

                    // Initialize Provinces
                    const provinces = Object.keys(PH_ADDRESS_DATA).filter(p => p !== '--select--' && p.trim() !== '');
                    populateDropdown(currentSelectors.province, provinces);
                    populateDropdown(permanentSelectors.province, provinces);

                    // Event Listeners
                    $('#currentProvince').on('select2:select', function(e) {
                        updateCities(e.params.data.id, currentSelectors);
                        if (sameAsCurrent.checked) syncAddresses();
                    });
                    $('#currentCity').on('select2:select', function(e) {
                        updateZipAndBarangay(currentSelectors.province.value, e.params.data.id, currentSelectors);
                        if (sameAsCurrent.checked) syncAddresses();
                    });
                    $('#currentZip, #currentBarangay').on('select2:select', function() {
                        if (sameAsCurrent.checked) syncAddresses();
                    });

                    $('#permanentProvince').on('select2:select', function(e) {
                        updateCities(e.params.data.id, permanentSelectors);
                        sameAsCurrent.checked = false;
                    });
                    $('#permanentCity').on('select2:select', function(e) {
                        updateZipAndBarangay(permanentSelectors.province.value, e.params.data.id, permanentSelectors);
                        sameAsCurrent.checked = false;
                    });

                    function syncAddresses() {
                        const prov = currentSelectors.province.value;
                        const city = currentSelectors.city.value;
                        const zip = currentSelectors.zip.value;
                        const brgy = currentSelectors.barangay.value;

                        permanentSelectors.province.value = prov;
                        updateCities(prov, permanentSelectors);
                        permanentSelectors.city.value = city;
                        updateZipAndBarangay(prov, city, permanentSelectors);
                        permanentSelectors.zip.value = zip;
                        permanentSelectors.barangay.value = brgy;
                    }

                    sameAsCurrent.addEventListener('change', function() {
                        if (this.checked) syncAddresses();
                    });

                    // Initialize Select2 on all select elements
                    $('select').not('.no-search').select2({
                        width: '100%',
                        placeholder: '--select--'
                    });

                    $('.no-search').select2({
                        width: '100%',
                        minimumResultsForSearch: Infinity
                    });
                });
            </script>

            <!-- Special Needs, Vaccination, Modality (Refined for format2.png) -->
            <div class="grid grid-cols-3 gap-8 mb-8 mt-8 border-t pt-8">
                <!-- Special Educational Needs -->
                <div>
                    <h3 class="text-xl text-gray-800 mb-6">Special Educational Needs</h3>
                    <p class="text-sm text-gray-700 mb-2">Does this learner have Educational Needs?</p>
                    <div class="flex space-x-4 text-sm mb-6">
                        <label class="flex items-center"><input type="radio" name="hasSEN" value="Yes" class="mr-1"> Yes</label>
                        <label class="flex items-center"><input type="radio" name="hasSEN" value="No" class="mr-1" checked> No</label>
                    </div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Classification/Type of Learner Special Educational Needs (LSEN)</label>
                    <select name="lsenClassification" class="w-full">
                        <option value="">-- Select --</option>
                        <optgroup label="With Medical Diagnosis">
                            <option>Visual Impairment</option>
                            <option>Hearing Impairment</option>
                            <option>Learning Disability (e.g., Dyslexia, Dyscalculia)</option>
                            <option>Intellectual Disability</option>
                            <option>Autism Spectrum Disorder (ASD)</option>
                            <option>Emotional-Behavioral Disorder</option>
                            <option>Orthopedic/Physical Handicap</option>
                            <option>Speech/Language Disorder</option>
                            <option>Cerebral Palsy</option>
                            <option>Special Health Problem/Chronic Disease (e.g., ADHD, Epilepsy, Cancer)</option>
                            <option>Multiple Disabilities</option>
                        </optgroup>
                        <optgroup label="Without Medical Diagnosis (Manifestations)">
                            <option>Difficulty in Seeing</option>
                            <option>Difficulty in Hearing</option>
                            <option>Difficulty in Communicating</option>
                            <option>Difficulty in Walking/Moving</option>
                            <option>Difficulty in Remembering/Concentrating/Paying Attention</option>
                            <option>Difficulty in Basic Learning and Applying Knowledge</option>
                            <option>Difficulty in Applying Adaptive Skills (Self-care, social skills)</option>
                            <option>Difficulty in Displaying Interpersonal Behavior</option>
                        </optgroup>
                        <optgroup label="Other Special Tagging Options">
                            <option>Giftedness/Talented</option>
                            <option>ARAL Tagging</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Vaccination -->
                <div>
                    <h3 class="text-xl text-gray-800 mb-6 border-b pb-1">Vaccination</h3>
                    <p class="text-sm text-gray-700 mb-4">Is the learner vaccinated against COVID-19?</p>
                    <div class="flex space-x-4 text-sm mb-6">
                        <label class="flex items-center"><input type="radio" name="isVac" value="Yes" class="mr-1" checked> <span class="font-bold">Yes</span></label>
                        <label class="flex items-center"><input type="radio" name="isVac" value="No" class="mr-1"> <span class="font-bold">No</span></label>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- 1st Shot -->
                        <div class="flex items-start">
                            <label class="w-24 text-sm text-gray-700 pt-1">1st Shot</label>
                            <div class="flex-1">
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <select name="vac1Month" class="no-search border border-gray-300 rounded-sm px-2 py-1 text-sm bg-white">
                                        <option value="">Month</option>
                                        <option>January</option><option selected>February</option><option>March</option><option>April</option><option>May</option><option>June</option>
                                        <option>July</option><option>August</option><option>September</option><option>October</option><option>November</option><option>December</option>
                                    </select>
                                    <select name="vac1Day" class="no-search border border-gray-300 rounded-sm px-2 py-1 text-sm bg-white">
                                        <option value="">Day</option>
                                        <?php for($i=1;$i<=31;$i++) { $sel = ($i==8) ? 'selected' : ''; echo "<option $sel>$i</option>"; } ?>
                                    </select>
                                </div>
                                <div class="w-1/2 pr-1">
                                    <select name="vac1Year" class="no-search border border-gray-300 rounded-sm px-2 py-1 text-sm bg-white w-full">
                                        <option value="">Year</option>
                                        <?php for($i=2020;$i<=2026;$i++) { $sel = ($i==2021) ? 'selected' : ''; echo "<option $sel>$i</option>"; } ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Full Vaccination -->
                        <div class="flex items-start">
                            <label class="w-24 text-sm text-gray-700 pt-1 leading-tight">Full<br>Vaccination</label>
                            <div class="flex-1 border-t border-gray-100 pt-4">
                                <div class="grid grid-cols-2 gap-2 mb-2">
                                    <select name="vacFullMonth" class="no-search border border-gray-300 rounded-sm px-2 py-1 text-sm bg-white">
                                        <option value="">Month</option>
                                        <option>January</option><option>February</option><option selected>March</option><option>April</option><option>May</option><option>June</option>
                                        <option>July</option><option>August</option><option>September</option><option>October</option><option>November</option><option>December</option>
                                    </select>
                                    <select name="vacFullDay" class="no-search border border-gray-300 rounded-sm px-2 py-1 text-sm bg-white">
                                        <option value="">Day</option>
                                        <?php for($i=1;$i<=31;$i++) { $sel = ($i==1) ? 'selected' : ''; echo "<option $sel>$i</option>"; } ?>
                                    </select>
                                </div>
                                <div class="w-1/2 pr-1">
                                    <select name="vacFullYear" class="no-search border border-gray-300 rounded-sm px-2 py-1 text-sm bg-white w-full">
                                        <option value="">Year</option>
                                        <?php for($i=2020;$i<=2026;$i++) { $sel = ($i==2021) ? 'selected' : ''; echo "<option $sel>$i</option>"; } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-600 italic mt-8 leading-normal">* If the learner was vaccinated with Janssen, enter the date under full vaccination</p>
                </div>

                <!-- Actual Modality -->
                <div>
                    <h3 class="text-xl text-gray-800 mb-6">Actual Modality</h3>
                    <select name="modality" class="w-full mb-2">
                        <option value="Online">Online</option>
                        <option>Modular (print)</option>
                        <option>Modular Digital</option>
                        <option>Eduactional TV</option>
                        <option>Radio-Based Instruction</option>
                        <option>Homeschooling</option>
                        <option>Blended</option>
                        <option>Face to face</option>
                    </select>
                </div>
            </div>

            <!-- ADM and Academic Recovery (Boxes with headers as per format2.png) -->
            <div class="grid grid-cols-2 gap-6 mb-12">
                <!-- ADM Box -->
                <div class="border border-gray-300 rounded-sm overflow-hidden flex flex-col min-h-[180px]">
                    <div class="bg-[#f0f0f0] px-4 py-2 border-b border-gray-300 text-sm text-gray-700">
                        Alternative Delivery Mode
                    </div>
                    <div class="p-4 flex-grow space-y-2">
                        <label class="flex items-center text-sm text-gray-700">
                            <input type="radio" name="adm" value="OHSP" class="mr-2"> Open high School Program(OHSP)
                        </label>
                        <label class="flex items-center text-sm text-gray-700">
                            <input type="radio" name="adm" value="Other" class="mr-2"> Other School Initiated Intervention
                        </label>
                    </div>
                    <div class="p-4 pt-0 flex justify-end">
                        <button type="button" disabled class="bg-[#f8f8f8] border border-gray-300 px-4 py-1 rounded-sm text-sm text-gray-400 shadow-sm cursor-not-allowed">Not Applicable</button>
                    </div>
                </div>

                <!-- Academic Recovery Box -->
                <div class="border border-gray-300 rounded-sm overflow-hidden flex flex-col min-h-[180px]">
                    <div class="bg-[#f0f0f0] px-4 py-2 border-b border-gray-300 text-sm text-gray-700">
                        Academic Recovery and Accessible Learning
                    </div>
                    <div class="p-4 flex-grow">
                        <!-- Empty content as per image -->
                    </div>
                    <div class="p-4 pt-0 flex justify-end">
                        <button type="button" disabled class="bg-[#f8f8f8] border border-gray-300 px-4 py-1 rounded-sm text-sm text-gray-400 shadow-sm cursor-not-allowed">Not Applicable</button>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-6 border-t pt-6 pb-2">
                <a href="index.php?page=final_enrolment" class="bg-[#f8f8f8] border border-gray-300 px-6 py-1.5 rounded-sm text-sm text-gray-700 shadow-sm hover:bg-gray-200">Cancel</a>
                <button type="submit" name="enrol" class="px-10 py-1.5 bg-[#337ab7] text-white border border-[#2e6da4] rounded-sm hover:bg-[#286090] text-sm font-medium shadow-sm">Enrol</button>
            </div>
            
            <!-- Pass learner data hiddenly -->
            <input type="hidden" name="lrn" value="<?php echo $learner['lrn']; ?>">
            <input type="hidden" name="lastName" value="<?php echo $learner['lastName']; ?>">
            <input type="hidden" name="firstName" value="<?php echo $learner['firstName']; ?>">
            <input type="hidden" name="middleName" value="<?php echo $learner['middleName']; ?>">
            <input type="hidden" name="gender" value="<?php echo $learner['gender']; ?>">
            <input type="hidden" name="birthdate" value="<?php echo $learner['birthdate']; ?>">
            <input type="hidden" name="section" value="<?php echo $selectedClass; ?>">
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>