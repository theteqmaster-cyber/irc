<?php

require_once __DIR__ . '/../vendor/autoload.php';

use IRC\Config\Env;
use IRC\Controllers\WorkspaceController;
use IRC\Controllers\DocumentController;
use IRC\Controllers\ChatController;
use IRC\Controllers\StudyController;
use IRC\Controllers\PaymentController;

Env::load();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit();
}

switch ($uri) {
    case '/api/workspaces':
        $controller = new WorkspaceController();
        if ($method === 'POST') {
            $controller->createWorkspace();
        } else {
            $controller->listWorkspaces();
        }
        break;

    case '/api/documents':
        $controller = new DocumentController();
        if ($method === 'POST') {
            $controller->uploadDocument();
        } else {
            $controller->listDocuments();
        }
        break;

    case '/api/chat':
        if ($method === 'POST') {
            (new ChatController())->askQuestion();
        }
        break;

    case '/api/flashcards':
        $controller = new StudyController();
        if ($method === 'POST') {
            $controller->generateFlashcards();
        } else {
            $controller->getFlashcards();
        }
        break;

    case '/api/feynman':
        if ($method === 'POST') {
            (new StudyController())->evaluateFeynman();
        }
        break;

    case '/api/quiz':
        (new StudyController())->getQuiz();
        break;

    case '/api/rubric':
        if ($method === 'POST') {
            (new StudyController())->gradeRubric();
        }
        break;

    case '/api/payments/initiate':
        if ($method === 'POST') {
            (new PaymentController())->initiate();
        }
        break;

    case '/api/payments/poll':
        (new PaymentController())->poll();
        break;

    case '/api/studio/irac':
        if ($method === 'POST') {
            (new StudyController())->evaluateIRAC();
        }
        break;

    case '/api/studio/blurting':
        if ($method === 'POST') {
            (new StudyController())->evaluateBlurting();
        }
        break;

    case '/api/studio/decoder':
        (new StudyController())->getLecturerDecoder();
        break;


    default:
        require_once __DIR__ . '/../src/Views/app.php';
        break;
}
