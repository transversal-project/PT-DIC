<?php
    # Configuartion
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    use App\Controllers\ConnexionController;
    use App\Controllers\ManipUsersController;

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
    ## Connexion d'un utilisateur
    $app->post('/connexion', ConnexionController::class.':connect');

    ## Ajout d'un utilisateur
    $app->post('/ajoutUtilisateur', ManipUsersController::class.':addUser');


    #Lancement de l'application
    $app->run();
