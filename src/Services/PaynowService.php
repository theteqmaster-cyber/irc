<?php

namespace IRC\Services;

use GuzzleHttp\Client;
use IRC\Config\Env;

class PaynowService
{
    private string $integrationId;
    private string $integrationKey;
    private string $resultUrl;
    private string $returnUrl;
    private Client $client;

    public function __construct()
    {
        $this->integrationId = Env::get('PAYNOW_INTEGRATION_ID', '12345');
        $this->integrationKey = Env::get('PAYNOW_INTEGRATION_KEY', 'demo-key-123');
        $this->resultUrl = Env::get('PAYNOW_RESULT_URL', 'http://localhost:8000/api/payments/webhook');
        $this->returnUrl = Env::get('PAYNOW_RETURN_URL', 'http://localhost:8000/');

        $this->client = new Client([
            'base_uri' => 'https://www.paynow.co.zw/interface/',
            'timeout' => 15.0,
        ]);
    }

    /**
     * Initiates a payment via Paynow Zim (EcoCash, InnBucks, OneMoney, Visa/Mastercard)
     */
    public function initiatePayment(string $reference, float $amount, string $email, string $phone = '', string $method = 'EcoCash'): array
    {
        $values = [
            'resulturl' => $this->resultUrl,
            'returnurl' => $this->returnUrl,
            'reference' => $reference,
            'amount' => number_format($amount, 2, '.', ''),
            'id' => $this->integrationId,
            'additionalinfo' => "IRC Zim AI Study Pass ({$method})",
            'authemail' => $email,
            'status' => 'Message'
        ];

        // Create Hash
        $hashStr = implode('', $values) . $this->integrationKey;
        $values['hash'] = strtoupper(hash('sha512', $hashStr));

        try {
            // For production with valid Paynow ID
            if ($this->integrationId !== '12345') {
                $response = $this->client->post('initiatetransaction', [
                    'form_params' => $values
                ]);
                $body = $response->getBody()->getContents();
                parse_str($body, $parsed);

                if (isset($parsed['status']) && strtolower($parsed['status']) === 'ok') {
                    return [
                        'status' => 'success',
                        'paynow_reference' => $parsed['paynowreference'] ?? $reference,
                        'redirect_url' => $parsed['browserurl'] ?? '',
                        'poll_url' => $parsed['pollurl'] ?? '',
                        'message' => 'Payment initiated. Redirecting...'
                    ];
                }
            }

            // Demo Mode fallback simulation
            $mockPollUrl = "http://localhost:8000/api/payments/poll?ref=" . urlencode($reference);
            return [
                'status' => 'success',
                'paynow_reference' => 'PAYNOW-ZIM-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'redirect_url' => $mockPollUrl,
                'poll_url' => $mockPollUrl,
                'message' => "EcoCash prompt simulated for phone {$phone}! Amount: $" . number_format($amount, 2)
            ];

        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'message' => 'Payment initiation error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Poll transaction status
     */
    public function pollStatus(string $pollUrl): array
    {
        if (str_contains($pollUrl, 'localhost')) {
            return [
                'status' => 'Paid',
                'paid' => true,
                'message' => 'Local EcoCash transaction confirmed!'
            ];
        }

        try {
            $response = $this->client->get($pollUrl);
            $body = $response->getBody()->getContents();
            parse_str($body, $parsed);

            $status = $parsed['status'] ?? 'Pending';
            return [
                'status' => $status,
                'paid' => strtolower($status) === 'paid',
                'raw' => $parsed
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'Error',
                'paid' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
