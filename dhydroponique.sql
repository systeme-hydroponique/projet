CREATE DATABASE IF NOT EXISTS dhydroponique;

USE dhydroponique;

CREATE TABLE IF NOT EXISTS hdpc (
    ID_hdpc INT(11) PRIMARY KEY AUTO_INCREMENT,
    nom_hdpc VARCHAR(25),
    prenom_hdpc VARCHAR(25),
    date_naissance_hdpc DATE,
    email VARCHAR(25),
    mdp VARCHAR(25)
);

CREATE TABLE IF NOT EXISTS espece (
    ID_espece INT(11) PRIMARY KEY AUTO_INCREMENT,
    nom_plante VARCHAR(50),
    type_plante VARCHAR(50),
    type_de_substrat VARCHAR(25)
);


CREATE TABLE IF NOT EXISTS plante (
    ID_plante INT(11) PRIMARY KEY AUTO_INCREMENT,
    FOREIGN KEY (ID_espece) REFERENCES espece(ID_espece),
    date_semis DATE,
);



CREATE TABLE IF NOT EXISTS possession(
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    FOREIGN KEY (ID_hdpc) REFERENCES hdpc(ID_hdpc),
);

CREATE TABLE IF NOT EXISTS c_ensoleillement (
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    lux INT(2),
    date_recuperation DATETIME 
);

CREATE TABLE IF NOT EXISTS c_humidite_sol (
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    HR FLOAT(5),
    date_recuperation DATETIME 
);

CREATE TABLE IF NOT EXISTS c_pH (
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    pH INT(1),
    date_recuperation DATETIME 
);

CREATE TABLE IF NOT EXISTS c_temperature (
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    temperature INT(1),
    date_recuperation DATETIME 
);
CREATE TABLE IF NOT EXISTS c_niveau_eau (
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    niveau INT(1),
    date_recuperation DATETIME 
);

