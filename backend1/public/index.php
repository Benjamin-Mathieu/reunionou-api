<?php
require_once __DIR__ . '/../src/vendor/autoload.php' ;
$config_slim = require_once('conf/Settings.php'); /* Récupération de la config de Slim */
$errors = require_once('conf/Errors.php'); /* Récupération des erreurs */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection($config_slim['settings']['dbconf']); /* configuration avec nos paramètres */
$db->setAsGlobal();              /* rendre la connexion visible dans tout le projet */
$db->bootEloquent();             /* établir la connexion */
use \atelier\api\controllers\ControllerUser;
use \lbs\fidelisation\middlewares\Cors;
$c = new \Slim\Container(array_merge($config_slim, $errors));
$app = new \Slim\App($c);
########################Route User#################################
$app->post('/signIn', ControllerUser::class.':signIn')
    ->add(Cors::class.':verificationAjoutHeader');

$app->get('/signUp', ControllerUser::class.':signUp')
    ->add(Cors::class.':verificationAjoutHeader');
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});
$app->run();
