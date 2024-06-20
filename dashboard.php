<!DOCTYPE html>
<html lang="fr">
<head>    
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page gestion</title>
    <link rel="stylesheet" href="testcode.css">
</head>

<body>
<h1>Mes systèmes hydroponiques</h1>
<?php
    session_start();
    // Voir les erreurs
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $id_hdpc = $_SESSION['id_hdpc'];
    $bdd = new PDO("mysql:host=localhost;dbname=dhydroponique;charset=utf8", "hydrop", "Hydrop1234");

    // Vérifier si l'ID de session est défini pour éviter les erreurs
    if (isset($id_hdpc)) {
        $req_n_p = $bdd->prepare("SELECT nom_hdpc, prenom_hdpc FROM hdpc WHERE ID_hdpc = :id_hdpc");
        $req_n_p->bindParam(':id_hdpc', $id_hdpc);
        $req_n_p->execute();
        $ligne_n_p = $req_n_p->fetch();
    } else {
        // Gérer le cas où l'ID_hdpc n'est pas défini (par exemple, utilisateur non connecté)
        $ligne_n_p = array('prenom_hdpc' => 'Inconnu', 'nom_hdpc' => 'Utilisateur');
    }
?>

<header class="sticky-header">
    <nav>
        <ul class="nav-list">
            <li><p><?php echo $ligne_n_p['prenom_hdpc']." ". $ligne_n_p['nom_hdpc']; ?></p></li>
            <li><a href="page_gestion.php">Tableau de bord</a></li>
            <li><a href="page_deconnexion.php">Se déconnecter</a></li>
        </ul>
    </nav>
</header>

<div class="contenant">
    <?php
        if (isset($id_hdpc)) {
            $req_mes_systemes = $bdd->prepare("SELECT possession.nom_possession, plante.lien FROM possession INNER JOIN plante ON plante.ID_plante = possession.ID_plante WHERE possession.ID_hdpc = :id_hdpc");
            $req_mes_systemes->bindParam(':id_hdpc', $id_hdpc);
            $req_mes_systemes->execute();
            $nb_lignes = $req_mes_systemes->rowCount();
            while ($ligne = $req_mes_systemes->fetch()){
                $nom_possession = $ligne['nom_possession'];
                $lien_dashboard = $ligne['lien'];
                echo '<a href="' . $lien_dashboard . '" class="bouton-lien">' . $nom_possession . '</a>';
            }
        } else {
            echo "<p>Aucun système hydroponique trouvé.</p>";
        }
    ?>
</div>

</body>
</html>


