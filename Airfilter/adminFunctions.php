<?php
    function getComptes(){
        global $bdd;
        $requete = "SELECT * FROM users ORDER BY validation";
        $reponse = $bdd->query($requete);
        
        if($comptes = $reponse->fetchAll()){
            $reponse->closeCursor();
            return $comptes;
        }
        else{
            $reponse->closeCursor();
            echo json_encode(['type'=>'Echec', 'msg'=>"Aucun compte trouve"]);
            return false;
        }

    } //End getComptes

    function validerCompte($id){
        global $bdd;
        $requete = "UPDATE users SET validation='ok' WHERE id=?";
        $reponse = $bdd->prepare($requete);
        $reponse->execute(array($id));
        
        if($reponse->rowCount() > 0){
            header('Location: admin.php');
        }
        else{
            $reponse->closeCursor();
            echo json_encode(['type'=>'Echec', 'msg'=>"Echec de validation du compte"]);
            return false;
        }

    } //End validerCompte