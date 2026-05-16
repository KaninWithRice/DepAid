<?php
session_start();
require_once 'includes/firestore.php';

$firestoreHelper = new FirestoreHelper();
$auth = $firestoreHelper->getAuth();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        try {
            $signInResult = $auth->signInWithEmailAndPassword($email, $password);
            $_SESSION['user_id'] = $signInResult->firebaseUserId();
            $_SESSION['email'] = $email;
            header('Location: index.php?page=portal');
            exit();
        } catch (Exception $e) {
            $loginError = "Invalid email or password.";
        }
    } else {
        include 'pages/login.php';
        exit();
    }
}

// Simple routing logic
$page = isset($_GET['page']) ? $_GET['page'] : 'portal';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Global shared variables
$sectionData = [
    'Grade 11' => [
        'Bezos', 'Gates', 'Buffett', 'Arnault', 'Ellison', 'Page', 'Musk', 'Ambani',
        'Bang Si-Hyuk', 'Husserl', 'Comte', 'Aquinas', 'Herodotus', 'Camus', 'Augustine',
        'Durkheim', 'Enriquez', 'Freud', 'Heidegger', 'Confucius', 'Mercado', 'Plato',
        'Socrates', 'Adler', 'Agoncillo', 'Salazar', 'Descartes', 'Euclid', 'Pythagoras',
        'Archimedes', 'Fibonacci', 'Diophantus', 'Lovelace'
    ],
    'Grade 12' => [
        'Cojuangco', 'Gokongwei', 'Gozon', 'Uytengsu', 'Razon', 'Zobel', 'Pangilinan',
        'Gotianun', 'Aboitiz', 'Hernandez', 'Bautista', 'Atalia', 'Gonzales', 'Batacan',
        'Balagtas', 'Celerio', 'Florentino', 'Francisco', 'Galang', 'Bulosan', 'Joaquin',
        'Lumbrera', 'Zaide', 'Almario', 'Arcellana', 'Santos', 'Cavendish', 'Pasteur',
        'Heisenberg', 'Lavoisier', 'Rutherford', 'Curie'
    ]
];

// Helper to convert array to class options
$classOptions = [];
foreach ($sectionData as $grade => $sections) {
    foreach ($sections as $section) {
        $classOptions[] = "$grade – $section";
    }
}

// Fetch custom sections from Firestore
$customSections = $firestoreHelper->getAllSections();
foreach ($customSections as $cs) {
    $opt = $cs['grade'] . " – " . $cs['sectionName'];
    if (!in_array($opt, $classOptions)) {
        $classOptions[] = $opt;
    }
}

$selectedClass = isset($_SESSION['selected_class']) ? $_SESSION['selected_class'] : 'Grade 11 – Bezos';
if (isset($_POST['set_class'])) {
    $_SESSION['selected_class'] = $_POST['selected_class'];
    $selectedClass = $_SESSION['selected_class'];
}

// Logic for different pages
switch ($page) {
    case 'portal':
        include 'pages/portal.php';
        break;
    case 'dashboard':
        include 'pages/dashboard.php';
        break;
    case 'masterlist':
        $learners = $firestoreHelper->getLearnersBySection($selectedClass);
        include 'pages/masterlist.php';
        break;
    case 'enrolment':
        include 'pages/enrolment.php';
        break;
    case 'search':
        $searchResult = null;
        if (isset($_POST['search_lrn'])) {
            $searchResult = $firestoreHelper->searchLearnerByLRN($_POST['lrn']);
            if ($searchResult) {
                $_SESSION['searched_learner'] = $searchResult;
            } else {
                unset($_SESSION['searched_learner']);
            }
        }
        include 'pages/search.php';
        break;
    case 'final_enrolment':
        $learner = isset($_SESSION['searched_learner']) ? $_SESSION['searched_learner'] : null;
        include 'pages/final_enrolment.php';
        break;
    case 'detailed_enrolment':
        $learner = isset($_SESSION['searched_learner']) ? $_SESSION['searched_learner'] : null;
        if (isset($_POST['enrol'])) {
            $dataToSave = [
                'lrn' => $_POST['lrn'],
                'lastName' => $_POST['lastName'],
                'firstName' => $_POST['firstName'],
                'middleName' => $_POST['middleName'],
                'gender' => $_POST['gender'],
                'birthdate' => $_POST['birthdate'],
                'section' => $_POST['section'],
                'enrolmentStatus' => $_POST['enrolmentStatus'],
                'guardianLastName' => $_POST['guardianLastName'] ?? '',
                'guardianFirstName' => $_POST['guardianFirstName'] ?? '',
                'guardianRelationship' => $_POST['guardianRelationship'] ?? '',
                'enrolledAt' => date('Y-m-d H:i:s')
            ];
            $firestoreHelper->enrolLearner($dataToSave);
            unset($_SESSION['searched_learner']);
            header('Location: index.php?page=masterlist');
            exit();
        }
        include 'pages/detailed_enrolment.php';
        break;
    case 'support':
        if ($action === 'add') {
            $id = $_POST['lrn'];
            $data = [
                'lrn' => $_POST['lrn'],
                'lastName' => $_POST['lastName'],
                'firstName' => $_POST['firstName'],
                'middleName' => $_POST['middleName'] ?? '',
                'extName' => $_POST['extName'] ?? '',
                'gender' => $_POST['gender'],
                'birthdate' => $_POST['birthdate'],
                'currentProvince' => $_POST['currentProvince'] ?? '',
                'currentCity' => $_POST['currentCity'] ?? '',
                'currentBarangay' => $_POST['currentBarangay'] ?? '',
                'fatherLastName' => $_POST['fatherLastName'] ?? '',
                'fatherFirstName' => $_POST['fatherFirstName'] ?? '',
                'fatherMiddleName' => $_POST['fatherMiddleName'] ?? '',
                'fatherExtName' => $_POST['fatherExtName'] ?? '',
                'motherMaidenLastName' => $_POST['motherMaidenLastName'] ?? '',
                'motherMaidenFirstName' => $_POST['motherMaidenFirstName'] ?? '',
                'motherMaidenMiddleName' => $_POST['motherMaidenMiddleName'] ?? '',
                'motherMaidenExtName' => $_POST['motherMaidenExtName'] ?? '',
                'guardianLastName' => $_POST['guardianLastName'] ?? '',
                'guardianFirstName' => $_POST['guardianFirstName'] ?? '',
                'guardianMiddleName' => $_POST['guardianMiddleName'] ?? '',
                'guardianExtName' => $_POST['guardianExtName'] ?? '',
                'guardianRelationship' => $_POST['guardianRelationship'] ?? '',
                'previousSchoolName' => $_POST['previousSchoolName'] ?? '',
                'previousEnrolmentRecord' => $_POST['previousEnrolmentRecord'] ?? ''
            ];
            $firestoreHelper->upsertSearchableLearner($id, $data);
            $_SESSION['support_message'] = "Learner " . $id . " registered with full profile.";
            header('Location: index.php?page=support');
            exit();
        } elseif ($action === 'create_section') {
            $grade = $_POST['grade'];
            $sectionName = $_POST['sectionName'];
            if ($firestoreHelper->createSection($grade, $sectionName)) {
                $_SESSION['support_message'] = "Section '$sectionName' created for $grade.";
            } else {
                $_SESSION['support_message'] = "Failed to create section.";
            }
            header('Location: index.php?page=support');
            exit();
        } elseif ($action === 'seed') {
            $samples = [
                [
                    'lrn' => '006346555172', 
                    'lastName' => 'Brakus', 'firstName' => 'Tyrique', 'middleName' => 'Kennedy', 'extName' => '',
                    'gender' => 'male', 'birthdate' => '2008-08-24',
                    'currentProvince' => 'Metro Manila', 'currentCity' => 'Quezon City', 'currentBarangay' => '267 Lizzie Flats',
                    'fatherLastName' => 'Brakus', 'fatherFirstName' => 'Wayne', 'fatherMiddleName' => 'Kennedy', 'fatherExtName' => 'Sr.',
                    'motherMaidenLastName' => 'Brakus', 'motherMaidenFirstName' => 'Melanie', 'motherMaidenMiddleName' => 'Kennedy', 'motherMaidenExtName' => '',
                    'guardianLastName' => 'Brakus', 'guardianFirstName' => 'Melanie', 'guardianMiddleName' => 'Nader', 'guardianExtName' => '', 'guardianRelationship' => 'Mother',
                    'previousSchoolName' => '301218 - Tanza National High School', 'previousEnrolmentRecord' => 'SY 2021 - 2022 / Grade 10 / Completer'
                ],
                [
                    'lrn' => '108170090229', 
                    'lastName' => 'DELLOMOS', 'firstName' => 'MARY GRACE', 'middleName' => 'JOVEN',
                    'gender' => 'female', 'birthdate' => '2004-04-02',
                    'previousSchoolName' => '301218 - Tanza National Comprehensive High School', 'previousEnrolmentRecord' => 'SY 2021 - 2022 / Grade 12 / Completer'
                ]
            ];
            foreach ($samples as $sample) {
                $firestoreHelper->upsertSearchableLearner($sample['lrn'], $sample);
            }
            $_SESSION['support_message'] = "Database seeded with " . count($samples) . " detailed sample learners.";
            header('Location: index.php?page=support');
            exit();
        }
        include 'pages/support.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: index.php');
        exit();
    default:
        include 'pages/portal.php';
}
?>