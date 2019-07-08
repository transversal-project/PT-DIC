<?php
    class Database{
        # Variable de connexion à la base de données
        private $bdd;

        # Constructeur
        public function __construct($bdd){
            $this->bdd = $bdd;
        }

        #Fonctions
        /**
         * Fonction de connexion d'un utilisateur
         */
        function connexion($email, $password, $admin){
            $pdo = $this->bdd;
            // Hachage du mot de passe
            $pass_hache = md5(sha1(hash('sha256', $password)));
    
            // Vérification des identifiants
            if($admin==false)
                $reqVerif = "SELECT idClient FROM client WHERE email=:email AND password=:password";
                
            else  
                $reqVerif = "SELECT id FROM admins WHERE email=:email AND password=:password";
            
            $reponse = $pdo->prepare($reqVerif);
            $reponse->execute(array(
                'email' => $email,
                'password' => $pass_hache));
            //Vérification du résultat
            if ($resultat = $reponse->fetch()){
                #Vérification de l'état du compte
                if($admin==false)
                    $etat = self::checkStateValidation($resultat['idClient']);
                else
                    $etat = self::checkStateValidation($resultat['id'], true);
                switch ($etat){
                    case 'ok':
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

        function ajoutClient($prenom, $nom, $email, $password, $telephone){
            $pdo = $this->bdd;

            if(self::checkEmail($email))
                echo json_encode(['type'=>'Echec', 'msg'=>"Cet email est déjà utilisé !"]); 
            else{
                // Hachage du mot de passe
                $pass_hache = md5(sha1(hash('sha256', $password)));
                $reqAjoutUtilisateur = 'INSERT INTO client (prenom, nom, email, password, telephone) VALUES (:prenom, :nom, :email, :password, :telephone)';
                $reponse = $pdo->prepare($reqAjoutUtilisateur);
                $reponse->execute(array(
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'email' => $email,
                    'password' => $pass_hache,
                    'telephone' => $telephone
                ));
                //Vérification de la réussite de l'ajout
                if($reponse->rowCount() > 0){
                    echo json_encode(['type'=>'Succes', 'msg'=>"Client ajouté !"]); 
                } 
                else{
                    echo json_encode(['type'=>'Echec', 'msg'=>"Une erreur est survenue lors de l'ajout du client !"]); 
                    return false;
                }
    
                $reponse->closeCursor();
            } //End else checkLogin
            
        } //End ajoutUtilisateur()

        public function checkEmail($email){
            $pdo = $this->bdd;

            $requete = "SELECT email FROM client WHERE email LIKE ? ";
            $reponse = $pdo->prepare($requete);
            $reponse->execute(array($email));
            if($reponse->fetch()){
                $reponse->closeCursor();
                return true;
            }
            else{
                $reponse->closeCursor();
                return false;
            }
    
        } //Enc checkLogin
    
        public function checkStateValidation($id, $admin=false){
            $pdo = $this->bdd;

            if($admin==false)
                $requete = "SELECT validation FROM client WHERE idClient=?";
            else
                $requete = "SELECT validation FROM admins WHERE id=?";
            $reponse = $pdo->prepare($requete);
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

        public function afficheMesures($position, $dateDebut, $dateFin){
            $pdo = $this->bdd;
            $params = array(); #Tableau contenant les paramètres de la requête
            # Vérification de la validité des paramètres entrés
            # Si dateDebut est null, dateFin doit l'être également
            if($dateDebut==null && $dateFin!=null){
                echo json_encode(['type'=>'Echec', 'msg'=>"Combinaison non autorisée. Si dateDebut est vide, dateFin doit l'être également."]); 
                return false;
            }

            if($position==null && $dateDebut==null){
                $requete = "SELECT valeur, date, nomCapteur, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur ORDER BY date";
            }
            else if($position!=null && $dateDebut==null){
                $requete = "SELECT valeur, date, nomCapteur, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND position LIKE ? ORDER BY date";
                $params = array($position);
            }
            else if($position==null && $dateDebut!=null){
                if($dateFin==null){
                    # Seule une date est fournie
                    # Formalisation de la date
                    $date = date("Y-m-d", strtotime($dateDebut));

                    $requete = "SELECT valeur, date, nomCapteur, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND date = ? ORDER BY date";
                    $params = array($date);
                }
                else{
                    # Une période est fournie
                    # Formalisation des dates
                    $dateDebut = date("Y-m-d", strtotime($dateDebut));
                    $dateFin = date("Y-m-d", strtotime($dateFin));
                    
                    if($dateDebut > $dateFin){
                        echo json_encode(['type'=>'Echec', 'msg'=>"Combinaison non autorisée. dateDebut doit être supérieure à dateFin."]); 
                        return false;
                    }

                    $requete = "SELECT valeur, date, nomCapteur, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND date >= ? AND date <= ? ORDER BY date";
                    $params = array($dateDebut, $dateFin);
                } //End else 
            } //End else if
            else{ # Ni position ni dateDebut n'est nul
                if($dateFin==null){
                    # Seule une date est fournie
                    # Formalisation de la date
                    $date = date("Y-m-d", strtotime($dateDebut));

                    $requete = "SELECT valeur, date, nomCapteur, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND position LIKE ? AND date = ? ORDER BY date";
                    $params = array($position, $date);
                }
                else{
                    # Une période est fournie
                    # Formalisation des dates
                    $dateDebut = date("Y-m-d", strtotime($dateDebut));
                    $dateFin = date("Y-m-d", strtotime($dateFin));
                    
                    if($dateDebut > $dateFin){
                        echo json_encode(['type'=>'Echec', 'msg'=>"Combinaison non autorisée. dateDebut doit être supérieure à dateFin."]); 
                        return false;
                    }

                    $requete = "SELECT valeur, date, nomCapteur, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND position LIKE ? AND date >= ? AND date <= ? ORDER BY date";
                    $params = array($position, $dateDebut, $dateFin);
                }
                
            } //End else de # Ni position ni dateDebut n'est nul
            
            $reponse = $pdo->prepare($requete);
            $reponse->execute($params);

            if($data = $reponse->fetchAll()){
                return json_encode($data);
            }
            else{
                echo json_encode(['type'=>'Echec', 'msg'=>"Aucune données de mesure présente dans la base de données."]); 
                return false;
            }

        } //End afficheMesures

        public function mesuresCapteur($nomCapteur, $dateDebut, $dateFin){
            $pdo = $this->bdd;
            $params = array(); #Tableau contenant les paramètres de la requête

            if($nomCapteur==null){
                echo json_encode(['type'=>'Echec', 'msg'=>"Erreur. Le nom du capteur est obligatoire."]); 
                return false;
            }
            # Vérification de la validité des paramètres entrés
            # Si dateDebut est null, dateFin doit l'être également
            if($dateDebut==null && $dateFin!=null){
                echo json_encode(['type'=>'Echec', 'msg'=>"Combinaison non autorisée. Si dateDebut est vide, dateFin doit l'être également."]); 
                return false;
            }
            else if($nomCapteur!=null && $dateDebut==null){
                $requete = "SELECT valeur, date, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND nomCapteur LIKE ? ORDER BY date";
                $params = array($nomCapteur);
            }
            else{ # Ni nomCapteur ni dateDebut n'est nul
                if($dateFin==null){
                    # Seule une date est fournie
                    # Formalisation de la date
                    $date = date("Y-m-d", strtotime($dateDebut));

                    $requete = "SELECT valeur, date, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND nomCapteur LIKE ? AND date = ? ORDER BY date";
                    $params = array($nomCapteur, $date);
                }
                else{
                    # Une période est fournie
                    # Formalisation des dates
                    $dateDebut = date("Y-m-d", strtotime($dateDebut));
                    $dateFin = date("Y-m-d", strtotime($dateFin));
                    
                    if($dateDebut > $dateFin){
                        echo json_encode(['type'=>'Echec', 'msg'=>"Combinaison non autorisée. dateDebut doit être supérieure à dateFin."]); 
                        return false;
                    }

                    $requete = "SELECT valeur, date, typeDonnee, position FROM mesure m, capteur c WHERE m.idCapteur = c.idCapteur AND nomCapteur LIKE ? AND date >= ? AND date <= ? ORDER BY date";
                    $params = array($nomCapteur, $dateDebut, $dateFin);
                }
                
            } //End else de # Ni nomCapteur ni dateDebut n'est nul
            
            $reponse = $pdo->prepare($requete);
            $reponse->execute($params);

            if($data = $reponse->fetchAll()){
                return json_encode($data);
            }
            else{
                echo json_encode(['type'=>'Echec', 'msg'=>"Aucune données de mesure présente dans la base de données."]); 
                return false;
            }

        } //End mesuresCapteur

        public function ajoutMesure($idCapteur, $valeur, $date){
            $pdo = $this->bdd;
            if($date==null)
            #Si la date n'est pas fourni, on prend la date du jour
                $date = date("Y-m-d");
            else
                $date = date("Y-m-d", strtotime($date));

            $requete = "INSERT INTO mesure VALUES (?, ?, ?)";
            $reponse = $pdo->prepare($requete);
            $reponse->execute(array($idCapteur, $valeur, $date));

            if($reponse->rowCount() > 0){
                echo json_encode(['type'=>'Success', 'msg'=>"Mesure ajoutée."]); 
                return true;
            }
            else{
                echo json_encode(['type'=>'Echec', 'msg'=>"Une erreur est survenue lors de l'ajout de la mesure."]); 
                return false;
            }

        } //End ajoutmesure

        public function ajoutCapteur($nomCapteur, $typeDonnee, $position, $latitude, $longitude){
            $pdo = $this->bdd;
            # Vérification de la validité du type de données entré
            if(!self::checkTypeDonnee($typeDonnee)){
                echo json_encode(['type'=>'Echec', 'msg'=>'Type non autorisé.', 'Types autorisés' => self::getTypeDonnee()]);
                return -1;
            }
            # Ajout de la position du capteur dans la table position
            if(self::ajoutPosition($latitude, $longitude, $position)==-1){
                return -1;
            }
            $requete = "INSERT INTO capteur (nomCapteur, typeDonnee, position) VALUES (:nomCapteur, :typeDonnee, :position)";
            $reponse = $pdo->prepare($requete);
            $reponse->execute(array(
                'nomCapteur' => $nomCapteur,
                'typeDonnee' => $typeDonnee,
                'position' => $position          
            ));

            if($reponse->rowCount() > 0){
                echo json_encode(['type'=>'Success', 'msg'=>"Capteur ajouté."]); 
                return 0;
            }
            else{
                echo json_encode(['type'=>'Echec', 'msg'=>"Une erreur est survenue lors de l'ajout du capteur."]); 
                return -1;
            }

        } //End ajoutCapteur

        private function ajoutPosition($latitude, $longitude, $position){
            $pdo = $this->bdd;
            # Vérification de l'unicité de la position
            if(self::checkPosition($latitude, $longitude)==-1)
                return 0;

            $requete = "INSERT INTO position VALUES (?, ?, ?)";
            $reponse = $pdo->prepare($requete);
            $reponse->execute(array($latitude, $longitude, $position));

            if($reponse->rowCount() > 0){
                echo json_encode(['type'=>'Success', 'msg'=>"Position ajoutée."]); 
                return 0;
            }
            else{
                echo json_encode(['type'=>'Echec', 'msg'=>"Une erreur est survenue lors de l'ajout de la position du capteur."]); 
                return -1;
            }
        } //End ajoutPosition

        private function checkPosition($latitude, $longitude){
            $pdo = $this->bdd;

            $requete = "SELECT designation FROM position WHERE latitude=? AND longitude=?";
            $reponse = $pdo->prepare($requete);
            $reponse->execute(array($latitude, $longitude));

            if($reponse->fetch())
                return -1;
            else
                return 0;

        } //End checkPosition

        private function checkTypeDonnee($typeDonnee){
            $pdo = $this->bdd;
            $requete = "SELECT idCapteur FROM capteur WHERE typeDonnee=?";
            $reponse = $pdo->prepare($requete);
            $reponse->execute(array($typeDonnee));

            if($reponse->fetch())
                return true;
            else
                return false;

        } //End checkTypeDonnee

        private function getTypeDonnee(){
            $pdo = $this->bdd;
            $requete = "SELECT typeDonnee FROM capteur ORDER BY typeDonnee";
            $reponse = $pdo->query($requete);

            if($types = $reponse->fetchAll())
                return $types;
            else{
                echo json_encode(['type'=>'Echec', 'msg'=>"Aucun type de donnée trouvé dans la base de données."]); 
                return -1;
            }

        } //End getTypeDonnee

    } //End Databse
