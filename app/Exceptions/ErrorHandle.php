<?php

namespace App\Exceptions;

use Bow\Database\Exception\NotFoundException as ModelNotFoundException;
use Bow\Http\Exception\HttpException;
use Bow\Http\Exception\ResponseException as HttpResponseException;
use Exception;
use PDOException;
use Policier\Exception\TokenExpiredException;
use Policier\Exception\TokenInvalidException;

class ErrorHandle
{
    /**
     * handle the error
     *
     * @param Exception $exception
     * @return void
     */
    public function handle($exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return $this->render('errors.404', ['code' => 404, 'exception' => $exception]);
        }

        if ($exception instanceof HttpResponseException) {
            return $this->render('errors.500', ['code' => 404, 'exception' => $exception]);
        }
    }

    /**
     * Render view as response
     *
     * @param string $view
     * @param array $data
     * @return mixed
     */
    private function render($view, $data = [], $code = 200)
    {
        if (is_numeric($data)) {
            $code = $data;
            $data = [];
        }

        $content = view($view, $data, $code)->getContent();

        $this->send($content);
    }

    /**
    * Send the json as response
    *
    * @param string $data
    * @param mixed $code
    * @return mixed
    */
    private function json($exception, $code = null)
    {
        if ($exception instanceof TokenInvalidException) {
            $code = 'TOKEN_INVALID';
        }

        if ($exception instanceof TokenExpiredException) {
            $code = 'TOKEN_EXPIRED';
        }

        if (is_null($code)) {
            if (method_exists($exception, 'getStatus')) {
                $code = $exception->getStatus();
            } else {
                $code = 'INTERNAL_SERVER_ERROR';
            }
        }

        if (app_env("APP_ENV") == "production" && $exception instanceof PDOException) {
            $message = 'Internal error occured';
        } else {
            $message = $exception->getMessage();
        }

        $error = [
            'message' => $message,
            'code' => $code,
            'time' => date('Y-m-d H:i:s')
        ];

        if (config('app.error_trace')) {
            $trace = $exception->getTrace();
        } else {
            $trace = [];
        }

        if ($exception instanceof HttpException) {
            $content = json(compact('error', 'trace'), $exception->getStatusCode());
        } else {
            $content = json(compact('error', 'trace'), 500);
        }
        
        $this->send($content);
    }

    /**
     * Die the process with content
     *
     * @param string $content
     * @return mixed
     */
    private function send($content)
    {
        die($content);
    }
}
