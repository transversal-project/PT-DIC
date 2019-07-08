<?php
    # Configuartion
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    use App\Controllers\ManipUsersController;
    use App\Controllers\SensorController;

    # Importation des dépendances
    require 'vendor/autoload.php';

    # Lancement de l'application
    $app = new \Slim\App([
        'settings' => [
            'displayErrorDetails' => true
        ]
    ]);

    # Initialisation des conteneurs
    require 'app/containers.php';

    # Gestion des routes
    /************ Requêtes post ************/
    ## Connexion d'un client
    $app->post('/connexion', ManipUsersController::class.':connect');
    ## Ajout d'un client
    $app->post('/ajoutClient', ManipUsersController::class.':addCustomer');
    ## Ajout d'une mesure de capteur
    $app->post('/ajoutMesure', SensorController::class.':addSensorData');
    ## Ajout d'un capteur
    $app->post('/ajoutCapteur', SensorController::class.':addSensor');


    /************ Requêtes get ************/
    $app->get('/afficheMesures', SensorController::class.':showData');
    $app->get('/mesuresCapteur', SensorController::class.':showSensorData');
    
    #Lancement de l'application
    $app->run();
