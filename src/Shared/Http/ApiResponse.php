<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponse extends JsonResponse
{
    /**
     * @param mixed[]  $errors
     * @param string[] $headers
     */
    public function __construct(string $message, mixed $data = null, array $errors = [], int $status = 200, array $headers = [], bool $json = false)
    {
        parent::__construct($this->format($message, $data, $errors), $status, $headers, $json);
    }

    /**
     * @param mixed[] $errors
     *
     * @return mixed[]
     */
    private function format(string $message, mixed $data = null, array $errors = []): array
    {
        if (null === $data) {
            $data = new \ArrayObject();
        }

        $response = [
            'message' => $message,
            'data' => $data,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return $response;
    }
}
