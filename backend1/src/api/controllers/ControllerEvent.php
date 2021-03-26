<?php

namespace atelier\api\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\models\Event;
use \atelier\api\models\Message;
use \atelier\api\models\User;
use \atelier\api\models\Participants;
use \GuzzleHttp\Client;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;

class ControllerEvent
{
    private $c;

    public function __construct(\Slim\Container $c)
    {
        $this->c = $c;
    }

    public function paginationEvents(Request $req, Response $res, array $args): Response
    {
        $page = $req->getQueryParam('page', 1);
        if ($page <= 0)
            $page = 1;
        $size = 15; //Nombre d'evenement maximum affiché
        $events = Event::where('public', '=', 1)->orderBy('date')->with('creator');
        $count = $events->count();
        $lastPage = intdiv($count, $size) + 1;
        if ($page > $lastPage)
            $page = $lastPage;
        $rows = $events->skip(($page - 1) * $size)->take($size)->get();
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode($lastPage));
        return $res;
    }

    public function getPrivateEvents(Request $req, Response $res, array $args): Response
    {
        $events = Event::where('user_id', '=', $req->getAttribute('token')->user->id)->where('public', '=', 0)->orderBy('date')->take(15)->get();
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(
            [
                "type" => "collections",
                "events" => $events
            ]
        ));
        return $res;
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
            "type" => "collections",
            "count" => $events->count(),
            "events" => $result
        ]));
        return $res;
    }

    public function getEvent(Request $req, Response $res, array $args): Response
    {
        $id = $args['id'];
        try {
            $event = Event::where('id', '=', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        $creator = $event->creator()->get();
        $participants = $event->participants()->get();
        foreach ($participants as $participant)
            unset($participant['pivot']['event_id']);
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(
            [
                "type" => "resource",
                "event" => [
                    "id" => $event->id,
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
        $event->title = filter_var($body->title, FILTER_SANITIZE_STRING);
        $event->description = filter_var($body->description, FILTER_SANITIZE_STRING);
        $event->date = $body->date;
        $event->user_id = $token->user->id;
        $event->token = bin2hex(random_bytes(32));
        $event->adress = filter_var($body->adress, FILTER_SANITIZE_STRING);
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
        $res->getBody()->write(json_encode([
            "success" => "Event has been created",
            "token" => $event->token,
            "id" => $event->id
        ]));
        return $res;
    }

    public function getEventsMessages(Request $req, Response $res, array $args): Response
    {
        try {
            $event = Event::where('id', '=', $args['id'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }

        $messages = $event->messages()->get();
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode($messages));
        return $res;
    }

    public function createEventsMessage(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        $token = $req->getAttribute('token');
        $message = new Message;
        $message->text = filter_var($body->text, FILTER_FULL_STRING);
        $message->user_id = $token->user->id;
        $message->user_id = $args['id'];
        try {
            $message->save();
        } catch (\Exception $e) {
            $res = $res->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode($e->getMessage()));
            return $res;
        }
        $res = $res->withStatus(201)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "Message has been created"]));
        return $res;
    }

    public function deleteEvent(Request $req, Response $res, array $args): Response
    {
        try {
            $event = Event::where('id', '=', $args['id'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }

        if ($event->user_id === $req->getAttribute('token')->user->id) {
            try {
                $event->delete();
            } catch (\Exception $e) {
                $res = $res->withStatus(500)
                    ->withHeader('Content-Type', 'application/json');
                $res->getBody()->write(json_encode($e->getMessage()));
                return $res;
            }
        } else {
            $res = $res->withStatus(401)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "You are not allowed to do that"]));
            return $res;
        }
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "The event has been deleted"]));
        return $res;
    }

    public function addParticipants(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        $event_id = $args['id'];
        try {
            $user = User::select('id', 'mail')->where('mail', '=', $body->mail)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "User not Found"]));
            return $res;
        }
        Event::find($args['id'])->participants()->save(new Participants(array(
            'user_id' => $user->id,
            'event_id' => $event_id
        )));
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "L'utilisateur "]));
        return $res;
    }
}
