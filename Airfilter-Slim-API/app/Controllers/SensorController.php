<?php
    namespace App\Controllers;

    # Configuartion
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    class SensorController{
        # Variable stockant le conteneur
        private $container;

        #Constructeur
        public function __construct($container){
            $this->container = $container;
        }

        /**
         * Fonction d'affichage des mesures des capteurs
         */
        public function showData(Request $request, Response $response){
            $bdd = $this->container->db;
            $location = $request->getParam('location');
            $date = $request->getParam('date');
            
            $response->getBody()->write($bdd->afficheMesures($location, $date));
        } //End showData
       
    } //End SensorController