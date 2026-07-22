<?php

namespace IRC\Services;

use GuzzleHttp\Client;
use IRC\Config\Env;

class FirebaseService
{
    private Client $client;
    private string $projectId;
    private string $apiKey;
    private bool $isDemoMode;

    public static string $fallbackDbFile = __DIR__ . '/../../database/firebase_local_store.json';

    public function __construct()
    {
        $this->projectId = Env::get('FIREBASE_PROJECT_ID', 'irc-zim-study-ai');
        $this->apiKey = Env::get('FIREBASE_API_KEY', '');
        $this->isDemoMode = empty($this->apiKey) || $this->apiKey === 'demo_firebase_key';

        if (!$this->isDemoMode) {
            $this->client = new Client([
                'base_uri' => "https://firestore.googleapis.com/v1/projects/{$this->projectId}/databases/(default)/documents/",
                'timeout' => 15.0,
            ]);
        }

        $this->initLocalStore();
    }

    private function initLocalStore(): void
    {
        $dir = dirname(self::$fallbackDbFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (!file_exists(self::$fallbackDbFile)) {
            $initialData = [
                'users' => [
                    [
                        'id' => 'user_001',
                        'email' => 'student@irc.co.zw',
                        'full_name' => 'Kudzai Moyo',
                        'tier' => 'full_free_trial',
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                ],
                'workspaces' => [
                    [
                        'id' => 'ws_physics_001',
                        'name' => 'ZIMSEC A-Level Physics & Maths',
                        'description' => 'Calculus, Mechanics, Waves & Electromagnetism study vault.',
                        'category' => 'ZIMSEC A-Level',
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                ],
                'documents' => [],
                'document_chunks' => [],
                'chat_sessions' => [],
                'chat_messages' => [],
                'flashcards' => [],
                'feynman_logs' => [],
                'payments' => []
            ];
            file_put_contents(self::$fallbackDbFile, json_encode($initialData, JSON_PRETTY_PRINT));
        }
    }

    public function insert(string $collection, array $data): array
    {
        if ($this->isDemoMode) {
            return $this->insertLocal($collection, $data);
        }

        try {
            if (!isset($data['id'])) {
                $data['id'] = 'fb_' . uniqid();
            }
            $data['created_at'] = date('Y-m-d H:i:s');

            $firestoreFields = $this->convertToFirestoreFields($data);
            $response = $this->client->post("{$collection}?documentId={$data['id']}&key={$this->apiKey}", [
                'json' => ['fields' => $firestoreFields]
            ]);

            return [$data];
        } catch (\Throwable $e) {
            return $this->insertLocal($collection, $data);
        }
    }

    public function select(string $collection, array $params = []): array
    {
        if ($this->isDemoMode) {
            return $this->selectLocal($collection, $params);
        }

        try {
            $response = $this->client->get("{$collection}?key={$this->apiKey}");
            $body = json_decode($response->getBody()->getContents(), true);
            $documents = $body['documents'] ?? [];

            $rows = [];
            foreach ($documents as $doc) {
                $rows[] = $this->convertFromFirestoreFields($doc['fields'] ?? []);
            }

            if (isset($params['workspace_id'])) {
                $val = str_replace('eq.', '', $params['workspace_id']);
                $rows = array_values(array_filter($rows, fn($r) => ($r['workspace_id'] ?? '') === $val));
            }

            return $rows;
        } catch (\Throwable $e) {
            return $this->selectLocal($collection, $params);
        }
    }

    public function queryChunks(string $workspaceId, int $limit = 5): array
    {
        $chunks = $this->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        return array_slice($chunks, 0, $limit);
    }

    private function insertLocal(string $collection, array $data): array
    {
        $db = json_decode(file_get_contents(self::$fallbackDbFile), true) ?: [];
        if (!isset($db[$collection])) {
            $db[$collection] = [];
        }
        if (!isset($data['id'])) {
            $data['id'] = 'fb_' . uniqid();
        }
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        $db[$collection][] = $data;
        file_put_contents(self::$fallbackDbFile, json_encode($db, JSON_PRETTY_PRINT));
        return [$data];
    }

    private function selectLocal(string $collection, array $params = []): array
    {
        $db = json_decode(file_get_contents(self::$fallbackDbFile), true) ?: [];
        $rows = $db[$collection] ?? [];

        if (isset($params['workspace_id'])) {
            $val = str_replace('eq.', '', $params['workspace_id']);
            $rows = array_values(array_filter($rows, fn($r) => ($r['workspace_id'] ?? '') === $val));
        }

        if (isset($params['id'])) {
            $val = str_replace('eq.', '', $params['id']);
            $rows = array_values(array_filter($rows, fn($r) => ($r['id'] ?? '') === $val));
        }

        return $rows;
    }

    private function convertToFirestoreFields(array $data): array
    {
        $fields = [];
        foreach ($data as $key => $val) {
            if (is_int($val)) {
                $fields[$key] = ['integerValue' => $val];
            } elseif (is_float($val)) {
                $fields[$key] = ['doubleValue' => $val];
            } elseif (is_bool($val)) {
                $fields[$key] = ['booleanValue' => $val];
            } else {
                $fields[$key] = ['stringValue' => (string)$val];
            }
        }
        return $fields;
    }

    private function convertFromFirestoreFields(array $fields): array
    {
        $data = [];
        foreach ($fields as $key => $typeVal) {
            $data[$key] = reset($typeVal);
        }
        return $data;
    }
}
