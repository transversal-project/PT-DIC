<?php
    # Initialisation des conteneurs
    $container = $app->getContainer();
    $container['bdd'] = function (){
        try{
            $bdd = new PDO('mysql:host=localhost;dbname=airfliter;charset=utf8', 'derguene', 'ordinateur97');
            $bdd->setAttribute(PDO::ATTR_ERRMODE ,PDO::ERRMODE_EXCEPTION);
            
            return $bdd;
        }
        catch (Exception $e){
            die('Erreur: '. $e->getMessage());
        }
    };

    $container['db'] = function(){
        global $container;
        require 'Functions/Database.php';

        return new Database($container->bdd);
    };