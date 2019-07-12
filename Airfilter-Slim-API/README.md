# Airfilter API
Transversal project DIC: API gérant la communication avec la base de données  
------------------------------------------------------------------
Cet API, réalisé avec le micro framework  __Slim 3__, contient une multitude de *fonctions* renvoyant des données au format __JSON__.  
Les requêtes devront être structurées de la sorte :  
## Pour les fonctions d'affichage (qui doivent retourner des données) :  

	get('http://localhost:numero-du-port/nom-de-la-fonction',{  
                Paramètre_1: 'valeur_1',  
                Paramètre_2: 'valeur_2',  
                ...,  
                Paramètre_n: 'valeur_n'  
                
                } ,function(data){  
                
                console.log(data)  
            })  
  
## Pour les fonctions d'upload de données (qui doivent recevoir des données) :  

	post('http://localhost:numero-du-port/nom-de-la-fonction',{
                Paramètre_1: 'valeur_1',
                Paramètre_2: 'valeur_2',
                ...,
                Paramètre_n: 'valeur_n'
                
                } ,function(data){
                
                console.log(data)
            })
  
  
## Fonctions disponibles :  
### Fonctions d'affichage (get):  
* __afficheMesures (position, dateDebut, dateFin)__ : Affichage des mesures des capteurs sur une période définie.  
> __Note:__ Tous les paramètres sont facultatifs. S'ils ne sont pas spécifiés, toutes les mesures seront retournées. Il est possible de spécifier un paramètre à la fois sauf __dateFin__.  
* __mesuresCapteur (nomCapteur, dateDebut, dateFin)__ : affichage d'un capteur donné.  
> __Note:__ Tous les paramètres sont facultatifs sauf __nomCapteur__. Il est possible de spécifier un paramètre à la fois sauf __dateFin__.   
* __distanceCapteur (latitude1, longitude1, latitude2, longitude2, unite)__ : Calcul de distance entre deux capteurs à partir de leurs coordonnées géographiques (latitudes et longitudes).  
> __Note:__ L'__unité__ est facultative. Le __km__ est considéré par défaut. Les unités prises en compte sont: __K__ (Kilomètre), __M__ (Miles) et __N__ (Nautical miles).  
* __afficheCapteurs (nomCapteur, typeDonnee, position)__ : Fonction d'affichage des capteurs.  
> __Note:__ Les paramètres sont facultatifs. Un seul paramètre peut être spécifié à la fois. Si aucun paramètre n'est spécifié, tous les capteurs seront affichés.  


### Fonctions d'upload de données (post):  
* __connexion (email, password, admin)__ : Connexion d'un utilisateur à la base de données.  
> __Note:__ le paramètre *admin* est un booléen qui vaut *true* si l'utilisateur qui se connecte est un administrateur et *false* sinon. Il est __facultatif__, il est considéré *false* par défaut.   
* __ajoutClient (prenom, nom, email, password, telephone)__: Ajout d'un nouveau client à la base de données.  
* __ajoutMesure (idCapteur, valeur, date)__ : Ajout d'une mesure d'un capteur.  
> __Note:__ L'attribut __date__ est *facultatif*; S'il n'est pas spécifié, la date du jour sera considéré.  
* __ajoutCapteur (nomCapteur, typeDonnee, position, latitude, longitude)__: Ajout d'un capteur.  
> __Note:__ Le type de donnée est soit __pm1__, __pm10__ ou __pm25__.   




