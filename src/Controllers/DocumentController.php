<?php

namespace IRC\Controllers;

use IRC\Services\FirebaseService;
use IRC\Services\DocumentParserService;
use IRC\Services\AIService;

class DocumentController
{
    private FirebaseService $firebase;
    private DocumentParserService $parser;
    private AIService $ai;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
        $this->parser = new DocumentParserService();
        $this->ai = new AIService();
    }

    public function listDocuments(): void
    {
        $workspaceId = $_GET['workspace_id'] ?? '';
        $params = $workspaceId ? ['workspace_id' => 'eq.' . $workspaceId] : [];
        $docs = $this->firebase->select('documents', $params);

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $docs]);
    }

    public function uploadDocument(): void
    {
        header('Content-Type: application/json');
        
        $workspaceId = $_POST['workspace_id'] ?? '';
        if (empty($workspaceId)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Workspace ID is required.']);
            return;
        }

        $fileName = 'Uploaded_Notes.pdf';
        $tempPath = sys_get_temp_dir() . '/upload_' . uniqid() . '.pdf';
        $mimeType = 'application/pdf';

        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileName = $_FILES['file']['name'];
            $mimeType = $_FILES['file']['type'];
            move_uploaded_file($_FILES['file']['tmp_name'], $tempPath);
        } else {
            $rawText = $_POST['text'] ?? "ZIMSEC A-Level Physics Chapter 1: Newton's Laws of Motion. Page 1: Force equals mass times acceleration (F=ma). Page 2: Friction opposing relative motion.";
            file_put_contents($tempPath, $rawText);
            $fileName = $_POST['title'] ?? 'Sample_Physics_Notes.txt';
            $mimeType = 'text/plain';
        }

        // Save document record in Firebase
        $doc = $this->firebase->insert('documents', [
            'workspace_id' => $workspaceId,
            'file_name' => $fileName,
            'file_path' => $tempPath,
            'file_size' => filesize($tempPath) ?: 1024,
            'file_type' => $mimeType,
            'status' => 'ready'
        ]);

        $docId = $doc[0]['id'] ?? 'doc_' . uniqid();

        // Parse and chunk
        $chunks = $this->parser->parseAndChunk($tempPath, $mimeType);

        $insertedChunksCount = 0;
        foreach ($chunks as $chunk) {
            $this->firebase->insert('document_chunks', [
                'document_id' => $docId,
                'workspace_id' => $workspaceId,
                'chunk_index' => $chunk['chunk_index'],
                'page_number' => $chunk['page_number'],
                'content' => $chunk['content']
            ]);
            $insertedChunksCount++;
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Document indexed into Firebase store successfully!',
            'data' => [
                'document_id' => $docId,
                'file_name' => $fileName,
                'chunks_created' => $insertedChunksCount
            ]
        ]);
    }
}
