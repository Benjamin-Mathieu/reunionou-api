<?php

namespace atelier\api\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\models\Event;
use \GuzzleHttp\Client;

class ControllerEvent
{
    private $c;

    public function __construct(\Slim\Container $c)
    {
        $this->c = $c;
    }

    public function getEvents(Request $req, Response $res, array $args): Response
    {
        $events = Event::where('public', '=', 1)->orderBy('date')->take(15)->with('creator')->get();
        $result = array();
        foreach ($events as $event) {
            unset($event->deleted_at);
            unset($event->updated_at);
            array_push($result, array(
                "event" => $event,
                "links" => array(
                    "self" => array(
                        "href" => $this->c->get('router')->pathFor('getEvent', ['id' => $event->id])
                    )
                )
            ));
        }
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode([
            "type" => "resources",
            "count" => $events->count(),
            "events" => $result
        ]));
        return $res;
    }

    public function getEvent(Request $req, Response $res, array $args): Response
    {
        $id = $args['id'];
        $event = Event::where('id', '=', $id)->first();
        $creator = $event->creator()->get();
        $participants = $event->participants()->get();
        foreach($participants as $participant)
            unset($participant['pivot']['event_id']);
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(
            [
                "type" => "resource",
                "event" => [
                    "title" => $event->title,
                    "description" => $event->description,
                    "date" => $event->date,
                    "user_id" => $event->user_id,
                    "adress" => $event->adress,
                    "public" => $event->public,
                    "main_event" => $event->main_event,
                    "creator" => $event->creator()->first(),
                    "participants" => $participants
                ]
            ]
        ));
        return $res;
    }

    public function createEvent(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        $token = $req->getAttribute('token');
        $event = new Event;
        $event->title = filter_var($body->title, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->description = filter_var($body->description, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->date = $body->date;
        $event->user_id = $token->user->id;
        $event->token = bin2hex(random_bytes(32));
        $event->adress = filter_var($body->adress, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->public = $body->public;
        $event->main_event = $body->main_event;

        try {
            $event->save();
        } catch (\Exception $e) {
            $res = $res->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode($e->getMessage()));
            return $res;
        }
        $res = $res->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "Event has been created"]));
        return $res;
    }
}
