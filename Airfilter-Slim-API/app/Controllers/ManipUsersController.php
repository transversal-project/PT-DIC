<?php
    namespace App\Controllers;

    # Configuartion
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    class ManipUsersController{
        # Variable stockant le conteneur
        private $container;

        #Constructeur
        public function __construct($container){
            $this->container = $container;
        }

        # Fonctions
        /**
         * Ajout d'utilisateurs
         */
        public function addUser(Request $request, Response $response){
            $bdd = $this->container->db;

            # Récupération des variables
            $login = $request->getParam('login');
            $password = $request->getParam('password');
            $email = $request->getParam('email');
    
            # Retour du résultat
            $response->getBody()->write($bdd->ajoutUtilisateur($login, $email, $password));
    
            return $response;
        }

    } //End ManipUsersController