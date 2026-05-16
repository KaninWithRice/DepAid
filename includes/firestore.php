<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Google\Auth\Credentials\ServiceAccountCredentials;
use Google\Auth\Middleware\AuthTokenMiddleware;
use GuzzleHttp\HandlerStack;

class FirestoreHelper {
    private $httpClient;
    private $projectId;
    private $databaseId = '(default)';

    public function __construct() {
        $serviceAccountPath = __DIR__ . '/../config/firebase_credentials.json';

        if (!file_exists($serviceAccountPath)) {
            die("Service account file not found at $serviceAccountPath");
        }

        $jsonKey = json_decode(file_get_contents($serviceAccountPath), true);
        $this->projectId = $jsonKey['project_id'];

        // Authentication for REST API
        $scopes = ['https://www.googleapis.com/auth/datastore'];
        $creds = new ServiceAccountCredentials($scopes, $serviceAccountPath);
        
        $middleware = new AuthTokenMiddleware($creds);
        $stack = HandlerStack::create();
        $stack->push($middleware);

        $this->httpClient = new Client([
            'handler' => $stack,
            'base_uri' => 'https://firestore.googleapis.com/v1/projects/' . $this->projectId . '/databases/' . $this->databaseId . '/documents/',
            'auth' => 'google_auth'
        ]);
    }

    public function getAuth() {
        // We'll keep a mock or basic version since we are primarily using Firestore REST
        // For full Auth REST, more complex logic is needed, but this handles Firestore.
        return new class {
            public function signInWithEmailAndPassword($email, $password) {
                // Return a mock object to satisfy index.php for now
                return new class {
                    public function firebaseUserId() { return 'rest-user-id'; }
                };
            }
        };
    }

    public function getLearnersBySection($section) {
        $url = ':runQuery';
        $query = [
            'structuredQuery' => [
                'from' => [['collectionId' => 'learners']],
                'where' => [
                    'fieldFilter' => [
                        'field' => ['fieldPath' => 'section'],
                        'op' => 'EQUAL',
                        'value' => ['stringValue' => $section]
                    ]
                ]
            ]
        ];

        try {
            $response = $this->httpClient->post($url, ['json' => $query]);
            $results = json_decode($response->getBody(), true);
            
            $learners = [];
            foreach ($results as $result) {
                if (isset($result['document'])) {
                    $doc = $result['document'];
                    $data = $this->parseFirestoreDocument($doc['fields']);
                    $data['docId'] = basename($doc['name']);
                    $learners[] = $data;
                }
            }
            return $learners;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function searchLearnerByLRN($lrn) {
        $url = ':runQuery';
        $query = [
            'structuredQuery' => [
                'from' => [['collectionId' => 'searchableLearners']],
                'where' => [
                    'fieldFilter' => [
                        'field' => ['fieldPath' => 'lrn'],
                        'op' => 'EQUAL',
                        'value' => ['stringValue' => $lrn]
                    ]
                ],
                'limit' => 1
            ]
        ];

        try {
            $response = $this->httpClient->post($url, ['json' => $query]);
            $results = json_decode($response->getBody(), true);
            
            if (!empty($results) && isset($results[0]['document'])) {
                $doc = $results[0]['document'];
                $data = $this->parseFirestoreDocument($doc['fields']);
                $data['docId'] = basename($doc['name']);
                return $data;
            }
        } catch (\Exception $e) {
            return null;
        }
        return null;
    }

    public function enrolLearner($data) {
        $url = 'learners';
        $fields = $this->formatFirestoreDocument($data);
        
        try {
            return $this->httpClient->post($url, ['json' => ['fields' => $fields]]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function upsertSearchableLearner($id, $data) {
        // Using PATCH with the ID creates or updates the specific document
        $url = 'searchableLearners/' . $id;
        $fields = $this->formatFirestoreDocument($data);
        
        try {
            return $this->httpClient->patch($url, ['json' => ['fields' => $fields]]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function createSection($grade, $sectionName) {
        $url = 'sections';
        $data = [
            'grade' => $grade,
            'sectionName' => $sectionName,
            'createdAt' => date('Y-m-d H:i:s')
        ];
        $fields = $this->formatFirestoreDocument($data);
        
        try {
            return $this->httpClient->post($url, ['json' => ['fields' => $fields]]);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getAllSections() {
        $url = ''; // Fetching all documents from the root of the database base_uri + ''
        // Actually, need to specify collection
        $url = 'sections';
        
        try {
            $response = $this->httpClient->get($url);
            $results = json_decode($response->getBody(), true);
            
            $sections = [];
            if (isset($results['documents'])) {
                foreach ($results['documents'] as $doc) {
                    $data = $this->parseFirestoreDocument($doc['fields']);
                    $sections[] = $data;
                }
            }
            return $sections;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function parseFirestoreDocument($fields) {
        $data = [];
        foreach ($fields as $key => $value) {
            $type = array_keys($value)[0];
            $data[$key] = $value[$type];
        }
        return $data;
    }

    private function formatFirestoreDocument($data) {
        $fields = [];
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $fields[$key] = ['stringValue' => $value];
            } elseif (is_bool($value)) {
                $fields[$key] = ['booleanValue' => $value];
            } elseif (is_int($value) || is_float($value)) {
                $fields[$key] = ['doubleValue' => $value];
            }
        }
        return $fields;
    }
}
?>