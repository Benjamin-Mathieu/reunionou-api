<?php

namespace atelier\api\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\models\Event;
use \GuzzleHttp\Client;
use Ramsey\Uuid\Rfc4122\UuidV4;
use \Ramsey\Uuid\Uuid;


class ControllerEvents
{
    private $c;

    public function __construct(\Slim\Container $c)
    {
        $this->c = $c;
    }

    public function createEvent(Request $req, Response $res): Response
    {
        $body = json_decode($req->getBody());
        $event = new Event;
        $event->title = filter_var($body->title, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->description = filter_var($body->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->date = $body->date;
        $event->user_id = $body->user_id;
        $event->token = bin2hex(random_bytes(32));
        $event->adress = filter_var($body->adress, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->public = $body->public;
        $event->main_event = $body->main_event;

        try {
            $event->save();
        } catch (\Exception $e) {
            $res = $res->withStatus(500)
                ->withHeader("Content-Type", "application/json");
            $res->getBody()->write(json_encode($e->getMessage()));
            return $res;
        }

        $json_event = array(
            "title" => $event->title,
            "description" => $event->description,
            "date" => $event->date,
            "user_id" => $event->user_id,
            "adress" => $event->adress,
            "public" => $event->public,
            "main_event" => $event->main_event
        );
        $res = $res->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode($json_event));
        return $res;
    }

    public function getEvent(Request $req, Response $res, array $args): Response
    {
        $event_id = $args['id'];
        $event = Event::where("id", "=", $event_id)->firstOrfail();

        $json_event = array(
            "title" => $event->title,
            "description" => $event->description,
            "date" => $event->date,
            "user_id" => $event->user_id,
            "adress" => $event->adress,
            "public" => $event->public,
            "main_event" => $event->main_event
        );
        $res = $res->withHeader("Content-Type", "application/json");
        $res->getBody()->write(json_encode($json_event));
        return $res;
    }
}
