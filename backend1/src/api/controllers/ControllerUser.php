<?php
namespace atelier\api\controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
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

    }

    public function signIn(Request $req, Response $res,array $args): Response
    {

    }


}