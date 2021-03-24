<?php
require_once __DIR__ . '/../src/vendor/autoload.php';
$config_slim = require_once('conf/Settings.php'); /* Récupération de la config de Slim */
$errors = require_once('conf/Errors.php'); /* Récupération des erreurs */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\controllers\ControllerUser;
use \atelier\api\controllers\ControllerEvents;
use \atelier\api\middlewares\Cors;
use \atelier\api\middlewares\Token;

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection($config_slim['settings']['dbconf']); /* configuration avec nos paramètres */
$db->setAsGlobal();              /* rendre la connexion visible dans tout le projet */
$db->bootEloquent();             /* établir la connexion */

$c = new \Slim\Container(array_merge($config_slim, $errors));
$app = new \Slim\App($c);

########################Route User#################################
$app->add(Cors::class . ':verificationAjoutHeader');
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

$app->post('/signIn', ControllerUser::class . ':signIn');

$app->post('/signUp', ControllerUser::class . ':signUp');

$app->post('/events', ControllerEvents::class . ':createEvent');

$app->get('/events/{id}', ControllerEvents::class . ':getEvent')
    ->add(Token::class . ':checkToken');

// Catch-all route to serve a 404 Not Found page if none of the routes match
// NOTE: make sure this route is defined last
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});

$app->run();
