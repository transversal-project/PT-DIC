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

        /**
         * Fonction de calcul de distance entre deux capteurs à partir
         * de leurs coordonnées géographiques (latitudes et longitudes).
         */
        public function getDistance(Request $request, Response $response){
            $bdd = $this->container->db;

            $latitude1 = $request->getParam('latitude1');
            $longitude1 = $request->getParam('longitude1');
            $latitude2 = $request->getParam('latitude2');
            $longitude2 = $request->getParam('longitude2');
            $unite = $request->getParam('unite');
            
            $response->getBody()->write(self::distance($latitude1, $longitude1, $latitude2, $longitude2, $unite));
        } //End addSensor

        /*::  This routine calculates the distance between two points (given the     :*/
        /*::  latitude/longitude of those points). It is being used to calculate     :*/
        /*::  the distance between two locations using GeoDataSource(TM) Products    :*/
        /*::                                                                         :*/
        /*::  Definitions:                                                           :*/
        /*::    South latitudes are negative, east longitudes are positive           :*/
        /*::                                                                         :*/
        /*::  Passed to function:                                                    :*/
        /*::    lat1, lon1 = Latitude and Longitude of point 1 (in decimal degrees)  :*/
        /*::    lat2, lon2 = Latitude and Longitude of point 2 (in decimal degrees)  :*/
        /*::    unit = the unit you desire for results                               :*/
        /*::           where: 'M' is statute miles                                   :*/
        /*::                  'K' is kilometers                                      :*/
        /*::                  'N' is nautical miles                                  :*/
        /*::  Worldwide cities and other features databases with latitude longitude  :*/
        /*::  are available at https://www.geodatasource.com                         :*/
        /*::                                                                         :*/
        /*::  For enquiries, please contact sales@geodatasource.com                  :*/
        /*::                                                                         :*/
        /*::  Official Web site: https://www.geodatasource.com                       :*/
        /*::                                                                         :*/
        /*::         GeoDataSource.com (C) All Rights Reserved 2018                  :*/
        /*::                                                                         :*/
        /*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
        private function distance($lat1, $lon1, $lat2, $lon2, $unit){
            if($unit == null)
                $unit = "K";
            if (($lat1 == $lat2) && ($lon1 == $lon2)){
              return 0;
            }
            else{
              $theta = $lon1 - $lon2;
              $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
              $dist = acos($dist);
              $dist = rad2deg($dist);
              $miles = $dist * 60 * 1.1515;
              $unit = strtoupper($unit);
          
                if ($unit == "K") 
                    return json_encode(['distance'=> ($miles * 1.609344)]);
                else if ($unit == "N") 
                    return json_encode(['distance'=> ($miles * 0.8684)]);
                else if ($unit == "M") 
                    return json_encode(['distance'=> $miles]);
                else
                    return json_encode(['echec' => 'Unité de mesure non reconnue.']);     
            } //End first else
          }// End distance
          
       
    } //End SensorController