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
* __afficheMesures (location, date)__ : Affichage des mesures des capteurs.  
> __Note:__ Les paramètres sont facultatifs. S'ils ne sont pas spécifiés, toutes les mesures seront retournées.  


### Fonctions d'upload de données (post):  
* __connexion(login, password, admin)__ : Connexion d'un utilisateur à la base de données.  
> __Note:__ le paramètre *admin* est un booléen qui vaut *true* si l'utilisateur qui se connecte est un administrateur et *false* sinon. Il est __facultatif__, il est considéré *false* par défaut.   
* __ajoutUtilisateur(login, email, password)__: Ajout d'un nouvel utilisateur à la base de données.  




