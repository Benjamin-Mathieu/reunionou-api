<?php
require_once __DIR__ . '/../src/vendor/autoload.php';
$config_slim = require_once('conf/Settings.php'); /* Récupération de la config de Slim */
$errors = require_once('conf/Errors.php'); /* Récupération des erreurs */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \atelier\api\controllers\ControllerUser;
use \atelier\api\controllers\ControllerEvent;
use \atelier\api\middlewares\Cors;
use \atelier\api\middlewares\Token;
use \atelier\api\middlewares\CheckAuthorization;
use \atelier\api\middlewares\CheckJWT;
use \atelier\api\middlewares\CheckAdress;

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection($config_slim['settings']['dbconf']); /* configuration avec nos paramètres */
$db->setAsGlobal();              /* rendre la connexion visible dans tout le projet */
$db->bootEloquent();             /* établir la connexion */

$c = new \Slim\Container(array_merge($config_slim, $errors));
$app = new \Slim\App($c);

$app->add(Cors::class . ':verificationAjoutHeader');


########################Routes User#####################################
$app->post('/signIn[/]', ControllerUser::class . ':signIn')
    ->add(CheckAuthorization::class . ':checkAuthorization');

$app->post('/signUp[/]', ControllerUser::class . ':signUp');

#########################################################################

#######################Routes Events#####################################
$app->get('/events[/]', ControllerEvent::class . ':getEvents');

$app->get('/privateEvents[/]', ControllerEvent::class . ':getPrivateEvents')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT');

$app->get('/events/{id}', ControllerEvent::class . ':getEvent')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    //->add(Token::class . ':checkToken')
    ->setName('getEvent');

$app->put('/events/{id}[/]', ControllerEvent::class . ':modifEvent')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT')
    ->add(CheckAdress::class.':checkAdress');

$app->delete('/events/{id}[/]', ControllerEvent::class . ':deleteEvent')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT');

$app->post('/events[/]', ControllerEvent::class . ':createEvent')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT')
    ->add(CheckAdress::class.':checkAdress');

$app->get('/events/{id}/messages[/]', ControllerEvent::class . ':getEventsMessages');

$app->post('/events/{id}/messages[/]', ControllerEvent::class.':createEventsMessage')
    ->add(CheckAuthorization::class.':checkAuthorization')
    ->add(CheckJWT::class.':checkJWT');

$app->post('/events/{id}/participants[/]', ControllerEvent::class . ':addParticipants')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT');

$app->put('/events/{id}/response[/]', ControllerEvent::class . ':responseParticipants')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT');

$app->put('/events/{id}/messages/{messageId}[/]', ControllerEvent::class . ':modifEventsMessage')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT');

$app->delete('/events/{id}/messages/{messagesId}[/]', ControllerEvent::class . ':deleteEventsMessage')
    ->add(CheckAuthorization::class . ':checkAuthorization')
    ->add(CheckJWT::class . ':checkJWT');
#########################################################################
// Catch-all route to serve a 404 Not Found page if none of the routes match
// NOTE: make sure this route is defined last
$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($req, $res) {
    $handler = $this->notFoundHandler; // handle using the default Slim page not found handler
    return $handler($req, $res);
});
$app->options('/{routes:.+}', function (Request $request, Response $response) {
    return $response;
});
$app->run();
