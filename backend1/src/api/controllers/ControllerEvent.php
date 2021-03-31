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
use Respect\Validation\Validator as v;

class ControllerEvent
{
    private $c;

    public function __construct(\Slim\Container $c)
    {
        $this->c = $c;
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
        $events = Event::where('public', '=', 1)->whereDate('date','>',date('Y-m-d'))->orderBy('date')->take(15)->with('creator')->get();
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
                    "token" => $event->token,
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
        //$datetime = date('Y-m-d',strtotime('+ 730 days'));
        $body->validator = v::attribute('title',v::stringType()->length(4,80))
                            ->attribute('description', v::stringType()->length(0,200))
                            ->attribute('public', v::boolVal())
                            ->attribute('main_event', v::boolVal());
        if(!$body->validator->validate($body))
        {
            $res = $res->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Missing Data or wrong data"]));
            return $res;
        }
        
        $event = new Event;
        $event->title = filter_var($body->title, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $event->description = filter_var($body->description, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $event->date = $body->date;
        $event->user_id = $req->getAttribute('token')->user->id;
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
            $event = Event::findOrFail($args['id']);
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        //Check du body de la requete
        $body->validator = v::attribute('title',v::stringType()->length(4,80))
                            ->attribute('description', v::stringType()->length(0,200))
                            ->attribute('public', v::boolVal())
                            ->attribute('main_event', v::boolVal());
        if(!$body->validator->validate($body))
        {
            $res = $res->withStatus(400)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Missing Data or wrong data"]));
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
        $bodyText = json_decode($req->getBody())->text;
        if(!v::stringType()->length(1,160)->validate($bodyText))
        {
            $res = $res->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Missing or too long data : text"]));
            return $res;
        }
        $token = $req->getAttribute('token');
        $message = new Message;
        $message->text = filter_var($bodyText,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
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
            $res->getBody()->write(json_encode(["error" => "Internal Server Error"]));
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

    public function modifEventsMessage(Request $req, Response $res, array $args): Response
    {
        $token = $req->getAttribute('token');
        try
        {
            $message = Message::where('id','=',$args['messageId'])->firstOrFail();
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Message Not Found"]));
        }
        if($message->user_id!=$token->user->id)
        {
            $res = $res->withStatus(403)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "You are not allowed to do that"]));
            return $res;
        }
        $bodyText = json_decode($req->getBody())->text;
        if(!v::stringType()->length(1,160)->validate($bodyText))
        {
            $res = $res->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Missing or too long data : text"]));
            return $res;
        }
        $message->text = filter_var($bodyText,FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
        $message->save();
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "This message has been edited"]));
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
            $res = $res->withStatus(403)
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
        if(!v::attribute('response',v::boolType())->validate($body)){
            $res = $res->withStatus(400)
            ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Missing or wrong data"]));
            return $res;
        }
        if($event->public == 1)
        {
            if($token->user->id != $event->user_id)
            {
                if(!$event->participants()->where('user_id','=',$token->user->id)->exists())
                    $event->participants()->attach(['user_id'=>$user->id],['present'=>true]);
                else
                    $user->participants()->updateExistingPivot($args['id'],array('present'=>$body->response));
            }
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
