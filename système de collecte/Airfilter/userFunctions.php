<?php
    function ajoutUtilisateur($login, $email, $password){
        global $bdd;
        if(checkLogin($login)){
            echo json_encode(['type'=>'Echec', 'msg'=>"Ce nom d'utilisateur est déjà utilisé !"]); 
        }
        else{
            // Hachage du mot de passe
            $pass_hache = md5(sha1(hash('sha256', $password)));
            $reqAjoutUtilisateur = 'INSERT INTO users (login, email, password) VALUES (:login, :email, :password)';
            $reponse = $bdd->prepare($reqAjoutUtilisateur);
            $reponse->execute(array(
                'login' => $login,
                'email' => $email,
                'password' => $pass_hache
            ));
            //Vérification de la réussite de l'ajout
            if($reponse->rowCount() > 0){
                echo json_encode(['type'=>'Succes', 'msg'=>"Utilisateur ajouté !"]); 
            } 
            else{
                echo json_encode(['type'=>'Echec', 'msg'=>"Une erreur est survenue lors de l'ajout de l'utilisateur !"]); 
                return false;
            }

            $reponse->closeCursor();
        } //End else checkLogin
        
    } //End ajoutUtilisateur()

    function connexion($login, $password, $admin=false){
        global $bdd;
        // Hachage du mot de passe
        $pass_hache = md5(sha1(hash('sha256', $password)));

        // Vérification des identifiants
        if($admin==false)
            $reqVerif = "SELECT id FROM users WHERE login=:login AND password=:password";
        else
            $reqVerif = "SELECT id FROM admins WHERE login=:login AND password=:password";
        $reponse = $bdd->prepare($reqVerif);
        $reponse->execute(array(
        'login' => $login,
        'password' => $pass_hache));
        //Vérification du résultat
        if ($resultat = $reponse->fetch()){
            #Vérification de l'état du compte
            if($admin==false)
                $etat = checkStateValidation($resultat['id']);
            else
                $etat = checkStateValidation($resultat['id'], true);
            switch ($etat){
                case 'ok':
                    if($admin==true)
                        header('Location: admin.php');
                    echo json_encode(['type'=>'Succes', 'msg'=>"Connexion reussi"]);
                break;
                case 'en attente':
                    echo json_encode(['type'=>'Echec', 'msg'=>"Compte en attente de validation par l'administrateur"]);
                break;
                case 'refus':
                    echo json_encode(['type'=>'Echec', 'msg'=>"Compte banni"]);
                break;
                default:
                    echo json_encode(['type'=>'Echec', 'msg'=>"Connexion impossible, ERREUR INTERNE !"]);
            } //End switch
                  
        } //End if
        else{
            echo json_encode(['type'=>'Echec', 'msg'=>"Identifiant ou Mot de passe incorrect"]);
            return false;
        }
        
    } //End connexion

    function checkLogin($login){
        global $bdd;
        $requete = "SELECT login FROM users WHERE login LIKE ? ";
        $reponse = $bdd->prepare($requete);
        $reponse->execute(array($login));
        if($reponse->fetch()){
            $reponse->closeCursor();
            return true;
        }
        else{
            $reponse->closeCursor();
            return false;
        }

    } //Enc checkLogin

    function checkStateValidation($id, $admin=false){
        global $bdd;
        if($admin==false)
            $requete = "SELECT validation FROM users WHERE id=?";
        else
            $requete = "SELECT validation FROM admins WHERE id=?";
        $reponse = $bdd->prepare($requete);
        $reponse->execute(array($id));
        if($state = $reponse->fetch()){
            $reponse->closeCursor();
            switch ($state['validation']){
                case 'ok':
                    return 'ok';
                break;
                case 'en attente':
                    return 'en attente';
                break;
                case 'refus':
                    return 'refus';
                break;
            } //End switch
        } //End if
        else{
            $reponse->closeCursor();
            return false;
        }

    } //End checkStateValidation($id)