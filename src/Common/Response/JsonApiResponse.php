<?php

namespace App\Common\Response;

/**
 * Класс формирования результатов запроса к API.
 * Отображает результат в JSON формате.
 *
 */
class JsonApiResponse extends ApiResponse implements ApiResponseInterface
{
    /**
     * {@inheritDoc}
     * @see \App\Common\Response\ApiResponse::display()
     */
    public function display(): void
    {
        header("HTTP/1.1 200 Ok");
        header('Content-Type: application/json');

        echo json_encode([
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ]);
    }
}
