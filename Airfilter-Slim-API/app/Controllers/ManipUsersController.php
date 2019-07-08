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
         * Connexion des clients à la base de données
         */
        public function connect(Request $request, Response $response){
            $bdd = $this->container->db;
            
            # Récupération des variables
            $email = $request->getParam('email');
            $password = $request->getParam('password');
            $admin = $request->getParam('admin');
            # Passage de string en booléen
            $admin = $admin=="true" ? true : false;
        
            # Vérification des attributs et retour du résultat
            $response->getBody()->write($bdd->connexion($email, $password, $admin));
        
            #return $response;
        } //End connect

        /**
         * Ajout d'utilisateurs
         */
        public function addCustomer(Request $request, Response $response){
            $bdd = $this->container->db;

            # Récupération des variables
            $prenom = $request->getParam('prenom');
            $nom = $request->getParam('nom');
            $email = $request->getParam('email');
            $password = $request->getParam('password');
            $telephone = $request->getParam('telephone');
    
            # Retour du résultat
            $response->getBody()->write($bdd->ajoutClient($prenom, $nom, $email, $password, $telephone));
    
            return $response;
        }

    } //End ManipUsersController