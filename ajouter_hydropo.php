<!DOCTYPE html>
<html lang="fr">
<head>    
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page gestion</title>
    <link rel="stylesheet" href="testcode.css">
</head>

<?php
    session_start();
    // Voir les erreurs
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $id_hdpc = $_SESSION['id_hdpc'];
    $bdd = new PDO("mysql:host=localhost;dbname=dhydroponique;charset=utf8", "root", "");

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
    
<body>

<header class="sticky-header">
    <nav>
        <ul class="nav-list">
            <li><p><?php echo $ligne_n_p['prenom_hdpc']." ". $ligne_n_p['nom_hdpc']; ?></p></li>
            <li><a href="page_gestion.php">Tableau de bord</a></li>
            <li><a href="page_deconnexion.php">Se déconnecter</a></li>
        </ul>
    </nav>
</header>
<h1>Ajouter un systeme hydroponique</h1>
<?php
    if (isset($_POST['code']) && isset($id_hdpc)){
        $code = $_POST['code'];

        $req_code_existe = $bdd->prepare("SELECT ID_plante FROM plante WHERE code = :code");
        $req_code_existe->bindParam(':code', $code);
        $req_code_existe->execute();
        $resultat = $req_code_existe->fetch();
        
        if ($resultat !== false) {
            $id_plante = $resultat['ID_plante'];
            $req_ajout = $bdd->prepare("INSERT INTO possession VALUES (:id_plante, :id_hdpc, 'Système hydroponique 1')");
            $req_ajout->bindParam(':id_plante', $id_plante);
            $req_ajout->bindParam(':id_hdpc', $id_hdpc);
            
            if ($req_ajout->execute()){
                echo "<script>alert('Système hydroponique ajouté avec succès !');</script>";
            } else {
                echo "<script>alert('Erreur lors de l\'ajout du système hydroponique, veuillez réessayer !');</script>";
            }
        } else {
            echo "<script>alert('Ce code n\'est associé à aucun système hydroponique, veuillez réessayer avec un autre code !');</script>";
        }
    }
?>
<form method="post" action="page_gestion.php">
    <div class="contenant">
        <label for="code">Code sur le système hydroponique: </label> 
        <input id="code" name="code" type="text" size="30" placeholder="00000000" required="required" /> 
    </div> 
    <div class="bouton-container">
        <input type="submit" name="Ajouter à mes systèmes hydroponiques" value="Ajouter à mes systèmes hydroponiques" class="bouton"/>
    </div>
</form>
