<?php

namespace IRC\Services;

use GuzzleHttp\Client;
use IRC\Config\Env;

class SupabaseService
{
    private Client $client;
    private string $url;
    private string $serviceKey;
    private bool $isDemoMode;

    public static string $fallbackDbFile = __DIR__ . '/../../database/local_store.json';

    public function __construct()
    {
        $this->url = rtrim(Env::get('SUPABASE_URL', ''), '/');
        $this->serviceKey = Env::get('SUPABASE_SERVICE_ROLE_KEY', '');
        $this->isDemoMode = empty($this->url) || str_contains($this->url, 'demo.supabase.co');

        if (!$this->isDemoMode) {
            $this->client = new Client([
                'base_uri' => $this->url . '/rest/v1/',
                'headers' => [
                    'apikey' => $this->serviceKey,
                    'Authorization' => 'Bearer ' . $this->serviceKey,
                    'Content-Type' => 'application/json',
                    'Prefer' => 'return=representation'
                ],
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
                        'id' => '00000000-0000-0000-0000-000000000001',
                        'email' => 'student@irc.co.zw',
                        'full_name' => 'Kudzai Moyo',
                        'tier' => 'scholar_monthly',
                        'credits' => 500,
                        'created_at' => date('Y-m-d H:i:s')
                    ]
                ],
                'workspaces' => [],
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

    public function isDemo(): bool
    {
        return $this->isDemoMode;
    }

    public function insert(string $table, array $data): array
    {
        if ($this->isDemoMode) {
            return $this->insertLocal($table, $data);
        }

        try {
            $response = $this->client->post($table, [
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true) ?: [$data];
        } catch (\Throwable $e) {
            // Fallback to local store on network error
            return $this->insertLocal($table, $data);
        }
    }

    public function select(string $table, array $params = []): array
    {
        if ($this->isDemoMode) {
            return $this->selectLocal($table, $params);
        }

        try {
            $response = $this->client->get($table, [
                'query' => $params
            ]);
            return json_decode($response->getBody()->getContents(), true) ?: [];
        } catch (\Throwable $e) {
            return $this->selectLocal($table, $params);
        }
    }

    public function rpc(string $functionName, array $params = []): array
    {
        if ($this->isDemoMode) {
            return $this->matchDocumentChunksLocal($params);
        }

        try {
            $response = $this->client->post("rpc/{$functionName}", [
                'json' => $params
            ]);
            return json_decode($response->getBody()->getContents(), true) ?: [];
        } catch (\Throwable $e) {
            return $this->matchDocumentChunksLocal($params);
        }
    }

    private function insertLocal(string $table, array $data): array
    {
        $db = json_decode(file_get_contents(self::$fallbackDbFile), true) ?: [];
        if (!isset($db[$table])) {
            $db[$table] = [];
        }
        if (!isset($data['id'])) {
            $data['id'] = sprintf(
                '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0x0fff) | 0x4000,
                mt_rand(0, 0x3fff) | 0x8000,
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
            );
        }
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        $db[$table][] = $data;
        file_put_contents(self::$fallbackDbFile, json_encode($db, JSON_PRETTY_PRINT));
        return [$data];
    }

    private function selectLocal(string $table, array $params = []): array
    {
        $db = json_decode(file_get_contents(self::$fallbackDbFile), true) ?: [];
        $rows = $db[$table] ?? [];

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

    private function matchDocumentChunksLocal(array $params): array
    {
        $db = json_decode(file_get_contents(self::$fallbackDbFile), true) ?: [];
        $chunks = $db['document_chunks'] ?? [];
        $workspaceId = $params['p_workspace_id'] ?? '';
        
        $filtered = array_filter($chunks, fn($c) => ($c['workspace_id'] ?? '') === $workspaceId);
        
        $results = [];
        foreach ($filtered as $chunk) {
            $results[] = [
                'id' => $chunk['id'],
                'document_id' => $chunk['document_id'],
                'content' => $chunk['content'],
                'page_number' => $chunk['page_number'] ?? 1,
                'similarity' => 0.88 // Default mock similarity for local offline mode
            ];
        }

        return array_slice($results, 0, $params['match_count'] ?? 5);
    }
}
