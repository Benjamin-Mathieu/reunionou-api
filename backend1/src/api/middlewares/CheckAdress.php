<?php
namespace atelier\api\middlewares;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \GuzzleHttp\Client;
use Respect\Validation\Validator as v;
class CheckAdress
{
    private $c;

    public function __construct(\Slim\Container $c)
    {
        $this->c = $c;
    }

    public function checkAdress(Request $req, Response $res, callable $next): Response
    {
        if(!v::attribute('adress', v::stringType())->validate(json_decode($req->getBody())))
        {
            $res = $res->withStatus(400)
                        ->withHeader('Content-Type', 'application/json');
            $res->getBody()->write(json_encode(["error" => "Missing Data or wrong data : adress"]));
            return $res;
        }
        $client = new Client([
            'base_uri' => 'https://api-adresse.data.gouv.fr/search/',
            'verify' => false
        ]);
        $responseAPI = $client->get("?q=".json_decode($req->getBody())->adress);
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
        $response = $next($req,$res);

        return $response;
    }
}