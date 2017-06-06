<?php
namespace Controllers;

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    public function initialize()
    {
        $this->view->TITLE = 'Webinspired test task';
    }

    public function outJson($payload, $status = 200)
    {
        $status = 200;
        $descriptions = [
            200 => 'OK', 400 => 'Bad Request',
        ];
        $headers = [];
        $contentType = 'application/json';
        $content = json_encode($payload);

        $response = new \Phalcon\Http\Response();

        $response->setStatusCode($status, $descriptions[$status]);
        $response->setContentType($contentType, 'UTF-8');
        $response->setContent($content);

        // Set the additional headers
        foreach ($headers as $key => $value) {
            $response->setHeader($key, $value);
        }

        $this->view->disable();

        return $response;
    }
}