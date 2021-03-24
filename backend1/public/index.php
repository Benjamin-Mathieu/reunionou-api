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
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});

$app->post('/signIn', ControllerUser::class . ':signIn')
    ->add(Cors::class . ':verificationAjoutHeader');

$app->post('/signUp', ControllerUser::class . ':signUp')
    ->add(Cors::class . ':verificationAjoutHeader');

$app->post('/events', ControllerEvents::class . ':createEvent')
    ->add(Cors::class . ':verificationAjoutHeader');

$app->get('/events/{id}', ControllerEvents::class . ':getEvent')
    ->add(Token::class . ':checkToken');

$app->run();
