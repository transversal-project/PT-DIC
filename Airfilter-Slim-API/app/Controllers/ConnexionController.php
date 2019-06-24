<?php
namespace App\Controllers;

# Configuartion
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


    class ConnexionController{
        # Variable stockant le conteneur
        private $container;

        #Constructeur
        public function __construct($container){
            $this->container = $container;
        }

        #Controlleur de connexion
        public function connect(Request $request, Response $response){
            $bdd = $this->container->db;
            
            # Récupération des variables
            $login = $request->getParam('login');
            $password = $request->getParam('password');
            $admin = $request->getParam('admin');
            # Passage de string en booléen
            $admin = $admin=="true" ? true : false;
        
            # Vérification des attributs et retour du résultat
            $response->getBody()->write($bdd->connexion($login, $password, $admin));
        
            #return $response;
        }

    } //End ConnexionController