<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="testcode.css">
    <title>Connexion</title>
</head>
<body>
    <header class="sticky-header">
        <nav>
            <ul class="nav-list">
                <li><a href="page_accueil.php">Accueil</a></li>
                <li><a href="page_connexion.php">Se connecter</a></li>
                <li><a href="page_inscription.php">S'inscrire</a></li>
            </ul>
        </nav>
    </header>

    <div class="contenant">
        <div id="titre_connexion"><h1>Se connecter</h1></div>
        <p>Vous n'avez pas de compte ? <a href="page_inscription.php">S'inscrire</a></p>
        <div class="formulaire">
            <form method="post" action="page_connexion.php">
                <div class="champ_entree">
                    <label for="E-mail">E-mail : </label>
                    <input id="E-mail" name="email" size="30" type="email" placeholder="andre.lenotre@utbm.fr" required="required"/>
                </div>
                <div class="champ_entree">
                    <label for="Mot de passe">Mot de passe : </label>
                    <input id="Mot de passe" name="mot_de_passe" size="30" type="password" required="required"/>
                </div>
                <div class="champ_entree">
                    <input type="submit" name="envoyer" value="Se connecter" class="bouton"/>
                </div>
            </form>
        </div>
    </div>

    <?php
        // Démarrer une session si nécessaire
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Connexion à la base de données
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=dhydroponique', 'root', '');
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }

        // Traitement du formulaire de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['mot_de_passe'])) {
            $email = $_POST['email'];
            $mdp = $_POST['mot_de_passe'];

            $req_email_existe = $bdd->prepare("SELECT email FROM hdpc WHERE email = :email");
            $req_email_existe->execute(['email' => $email]);
            $nb_lignes = $req_email_existe->rowCount();

            if ($nb_lignes === 0) {
                echo "<script>
                        var url = 'page_inscription.php';
                        if(window.confirm('Ce compte n\'est associé à aucun e-mail, cliquez sur confirmer pour naviguer vers la page inscription ou annuler pour saisir un autre e-mail.')) {
                            window.open(url);
                        }
                    </script>";
            } else {
                $req_connexion = $bdd->prepare("SELECT ID_hdpc FROM hdpc WHERE email = :email AND mdp = :mdp");
                $req_connexion->execute(['email' => $email, 'mdp' => $mdp]);
                $nb_lignes_2 = $req_connexion->rowCount();

                if ($nb_lignes_2 === 0) {
                    echo "<script>alert('Le mot de passe est incorrect, veuillez réessayer !');</script>";
                } else {
                    $_SESSION['id_hdpc'] = $req_connexion->fetch()['ID_hdpc'];
                    echo "<script>
                            alert('Connexion réussie!');
                            setTimeout(function() {
                                window.location.href = 'page_gestion.php';
                            }, 250);
                        </script>";
                }
            }
        }
    ?>

</body>
</html>
