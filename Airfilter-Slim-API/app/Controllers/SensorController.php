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
            $position = $request->getParam('position');
            $dateDebut = $request->getParam('dateDebut');
            $dateFin = $request->getParam('dateFin');
            
            $response->getBody()->write($bdd->afficheMesures($position, $dateDebut, $dateFin));
        } //End showData
        /**
         * Fonction d'affichage d'un capteur donné
         */
        public function showSensorData(Request $request, Response $response){
            $bdd = $this->container->db;
            $nomCapteur = $request->getParam('nomCapteur');
            $dateDebut = $request->getParam('dateDebut');
            $dateFin = $request->getParam('dateFin');
            
            $response->getBody()->write($bdd->mesuresCapteur($nomCapteur, $dateDebut, $dateFin));
        } //End showData

        /**
         * Fonction d'ajout d'une mesure d'un capteur donné
         */
        public function addSensorData(Request $request, Response $response){
            $bdd = $this->container->db;
            $idCapteur = $request->getParam('idCapteur');
            $valeur = $request->getParam('valeur');
            $date = $request->getParam('date');
            
            $response->getBody()->write($bdd->ajoutMesure($idCapteur, $valeur, $date));
        } //End addSensorData

        /**
         * Fonction d'ajout d'un d'un capteur
         */
        public function addSensor(Request $request, Response $response){
            $bdd = $this->container->db;

            $nomCapteur = $request->getParam('nomCapteur');
            $typeDonnee = $request->getParam('typeDonnee');
            $position = $request->getParam('position');
            $latitude = $request->getParam('latitude');
            $longitude = $request->getParam('longitude');
            
            $response->getBody()->write($bdd->ajoutCapteur($nomCapteur, $typeDonnee, $position, $latitude, $longitude));
        } //End addSensor
       
    } //End SensorController