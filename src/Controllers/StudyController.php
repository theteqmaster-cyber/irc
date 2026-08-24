<?php

namespace IRC\Controllers;

use IRC\Services\FirebaseService;
use IRC\Services\AIService;

class StudyController
{
    private FirebaseService $firebase;
    private AIService $ai;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
        $this->ai = new AIService();
    }

    public function generateFlashcards(): void
    {
        header('Content-Type: application/json');
        $workspaceId = $_GET['workspace_id'] ?? $_POST['workspace_id'] ?? '';

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        
        $contextText = "";
        foreach ($chunks as $chunk) {
            $contextText .= $chunk['content'] . "\n";
        }
        if (empty($contextText)) {
            $contextText = "Newton's Laws physics course notes for ZIMSEC A-Level.";
        }

        $cardsData = $this->ai->generateFlashcards($contextText);

        $savedCards = [];
        foreach ($cardsData as $card) {
            $res = $this->firebase->insert('flashcards', [
                'workspace_id' => $workspaceId,
                'question' => $card['question'] ?? 'Sample question?',
                'answer' => $card['answer'] ?? 'Sample answer.',
                'page_reference' => $card['page_reference'] ?? 1
            ]);
            $savedCards[] = $res[0] ?? $res;
        }

        echo json_encode(['status' => 'success', 'data' => $savedCards]);
    }

    public function getFlashcards(): void
    {
        header('Content-Type: application/json');
        $workspaceId = $_GET['workspace_id'] ?? '';
        
        $cards = $this->firebase->select('flashcards', ['workspace_id' => 'eq.' . $workspaceId]);
        if (empty($cards)) {
            $cards = [
                [
                    'id' => 'card_1',
                    'question' => "Explain Newton's 2nd Law of Motion (F=ma) as applied to a moving vehicle.",
                    'answer' => 'Net force equals mass times acceleration (or rate of change of momentum). Acceleration increases linearly with applied net force.',
                    'page_reference' => 1
                ],
                [
                    'id' => 'card_2',
                    'question' => 'Define Impulse and state its SI unit.',
                    'answer' => 'Impulse is the product of force and time duration (I = F * delta_t). SI unit is Newton-seconds (N s).',
                    'page_reference' => 2
                ]
            ];
        }

        echo json_encode(['status' => 'success', 'data' => $cards]);
    }

    public function evaluateFeynman(): void
    {
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? '';
        $topic = trim($input['topic'] ?? '');
        $explanation = trim($input['explanation'] ?? '');

        if (empty($topic) || empty($explanation)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Topic and explanation are required.']);
            return;
        }

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $eval = $this->ai->evaluateFeynman($topic, $explanation, $contextText);

        $this->firebase->insert('feynman_logs', [
            'workspace_id' => $workspaceId,
            'topic' => $topic,
            'student_explanation' => $explanation,
            'ai_feedback' => $eval['feedback'],
            'mastery_score' => $eval['mastery_score']
        ]);

        echo json_encode([
            'status' => 'success',
            'data' => [
                'topic' => $topic,
                'mastery_score' => $eval['mastery_score'],
                'feedback' => $eval['feedback']
            ]
        ]);
    }

    public function getQuiz(): void
    {
        header('Content-Type: application/json');
        $workspaceId = $_GET['workspace_id'] ?? $_POST['workspace_id'] ?? '';

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $quiz = $this->ai->generateExamQuiz($contextText);
        echo json_encode(['status' => 'success', 'data' => $quiz]);
    }

    public function gradeRubric(): void
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        $essay = trim($input['essay'] ?? '');
        $rubric = trim($input['rubric'] ?? 'Grading criteria: Clarity (30%), Accuracy (40%), Evidence (30%)');

        if (empty($essay)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Essay draft is required.']);
            return;
        }

        $result = $this->ai->gradeEssayRubric($essay, $rubric);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function evaluateIRAC(): void
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? '';
        $facts = trim($input['facts'] ?? '');

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $result = $this->ai->runIRACStudio($facts, $contextText);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function evaluateBlurting(): void
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? '';
        $topic = trim($input['topic'] ?? 'General Module Topics');
        $braindump = trim($input['braindump'] ?? '');

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $result = $this->ai->runBlurtingAudit($topic, $braindump, $contextText);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function getLecturerDecoder(): void
    {
        header('Content-Type: application/json');
        $workspaceId = $_GET['workspace_id'] ?? $_POST['workspace_id'] ?? '';

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $result = $this->ai->runLecturerDecoder($contextText);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function solve(): void
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? '';
        $question = trim($input['question'] ?? '');
        $subject = trim($input['subject'] ?? 'General');
        $deepReasoning = !empty($input['deep_reasoning']);

        if (empty($question)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Question is required.']);
            return;
        }

        $contextText = "";
        if (!empty($workspaceId)) {
            $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
            foreach ($chunks as $c) {
                $contextText .= $c['content'] . "\n";
            }
        }

        $result = $this->ai->solveProblem($question, $subject, $deepReasoning, $contextText);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function generateMockExam(): void
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? ($_GET['workspace_id'] ?? '');
        $subject = trim($input['subject'] ?? 'ZIMSEC / University Module');

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $exam = $this->ai->generateTimedMockExam($subject, $contextText);
        echo json_encode(['status' => 'success', 'data' => $exam]);
    }

    public function socraticDefense(): void
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? '';
        $topic = trim($input['topic'] ?? 'General Concept');
        $argument = trim($input['argument'] ?? '');

        if (empty($argument)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Argument is required.']);
            return;
        }

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $result = $this->ai->evaluateSocraticDefense($topic, $argument, $contextText);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }

    public function sq3rGuidance(): void
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $workspaceId = $input['workspace_id'] ?? '';
        $topic = trim($input['topic'] ?? 'Core Section');
        $step = trim($input['step'] ?? 'Survey');

        $chunks = $this->firebase->select('document_chunks', ['workspace_id' => 'eq.' . $workspaceId]);
        $contextText = "";
        foreach ($chunks as $c) {
            $contextText .= $c['content'] . "\n";
        }

        $result = $this->ai->runSQ3RGuidance($topic, $contextText, $step);
        echo json_encode(['status' => 'success', 'data' => $result]);
    }
}

