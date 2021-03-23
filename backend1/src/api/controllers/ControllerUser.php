<?php
namespace atelier\api\controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\models\User;
use \Ramsey\Uuid\Uuid;
use \GuzzleHttp\Client;
use \Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\BeforeValidException;

class ControllerUser
{
    private $c;

    public function __construct(\Slim\Container $c){
        $this->c = $c;
    }

    public function signUp(Request $req, Response $res,array $args): Response
    {
        $body = json_decode($req->getBody());
        $user = new User;
        $user->mail = filter_var($body->mail,FILTER_SANITIZE_EMAIL);
        $user->name = filter_var($body->name,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user->firstname = filter_var($body->firstname,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $user->password = password_hash($body->password,PASSWORD_DEFAULT);
        try
        {
            $user->save();
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
        $res->getBody()->write(json_encode("User has been created"));
        return $res;
    }

    public function signIn(Request $req, Response $res,array $args): Response
    {
        $authString = base64_decode(explode(" ",$req->getHeader('Authorization')[0])[1]);
        list($mail,$pass) = explode(':',$authString);
        try
        {
            $user = User::select('mail','name','firstname','password')->where('mail','=',$mail)->first();

            if(!password_verify($pass, $user->password))
                throw new \Exception("password check failed");

            unset($user->password);
        }
        catch(\Exception $e)
        {
            $res = $res->withStatus(404)
                        ->withHeader('Content-Type','application/json');
            $res->getBody()->write(json_encode("User Not Found"));
            return $res;
        }

        $token = JWT::encode([
            'iss' => 'https://docketu.iutnc.univ-lorraine.fr:14001/signIn',
            'aud' =>  'https://docketu.iutnc.univ-lorraine.fr:14001',
            'iat' => time(),
            'exp' => time()+3600,
            'user' => $user
        ],
        $this->c->settings['secrets'], 'HS512');

        $res = $res->withStatus(200)
                    ->withHeader('Content-Type','application/json');
        $res->getBody()->write(json_encode($token));
        return $res;
    }
}