<?php
namespace App\LmStudio\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class LmStudioService
{
    private HttpClientInterface $httpClient;
    private string $lmStudioHost;

    public function __construct(HttpClientInterface $httpClient, string $lmStudioHost)
    {
        $this->httpClient = $httpClient;
        $this->lmStudioHost = $lmStudioHost;
    }

    public function response(string $userMessage): array
    {
        $lmStudioUrl = $this->lmStudioHost;

        $payload = [
            'model' => 'llama-3.2-3b-instruct',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $userMessage
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => -1,
            'stream' => false
        ];

        $response = $this->httpClient->request('POST', $lmStudioUrl, [
            'json' => $payload
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error with connection LM Studio');
        }

        $data = $response->toArray();

        return $data['choices'][0]['message'];
    }
}
