<?php
    try{
        $bdd = new PDO('mysql:host=localhost;dbname=airfliter;charset=utf8', 'derguene', 'ordinateur97');
        //PDO->setAttribute(PDO::ERRMODE_EXCEPTION);
    }
    catch (Exception $e){
        die('Erreur: '. $e->getMessage());
    }