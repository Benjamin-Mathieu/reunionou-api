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
        $page = $req->getQueryParam('page',1);
        if($page<=0)
            $page = 1;
        $size = 15; //Nombre d'evenement maximum affiché
        $events = Event::where('public', '=', 1)->orderBy('date')->with('creator');
        $count = $events->count();
        $lastPage = intdiv($count,$size)+1;
        if($page > $lastPage)
            $page = $lastPage;
        $rows = $events->skip(($page-1)*$size)->take($size)->get();
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode($lastPage));
        return $res;

    }

    public function getPrivateEvents(Request $req, Response $res, array $args): Response
    {
        $token = $req->getAttribute('token');
        $events = Event::where('public','=',0)->with('creator')->where(function($q)use($token){
            $q->where('user_id','=',$token->user->id)->orWhereHas('participants',function($query) use($token)
            {
                $query->where('user_id','=',$token->user->id);
            });
        })->orderBy('date')->take(15)->get();
        $result= array();
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
        ]));
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
        try
        {
            $event = Event::where('id', '=', $id)->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
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
        //Interrogation de l'API du gouvernement pour vérifier si l'adresse est assez précise
        $client = new Client([
            'base_uri' => 'https://api-adresse.data.gouv.fr/search/',
            'verify' => false
        ]);
        $responseAPI = $client->get("?q=".$body->adress);
        $bodyResponse = json_decode($responseAPI->getBody());
        if(count($bodyResponse->features)<1)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "No adress found"]));
            return $res;
        }
        else if(count($bodyResponse->features)>1)
        {
            $res = $res->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Adress need to be more precise or wrong adress"]));
            return $res;
        }
        $event = new Event;
        $event->title = filter_var($body->title, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $event->description = filter_var($body->description, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $event->date = $body->date;
        $event->user_id = $token->user->id;
        $event->token = bin2hex(random_bytes(32));
        $event->adress = filter_var($body->adress, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
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

    public function modifEvent(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        try
        {
            $event = Event::where('id','=',$args['id'])->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        $event->title = filter_var($body->title, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $event->description = filter_var($body->description, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $event->date = $body->date;
        $event->adress = filter_var($body->adress, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $event->public = $body->public;
        $event->main_event = $body->main_event;
        $event->save();
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "Event has been modified"]));
        return $res;
    }
    public function getEventsMessages(Request $req, Response $res, array $args): Response
    {
        try
        {
            $event = Event::findOrFail($args['id']);
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        $result = array();
        $messages = $event->messages()->get();
        foreach($messages as $message)
        {
            array_push($result,[
                "message" => $message,


                "user" => $message->sender()->first()
            ]);
        }
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode([
            "type" => "collections",
            "messages" => $result
        ]));
        return $res;
    }

    public function createEventsMessage(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        $token = $req->getAttribute('token');
        $message = new Message;
        $message->text = filter_var($body->text,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $message->user_id = $token->user->id;
        $message->event_id = $args['id'];
        try
        {
            $message->save();
        }
        catch(\Exception $e)
        {
            $res = $res->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode($e->getMessage()));//["error" => "Internal Server Error"]));
            return $res;
        }
        $res = $res->withStatus(201)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "Message has been created"]));
        return $res;
    }

    public function deleteEventsMessage(Request $req, Response $res, array $args): Response
    {
        $message = Message::find($args['messagesId']);
        try
        {
            $message->delete();
        }
        catch(\Exception $e)
        {
            $res = $res->withStatus(500)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Internal Server Error"]));
            return $res;
        }
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "This message has been deleted"]));
        return $res;
    }
    public function deleteEvent(Request $req, Response $res, array $args): Response
    {
        try
        {
            $event = Event::where('id','=',$args['id'])->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }

        if($event->user_id === $req->getAttribute('token')->user->id)
        {
            try
            {
                $event->delete();
            }
            catch(\Exception $e)
            {
                $res = $res->withStatus(500)
                            ->withHeader('Content-Type', 'application/json');
                $res->getBody()->write(json_encode($e->getMessage()));
                return $res;
            }
        }
        else
        {
            $res = $res->withStatus(401)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "You are not allowed to do that"]));
            return $res;
        }
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "This event has been deleted"]));
        return $res;
    }

    public function addParticipants(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        try
        {
            $user = User::select('id')->where('mail','=',$body->mail)->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "User not Found"]));
            return $res;
        }
        try
        {
            $event = Event::findOrFail($args['id']);
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        if(($event->participants()->where('user_id','=',$user->id)->first())===null)
        {
            $event->participants()->attach(['user_id'=>$user->id]);
            $res = $res->withStatus(201)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["success" => "Participants has been added"]));
            return $res;
        }
        else
        {
            $res = $res->withStatus(409)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "This User already participate at this event"]));
            return $res;
        }
    }

    public function responseParticipants(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        $token = $req->getAttribute('token');
        /*$participant = Participants::where('event_id','=',$args['id'])->where(function($q)use($token){
            $q->where('user_id','=',$token->user->id);
        })->first();*/
        try
        {
            $user = User::findOrFail($token->user->id);
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "User not Found"]));
            return $res;
        }
        try
        {
            $event = Event::findOrFail($args['id']);
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        if($event->public == 1)
        {
            if(($token->user->id)!= $event->user_id)
                $event->participants()->attach(['user_id'=>$user->id],['present'=>true]);
            else
                $event->participants()->attach(['user_id'=>$user->id]);
        }
        else
        {
            $user->participants()->updateExistingPivot($args['id'],array('present'=>$body->response));
        }
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "participation updated"]));
        return $res;
    }
}
