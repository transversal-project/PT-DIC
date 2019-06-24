<?php
    try{
        $bdd = new PDO('mysql:host=localhost;dbname=airfliter;charset=utf8', 'derguene', 'ordinateur97');
        $bdd->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e){
        die('Erreur: '. $e->getMessage());
    }