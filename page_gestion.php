<DOCTYPE! html>
<html lang="fr">
<head>    
	<meta charset = 'UTF-8' /> 
	<meta name = 'viewport' content = 'width=device-width' initial-scale='1.0'/>
	<title>Page gestion</title>
	<link rel="stylesheet" href="graphisme.css">-->
</head>
<body>

    <?php
        session_start();
        // voir les erreurs //
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $id_hdpc = $_SESSION['id_hdpc'];
        $bdd = new PDO("mysql:host=localhost;dbname=dhydroponique;charset=utf8", "root", "");
    ?>

    <?php
        $req_n_p = $bdd->prepare("SELECT nom_hdpc, prenom_hdpc FROM hdpc WHERE ID_hdpc = '$id_hdpc' ;");
        $req_n_p->execute();
        $ligne_n_p = $req_n_p->fetch();
    ?>
    
    <div id = 'sous_titre'>
        <div id = 'sous_titre_1'><?php echo $ligne_n_p['prenom_hdpc']." ". $ligne_n_p['nom_hdpc']?></div>
        <div id = 'sous_titre_4'><a href = "page_deconnexion.php">Déconnexion</a></div>
    </div>
    <div id = 'titre_1'>
	    <p>Tableau de gestion</p>
    </div>

    <div> Mes systèmes hydropniques</div>
    <?php
        $req_mes_systemes = $bdd->prepare("SELECT possession.nom_possession, plante.lien FROM possession INNER JOIN plante ON plante.ID_plante = possession.ID_plante WHERE  possession.ID_hdpc = '$id_hdpc';");
        $req_mes_systemes->execute();
        $nb_lignes = $req_mes_systemes->rowCount();
        while ($ligne = $req_mes_systemes->fetch()){
                $nom_possession = $ligne['nom_possession'];
                $lien_dashboard = $ligne['lien'];
                echo "<a href = $lien_dashboard>$nom_possession</a>";}
    ?>
    <div> Ajouter un système hydroponique</div>
        <form method = 'post' action = 'page_gestion.php'>
            <div class = 'champ_entree'>
				<label for = 'Code'>Code sur le système hydroponique: </label> <input id='code' name='code' type='text' size = '30' placeholder='00000000' required='required' /> 
			</div> 
            <div class='bouton'><input type='submit' name ='Ajouter à mes systèmes hydroponiques' value='Ajouter à mes systèmes hydroponiques' /></div>
        </form>
    <?php
		if (isset($_POST['code'])){
            $code = $_POST['code'];

            $req_code_existe = $bdd->prepare("SELECT ID_plante FROM plante WHERE code = '$code' ;");
			$req_code_existe->execute();
            $resultat = $req_code_existe->fetch();
            $id_plante = $resultat['ID_plante'];
			$nb_lignes = $req_code_existe->rowCount();
			if ($nb_lignes === 0) {
				echo "<script>alert('Ce code n\'est associé à aucun système hydroponique, veuillez réessayer avec un autre code !');</script>";}			
			else {
                $req_ajout = $bdd->prepare("INSERT INTO possession VALUES ('$id_plante','$id_hdpc','Système hydroponique 1');");
                if ($req_ajout->execute()){
                    echo "<script>alert('Système hydroponique ajouté avec succès !');</script>";}
                else {
                    echo "<script>alert('Erreur lors de l\'ajout du système hydroponique, veuillez réessayer !');</script>";}
                }          
        }
    ?>

</body>