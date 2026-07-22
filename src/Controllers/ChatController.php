<?php

namespace IRC\Controllers;

use IRC\Services\FirebaseService;
use IRC\Services\AIService;

class ChatController
{
    private FirebaseService $firebase;
    private AIService $ai;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
        $this->ai = new AIService();
    }

    public function askQuestion(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? '';
        $question = trim($input['question'] ?? '');

        if (empty($workspaceId) || empty($question)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'workspace_id and question are required.']);
            return;
        }

        // Retrieve chunks from Firebase
        $relevantChunks = $this->firebase->queryChunks($workspaceId, 5);

        // Generate Groq Llama 3.3 70B response
        $ragResult = $this->ai->generateRAGResponse($question, $relevantChunks);

        // Record session in Firebase
        $session = $this->firebase->insert('chat_sessions', [
            'workspace_id' => $workspaceId,
            'title' => mb_substr($question, 0, 30) . '...'
        ]);
        $sessionId = $session[0]['id'] ?? 'sess_' . uniqid();

        $this->firebase->insert('chat_messages', [
            'session_id' => $sessionId,
            'sender' => 'user',
            'message' => $question
        ]);

        $this->firebase->insert('chat_messages', [
            'session_id' => $sessionId,
            'sender' => 'ai',
            'message' => $ragResult['message'],
            'citations' => json_encode($ragResult['citations'])
        ]);

        echo json_encode([
            'status' => 'success',
            'data' => [
                'session_id' => $sessionId,
                'question' => $question,
                'answer' => $ragResult['message'],
                'citations' => $ragResult['citations']
            ]
        ]);
    }
}
