<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="testcode.css">
    <title>Inscription</title>
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
        <div id="titre_inscrire"><h1>S'inscrire</h1></div>
        <p>Vous avez déjà un compte ? <a href="page_connexion.php" title="Cliquez ici pour vous connectez">Se connecter</a></p>
        <div class="formulaire">
            <form method="post" action="page_inscription.php">
                <div class="champ_entree">
                    <label for="nom">Nom : </label>
                    <input id="nom" name="nom" type="text" size="30" placeholder="Le Nôtre" required="required"/>
                </div>
                <div class="champ_entree">
                    <label for="prenom">Prénom : </label>
                    <input id="prenom" name="prenom" type="text" size="30" placeholder="André" required="required"/>
                </div>
                <div class="champ_entree">
                    <label for="date_naissance">Date de naissance : </label>
                    <input id="date_naissance" name="date_naissance" type="date" required="required"/>
                </div>
                <div class="champ_entree">
                    <label for="email">E-Mail : </label>
                    <input id="email" name="email" type="text" size="30" placeholder="andre.lenotre@utbm.fr" required="required"/>
                </div>
                <div class="champ_entree">
                    <label for="mdp">Mot de passe : </label>
                    <input id="mdp" name="mot_de_passe" type="password" size="30" required="required"/>
                </div>
                <div class="champ_entree">
                    <input type="submit" name="Inscription" value="S'inscrire" class="bouton"/>
                </div>
            </form>
        </div>
    </div>

    <?php
    $bdd = new PDO('mysql:host=localhost;dbname=dhydroponique', 'root', '');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['date_naissance']) && isset($_POST['email']) && isset($_POST['mot_de_passe'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $date_naissance = $_POST['date_naissance'];
        $email = $_POST['email'];
        $mot_de_passe = $_POST['mot_de_passe'];

        $req_email_existe = $bdd->prepare("SELECT email FROM hdpc WHERE email = :email");
        $req_email_existe->execute(['email' => $email]);
        $nb_lignes = $req_email_existe->rowCount();
        if ($nb_lignes > 0) {
            echo "<script>
                var url = 'page_connexion.php';
                if(window.confirm('Ce compte est déjà associé à un e-mail, cliquer sur confirmer pour naviguer vers la page connexion ou annuler pour saisir un autre e-mail.')) {
                    window.open(url);
                }
            </script>";
        } else {
            $req_inscription = $bdd->prepare("INSERT INTO hdpc (nom_hdpc, prenom_hdpc, date_naissance_hdpc, email, mdp) VALUES (:nom, :prenom, :date_naissance, :email, :mot_de_passe)");
            if ($req_inscription->execute(['nom' => $nom, 'prenom' => $prenom, 'date_naissance' => $date_naissance, 'email' => $email, 'mot_de_passe' => $mot_de_passe])) {
                echo "<script>
                    var url = 'page_connexion.php';
                    if(window.confirm('Inscription réussie, cliquer pour continuer vers la page connexion')) {
                        window.open(url);
                    }
                </script>";
            } else {
                echo "<script>alert('L\'inscription a échouée, veuillez réessayer.')</script>";
            }
        }
    }
    ?>
</body>
</html>
