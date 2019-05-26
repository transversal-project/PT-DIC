<?php
    require_once 'connexion.php';
    //***************************Fonctions Utilisateur  */
    require_once 'userFunctions.php';
   
    //****************************Traitement des inscriptions */
    if(isset($_POST['username']) && isset($_POST['email']) && isset($_POST['pass1']) && isset($_POST['pass2'])){
        $login = htmlspecialchars($_POST['username']);
        $email = htmlspecialchars($_POST['email']);
        $pass1 = htmlspecialchars($_POST['pass1']);
        $pass2 = htmlspecialchars($_POST['pass2']);

        if(strcmp($pass1, $pass2)!=0){
            echo json_encode(['type'=>'Echec', 'msg'=>"Les mots de passes ne concordent pas"]);
            die();
        } //End second if
        ajoutUtilisateur($login, $email, $pass1);
    } //End if
    
    //****************************Traitement des inscriptions */
    if(isset($_POST['login']) && isset($_POST['password'])){
        $login = htmlspecialchars($_POST['login']);
        $password = htmlspecialchars($_POST['password']);
        //Vérification du type de l'utilisateur
        if(isset($_POST['admin']))
            connexion($login, $password, TRUE);
        else
            connexion($login, $password);
    } //End if