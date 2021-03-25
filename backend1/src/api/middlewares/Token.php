<?php

namespace atelier\api\middlewares;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use atelier\api\models\Event;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Token
{
    private $c; // le conteneur de dépendances de l'appli

    public function __construct(\Slim\Container $c)
    {
        $this->c = $c;
    }

    public function checkToken(Request $rq, Response $res, callable $next): Response
    {
        // récupération de l'id de la commande et le token
        $id = $rq->getAttribute("route")->getArgument("id");
        $token = $rq->getQueryParam("token", null);

        // Vérification si l'id et le token dans l'url correspondent à celles dans la bdd
        try {
            Event::where("id", "=", $id)
                ->where("token", "=", $token)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $json_error = array(
                "error" => 401,
                "message" => "Unauthorized - Token missing or false"
            );
            $res = $res->withStatus(401)
                ->withHeader("Content-Type", "application/json; charset=utf-8");
            $res->getBody()->write(json_encode($json_error));
            return $res;
        }
        return $next($rq, $res);
    }
}
