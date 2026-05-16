<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search - Learner Information System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        body { font-family: 'Roboto', sans-serif; background-color: #e9e9e9; }
        .search-box {
            width: 350px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .result-box {
            width: 750px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            margin-left: 30px;
        }
        .box-header {
            background-color: #f5f5f5;
            border-bottom: 1px solid #ddd;
            padding: 8px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
        }
        .cancel-btn {
            background-color: white;
            border: 1px solid #ccc;
            padding: 2px 8px;
            font-size: 12px;
            color: #333;
            border-radius: 3px;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid #ddd;
            background-color: white;
        }
        .tab {
            padding: 10px 15px;
            font-size: 14px;
            cursor: pointer;
            border-right: 1px solid #ddd;
        }
        .tab.active {
            color: #555;
            background-color: white;
        }
        .tab.inactive {
            color: #337ab7;
        }
        .box-body {
            padding: 20px;
        }
        .search-input {
            width: 180px;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-radius: 3px;
            font-size: 14px;
        }
        .search-btn {
            background-color: #337ab7;
            border: 1px solid #2e6da4;
            color: white;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 3px;
            margin-left: 8px;
        }
        .preview-btn {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            padding: 2px 10px;
            font-size: 12px;
            color: #333;
            border-radius: 3px;
            cursor: pointer;
        }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        th { font-weight: bold; text-align: left; padding: 8px; border: 1px solid #ddd; background-color: #fff; }
        td { padding: 8px; border: 1px solid #ddd; color: #333; }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .modal-content {
            background-color: white;
            border-radius: 4px;
            width: 800px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
        }
        .modal-header {
            padding: 15px;
            border-bottom: 1px solid #e5e5e5;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            color: #555;
        }
        .modal-close {
            background: transparent;
            border: 1px solid #ccc;
            background-color: #f5f5f5;
            border-radius: 3px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            padding: 2px 8px;
            cursor: pointer;
            opacity: 0.6;
        }
        .modal-close:hover { opacity: 1; }
        .modal-body {
            padding: 15px;
        }
        .profile-box {
            border: 1px solid #ddd;
            border-radius: 3px;
        }
        .profile-box-header {
            background-color: #f5f5f5;
            padding: 10px 15px;
            border-bottom: 1px solid #ddd;
            font-size: 14px;
            color: #333;
        }
        .profile-box-body {
            padding: 15px;
            font-size: 14px;
        }
        .modal-footer {
            padding: 15px;
            border-top: 1px solid #e5e5e5;
            text-align: right;
        }
        .btn-continue {
            background-color: #5cb85c;
            border: 1px solid #4cae4c;
            color: white;
            padding: 6px 12px;
            font-size: 14px;
            border-radius: 3px;
            cursor: pointer;
        }
        .btn-continue:hover {
            background-color: #449d44;
            border-color: #398439;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <?php
    $searchResult = null;
    $lrnValue = '';
    $ageText = '';
    if (isset($_POST['search_lrn'])) {
        require_once 'includes/firestore.php';
        $firestoreHelper = new FirestoreHelper();
        $lrnValue = $_POST['lrn'];
        $searchResult = $firestoreHelper->searchLearnerByLRN($lrnValue);
        if ($searchResult) {
            $_SESSION['searched_learner'] = $searchResult;
            
            // Calculate Age
            $dob = date_create(str_replace('-', '/', $searchResult['birthdate']));
            if ($dob) {
                $now = new DateTime();
                $age = $now->diff($dob)->y;
                $ageText = $age . " y/o as of today, " . date('m/d/Y');
            }
        } else {
            unset($_SESSION['searched_learner']);
        }
    }
    ?>

    <!-- Top White Band -->
    <div class="bg-white w-full flex justify-center py-10 shadow-sm border-b border-gray-200">
        <div class="flex items-start">
            <!-- Search Parameters Box -->
            <div class="search-box">
                <div class="box-header">
                    <span class="text-sm font-medium text-gray-700">Search Parameters</span>
                    <a href="index.php?page=enrolment" class="cancel-btn">Cancel Search</a>
                </div>
                <div class="tabs">
                    <div class="tab active">Search by LRN</div>
                    <div class="tab inactive">Search by Name</div>
                </div>
                <div class="box-body">
                    <form method="POST" action="index.php?page=search" class="flex items-center">
                        <input type="text" name="lrn" class="search-input" value="<?php echo htmlspecialchars($lrnValue); ?>" required>
                        <button type="submit" name="search_lrn" class="search-btn">Search</button>
                    </form>
                </div>
            </div>

            <!-- Search Result Box (Visible when result exists) -->
            <?php if ($searchResult): ?>
            <div class="result-box">
                <div class="box-header">
                    <span class="text-sm font-medium text-gray-700">Search Result</span>
                </div>
                <div class="p-2">
                    <table>
                        <thead>
                            <tr>
                                <th class="w-8">#</th>
                                <th>LRN</th>
                                <th>Last name</th>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Ext name</th>
                                <th>Gender</th>
                                <th>Birthdate</th>
                                <th class="w-20"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><?php echo $searchResult['lrn']; ?></td>
                                <td class="uppercase"><?php echo $searchResult['lastName']; ?></td>
                                <td class="uppercase"><?php echo $searchResult['firstName']; ?></td>
                                <td class="uppercase"><?php echo $searchResult['middleName'] ?? ''; ?></td>
                                <td class="uppercase"><?php echo $searchResult['extName'] ?? ''; ?></td>
                                <td><?php echo $searchResult['gender']; ?></td>
                                <td><?php echo $searchResult['birthdate']; ?></td>
                                <td class="text-center">
                                    <button type="button" class="preview-btn inline-block" onclick="document.getElementById('previewModal').style.display='flex'">Preview</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- The Modal -->
    <?php if ($searchResult): ?>
    <div id="previewModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <span>Learner <?php echo $searchResult['lrn']; ?></span>
                <button class="modal-close" onclick="document.getElementById('previewModal').style.display='none'">&times;</button>
            </div>
            <div class="modal-body grid grid-cols-2 gap-4">
                <!-- Basic profile -->
                <div class="profile-box">
                    <div class="profile-box-header">Basic profile</div>
                    <div class="profile-box-body">
                        <div class="grid grid-cols-3 gap-2">
                            <div class="text-right font-bold text-gray-700">Last name</div>
                            <div class="col-span-2 uppercase"><?php echo $searchResult['lastName']; ?></div>
                            <div class="text-right font-bold text-gray-700">First name</div>
                            <div class="col-span-2 uppercase"><?php echo $searchResult['firstName']; ?></div>
                            <div class="text-right font-bold text-gray-700">Middle name</div>
                            <div class="col-span-2 uppercase"><?php echo $searchResult['middleName'] ?? ''; ?></div>
                            <div class="text-right font-bold text-gray-700 mt-2">Gender</div>
                            <div class="col-span-2 mt-2 uppercase"><?php echo (substr($searchResult['gender'] ?? 'M', 0, 1) === 'F') ? 'F' : 'M'; ?></div>
                            <div class="text-right font-bold text-gray-700">Birthdate</div>
                            <div class="col-span-2">
                                <div><?php echo $searchResult['birthdate']; ?></div>
                                <div class="text-xs text-gray-500 mt-0.5"><?php echo $ageText; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Most recent enrolment record -->
                <div class="profile-box h-fit">
                    <div class="profile-box-header">Most recent enrolment record</div>
                    <div class="profile-box-body">
                        <div class="flex justify-between items-start mb-2">
                            <a href="#" class="text-[#337ab7] hover:underline text-sm"><?php echo htmlspecialchars($searchResult['previousSchoolName'] ?? 'No record found'); ?></a>
                            <svg class="w-4 h-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars($searchResult['previousEnrolmentRecord'] ?? 'N/A'); ?></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="index.php?page=final_enrolment" class="btn-continue text-decoration-none inline-block">Continue</a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex-grow"></div>

    <footer class="bg-white border-t border-gray-200 w-full mt-auto">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
            Department of Education
        </div>
    </footer>
</body>
</html>