<?php
    namespace App\Controllers;

    # Configuartion
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;

    class ManipUsersController{
        # Variable stockant le conteneur
        private $container;

        #Constructeur
        public function __construct($container){
            $this->container = $container;
        }

        # Fonctions
        /**
         * Connexion des clients à la base de données
         */
        public function connect(Request $request, Response $response){
            $bdd = $this->container->db;
            
            # Récupération des variables
            $email = $request->getParam('email');
            $password = $request->getParam('password');
            $admin = $request->getParam('admin');
            # Passage de string en booléen
            $admin = $admin=="true" ? true : false;
        
            # Vérification des attributs et retour du résultat
            $response->getBody()->write($bdd->connexion($email, $password, $admin));
        
            #return $response;
        } //End connect

        /**
         * Ajout d'utilisateurs
         */
        public function addCustomer(Request $request, Response $response){
            $bdd = $this->container->db;

            # Récupération des variables
            $prenom = $request->getParam('prenom');
            $nom = $request->getParam('nom');
            $email = $request->getParam('email');
            $password = $request->getParam('password');
            $telephone = $request->getParam('telephone');
    
            # Retour du résultat
            $response->getBody()->write($bdd->ajoutClient($prenom, $nom, $email, $password, $telephone));
    
            return $response;
        }

        /**
         * Fonction permettant d'envoyer des mails
         */
        public function sendEmail(Request $request, Response $response) {
            //Prise des paramètres
            $destinataire = $request->getParam('destinataire');
            $sujet = $request->getParam('sujet');
            $message = $request->getParam('message');

            // Create the Transport
            $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
            ->setUsername('un_compte_gmail')
            ->setPassword('le_mot_de_passe_correspondant')
            ;

            // Create the Mailer using your created Transport
            $mailer = new \Swift_Mailer($transport);

            // Create a message
            $message = (new \Swift_Message($sujet))
            ->setFrom(['mbayederguene97@gmail.com' => 'Derguene Mbaye'])
            ->setTo([$destinataire => $destinataire])
            ->setBody($message)
            ;

            // Send the message
            $message = ($mailer->send($message)) == 1 ? "Message envoyé" : "Echec de l'envoie du message";
            $response->getBody()->write($message);
        } //End sendEmail

    } //End ManipUsersController