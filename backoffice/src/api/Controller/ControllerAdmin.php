<?php
namespace backOffice\api\controller;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \backOffice\api\models\UserAdmin;
use \backOffice\api\models\User;
use \backOffice\api\models\Event;
use \backOffice\api\models\Message;
use \backOffice\api\models\Participants;
use \GuzzleHttp\Client;
use \Firebase\JWT\JWT;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Database\QueryException;

class ControllerAdmin
{
    private $c;

    public function __construct(\Slim\Container $c)
    {
        $this->c = $c;
    }

    public function signIn(Request $req, Response $res, array $args): Response
    {
        $authString = base64_decode(explode(" ", $req->getHeader('Authorization')[0])[1]);
        list($username, $pass) = explode(':', $authString);
        try {
            $user = UserAdmin::select('id','username', 'password')->where('username', '=', $username)->firstOrFail();

            if (!password_verify($pass, $user->password))
            {
                $res = $res->withStatus(401)
                            ->withHeader('Content-Type', 'application/json');
                $res->getBody()->write(json_encode(["error"=>"Password check failed"]));
                return $res;
            }

            unset($user->password);
        } catch (ModelNotFoundException $e) {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error"=>"User Not Found"]));
            return $res;
        }
        $token = JWT::encode(
            [
                'iss' => 'https://docketu.iutnc.univ-lorraine.fr:14003/signIn',
                'aud' => 'https://docketu.iutnc.univ-lorraine.fr:14003',
                'iat' => time(),
                'exp' => time() + 60*60*24,
                'user' => $user
            ],
            $this->c->settings['secrets'],
            'HS512'
        );

        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode($token));
        return $res;
    }

    public function getAllEvents(Request $req, Response $res, array $args): Response
    {
        $events = Event::select('id','title','description','date','token','adress','user_id','public')->whereDate('date','<',date('Y-m-d',strtotime('- 1 days')))->orderBy('date')->with('creator')
        ->orWhereHas('messages',function($q){
            $q->whereDate('created_at','<',date('Y-m-d',strtotime('- 1 days')));
        })->get();
        $result = array();
        foreach ($events as $event) {
            unset($event->user_id);
            array_push($result, array(
                "event" => $event,
            ));
        }
        $res->getBody()->write(json_encode([
            "type" => "collections",
            "count" => $events->count(),
            "events" => $result
        ]));
        return $res;
    }

    public function getAllUsers(Request $req, Response $res, array $args): Response
    {
        $users = User::select('id','mail','name','firstname')->get();
        $res = $res->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode([
            "type" => "collections",
            "users" => $users
        ]));
        return $res;
    }

    public function deleteEvent(Request $req, Response $res, array $args): Response
    {
        try
        {
            $event = Event::findOrFail($args['id']);
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error"=>"User Not Found"]));
            return $res;
        }
        try
        {
            $event->delete();
        }
        catch(\Exception $e)
        {
            $res = $res->withStatus(500)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Something went wrong!, Internal Server Error"]));
            return $res;
        }
        $res = $res->withStatus(200)
        ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "Event has been deleted"]));
        return $res;
    }

    public function deleteUser(Request $req, Response $res, array $args): Response
    {
        try
        {
            $user = User::findOrFail($args['id']);
        }
        catch(ModelNotFoundException $e)
        {
            $res = $res->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error"=>"User Not Found"]));
            return $res;
        }
        try
        {
            $user->delete();
        }
        catch(\Exception $e)
        {
            $res = $res->withStatus(500)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Something went wrong!, Internal Server Error"]));
            return $res;
        }
        $res = $res->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
        $res->getBody()->write(json_encode(["success" => "User has been deleted"]));
        return $res;
    }
}