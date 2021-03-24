<?php
namespace atelier\api\controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\models\User;
use \atelier\api\models\Event;
use \Ramsey\Uuid\Uuid;
use \GuzzleHttp\Client;

class ControllerEvent
{
    private $c;

    public function __construct(\Slim\Container $c){
        $this->c = $c;
    }

    public function getEvents(Request $req, Response $res, array $args): Response
    {
        $events = Event::where('public','=',1)->orderBy('date')->take(15)->with(array('creator'=> function($query)
        {
            $query->select('id','name','firstname','mail');
        }))->get();
        $result = array();
        foreach($events as $event)
        {
            unset($event->deleted_at);
            unset($event->updated_at);
            array_push($result,array(
                "event" => $event,
                "links" => array(
                    "self" => array(
                        "href" => $this->c->get('router')->pathFor('getEvent',['id'=>$event->id])
                    )
                )
            ));
        }
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type','application/json');
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
        try
        {
            $event = Event::where('id','=',$id)->first();
        }
        catch(\Exception $e)
        {
            $res = $res->withStatus(404)
                    ->withHeader('Content-Type','application/json');
            $res->getBody()->write(json_encode(["error" => "Event not Found"]));
            return $res;
        }
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type','application/json');
        $res->getBody()->write(json_encode(
        [
            "type" => "resource",
            "events" => $event
        ]
        ));
        return $res;
    }

    public function createEvent(Request $req, Response $res, array $args): Response
    {
        $body = json_decode($req->getBody());
        $event = new Event;
        $event->title = filter_var($body->title,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->description = filter_var($body->description,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->date = $body->date;
        $event->user_id = 1;
        $event->token = bin2hex(random_bytes(32));
        $event->adress = filter_var($body->adress,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $event->public = 1;
        $event->main_event = 1;

        try
        {
            $event->save();
        }
        catch(\Exception $e)
        {
            $res = $res->withStatus(500)
                        ->withHeader('Content-Type','application/json');
            $res->getBody()->write(json_encode($e->getMessage()));
            return $res;
        }
        $res = $res->withStatus(201)
                    ->withHeader('Content-Type','application/json');
        $res->getBody()->write(json_encode(["success" => "Event has been created"]));
        return $res;
    }
}