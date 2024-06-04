<DOCTYPE! html>
<html lang="fr">
	<head>    
		<meta charset = 'UTF-8' /> 
		<meta name = 'viewport' content = 'width=device-width; initial-scale=1.0'/>
		<!--<link rel = 'stylesheet' href = 'style_conn_ins.css'/>-->
		<title>Inscription</title>
	</head>
	<body>

	<div id ='contenant_1'>
		<div id = 'conn'><a href = "page_accueil.php">Page d'accueil</a></div>
	</div>

		<div class ='contenant'>
		<h1>S'inscrire</h1>
		<p>Vous avez déjà un compte ? <a href ='connexion.php' title="Cliquez ici pour vous connectez">Se connecter</a></p>
			<form method = 'post' action = 'page_inscription.php'>
				<div class = 'champ_entree'>
					<label for = 'Nom'>Nom : </label> <input id='nom' name='nom' type='text' size = '30' placeholder='Le Nôtre' required='required' /> 
				</div> 
				<div class = 'champ_entree'>
					<label for = 'Prénom'>Prénom : </label><input id='prenom' name='prenom' type='text' size = '30' placeholder='André'required='required'/>  
				</div>
				<div class = 'champ_entree'>
					<label for = 'Date de naissance'>Date de naissance : </label><input id='date-naissance' name='naissance' type='date' required='required'/>  
				</div>
				<div class = 'champ_entree'>
					<label for = 'Email'>E-Mail : </label><input id='email' name='email' type='text' size = '30' placeholder='andre.lenotre@utbm.fr'required='required'/>  
				</div>
				<div class = 'champ_entree'>
					<label for = 'Mot de passe'>Mot de passe : </label><input id='mdp' name='mot_de_passe' type='password' size = '30'required='required'/>  
				</div>
			</form>
	</body>
</html>