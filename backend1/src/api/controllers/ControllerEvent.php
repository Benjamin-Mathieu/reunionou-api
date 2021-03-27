<?php

namespace atelier\api\controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\models\Event;
use \atelier\api\models\Message;
use \atelier\api\models\Participants;
use \atelier\api\models\User;
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
        $token = $req->getAttribute('token');
        $events = Event::where('public', '=', 0)->with('creator')->where(function ($q) use ($token) {
            $q->where('user_id', '=', $token->user->id)->orWhereHas('participants', function ($query) use ($token) {
                $query->where('user_id', '=', $token->user->id);
            });
        })->orderBy('date')->take(15)->get();
        $result = array();
        foreach ($events as $event) {
            //unset($event->updated_at);
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
        $res->getBody()->write(json_encode(
            [
                "type" => "collections",
                "count" => $events->count(),
                "events" => $result
            ]
        ));
        return $res;
    }
    public function getEvents(Request $req, Response $res, array $args): Response
    {
        $events = Event::where('public', '=', 1)->orderBy('date')->take(15)->with('creator')->get();
        $result = array();
        foreach ($events as $event) {
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
        $event->description = filter_var($body->description, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
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
        $res->getBody()->write(json_encode(["success" => "Event has been created"]));
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
        try {
            $user = User::select('id')->where('mail', '=', $body->mail)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "User not Found"]));
            return $res;
        }
        try {
            $event = Event::findOrFail($args['id']);
        } catch (ModelNotFoundException $e) {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        $event->participants()->attach(['user_id' => $user->id]);
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "Participants has been added"]));
        return $res;
    }

    public function responseParticipants(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        $token = $req->getAttribute('token');
        $participant = Participants::where('event_id', '=', $args['id'])->where(function ($q) use ($token) {
            $q->where('user_id', '=', $token->user->id);
        })->first();
        if (is_null($participant)) {
            $participant = new Participants;
            $participant->user_id = $token->user->id;
            $participant->event_id = $args['id'];
            $participant->present = $body->response;
            try {
                $participant->save();
            } catch (\Exception $e) {
                $res = $res->withStatus(500)
                    ->withHeader('Content-Type', 'application/json');
                $res->getBody()->write(json_encode(["error" => "Internal Server Error"]));
                return $res;
            }
        } else {
            $participant->present = $body->response;
            echo $participant;
            try {
                $participant->save();
            } catch (\Exception $e) {
                $res = $res->withStatus(500)
                    ->withHeader('Content-Type', 'application/json');
                $res->getBody()->write(json_encode(["error" => "Internal Server Error"]));
            }
        }
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "participation updated"]));
        return $res;
    }
}
