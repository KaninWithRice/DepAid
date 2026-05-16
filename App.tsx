import React, { useState, useEffect } from 'react';
import { initializeApp } from 'firebase/app';
// FIX: Use named imports for Firebase v9+ modular SDK functions and types instead of a namespace import.
import { getAuth, signInWithEmailAndPassword, onAuthStateChanged, signOut, User } from 'firebase/auth';
import { getFirestore, collection, getDocs, addDoc, query, where } from 'firebase/firestore';
import { PH_ADDRESS_DATA, type ProvinceInfo } from './addressData';

// NOTE FOR USER: 
// In your Firebase console:
//    - Enable Email/Password authentication.
//    - Create a user to log in with.
//    - Create a 'learners' collection for the masterlist.
//    - Create a 'searchableLearners' collection and populate it with sample data for the search feature.
//    - Update Firestore security rules for production.
const firebaseConfig = {
  apiKey: "AIzaSyCjSonkoDyIrkY__Nb3uVvNvvdCk46URsU",
  authDomain: "deped-711d8.firebaseapp.com",
  projectId: "deped-711d8",
  storageBucket: "deped-711d8.appspot.com",
  messagingSenderId: "830187902317",
  appId: "1:830187902317:web:1417c2ef95956b7e45b664",
  measurementId: "G-H972S2SG9X"
};

// Initialize Firebase v9+
const app = initializeApp(firebaseConfig);
// FIX: `getAuth` is a named export in Firebase v9+, so it should be called directly.
const auth = getAuth(app);
const db = getFirestore(app);


// --- Shared Icon Components ---
const DepEdLogo: React.FC = () => (
  <div className="flex items-baseline" aria-label="DepEd Logo">
    <span className="text-2xl font-bold text-[#bf2126]">Dep</span>
    <span className="text-2xl font-bold text-[#21438b]">ED</span>
  </div>
);

const DownArrowIcon: React.FC = () => (
  <svg className="w-4 h-4 ml-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" /></svg>
);

const CloseIcon: React.FC<{ className?: string }> = ({ className }) => (
  <svg className={`w-3 h-3 ${className}`} fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
);

const PencilIcon: React.FC = () => (
    <svg className="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L15.232 5.232z"></path></svg>
);
const ReportIcon: React.FC = () => (
    <svg className="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
);

const CalendarIcon: React.FC = () => (
    <svg className="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
);

const InfoIcon: React.FC = () => (
    <svg className="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
);

type SearchResult = {
    docId?: string; // Firestore document ID
    lrn: string;
    lastName: string;
    firstName: string;
    middleName: string;
    extName: string;
    gender: string;
    birthdate: string;
    section?: string;
    enrolmentStatus?: 'Regular' | 'Transfer-in' | 'Balik-aral' | 'Repeater';
    
    // Parent/Guardian and Address Info from central database
    motherMaidenLastName?: string;
    motherMaidenFirstName?: string;
    motherMaidenMiddleName?: string;
    motherMaidenExtName?: string;
    fatherLastName?: string;
    fatherFirstName?: string;
    fatherMiddleName?: string;
    fatherExtName?: string;
    guardianLastName?: string;
    guardianFirstName?: string;
    guardianMiddleName?: string;
    guardianExtName?: string;
    guardianRelationship?: string;
    currentProvince?: string;
    currentCity?: string;
    currentBarangay?: string;
    currentZip?: string;
    permProvince?: string;
    permCity?: string;
    permBarangay?: string;
    permZip?: string;

    // Previous school details
    previousSchoolName?: string;
    previousEnrolmentRecord?: string;
};


// --- Data Definitions ---

// Sample data to be shown if Firestore is empty for the specified section.
const sampleEnrolledLearners: SearchResult[] = [
    {
        docId: 'sample-1',
        lrn: '108170090230',
        lastName: 'CRUZ',
        firstName: 'JUAN',
        middleName: 'SANTOS',
        extName: 'JR',
        gender: 'M',
        birthdate: '01/15/2005',
        section: 'Grade 11 – Bezos',
        enrolmentStatus: 'Transfer-in',
    },
    {
        docId: 'sample-2',
        lrn: '108170090231',
        lastName: 'REYES',
        firstName: 'MARIA',
        middleName: 'GARCIA',
        extName: '',
        gender: 'Female',
        birthdate: '05/20/2005',
        section: 'Grade 11 – Bezos',
        enrolmentStatus: 'Balik-aral',
    },
    {
        docId: 'sample-3',
        lrn: '108170090232',
        lastName: 'GONZALES',
        firstName: 'PETER',
        middleName: 'LIM',
        extName: '',
        gender: 'M',
        birthdate: '09/01/2004',
        section: 'Grade 11 – Bezos',
        enrolmentStatus: 'Repeater',
    },
];

const sectionData: Record<string, string[]> = {
  'Grade 11': [
    'Bezos', 'Gates', 'Buffett', 'Arnault', 'Ellison', 'Page', 'Musk', 'Ambani',
    'Bang Si-Hyuk', 'Husserl', 'Comte', 'Aquinas', 'Herodotus', 'Camus', 'Augustine',
    'Durkheim', 'Enriquez', 'Freud', 'Heidegger', 'Confucius', 'Mercado', 'Plato',
    'Socrates', 'Adler', 'Agoncillo', 'Salazar', 'Descartes', 'Euclid', 'Pythagoras',
    'Archimedes', 'Fibonacci', 'Diophantus', 'Lovelace'
  ],
  'Grade 12': [
    'Cojuangco', 'Gokongwei', 'Gozon', 'Uytengsu', 'Razon', 'Zobel', 'Pangilinan',
    'Gotianun', 'Aboitiz', 'Hernandez', 'Bautista', 'Atalia', 'Gonzales', 'Batacan',
    'Balagtas', 'Celerio', 'Florentino', 'Francisco', 'Galang', 'Bulosan', 'Joaquin',
    'Lumbrera', 'Zaide', 'Almario', 'Arcellana', 'Santos', 'Cavendish', 'Pasteur',
    'Heisenberg', 'Lavoisier', 'Rutherford', 'Curie'
  ]
};


// --- Sign In Page Components ---
const SignInHeader: React.FC = () => (
  <header className="border-b border-gray-200 w-full">
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div className="flex justify-between items-center h-16">
        <div className="flex items-center space-x-4">
          <DepEdLogo />
          <span className="text-xl text-gray-600 font-light">Single Sign On</span>
        </div>
        <a href="#" className="text-sm text-gray-600 hover:text-gray-800">Help</a>
      </div>
    </div>
  </header>
);

const LoginForm: React.FC = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    try {
      // FIX: `signInWithEmailAndPassword` is a named export in Firebase v9+, so it should be called directly.
      await signInWithEmailAndPassword(auth, email, password);
      // Successful sign-in is handled by the onAuthStateChanged listener in App.tsx
    } catch (error) {
      setError('Invalid email or password. Please try again.');
      console.error("Authentication error:", error);
    }
  };

  return (
    <div className="w-full max-w-sm">
      <h1 className="text-3xl font-light text-gray-700 mb-6">Please sign in</h1>
      {error && <p className="bg-red-100 text-red-700 p-3 rounded-md mb-4 text-sm">{error}</p>}
      <form onSubmit={handleSubmit} aria-label="Sign-in form">
        <div className="mb-3">
          <input
            type="email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            className="w-full px-3 py-2 border border-[#BEDAEC] rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-[#EAF3FA] text-gray-700"
            placeholder="Email"
            aria-label="Email"
            autoComplete="email"
          />
        </div>
        <div className="mb-4">
          <input
            type="password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            className="w-full px-3 py-2 border border-[#BEDAEC] rounded-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-[#EAF3FA] text-gray-700"
            placeholder="Password"
            aria-label="Password"
            autoComplete="current-password"
          />
        </div>
        <button
          type="submit"
          className="px-5 py-2 bg-[#337ab7] text-white rounded-md hover:bg-[#286090] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#286090] transition-colors font-medium"
        >
          Sign in
        </button>
      </form>
    </div>
  );
};

const ForgotPasswordInfo: React.FC = () => (
  <div className="w-full max-w-sm mt-8 bg-[#f5f5f5] border border-[#e3e3e3] p-5 rounded-md">
    <h2 className="text-lg font-medium text-gray-700 mb-2">Forgot password?</h2>
    <p className="text-sm text-gray-600 leading-relaxed">
      For class advisers, request School Head or designated school system administrator to reset password. For school heads, request Division Planning Officer to reset password.
    </p>
  </div>
);

const SignInFooter: React.FC = () => (
  <footer className="bg-gray-100 border-t border-gray-200 w-full mt-auto">
    <div className="max-w-6xl mx-auto py-4 px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
      Department of Education
    </div>
  </footer>
);

const SignInPage: React.FC = () => {
  return (
    <div className="flex flex-col min-h-screen bg-white font-sans text-gray-800 items-center">
      <SignInHeader />
      <main className="flex-grow w-full flex flex-col items-center justify-start pt-20 px-4">
        <LoginForm />
        <ForgotPasswordInfo />
      </main>
      <SignInFooter />
    </div>
  );
};


// --- App Layout Components (Used by Dashboard & Masterlist) ---

const AppHeader: React.FC<{ onSignOut: () => void }> = ({ onSignOut }) => (
    <header className="bg-white shadow-sm w-full sticky top-0 z-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex justify-between items-center h-16">
                <div className="flex items-center space-x-3">
                    <DepEdLogo />
                    <span className="text-xl text-gray-700">Learner Information System</span>
                </div>
                <div className="flex items-center space-x-6 text-sm text-gray-600">
                    <button className="flex items-center hover:text-gray-800" aria-label="User menu">
                        <span>{auth.currentUser?.email || 'JUAN DELA CRUZ'}</span>
                        <DownArrowIcon />
                    </button>
                    <a href="#" className="hover:text-gray-800">Help</a>
                    <button onClick={onSignOut} className="hover:text-gray-800">Sign out</button>
                </div>
            </div>
        </div>
    </header>
);

const DashboardSubHeader: React.FC<{ onNavigate: (page: string) => void; }> = ({ onNavigate }) => (
    <div className="bg-white border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex justify-between items-end py-4">
                <div>
                    <h1 className="text-2xl font-light text-gray-700">Dashboard</h1>
                    <a href="#" className="text-blue-600 hover:underline text-sm">301218 - Tanza National Comprehensive High School</a>
                </div>
                <nav className="flex space-x-8 text-sm font-medium text-gray-500" aria-label="Main navigation">
                    <button onClick={() => onNavigate('dashboard')} className="text-gray-900 border-b-2 border-red-500 pb-1" aria-current="page">Dashboard</button>
                    <button onClick={() => onNavigate('masterlist')} className="hover:text-gray-900">Masterlist</button>
                    <a href="#" className="hover:text-gray-900">School Forms</a>
                    <a href="#" className="flex items-center hover:text-gray-900">
                        Data Corrections <span className="ml-1.5 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full" aria-label="86 new data corrections">86</span>
                    </a>
                    <a href="#" className="hover:text-gray-900">Support</a>
                </nav>
            </div>
        </div>
    </div>
);

const MasterlistSubHeader: React.FC<{ onNavigate: (page: string) => void; title: string; }> = ({ onNavigate, title }) => (
    <div className="bg-white border-b">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex justify-between items-end py-4">
                <div>
                    <h1 className="text-2xl font-light text-gray-700">{title}</h1>
                    <a href="#" className="text-blue-600 hover:underline text-sm">301218 - Tanza National Comprehensive High School</a>
                </div>
                <nav className="flex space-x-8 text-sm font-medium text-gray-500" aria-label="Main navigation">
                    <button onClick={() => onNavigate('dashboard')} className="hover:text-gray-900">Dashboard</button>
                    <button onClick={() => onNavigate('masterlist')} className="text-gray-900 border-b-2 border-red-500 pb-1" aria-current="page">Masterlist</button>
                    <a href="#" className="hover:text-gray-900">School Forms</a>
                    <a href="#" className="flex items-center hover:text-gray-900">
                        Data Corrections <span className="ml-1.5 bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full" aria-label="86 new data corrections">86</span>
                    </a>
                    <a href="#" className="hover:text-gray-900">Support</a>
                </nav>
            </div>
        </div>
    </div>
);


const AppFooter: React.FC = () => (
    <footer className="w-full py-4 mt-auto">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-gray-500">
           Department of Education
        </div>
    </footer>
);


// --- Dashboard Page ---

const DashboardContent: React.FC = () => (
  <main className="flex-grow py-6">
    <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="mb-4">
            <button className="bg-white border border-gray-300 px-4 py-1.5 rounded-md text-sm flex items-center shadow-sm hover:bg-gray-50">
                Explore <DownArrowIcon />
            </button>
        </div>

        <div className="bg-white border border-gray-200 shadow-sm">
            <div className="p-6">
                <div className="flex justify-between items-start border-b pb-4 mb-6">
                    <div>
                        <p className="text-sm text-gray-500">Today</p>
                        <p className="text-lg text-gray-800">Oct 23, SY 2025-2026</p>
                    </div>
                    <button className="border border-gray-300 px-3 py-1.5 rounded-md text-sm flex items-center bg-gray-50 shadow-sm hover:bg-gray-100" aria-label="Select date">
                        Oct 23, SY 2025-2026 <DownArrowIcon />
                    </button>
                </div>
                
                <div className="border border-gray-200 max-w-lg">
                    <div className="flex justify-between items-center bg-gray-100 px-4 py-2 border-b">
                        <h2 className="text-md font-semibold text-gray-700">Enrolment</h2>
                        <div className="text-sm" role="tablist">
                            <button role="tab" aria-selected="true" className="text-gray-800 border-b-2 border-gray-700 pb-1 mr-4 cursor-pointer">Overview</button>
                            <button role="tab" aria-selected="false" className="text-gray-500 cursor-pointer hover:text-gray-800">Summary</button>
                        </div>
                    </div>
                    <div role="tabpanel" className="bg-white p-8 text-center">
                        <p className="text-md text-gray-600">Total Enrolment</p>
                        <p className="text-6xl font-light text-gray-800 my-4">1,847</p>
                        <div className="flex justify-center space-x-12 text-md text-gray-600">
                            <div>
                                <span>Male</span>
                                <p className="text-xl text-gray-800 font-medium">889</p>
                            </div>
                            <div>
                                <span>Female</span>
                                <p className="text-xl text-gray-800 font-medium">958</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </main>
);

const DashboardPage: React.FC<{ onSignOut: () => void; onNavigate: (page: string) => void }> = ({ onSignOut, onNavigate }) => {
    return (
        <div className="bg-[#f5f5f5] min-h-screen font-sans text-gray-800 flex flex-col">
            <AppHeader onSignOut={onSignOut} />
            <DashboardSubHeader onNavigate={onNavigate} />
            <DashboardContent />
            <AppFooter />
        </div>
    );
};

// --- Masterlist Page ---

const MasterlistContent: React.FC<{
    onNavigate: (page: string) => void;
    enrolledLearners: SearchResult[];
    sectionData: Record<string, string[]>;
    selectedClass: string;
    setSelectedClass: (cls: string) => void;
}> = ({ onNavigate, enrolledLearners, sectionData, selectedClass, setSelectedClass }) => {
    const repeaterSummaryTotal = enrolledLearners.filter(l => l.enrolmentStatus === 'Repeater').length;

    const summaryData = [
        { label: "Transferred out", value: 0 },
        { label: "Dropped out", value: 0 },
        { label: "No longer in school", value: 0 },
        { label: "Repeater", value: repeaterSummaryTotal },
    ];
    
    // FIX: Replaced .flatMap() with .reduce() to fix a type inference issue where 'sections' was being incorrectly typed as 'unknown'.
    const classOptions = Object.entries(sectionData).reduce<string[]>((allOptions, [grade, sections]: [string, string[]]) => {
        const newOptions = sections.map(section => `${grade} – ${section}`);
        return allOptions.concat(newOptions);
    }, []);
    
    const transferInMale = enrolledLearners.filter(l => l.enrolmentStatus === 'Transfer-in' && l.gender === 'M').length;
    const transferInFemale = enrolledLearners.filter(l => l.enrolmentStatus === 'Transfer-in' && l.gender === 'Female').length;
    const transferInTotal = transferInMale + transferInFemale;

    const balikAralMale = enrolledLearners.filter(l => l.enrolmentStatus === 'Balik-aral' && l.gender === 'M').length;
    const balikAralFemale = enrolledLearners.filter(l => l.enrolmentStatus === 'Balik-aral' && l.gender === 'Female').length;
    const balikAralTotal = balikAralMale + balikAralFemale;

    const repeaterMale = enrolledLearners.filter(l => l.enrolmentStatus === 'Repeater' && l.gender === 'M').length;
    const repeaterFemale = enrolledLearners.filter(l => l.enrolmentStatus === 'Repeater' && l.gender === 'Female').length;
    const repeaterTotal = repeaterMale + repeaterFemale;

    return(
    <main className="flex-grow py-6">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
             <div className="flex space-x-2 mb-4">
                <SelectInput 
                    value={selectedClass} 
                    onChange={(e) => setSelectedClass(e.target.value)} 
                    className="bg-white border border-gray-300 px-3 py-2 rounded-md text-sm shadow-sm hover:bg-gray-50 w-80"
                >
                    {classOptions.map(option => 
                        <option key={option} value={option}>{`${option} (SY 2025–2026)`}</option>
                    )}
                </SelectInput>
                 <button className="bg-white border border-gray-300 px-4 py-1.5 rounded-md text-sm flex items-center shadow-sm hover:bg-gray-50">
                    Select Tagging <DownArrowIcon />
                </button>
            </div>

            <div className="bg-white border border-gray-200 shadow-sm p-6">
                <div className="flex justify-between items-center mb-6">
                    <h2 className="text-3xl font-light text-gray-700">Masterlist</h2>
                    <button onClick={() => onNavigate('enrolment')} className="bg-[#5cb85c] text-white px-4 py-2 rounded-md hover:bg-[#4cae4c] font-medium text-sm">Enrol Learner</button>
                </div>

                {/* Overview */}
                <div className="border rounded-sm mb-6">
                    <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Overview</h3>
                    <div className="p-4">
                        <div className="flex justify-between items-center text-sm p-3 bg-gray-50 rounded-sm">
                            <span><strong className="font-semibold">Adviser</strong> DELA CRUZ, JUAN</span>
                            <span className="text-gray-600">{`${selectedClass} / SY 2025-2026`}</span>
                        </div>
                        <div className="mt-4 bg-[#fcf8e3] border border-[#faebcc] text-[#8a6d3b] p-4 rounded-sm text-sm">
                            <p><strong className="font-semibold">Warning</strong> The following requires immediate attention.</p>
                            <p className="mt-1">2 pending enrolment</p>
                        </div>
                    </div>
                </div>

                {/* Summary */}
                <div className="border rounded-sm mb-6">
                    <h3 className="text-lg font-normal text-gray-800 mb-4 px-4 pt-4">Summary</h3>
                    <div className="flex px-4 pb-4">
                        <div className="w-1/4 text-center border-r pr-8">
                            <p className="font-semibold text-gray-800">No of learners</p>
                            <p className="text-6xl font-light my-2 text-gray-800">{enrolledLearners.length}</p>
                            <div className="flex justify-center space-x-6 text-sm">
                                <div><span className="text-gray-600">Male</span> <p className="font-semibold text-gray-800">{enrolledLearners.filter(l => l.gender === 'M').length}</p></div>
                                <div><span className="text-gray-600">Female</span> <p className="font-semibold text-gray-800">{enrolledLearners.filter(l => l.gender === 'Female').length}</p></div>
                            </div>
                        </div>
                        <div className="w-3/4 flex pl-8 text-sm">
                             <table className="w-1/2">
                                <thead>
                                    <tr className="text-left">
                                        <th className="font-normal text-gray-800 pb-1"></th>
                                        <th className="font-normal text-gray-800 pb-1 text-center">Male</th>
                                        <th className="font-normal text-gray-800 pb-1 text-center">Female</th>
                                        <th className="font-normal text-gray-800 pb-1 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr className="border-b"><td className="py-1 text-gray-800">Transfer-in</td><td className="text-center text-gray-800">{transferInMale}</td><td className="text-center text-gray-800">{transferInFemale}</td><td className="text-center text-gray-800">{transferInTotal}</td></tr>
                                    <tr className="border-b"><td className="py-1 text-gray-800">Balik-aral</td><td className="text-center text-gray-800">{balikAralMale}</td><td className="text-center text-gray-800">{balikAralFemale}</td><td className="text-center text-gray-800">{balikAralTotal}</td></tr>
                                    <tr><td className="py-1 pt-2 text-gray-800">Repeater</td><td className="text-center text-gray-800 pt-2">{repeaterMale}</td><td className="text-center text-gray-800 pt-2">{repeaterFemale}</td><td className="text-center text-gray-800 pt-2">{repeaterTotal}</td></tr>
                                </tbody>
                            </table>
                            <table className="w-1/2 ml-8">
                                <thead>
                                    <tr className="text-left">
                                        <th className="font-normal text-gray-800 pb-1"></th>
                                        <th className="font-normal text-gray-800 pb-1 text-center">Male</th>
                                        <th className="font-normal text-gray-800 pb-1 text-center">Female</th>
                                        <th className="font-normal text-gray-800 pb-1 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr className="border-b"><td className="py-1"><a href="#" className="text-blue-600 border-b border-dotted border-blue-600">CCT Recipient</a></td><td className="text-center text-gray-800">0</td><td className="text-center text-gray-800">0</td><td className="text-center text-gray-800">0</td></tr>
                                    <tr className="border-b"><td className="py-1"><a href="#" className="text-blue-600 border-b border-dotted border-blue-600">ALIVE</a></td><td className="text-center text-gray-800">0</td><td className="text-center text-gray-800">0</td><td className="text-center text-gray-800">0</td></tr>
                                    <tr><td className="py-1 pt-2"><a href="#" className="text-blue-600 border-b border-dotted border-blue-600">ADM</a></td><td className="text-center text-gray-800 pt-2">0</td><td className="text-center text-gray-800 pt-2">0</td><td className="text-center text-gray-800 pt-2">0</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                     <div className="mt-4 border-t px-4">
                        {summaryData.map(item => (
                            <div key={item.label} className="flex justify-between items-center text-sm py-2.5 border-b last:border-b-0 text-gray-800">
                                <span>{item.label}</span>
                                <span className="flex items-center justify-center text-xs font-semibold bg-gray-600 text-white rounded-full w-5 h-5">{item.value}</span>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Enrolment Table */}
                <div className="border rounded-sm">
                    <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Enrolment</h3>
                     <div className="overflow-x-auto">
                        <table className="w-full text-sm text-left text-gray-800">
                            <thead className="bg-gray-50 text-gray-700 font-semibold">
                                <tr>
                                    <th className="p-3 font-semibold">#</th>
                                    <th className="p-3 font-semibold">Learner</th>
                                    <th className="p-3 font-semibold">Gender</th>
                                    <th className="p-3 font-semibold">Date of First Attendance</th>
                                    <th className="p-3 font-semibold">Grading</th>
                                    <th className="p-3 font-semibold">Status</th>
                                    <th className="p-3"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {enrolledLearners.length > 0 ? (
                                    enrolledLearners.map((learner, index) => (
                                    <tr className="border-b" key={learner.docId || learner.lrn}>
                                        <td className="p-3">{index + 1}</td>
                                        <td className="p-3">{`${learner.lrn} ${learner.lastName}, ${learner.firstName}, ${learner.middleName}`}</td>
                                        <td className="p-3">{learner.gender === 'Female' ? 'F' : 'M'}</td>
                                        <td className="p-3">06/16/25</td>
                                        <td className="p-3"><button className="flex items-center space-x-1 hover:text-blue-600"><PencilIcon /><span></span></button></td>
                                        <td className="p-3">
                                            <div className="flex items-center space-x-2">
                                                <button className="flex items-center space-x-1 hover:text-blue-600"><ReportIcon/> <span>Report</span></button>
                                                <button className="flex items-center space-x-1 hover:text-blue-600"><PencilIcon /> <span>No status</span></button>
                                            </div>
                                        </td>
                                        <td className="p-3 text-right"><button className="bg-white border border-gray-300 px-4 py-1 rounded-md text-xs shadow-sm hover:bg-gray-50 font-semibold">Profile</button></td>
                                    </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={7} className="text-center p-6 text-gray-500">No learners enrolled in this class yet.</td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    );
};

const MasterlistPage: React.FC<{
    onSignOut: () => void;
    onNavigate: (page: string) => void;
    enrolledLearners: SearchResult[];
    sectionData: Record<string, string[]>;
    selectedClass: string;
    setSelectedClass: (cls: string) => void;
}> = (props) => {
    return (
        <div className="bg-[#f5f5f5] min-h-screen font-sans text-gray-800 flex flex-col">
            <AppHeader onSignOut={props.onSignOut} />
            <MasterlistSubHeader onNavigate={props.onNavigate} title="Masterlist" />
            <MasterlistContent {...props} />
            <AppFooter />
        </div>
    );
};

// --- Enrolment Page ---
const EnrolmentContent: React.FC<{ onNavigate: (page: string) => void; selectedClass: string; }> = ({ onNavigate, selectedClass }) => {
    const grade = selectedClass.split(' – ')[0];

    return (
        <main className="flex-grow py-6">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="bg-white border border-gray-200 shadow-sm p-6">
                    <div className="text-sm text-gray-500 mb-8" aria-label="breadcrumb">
                        <a href="#" onClick={(e) => { e.preventDefault(); onNavigate('masterlist'); }} className="text-blue-600 hover:underline">Masterlist</a>
                        <span className="mx-2">/</span>
                        <a href="#" onClick={(e) => { e.preventDefault(); onNavigate('masterlist'); }} className="text-blue-600 hover:underline">{selectedClass}</a>
                        <span className="mx-2">/</span>
                        <span>Enrolment</span>
                    </div>

                    <div className="max-w-xl mx-auto text-center py-12">
                        <h2 className="text-xl text-gray-800 mb-4">{`${grade} Enrolment`}</h2>
                        <p className="text-gray-600 text-sm leading-6 mb-6">
                            Use applicable documents as source to ensure accuracy of this enrolment transaction.
                        </p>
                        <ul className="text-gray-600 text-sm list-disc list-inside inline-block text-left mb-8">
                            <li>NSO/Birth/Baptismal certificate</li>
                            <li>Form 137/138</li>
                        </ul>
                        <div>
                             <button onClick={() => onNavigate('search')} className="bg-[#5cb85c] text-white px-6 py-2 rounded-md hover:bg-[#4cae4c] font-medium text-sm">Proceed Enrolment</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    );
};


const EnrolmentPage: React.FC<{ onSignOut: () => void; onNavigate: (page: string) => void; selectedClass: string; }> = (props) => {
    return (
        <div className="bg-[#f5f5f5] min-h-screen font-sans text-gray-800 flex flex-col">
            <AppHeader onSignOut={props.onSignOut} />
            <MasterlistSubHeader onNavigate={props.onNavigate} title="Enrolment" />
            <EnrolmentContent onNavigate={props.onNavigate} selectedClass={props.selectedClass}/>
            <AppFooter />
        </div>
    );
};

// --- Search Page & Related Components ---

const PreviewModal: React.FC<{ learner: SearchResult; onClose: () => void; onContinue: () => void; }> = ({ learner, onClose, onContinue }) => {
    return (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50" role="dialog" aria-modal="true" aria-labelledby="modal-title">
            <div className="bg-white rounded-md shadow-lg w-full max-w-3xl">
                <div className="flex justify-between items-center px-6 py-3 border-b bg-gray-50 rounded-t-md">
                    <h2 id="modal-title" className="text-lg text-gray-800 font-medium">Learner {learner.lrn}</h2>
                    <button onClick={onClose} className="text-gray-400 hover:text-gray-600" aria-label="Close modal">
                        <CloseIcon className="w-5 h-5" />
                    </button>
                </div>
                <div className="p-6">
                    <div className="flex space-x-6">
                        {/* Basic Profile */}
                        <div className="w-1/2 border rounded-sm">
                            <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Basic profile</h3>
                            <div className="p-4 text-sm">
                                <dl className="grid grid-cols-2 gap-x-4 gap-y-3">
                                    <dt className="font-semibold text-gray-600">Last name</dt>
                                    <dd className="text-gray-800">{learner.lastName}</dd>
                                    <dt className="font-semibold text-gray-600">First name</dt>
                                    <dd className="text-gray-800">{learner.firstName}</dd>
                                    <dt className="font-semibold text-gray-600">Middle name</dt>
                                    <dd className="text-gray-800">{learner.middleName}</dd>
                                    <dt className="font-semibold text-gray-600">Gender</dt>
                                    <dd className="text-gray-800">{learner.gender === 'Female' ? 'F' : 'M'}</dd>
                                    <dt className="font-semibold text-gray-600">Birthdate</dt>
                                    <dd className="text-gray-800">
                                        {learner.birthdate}
                                        <p className="text-xs text-gray-500">21 y/o as of today, 10/23/2025</p>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        {/* Enrolment Record */}
                        <div className="w-1/2 border rounded-sm">
                             <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 px-4 py-2 border-b">Most recent enrolment record</h3>
                             <div className="p-4 text-sm">
                                 <a href="#" className="text-blue-600 hover:underline font-medium">{learner.previousSchoolName || 'Data not available'}</a>
                                 <div className="flex items-center justify-between mt-1 text-gray-600">
                                     <span>{learner.previousEnrolmentRecord || 'Data not available'}</span>
                                     <InfoIcon />
                                 </div>
                             </div>
                        </div>
                    </div>
                </div>
                 <div className="px-6 py-3 bg-gray-50 border-t rounded-b-md flex justify-end">
                    <button onClick={onContinue} className="bg-[#5cb85c] text-white px-6 py-1.5 rounded-md hover:bg-[#4cae4c] font-medium text-sm">Continue</button>
                </div>
            </div>
        </div>
    );
};


const SearchResultTable: React.FC<{ result: SearchResult; onPreview: () => void }> = ({ result, onPreview }) => (
    <div className="bg-white border border-gray-300 shadow-sm">
        <div className="bg-gray-100 px-4 py-2 border-b border-gray-300">
            <h3 className="text-sm font-semibold text-gray-700">Search Result</h3>
        </div>
        <div className="p-2">
            <table className="w-full text-sm text-left text-gray-800 border-collapse">
                <thead>
                    <tr className="bg-gray-50">
                        <th className="p-2 font-semibold border border-gray-300">LRN</th>
                        <th className="p-2 font-semibold border border-gray-300">Last name</th>
                        <th className="p-2 font-semibold border border-gray-300">First name</th>
                        <th className="p-2 font-semibold border border-gray-300">Middle name</th>
                        <th className="p-2 font-semibold border border-gray-300">Ext. name</th>
                        <th className="p-2 font-semibold border border-gray-300">Gender</th>
                        <th className="p-2 font-semibold border border-gray-300">Birthdate</th>
                        <th className="p-2 font-semibold border border-gray-300"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td className="p-2 border border-gray-300">{result.lrn}</td>
                        <td className="p-2 border border-gray-300">{result.lastName}</td>
                        <td className="p-2 border border-gray-300">{result.firstName}</td>
                        <td className="p-2 border border-gray-300">{result.middleName}</td>
                        <td className="p-2 border border-gray-300">{result.extName}</td>
                        <td className="p-2 border border-gray-300">{result.gender}</td>
                        <td className="p-2 border border-gray-300">{result.birthdate}</td>
                        <td className="p-2 border border-gray-300 text-center">
                            <button onClick={onPreview} className="bg-gray-50 border border-gray-300 px-3 py-1 rounded-sm text-xs shadow-sm hover:bg-gray-200">Preview</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
);


const SearchContent: React.FC<{ onNavigate: (page: string, data?: any) => void }> = ({ onNavigate }) => {
    const [activeTab, setActiveTab] = useState('lrn');
    const [showBirthDate, setShowBirthDate] = useState(false);
    const [lrnInput, setLrnInput] = useState('');
    const [searchResult, setSearchResult] = useState<SearchResult | null>(null);
    const [showPreviewModal, setShowPreviewModal] = useState(false);
    const [searching, setSearching] = useState(false);

    const handleLrnSearch = async () => {
        if (!lrnInput) return;
        setSearching(true);
        setSearchResult(null);

        const searchableLearnersRef = collection(db, 'searchableLearners');
        const q = query(searchableLearnersRef, where("lrn", "==", lrnInput));
        
        try {
            const querySnapshot = await getDocs(q);
            if (!querySnapshot.empty) {
                const foundLearner = querySnapshot.docs[0].data() as SearchResult;
                setSearchResult(foundLearner);
            } else {
                alert('Learner not found in the central database.');
            }
        } catch (error) {
            console.error("Error searching learners:", error);
            alert('An error occurred while searching. Please check your Firestore rules and collection name ("searchableLearners").');
        } finally {
            setSearching(false);
        }
    };

    const handleContinueFromPreview = () => {
        if (searchResult) {
            onNavigate('finalEnrolment', searchResult);
        }
    };
    
    const handleTabChange = (tab: string) => {
        setActiveTab(tab);
        setSearchResult(null);
        setShowBirthDate(false);
        setLrnInput('');
    }

    return (
        <>
            <div className="flex items-start space-x-4">
                <div className="bg-white border border-gray-300 shadow-sm flex-shrink-0">
                    <div className="flex justify-between items-center bg-gray-100 px-4 py-2 border-b border-gray-300">
                        <h3 className="text-sm font-semibold text-gray-700">Search Parameters</h3>
                        <button
                            onClick={() => onNavigate('enrolment')}
                            className="text-xs bg-gray-50 border border-gray-300 px-3 py-1 rounded-sm shadow-sm hover:bg-gray-200"
                        >
                            Cancel Search
                        </button>
                    </div>
                    <div className="p-4">
                        <div className="flex border-b -mb-px">
                            <button
                                onClick={() => handleTabChange('lrn')}
                                className={`px-4 py-2 text-sm border rounded-t ${
                                    activeTab === 'lrn'
                                    ? 'bg-white border-gray-300 border-b-white text-gray-700 font-medium'
                                    : 'bg-gray-50 border-gray-300 text-blue-600 hover:bg-gray-100'
                                }`}
                            >
                                Search by LRN
                            </button>
                            <button
                                onClick={() => handleTabChange('name')}
                                className={`px-4 py-2 text-sm border rounded-t -ml-px ${
                                    activeTab === 'name'
                                    ? 'bg-white border-gray-300 border-b-white text-gray-700 font-medium'
                                    : 'bg-gray-50 border-gray-300 text-blue-600 hover:bg-gray-100'
                                }`}
                            >
                                Search by Name
                            </button>
                        </div>
                        <div className="border border-t-0 border-gray-300 p-6">
                            {activeTab === 'lrn' ? (
                                <div className="flex items-center space-x-2 w-72">
                                    <input
                                        type="text"
                                        value={lrnInput}
                                        onChange={(e) => setLrnInput(e.target.value)}
                                        className="w-full px-3 py-2 border border-gray-400 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 bg-white"
                                        aria-label="Search by LRN"
                                    />
                                    <button
                                        onClick={handleLrnSearch}
                                        disabled={searching}
                                        className="px-4 py-1.5 bg-[#337ab7] text-white border border-[#2e6da4] rounded-sm hover:bg-[#286090] text-sm flex-shrink-0 disabled:bg-gray-400"
                                    >
                                        {searching ? 'Searching...' : 'Search'}
                                    </button>
                                </div>
                            ) : (
                                <div className="space-y-4 w-72">
                                    <div>
                                        <label htmlFor="lastName" className="block text-xs font-bold text-gray-700 mb-1">
                                            Last name <span className="font-normal text-gray-700">*</span>
                                        </label>
                                        <input
                                            id="lastName"
                                            type="text"
                                            className="w-full px-3 py-2 border border-gray-400 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 bg-white"
                                        />
                                    </div>
                                    <div>
                                        <label htmlFor="firstName" className="block text-xs font-bold text-gray-700 mb-1">
                                            First name <span className="font-normal text-gray-700">*</span>
                                        </label>
                                        <input
                                            id="firstName"
                                            type="text"
                                            className="w-full px-3 py-2 border border-gray-400 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 bg-white"
                                        />
                                    </div>

                                    {showBirthDate && (
                                        <div>
                                            <label htmlFor="birthDate" className="block text-xs font-bold text-red-600 mb-1">
                                                Birth date
                                            </label>
                                            <p className="text-xs text-red-600 mb-1">This value is not valid.</p>
                                            <div className="relative">
                                                <input
                                                    id="birthDate"
                                                    type="text"
                                                    placeholder="mm/dd/yyyy"
                                                    className="w-full px-3 py-2 border border-red-500 rounded-sm focus:outline-none focus:ring-1 focus:ring-red-400 bg-white pr-8"
                                                />
                                                <div className="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                    <CalendarIcon />
                                                </div>
                                            </div>
                                        </div>
                                    )}

                                    <div>
                                        <button
                                        onClick={() => setShowBirthDate(true)}
                                        className="px-4 py-1.5 bg-[#337ab7] text-white border border-[#2e6da4] rounded-sm hover:bg-[#286090] text-sm">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {searchResult && <SearchResultTable result={searchResult} onPreview={() => setShowPreviewModal(true)} />}
            </div>
            {showPreviewModal && searchResult && (
                <PreviewModal 
                    learner={searchResult} 
                    onClose={() => setShowPreviewModal(false)}
                    onContinue={handleContinueFromPreview} 
                />
            )}
        </>
    );
};

const SearchPage: React.FC<{ onNavigate: (page: string, data?: any) => void }> = ({ onNavigate }) => {
    return (
        <div className="bg-[#f0f0f0] min-h-screen font-sans text-gray-800 flex flex-col">
            <main className="flex-grow w-full flex justify-center items-start pt-24 px-4">
                <SearchContent onNavigate={onNavigate} />
            </main>
            <div className="bg-[#f0f0f0]">
                <AppFooter />
            </div>
        </div>
    );
};

// --- Final Enrolment Page ---
const FinalEnrolmentContent: React.FC<{ learner: SearchResult; onNavigate: (page: string, data?: any) => void; selectedClass: string; }> = ({ learner, onNavigate, selectedClass }) => {
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const days = Array.from({ length: 31 }, (_, i) => i + 1);
    const years = [2025, 2024, 2023]; // Example years
    
    return (
        <main className="flex-grow py-6">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                 <div className="bg-white border border-gray-200 shadow-sm p-6">
                    <div className="text-sm text-gray-500 mb-8" aria-label="breadcrumb">
                        <a href="#" onClick={(e) => { e.preventDefault(); onNavigate('masterlist'); }} className="text-blue-600 hover:underline">Masterlist</a>
                        <span className="mx-2">/</span>
                        <a href="#" onClick={(e) => { e.preventDefault(); onNavigate('masterlist'); }} className="text-blue-600 hover:underline">{selectedClass}</a>
                        <span className="mx-2">/</span>
                        <span>Enrolment</span>
                    </div>

                    <div className="flex space-x-6">
                        {/* Learner Profile */}
                        <div className="w-1/2 border rounded-sm p-4">
                             <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Learner {learner.lrn}</h3>
                             <dl className="grid grid-cols-3 gap-x-4 gap-y-3 text-sm">
                                <dt className="font-bold text-gray-700 col-span-1 text-right">Last name</dt>
                                <dd className="text-gray-800 col-span-2">{learner.lastName}</dd>
                                <dt className="font-bold text-gray-700 col-span-1 text-right">First name</dt>
                                <dd className="text-gray-800 col-span-2">{learner.firstName}</dd>
                                <dt className="font-bold text-gray-700 col-span-1 text-right">Middle name</dt>
                                <dd className="text-gray-800 col-span-2">{learner.middleName}</dd>
                                <dt className="font-bold text-gray-700 col-span-1 text-right">Birthdate</dt>
                                <dd className="text-gray-800 col-span-2">{learner.birthdate.replace(/\//g, '-')}</dd>
                                <dt className="font-bold text-gray-700 col-span-1 text-right">Gender</dt>
                                <dd className="text-gray-800 col-span-2">{learner.gender === 'Female' ? 'F' : 'M'}</dd>
                             </dl>
                        </div>
                        {/* Enrolment Form */}
                        <div className="w-1/2 border rounded-sm p-4">
                            <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Enrolment</h3>
                             <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">Date of First Attendance</label>
                                <div className="flex space-x-2">
                                    <select className="w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm">
                                        <option>Month</option>
                                        {months.map(m => <option key={m}>{m}</option>)}
                                    </select>
                                    <select className="w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm">
                                        <option>Day</option>
                                        {days.map(d => <option key={d}>{d}</option>)}
                                    </select>
                                    <select className="w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm">
                                        <option>2025</option>
                                        {years.map(y => <option key={y}>{y}</option>)}
                                    </select>
                                </div>
                                <p className="text-xs text-gray-500 mt-2">The date of learner's first day of attendance in class or learning session.</p>
                            </div>
                        </div>
                    </div>
                    <div className="flex justify-between items-center mt-6 border-t pt-4">
                        <button onClick={() => onNavigate('search')} className="bg-gray-50 border border-gray-300 px-4 py-1.5 rounded-sm text-sm shadow-sm hover:bg-gray-200">Cancel</button>
                        <button onClick={() => onNavigate('detailedEnrolment', learner)} className="px-4 py-1.5 bg-[#337ab7] text-white border border-[#2e6da4] rounded-sm hover:bg-[#286090] text-sm">Continue</button>
                    </div>
                </div>
            </div>
        </main>
    )
};

const FinalEnrolmentPage: React.FC<{ learner: SearchResult; onSignOut: () => void; onNavigate: (page: string, data?: any) => void; selectedClass: string; }> = ({ learner, onSignOut, onNavigate, selectedClass }) => {
     return (
        <div className="bg-[#f5f5f5] min-h-screen font-sans text-gray-800 flex flex-col">
            <AppHeader onSignOut={onSignOut} />
            <MasterlistSubHeader onNavigate={onNavigate} title="Enrolment" />
            <FinalEnrolmentContent learner={learner} onNavigate={onNavigate} selectedClass={selectedClass} />
            <AppFooter />
        </div>
    );
};

// --- Detailed Enrolment Form Page ---

const FormSection: React.FC<{ title: string; children: React.ReactNode; className?: string }> = ({ title, children, className }) => (
    <div className={`bg-gray-50 border border-gray-200 p-4 rounded-sm ${className}`}>
        <h3 className="text-sm font-semibold text-gray-700 mb-4">{title}</h3>
        <div className="space-y-4">
            {children}
        </div>
    </div>
);

const FormField: React.FC<{ label: string; children: React.ReactNode }> = ({ label, children }) => (
    <div>
        <label className="block text-xs font-bold text-gray-600 mb-1">{label}</label>
        {children}
    </div>
);

const TextInput: React.FC<{ value?: string; onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void; disabled?: boolean; className?: string }> = ({ value, onChange, disabled, className }) => (
    <input type="text" value={value} onChange={onChange} disabled={disabled} className={`w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm disabled:bg-gray-100 ${className}`} />
);

const SelectInput: React.FC<{ children: React.ReactNode; value?: string; defaultValue?: string; onChange?: (e: React.ChangeEvent<HTMLSelectElement>) => void; disabled?: boolean; className?: string }> = ({ children, value, defaultValue, onChange, disabled, className }) => (
    <select value={value} defaultValue={defaultValue} onChange={onChange} disabled={disabled} className={`w-full px-3 py-2 border border-gray-300 rounded-sm bg-white text-sm disabled:bg-gray-100 ${className}`}>
        {children}
    </select>
);

const DetailedEnrolmentFormContent: React.FC<{ learner: SearchResult; onNavigate: (page: string, data?: any) => void; onEnrol: (learner: SearchResult) => void; selectedClass: string; }> = ({ learner, onNavigate, onEnrol, selectedClass }) => {
    // Form state, pre-populated from the searched learner data
    const [guardianLastName, setGuardianLastName] = useState(learner.guardianLastName || '');
    const [guardianFirstName, setGuardianFirstName] = useState(learner.guardianFirstName || '');
    const [guardianMiddleName, setGuardianMiddleName] = useState(learner.guardianMiddleName || '');
    const [guardianExtName, setGuardianExtName] = useState(learner.guardianExtName || '');
    const [guardianRelationship, setGuardianRelationship] = useState(learner.guardianRelationship || '--select--');
    
    const [motherLastName, setMotherLastName] = useState(learner.motherMaidenLastName || '');
    const [motherFirstName, setMotherFirstName] = useState(learner.motherMaidenFirstName || '');
    const [motherMiddleName, setMotherMiddleName] = useState(learner.motherMaidenMiddleName || '');
    const [motherExtName, setMotherExtName] = useState(learner.motherMaidenExtName || '');

    const [fatherLastName, setFatherLastName] = useState(learner.fatherLastName || '');
    const [fatherFirstName, setFatherFirstName] = useState(learner.fatherFirstName || '');
    const [fatherMiddleName, setFatherMiddleName] = useState(learner.fatherMiddleName || '');
    const [fatherExtName, setFatherExtName] = useState(learner.fatherExtName || '');

    // State for Current Residence
    const [currentProvince, setCurrentProvince] = useState(learner.currentProvince || 'REGION IV-A - CAVITE');
    const [currentCity, setCurrentCity] = useState(learner.currentCity || 'TANZA');
    const [currentBarangay, setCurrentBarangay] = useState(learner.currentBarangay || 'Bagtas');
    const [currentZip, setCurrentZip] = useState(learner.currentZip || '');
    const [enrolmentStatus, setEnrolmentStatus] = useState<'Regular' | 'Transfer-in' | 'Balik-aral' | 'Repeater'>('Regular');

    const currentCities = Object.keys(PH_ADDRESS_DATA[currentProvince] || {});
    const currentBarangays = (PH_ADDRESS_DATA[currentProvince] as ProvinceInfo)?.[currentCity]?.barangays || [];
    const currentZipOptions = (PH_ADDRESS_DATA[currentProvince] as ProvinceInfo)?.[currentCity]?.zip || [];

    // State for Permanent Residence
    const [sameAsCurrent, setSameAsCurrent] = useState(false);
    const [permProvince, setPermProvince] = useState(learner.permProvince ||'--select--');
    const [permCity, setPermCity] = useState(learner.permCity || '');
    const [permBarangay, setPermBarangay] = useState(learner.permBarangay || '');
    const [permZip, setPermZip] = useState(learner.permZip || '');

    const permCities = Object.keys(PH_ADDRESS_DATA[permProvince] || {});
    const permBarangays = (PH_ADDRESS_DATA[permProvince] as ProvinceInfo)?.[permCity]?.barangays || [];
    const permZipOptions = (PH_ADDRESS_DATA[permProvince] as ProvinceInfo)?.[permCity]?.zip || [];
    
    useEffect(() => {
        // Automatically select the first zip code when the city or province changes
        if (currentZipOptions.length > 0 && !currentZipOptions.includes(currentZip)) {
            setCurrentZip(currentZipOptions[0]);
        } else if (currentZipOptions.length === 0) {
            setCurrentZip('');
        }
    }, [currentCity, currentProvince]);
    
    useEffect(() => {
        if (permZipOptions.length > 0 && !permZipOptions.includes(permZip)) {
            setPermZip(permZipOptions[0]);
        } else if (permZipOptions.length === 0) {
            setPermZip('');
        }
    }, [permCity, permProvince]);
    
    useEffect(() => {
        if (sameAsCurrent) {
            setPermProvince(currentProvince);
            setPermCity(currentCity);
            setPermBarangay(currentBarangay);
            setPermZip(currentZip);
        }
    }, [sameAsCurrent, currentProvince, currentCity, currentBarangay, currentZip]);
    
    const handleEnrolClick = () => {
        const finalLearnerData: SearchResult = {
            ...learner,
            enrolmentStatus,
            guardianLastName,
            guardianFirstName,
            guardianMiddleName,
            guardianExtName,
            guardianRelationship,
            motherMaidenLastName: motherLastName,
            motherMaidenFirstName: motherFirstName,
            motherMaidenMiddleName: motherMiddleName,
            motherMaidenExtName: motherExtName,
            fatherLastName,
            fatherFirstName,
            fatherMiddleName,
            fatherExtName,
            currentProvince,
            currentCity,
            currentBarangay,
            currentZip,
            permProvince: sameAsCurrent ? currentProvince : permProvince,
            permCity: sameAsCurrent ? currentCity : permCity,
            permBarangay: sameAsCurrent ? currentBarangay : permBarangay,
            permZip: sameAsCurrent ? currentZip : permZip,
        };
        onEnrol(finalLearnerData);
    };

    const motherTongues = [
        "Abaknon (Inabaknon of Capul)", "Abellen Ayta", "Adasen", "Agta, Alabat Island", "Agta, Casiguran-Dilásag",
        "Agta, Central Cagayan", "Agta, Dicamay", "Agta, Dupaninan", "Agta, Isarog", "Agta, Mt. Iraya", "Agta, Mt. Iriga",
        "Agta, Remontado", "Agta, Umiray Dumaget", "Agutaynen", "Aklanon", "Alangan (Mindoro)", "Albay Bikol", "Ambala Ayta",
        "Apayao (Isneg)", "Arta", "Asi (Bantoanon)", "Atta, Faire", "Atta, Pamplona", "Atta, Pudtol", "Ayangan Ifugao",
        "Badjao (Sama Dilaut)", "Bagobo, Clata", "Bagobo, Guiangan", "Bagobo, Tagabawa", "Balangao", "Banao Itneg",
        "Bantayanon", "Banwaon", "Batak (Palawan Negrito)", "Baybayanon", "Bikol, Buhi-non", "Bikol, Central", "Bikol, Libon",
        "Bikol, Miraya", "Bikol, Northern Catanduanes", "Bikol, Pandán", "Bikol, Partido", "Bikol, Rinconada",
        "Bikol, Southern Catanduanes", "Bikol, Virac", "Bikol, West Albay", "Binongan Itneg", "Binukid", "Binukidnon",
        "Boholano", "Bontok", "B’laan, Koronadal", "B’laan, Sarangani", "Brooke’s Point Palawano", "Buhid (Mindoro)",
        "Bukidnon, Talaandig", "Calamian Tagbanwa", "Capiznon", "Casiguran Dumagat", "Cavite Tagalog", "Cebuano (Bisaya)",
        "Central Bontok", "Central Palawano", "Central Tagbanwa", "Clata Bagobo", "Cotabato Manobo", "Cuyonon",
        "Davaoeño Chavacano", "Davawenyo", "Dicamay Agta", "Dupaninan Agta", "Eastern Bontok", "English", "Faire Atta",
        "Guiangan Bagobo", "Hanunoo (Mindoro)", "Higaonon", "Hiligaynon (Ilonggo)", "Ibaloy", "Ibanag", "Ifugao, Amganad",
        "Ifugao, Batad", "Ifugao, Tuwali", "Ilianen Manobo", "Ilocano (Ilokano)", "Iranun", "Iraya (Mindoro)", "Isinay",
        "Isneg (Apayao)", "Itawit", "Itneg (Tinggian)", "Kalagan", "Kalanguya (Kayapa)", "Kalinga, Butbut",
        "Kalinga, Lubuagan", "Kalinga, Majukayong", "Kalinga, Northern", "Kalinga, Southern", "Kamayo", "Kankanaey, Northern",
        "Kankanaey, Western", "Kapalangan (Pampangueño / Kapampangan)", "Katabagan (extinct)", "Kinaray-a", "Kolibugan Subanen",
        "Koronadal B’laan", "Laguna Tagalog", "Libon Bikol", "Livunganen Manobo", "Maguindanaon", "Mag-antsi Ayta",
        "Mag-indi Ayta", "Magbukún Ayta", "Majukayong Kalinga", "Malaweg", "Manila Tagalog", "Mandaya", "Manobo, Agusan",
        "Manobo, Ata", "Manobo, Cotabato", "Manobo, Dabawenyo", "Manobo, Dibabawon", "Manobo, Ilianen", "Manobo, Kalamansig",
        "Manobo, Livunganen", "Manobo, Matigsalug", "Manobo, Obo", "Manobo, San Miguel", "Manobo, Sarangani",
        "Manobo, Tagakaulo", "Manobo, Tinananen", "Mansaka", "Maranao", "Masbateño", "Matigsalug Manobo", "Miraya Bikol",
        "Molbog", "Mount Iraya Agta", "Northern Alta", "Northern Kalinga", "Northern Subanen", "Onhan (Inonhan)",
        "Palawano, Central", "Palawano, Southwest", "Pampanga (Kapampangan)", "Pangasinan", "Pandan Bikol", "Pamplona Atta",
        "Partido Bikol", "Porohanon", "Pudtol Atta", "Quezon Tagalog", "Rinconada Bikol", "Rizal Tagalog", "Romblomanon",
        "Sama (Sinama)", "Sambal, Bolinao (Binubolinao)", "Sambal, Botolan", "Sambal, Tina", "Sangil (Sangir)",
        "Sarangani B’laan", "Sarangani Manobo", "San Miguel Manobo", "Sorsogon Ayta", "Southern Alta", "Southern Bontok",
        "Southern Catanduanes Bikol", "Southern Kalinga", "Southern Subanen", "Southwest Palawano", "Subanen, Kolibugan",
        "Subanen, Northern", "Subanen, Southern", "Subanen, Western", "Surigaonon", "Tagabawa Bagobo", "Tagakaulo",
        "Tagalog", "Tagalog, Batangas", "Tagalog, Bulacan", "Tagalog, Cavite", "Tagalog, Laguna", "Tagalog, Manila",
        "Tagalog, Quezon", "Tagalog, Rizal", "Tadyawan (Mindoro)", "Talaandig Bukidnon", "Tausug", "Teduray", "Tina Sambal",
        "Tinggian (Itneg)", "T’boli", "Tuwali Ifugao", "Umiray Dumaget", "Villaviciosa Agta", "Virac Bikol", "Waray (Waray-Waray)",
        "Western Kankanaey", "Western Subanen", "Yakan", "Yogad", "Zamboangueño Chavacano"
    ];

    const religions = [
        "Buddhism", "Christianity", "Hinduism", "Indigenous Religion", "Islam", "Judaism",
        "No Religion", "Not disclosed", "Others", "Sikhism", "Taoism"
    ];

    const countries = [
        "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", 
        "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", 
        "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina Faso", "Burundi", "Cabo Verde", "Cambodia", 
        "Cameroon", "Canada", "Central African Republic", "Chad", "Chile", "China", "Colombia", "Comoros", "Congo, Democratic Republic of the", 
        "Congo, Republic of the", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czechia", "Denmark", "Djibouti", "Dominica", 
        "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Eswatini", "Ethiopia", "Fiji", 
        "Finland", "France", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", 
        "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", "India", "Indonesia", "Iran", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", 
        "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho", 
        "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
        "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", 
        "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "North Korea", 
        "North Macedonia", "Norway", "Oman", "Pakistan", "Palau", "Palestine", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", 
        "Poland", "Portugal", "Qatar", "Romania", "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", 
        "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", 
        "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", "South Sudan", "Spain", "Sri Lanka", "Sudan", 
        "Suriname", "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", 
        "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", 
        "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"
    ];

    const vaxMonths = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const vaxDays = Array.from({ length: 31 }, (_, i) => i + 1);
    const vaxYears = [2025, 2024, 2023, 2022, 2021, 2020];
    
    const modalities = [
        "Modular (print)",
        "Modular Digital",
        "Online",
        "Educational TV",
        "Radio-based Instruction",
        "Homeschooling",
        "Blended",
        "Face to Face"
    ];
    
    return (
        <main className="flex-grow py-6">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="bg-white border border-gray-200 shadow-sm p-6">
                    <div className="text-sm text-gray-500 mb-8" aria-label="breadcrumb">
                        <a href="#" onClick={(e) => { e.preventDefault(); onNavigate('masterlist'); }} className="text-blue-600 hover:underline">Masterlist</a>
                        <span className="mx-2">/</span>
                        <a href="#" onClick={(e) => { e.preventDefault(); onNavigate('masterlist'); }} className="text-blue-600 hover:underline">{selectedClass}</a>
                        <span className="mx-2">/</span>
                        <span>Enrolment</span>
                    </div>

                    <div className="grid grid-cols-3 gap-6 mb-6">
                        {/* Learner Profile */}
                        <div className="col-span-1 border rounded-sm p-4 h-fit">
                             <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Learner {learner.lrn}</h3>
                             <dl className="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                                <dt className="font-bold text-gray-700 text-right">Last name</dt>
                                <dd className="text-gray-800">{learner.lastName}</dd>
                                <dt className="font-bold text-gray-700 text-right">First name</dt>
                                <dd className="text-gray-800">{learner.firstName}</dd>
                                <dt className="font-bold text-gray-700 text-right">Middle name</dt>
                                <dd className="text-gray-800">{learner.middleName}</dd>
                                <dt className="font-bold text-gray-700 text-right">Birthdate</dt>
                                <dd className="text-gray-800">{learner.birthdate.replace(/\//g, '-')}</dd>
                                <dt className="font-bold text-gray-700 text-right">Gender</dt>
                                <dd className="text-gray-800">{learner.gender === 'Female' ? 'F' : 'M'}</dd>
                             </dl>
                        </div>
                        {/* Enrolment Details */}
                        <div className="col-span-2 border rounded-sm p-4">
                            <h3 className="bg-gray-100 text-sm font-semibold text-gray-700 -m-4 mb-4 px-4 py-2 border-b">Enrolment</h3>
                            <dl className="grid grid-cols-4 gap-x-4 gap-y-3 text-sm items-center">
                                <dt className="font-bold text-gray-700">School year</dt><dd className="col-span-3">2025 - 2026</dd>
                                <dt className="font-bold text-gray-700">Grade & Section</dt><dd className="col-span-3">{selectedClass}</dd>
                                <dt className="font-bold text-gray-700">Date of First Attendance</dt><dd className="col-span-3">2025-07-02</dd>
                            </dl>
                             <div className="mt-4 pt-4 border-t space-y-4">
                                <FormField label="Learner Status">
                                    <SelectInput value={enrolmentStatus} onChange={(e) => setEnrolmentStatus(e.target.value as any)}>
                                        <option value="Regular">Regular</option>
                                        <option value="Transfer-in">Transfer-in</option>
                                        <option value="Balik-aral">Balik-aral</option>
                                        <option value="Repeater">Repeater</option>
                                    </SelectInput>
                                </FormField>
                                <div className="space-y-2">
                                    <label className="block text-xs font-bold text-gray-600">Credentials</label>
                                    <label className="block text-xs font-bold text-gray-600">Reason</label>
                                    <SelectInput>
                                        <option>-- select --</option>
                                        <option>From accredited or recognized school</option>
                                        <option>From not accredited local school</option>
                                        <option>From foreign school abroad</option>
                                        <option>From Philippine school abroad</option>
                                        <option>From International School based in the Philippines</option>
                                        <option>From ALS</option>
                                    </SelectInput>
                                    <div className="flex items-center space-x-2 text-sm"><input type="checkbox" /><span>Arabic Language and Islamic Values Education</span></div>
                                    <div className="flex items-center space-x-2 text-sm"><input type="checkbox" /><span>Alternative delivery mode</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div className="grid grid-cols-3 gap-6 mb-6">
                       <FormSection title="Guardian">
                            <FormField label="Last name"><TextInput value={guardianLastName} onChange={e => setGuardianLastName(e.target.value)} /></FormField>
                            <FormField label="First name"><TextInput value={guardianFirstName} onChange={e => setGuardianFirstName(e.target.value)}/></FormField>
                            <FormField label="Middle name"><TextInput value={guardianMiddleName} onChange={e => setGuardianMiddleName(e.target.value)}/></FormField>
                            <div className="flex items-center space-x-2 text-sm"><input type="checkbox" /><span>No middle name</span></div>
                            <FormField label="Extension name"><TextInput value={guardianExtName} onChange={e => setGuardianExtName(e.target.value)} /></FormField>
                            <FormField label="Relationship">
                                <SelectInput value={guardianRelationship} onChange={e => setGuardianRelationship(e.target.value)}>
                                    <option value="--select--">-- select --</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Relative">Relative</option>
                                    <option value="Non-relative">Non-relative</option>
                                </SelectInput>
                            </FormField>
                        </FormSection>
                        <FormSection title="Mother's maiden name">
                            <FormField label="Last name"><TextInput value={motherLastName} onChange={e => setMotherLastName(e.target.value)} /></FormField>
                            <FormField label="First name"><TextInput value={motherFirstName} onChange={e => setMotherFirstName(e.target.value)}/></FormField>
                            <FormField label="Middle name"><TextInput value={motherMiddleName} onChange={e => setMotherMiddleName(e.target.value)}/></FormField>
                            <div className="flex items-center space-x-2 text-sm"><input type="checkbox" /><span>No middle name</span></div>
                            <FormField label="Extension name"><TextInput value={motherExtName} onChange={e => setMotherExtName(e.target.value)} /></FormField>
                            <div className="flex items-center space-x-2 text-sm mt-4"><input type="checkbox" /><span>Reason for not specifying mother's maiden name</span></div>
                            <div className="flex space-x-8 text-sm ml-6">
                                <label className="flex items-center"><input type="radio" name="mother_reason" className="mr-2"/>No mother</label>
                                <label className="flex items-center"><input type="radio" name="mother_reason" className="mr-2"/>Not disclosed</label>
                            </div>
                        </FormSection>
                        <FormSection title="Father">
                            <FormField label="Last name"><TextInput value={fatherLastName} onChange={e => setFatherLastName(e.target.value)}/></FormField>
                            <FormField label="First name"><TextInput value={fatherFirstName} onChange={e => setFatherFirstName(e.target.value)}/></FormField>
                            <FormField label="Middle name"><TextInput value={fatherMiddleName} onChange={e => setFatherMiddleName(e.target.value)}/></FormField>
                             <div className="flex items-center space-x-2 text-sm"><input type="checkbox" /><span>No middle name</span></div>
                            <FormField label="Extension name"><TextInput value={fatherExtName} onChange={e => setFatherExtName(e.target.value)}/></FormField>
                        </FormSection>
                    </div>

                    <div className="grid grid-cols-3 gap-6 mb-6">
                         {/* Column 1 */}
                        <div className="space-y-6">
                            <div className="text-sm">
                                <h3 className="font-bold text-gray-600 mb-1">Indigenous Peoples</h3>
                                <p className="text-xs text-gray-500 mb-2">Is this learner a member of Indigenous Cultural Communities/Indigenous Peoples?</p>
                                <div className="flex space-x-4"><label><input type="radio" name="indigenous" className="mr-1"/> Yes</label><label><input type="radio" name="indigenous" className="mr-1" defaultChecked/> No</label></div>
                            </div>
                            <div>
                               <label className="block text-xs font-bold text-gray-600 mb-1">Select Ethnicity</label>
                               <SelectInput><option>--select--</option></SelectInput>
                               <SelectInput className="mt-2"><option>--select--</option></SelectInput>
                            </div>
                           <FormSection title="Current Residence">
                                <FormField label="Province">
                                    <SelectInput value={currentProvince} onChange={(e) => { setCurrentProvince(e.target.value); setCurrentCity('--select--'); setCurrentBarangay(''); }}>
                                        {Object.keys(PH_ADDRESS_DATA).map(prov => <option key={prov} value={prov}>{prov}</option>)}
                                    </SelectInput>
                                </FormField>
                                <FormField label="City/Municipality">
                                    <SelectInput value={currentCity} onChange={(e) => { setCurrentCity(e.target.value); setCurrentBarangay(''); }} disabled={!currentProvince || currentProvince === '--select--'}>
                                        {currentCities.map(city => <option key={city} value={city}>{city}</option>)}
                                    </SelectInput>
                                </FormField>
                                <FormField label="Zip Code">
                                     <SelectInput value={currentZip} onChange={e => setCurrentZip(e.target.value)} disabled={currentZipOptions.length <= 1}>
                                        {currentZipOptions.length === 0 && <option value="">--select--</option>}
                                        {currentZipOptions.map(zip => <option key={zip} value={zip}>{zip}</option>)}
                                    </SelectInput>
                                </FormField>
                                <FormField label="Barangay">
                                    <SelectInput value={currentBarangay} onChange={e => setCurrentBarangay(e.target.value)} disabled={!currentCity || currentCity === '--select--'}>
                                        <option value="">--select--</option>
                                        {currentBarangays.map(bgy => <option key={bgy} value={bgy}>{bgy}</option>)}
                                    </SelectInput>
                                </FormField>
                            </FormSection>
                        </div>
                         {/* Column 2 */}
                        <div className="space-y-6">
                           <div>
                                <FormField label="Mother tongue">
                                    <SelectInput defaultValue="Tagalog">
                                        {motherTongues.map(tongue => <option key={tongue} value={tongue}>{tongue}</option>)}
                                    </SelectInput>
                                </FormField>
                            </div>
                           <div>
                                <label className="block text-xs font-bold text-gray-600 mb-1">Other spoken languages</label>
                                <SelectInput><option>--select--</option></SelectInput>
                                <SelectInput className="mt-2"><option>--select--</option></SelectInput>
                           </div>
                           <FormSection title="Permanent Residence">
                                <div className="flex items-center space-x-2 text-sm">
                                    <input type="checkbox" id="sameAsCurrent" checked={sameAsCurrent} onChange={(e) => setSameAsCurrent(e.target.checked)} />
                                    <label htmlFor="sameAsCurrent">Same as current address</label>
                                </div>
                                <FormField label="Province">
                                    <SelectInput value={permProvince} onChange={(e) => { setPermProvince(e.target.value); setPermCity(''); setPermBarangay(''); }} disabled={sameAsCurrent}>
                                        {Object.keys(PH_ADDRESS_DATA).map(prov => <option key={prov} value={prov}>{prov}</option>)}
                                    </SelectInput>
                                </FormField>
                                <FormField label="City/Municipality">
                                    <SelectInput value={permCity} onChange={e => { setPermCity(e.target.value); setPermBarangay(''); }} disabled={sameAsCurrent || !permProvince || permProvince === '--select--'}>
                                         {permCities.map(city => <option key={city} value={city}>{city}</option>)}
                                    </SelectInput>
                                </FormField>
                                <FormField label="Zip Code">
                                    <SelectInput value={permZip} onChange={e => setPermZip(e.target.value)} disabled={sameAsCurrent || permZipOptions.length <= 1}>
                                        {permZipOptions.length === 0 && <option value="">--select--</option>}
                                        {permZipOptions.map(zip => <option key={zip} value={zip}>{zip}</option>)}
                                    </SelectInput>
                                </FormField>
                                <FormField label="Barangay">
                                    <SelectInput value={permBarangay} onChange={e => setPermBarangay(e.target.value)} disabled={sameAsCurrent || !permCity || permCity === '--select--'}>
                                         <option value="">--select--</option>
                                         {permBarangays.map(bgy => <option key={bgy} value={bgy}>{bgy}</option>)}
                                    </SelectInput>
                                </FormField>
                            </FormSection>
                        </div>
                         {/* Column 3 */}
                        <div className="space-y-6">
                             <div>
                                <FormField label="Religion">
                                    <SelectInput defaultValue="Christianity">
                                        {religions.map(religion => <option key={religion} value={religion}>{religion}</option>)}
                                    </SelectInput>
                                </FormField>
                            </div>
                             <div><FormField label="Email Address"><TextInput value="example@email.com"/></FormField></div>
                             <div>
                                <FormField label="Country of Citizenship">
                                    <SelectInput defaultValue="Philippines">
                                        {countries.map(country => <option key={country} value={country}>{country}</option>)}
                                    </SelectInput>
                                </FormField>
                            </div>
                             <div>
                                <h3 className="font-bold text-gray-600 mb-1 text-sm">Conditional Cash Transfer (CCT)</h3>
                                <div className="flex items-center space-x-2 text-sm"><input type="checkbox" /><span>Is this learner CCT recipient?</span></div>
                             </div>
                        </div>
                    </div>

                     <div className="grid grid-cols-3 gap-6 mb-6">
                        {/* Column 1 */}
                        <div className="space-y-6">
                             <div className="text-sm">
                                <h3 className="font-bold text-gray-600 mb-1">Special Educational Needs</h3>
                                <p className="text-xs text-gray-500 mb-2">Does this learner have Educational Needs?</p>
                                <div className="flex space-x-4"><label><input type="radio" name="sped" className="mr-1"/> Yes</label><label><input type="radio" name="sped" className="mr-1" defaultChecked/> No</label></div>
                            </div>
                            <div>
                               <label className="block text-xs font-bold text-gray-600 mb-1">Classification/Type of Learner Special Educational Needs (LSEN)</label>
                               <SelectInput><option>-- Select --</option></SelectInput>
                            </div>
                             <FormSection title="Alternative Delivery Mode">
                                <label className="flex items-center text-sm"><input type="radio" name="adm" className="mr-2"/>Open high School Program(OHSP)</label>
                                <label className="flex items-center text-sm"><input type="radio" name="adm" className="mr-2"/>Other School Initiated Intervention</label>
                                <button className="bg-gray-200 text-gray-600 px-3 py-1 rounded-sm text-xs border border-gray-300">Not Applicable</button>
                            </FormSection>
                        </div>
                        {/* Column 2 */}
                        <div className="space-y-6">
                             <div className="text-sm">
                                <h3 className="font-bold text-gray-600 mb-1">Vaccination</h3>
                                <p className="text-xs text-gray-500 mb-2">Is the learner vaccinated against COVID-19?</p>
                                <div className="flex space-x-4"><label><input type="radio" name="vax" className="mr-1" defaultChecked/> Yes</label><label><input type="radio" name="vax" className="mr-1"/> No</label></div>
                            </div>
                             <div className="space-y-2 text-sm">
                                <div className="flex items-center space-x-2">
                                    <span className="w-20">1st Shot</span>
                                    <SelectInput defaultValue="February">
                                        {vaxMonths.map(month => <option key={`1st-${month}`} value={month}>{month}</option>)}
                                    </SelectInput>
                                    <SelectInput defaultValue="8">
                                        {vaxDays.map(day => <option key={`1st-${day}`} value={day}>{day}</option>)}
                                    </SelectInput>
                                    <SelectInput defaultValue="2021">
                                        {vaxYears.map(year => <option key={`1st-${year}`} value={year}>{year}</option>)}
                                    </SelectInput>
                                </div>
                                <div className="flex items-center space-x-2">
                                    <span className="w-20">Full Vaccination</span>
                                    <SelectInput defaultValue="March">
                                         {vaxMonths.map(month => <option key={`full-${month}`} value={month}>{month}</option>)}
                                    </SelectInput>
                                    <SelectInput defaultValue="1">
                                         {vaxDays.map(day => <option key={`full-${day}`} value={day}>{day}</option>)}
                                    </SelectInput>
                                    <SelectInput defaultValue="2021">
                                         {vaxYears.map(year => <option key={`full-${year}`} value={year}>{year}</option>)}
                                    </SelectInput>
                                </div>
                                <p className="text-xs text-gray-500 pt-2">* If the learner was vaccinated with Janssen, enter the date under full vaccination</p>
                            </div>
                        </div>
                        {/* Column 3 */}
                        <div className="space-y-6">
                            <div>
                                <FormField label="Actual Modality">
                                    <SelectInput defaultValue="Online">
                                        {modalities.map(m => <option key={m} value={m}>{m}</option>)}
                                    </SelectInput>
                                </FormField>
                            </div>
                        </div>
                    </div>


                    <div className="flex justify-between items-center mt-6 border-t pt-4">
                        <button onClick={() => onNavigate('finalEnrolment')} className="bg-gray-50 border border-gray-300 px-4 py-1.5 rounded-sm text-sm shadow-sm hover:bg-gray-200">Cancel</button>
                        <button onClick={handleEnrolClick} className="px-6 py-1.5 bg-[#337ab7] text-white border border-[#2e6da4] rounded-sm hover:bg-[#286090] text-sm">Enrol</button>
                    </div>
                </div>
            </div>
        </main>
    );
};

const DetailedEnrolmentFormPage: React.FC<{ learner: SearchResult; onSignOut: () => void; onNavigate: (page: string) => void; onEnrol: (learner: SearchResult) => void; selectedClass: string; }> = (props) => {
     return (
        <div className="bg-[#f5f5f5] min-h-screen font-sans text-gray-800 flex flex-col">
            <AppHeader onSignOut={props.onSignOut} />
            <MasterlistSubHeader onNavigate={props.onNavigate} title="Enrolment" />
            <DetailedEnrolmentFormContent {...props} />
            <AppFooter />
        </div>
    );
};



// --- Main App Component ---

function App() {
  // FIX: The `User` type is a named export in Firebase v9+, so it should be used directly.
  const [user, setUser] = useState<User | null>(null);
  const [authLoading, setAuthLoading] = useState(true);
  const [currentPage, setCurrentPage] = useState('dashboard');
  const [selectedLearner, setSelectedLearner] = useState<SearchResult | null>(null);
  const [selectedClass, setSelectedClass] = useState('Grade 11 – Bezos');
  const [enrolledLearners, setEnrolledLearners] = useState<SearchResult[]>([]);
  const [dataLoading, setDataLoading] = useState(false);

  useEffect(() => {
    // FIX: `onAuthStateChanged` is a named export in Firebase v9+, so it should be called directly.
    const unsubscribe = onAuthStateChanged(auth, (currentUser) => {
        setUser(currentUser);
        setAuthLoading(false);
        if (currentUser) {
            setCurrentPage('dashboard');
        }
    });
    return () => unsubscribe();
  }, []);

  useEffect(() => {
      if (user && selectedClass) {
          const fetchLearners = async () => {
              setDataLoading(true);
              const learnersCollectionRef = collection(db, 'learners');
              const q = query(learnersCollectionRef, where("section", "==", selectedClass));
              try {
                  const querySnapshot = await getDocs(q);
                  const learnersList = querySnapshot.docs.map(doc => ({
                      docId: doc.id,
                      ...doc.data()
                  })) as SearchResult[];
                  
                  // If no learners are found in Firestore for this specific section, show the sample data.
                  if (learnersList.length === 0 && selectedClass === 'Grade 11 – Bezos') {
                    setEnrolledLearners(sampleEnrolledLearners);
                  } else {
                    setEnrolledLearners(learnersList);
                  }

              } catch (error) {
                  console.error("Error fetching learners:", error);
                  // Also show sample learners on error for a better UX
                   if (selectedClass === 'Grade 11 – Bezos') {
                        setEnrolledLearners(sampleEnrolledLearners);
                   }
              } finally {
                  setDataLoading(false);
              }
          };
          fetchLearners();
      } else {
          setEnrolledLearners([]);
      }
  }, [user, selectedClass]);

  const handleSignOut = async () => {
    try {
        // FIX: `signOut` is a named export in Firebase v9+, so it should be called directly.
        await signOut(auth);
    } catch(error) {
        console.error("Sign out error", error);
    }
  };
  
  const handleNavigate = (page: string, data?: any) => {
    if ((page === 'finalEnrolment' || page === 'detailedEnrolment') && data) {
        setSelectedLearner(data as SearchResult);
    }
    setCurrentPage(page);
  };

  const handleEnrolLearner = async (learnerToEnrol: SearchResult) => {
      // Prevent enrolling sample data
      if (learnerToEnrol.docId?.startsWith('sample-')) {
          alert('This is sample data and cannot be re-enrolled.');
          return;
      }
      if (enrolledLearners.some(learner => learner.lrn === learnerToEnrol.lrn && !learner.docId?.startsWith('sample-'))) {
          alert('A learner with this LRN is already enrolled in this section.');
          return;
      }
      try {
          const learnersCollectionRef = collection(db, 'learners');
          // Create a new object with the section and without the docId for adding to Firestore
          const { docId, ...learnerData } = learnerToEnrol;
          const dataToSave = { ...learnerData, section: selectedClass };

          const docRef = await addDoc(learnersCollectionRef, dataToSave);
          
          // Refresh the list from Firestore to show the new learner and remove samples
           const q = query(collection(db, 'learners'), where("section", "==", selectedClass));
           const querySnapshot = await getDocs(q);
           const learnersList = querySnapshot.docs.map(doc => ({
               docId: doc.id,
               ...doc.data()
           })) as SearchResult[];
           setEnrolledLearners(learnersList);


          alert('Learner successfully enrolled!');
          setCurrentPage('masterlist');
      } catch (error) {
          console.error("Error enrolling learner:", error);
          alert('Failed to enrol learner. Please try again.');
      }
  };

  if (authLoading) {
      return <div className="flex justify-center items-center min-h-screen">Loading...</div>;
  }

  if (user) {
    switch (currentPage) {
        case 'masterlist':
            return <MasterlistPage 
                onSignOut={handleSignOut} 
                onNavigate={handleNavigate} 
                enrolledLearners={enrolledLearners}
                sectionData={sectionData}
                selectedClass={selectedClass}
                setSelectedClass={setSelectedClass}
            />;
        case 'enrolment':
            return <EnrolmentPage onSignOut={handleSignOut} onNavigate={handleNavigate} selectedClass={selectedClass} />;
        case 'search':
            return <SearchPage onNavigate={handleNavigate} />;
        case 'finalEnrolment':
            if (selectedLearner) {
                return <FinalEnrolmentPage learner={selectedLearner} onSignOut={handleSignOut} onNavigate={handleNavigate} selectedClass={selectedClass} />;
            }
             // Fallback if no learner is selected
            return <MasterlistPage onSignOut={handleSignOut} onNavigate={handleNavigate} enrolledLearners={enrolledLearners} sectionData={sectionData} selectedClass={selectedClass} setSelectedClass={setSelectedClass} />;
        case 'detailedEnrolment':
             if (selectedLearner) {
                return <DetailedEnrolmentFormPage learner={selectedLearner} onSignOut={handleSignOut} onNavigate={handleNavigate} onEnrol={handleEnrolLearner} selectedClass={selectedClass} />;
            }
            // Fallback
            return <MasterlistPage onSignOut={handleSignOut} onNavigate={handleNavigate} enrolledLearners={enrolledLearners} sectionData={sectionData} selectedClass={selectedClass} setSelectedClass={setSelectedClass} />;
        case 'dashboard':
        default:
            return <DashboardPage onSignOut={handleSignOut} onNavigate={handleNavigate} />;
    }
  }

  return <SignInPage />;
}

export default App;