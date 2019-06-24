<?php

# Configuartion
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

# Importation des dépendances
require 'vendor/autoload.php';
require 'Functions/connexion.php';
require 'Functions/userFunctions.php';

$app = new \Slim\App;

# Gestion des routes
## Connexion d'un utilisateur
$app->post('/connexion/', function(Request $request, Response $response){
    # Récupération des variables
    $login = $request->getParam('login');
    $password = $request->getParam('password');
    $admin = $request->getParam('admin');
    # Passage de string en booléen
    $admin = $admin=="true" ? true : false;

    # Vérification des attributs et retour du résultat
    $response->getBody()->write(connexion($login, $password, $admin));

    return $response;
});

## Ajout d'un utilisateur
$app->post('/ajoutUtilisateur/', function(Request $request, Response $response){
    # Récupération des variables
    $login = $request->getParam('login');
    $password = $request->getParam('password');
    $email = $request->getParam('email');

    # Vérification des attributs et retour du résultat
    $response->getBody()->write(ajoutUtilisateur($login, $email, $password));

    return $response;
});


#Lancement de l'application
$app->run();
