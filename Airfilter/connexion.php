<?php
    try{
        $bdd = new PDO("mysql:host=localhost; dbname=airfliter", "derguene", "ordinateur97");
    }
    catch(Exception $e){
        die('Erreur: '. $e->getMessage());
    }
