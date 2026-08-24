<?php

namespace IRC\Controllers;

use IRC\Services\FirebaseService;

class PaymentController
{
    private FirebaseService $firebase;

    public function __construct()
    {
        $this->firebase = new FirebaseService();
    }

    public function initiate(): void
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $passType = $input['pass_type'] ?? 'pass_supporter';

        $this->firebase->insert('payments', [
            'amount' => $passType === 'scholar_supporter' ? 5.00 : 0.50,
            'status' => 'coming_soon',
            'pass_type' => $passType
        ]);

        echo json_encode([
            'status' => 'success',
            'title' => '🚀 EcoCash Supporter Pass Prompt Initiated',
            'message' => 'Thank you for supporting StudyBee Zimbabwe! EcoCash prompt initiated.',
            'data' => [
                'tier_options' => [
                    ['id' => 'pass_supporter', 'name' => 'Option 1: Pass Supporter Pass', 'price' => '$0.50 EcoCash'],
                    ['id' => 'scholar_supporter', 'name' => 'Option 2: Scholar Supporter Pass', 'price' => '$5.00 EcoCash']
                ],
                'free_trial_status' => '100% FULL ACCESS ACTIVE'
            ]
        ]);
    }

    public function poll(): void
    {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'coming_soon', 'message' => 'Payment polling coming soon. Full free access is currently active!']);
    }
}
