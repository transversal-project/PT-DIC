<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Interface d'administration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    
</head>
<body>
    <!-- header -->
    <section class="container-fluid header">
            <div class="container">
                <a href="admin.php" class="logo">Airfilter Administration Platform</a>
             <!--   <nav class="menu">
                    <a href="index.php"><i class="fa fa-home" aria-hidden="true"></i> Accueil</a>
                    <a href="index.php?page=reservation"><i class="fa fa-calendar-check-o" aria-hidden="true"></i> Réservation</a>
                    <a href="index.php?page=login"><i class="fa fa-user-circle" aria-hidden="true"></i> Connexion</a>
                </nav> -->
            </div>
    </section>
    <!-- End header -->

    <!-- Vehicules -->
    <section class="container datatable-section">
        <h2 id="about" class="text-center"> Utilisateurs </h2>
        <hr class="separator">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <table id="datatable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Nom d'utilisateur</th>
                                <th>Mot de passe</th>
                                <th>Email</th>
                                <th>État du compte</th>
                            </tr>
                        </thead>
                    <tbody>
                    <?php /** Début Traitement BD */
                        #Intégration des dépendances
                        require_once 'connexion.php';
                        require_once 'adminFunctions.php';
                        //Chargement des comptes
                        $comptes = getComptes();

                        //Validation d'un compte
                        if(isset($_GET['id'])){
                            $id = htmlspecialchars($_GET['id']);
                            validerCompte($id);
                        } //End if

                        //Affichage des comptes
                        foreach($comptes as $compte){
                    ?>                               
                            <tr>
                                <td><?php echo $compte['login']; ?></a></td>
                                <td><?php echo $compte['password']; ?></td>
                                <td><?php echo $compte['email']; ?></td>
                                <td><?php echo $compte['validation']; ?></td>
                                <?php
                                    #Affichage de la fonction à réaliser selon l'état du compte 
                                    if($compte['validation']=='ok'){
                                        ?>
                                        <td><button class="btn btn-success">Validé</button></td>
                                        <?php
                                    }
                                    else if($compte['validation']=='en attente'){
                                        ?>
                                        <td><a href="admin.php?id=<?php echo $compte['id'];?>" > <button class="btn btn-warning">Valider</button> </a> </td>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <td><a href="admin.php?id=<?php echo $compte['id'];?>" > <button class="btn btn-danger">Débannir</button> </a> </td>
                                        <?php
                                    }
                                ?>
                            </tr>
                        <?php
                        } //End foreach
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        </section>
        <!-- End vehicules -->
         <!-- Footer -->
    <footer class="py-5 bg-primary">
      <div class="container-fluid">
        <p class="m-0 text-center text-white">Copyright &copy; Derguene-Mbaye 2018</p>
      </div>
      <!-- /.container -->
    </footer>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>