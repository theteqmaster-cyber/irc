<?php

namespace IRC\Controllers;

use IRC\Services\FirebaseService;

class WorkspaceController
{
    private FirebaseService $firebase;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
    }

    public function listWorkspaces(): void
    {
        $workspaces = $this->firebase->select('workspaces');
        
        if (empty($workspaces)) {
            $created = $this->firebase->insert('workspaces', [
                'name' => 'ZIMSEC A-Level Physics & Maths',
                'description' => 'Calculus, Mechanics, Waves & Electromagnetism study vault.',
                'category' => 'ZIMSEC A-Level'
            ]);
            $workspaces = $created;
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $workspaces]);
    }

    public function createWorkspace(): void
    {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        
        $name = trim($input['name'] ?? '');
        $description = trim($input['description'] ?? '');
        $category = trim($input['category'] ?? 'General');

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Workspace name is required.']);
            return;
        }

        $result = $this->firebase->insert('workspaces', [
            'name' => $name,
            'description' => $description,
            'category' => $category
        ]);

        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'data' => $result[0] ?? $result]);
    }
}
