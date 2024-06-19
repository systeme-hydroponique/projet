-- Active: 1713532825295@@127.0.0.1@3306@dhydroponique
CREATE DATABASE IF NOT EXISTS dhydroponique;

USE dhydroponique;

CREATE TABLE IF NOT EXISTS hdpc (
    ID_hdpc INT(11) PRIMARY KEY AUTO_INCREMENT,
    nom_hdpc VARCHAR(25),
    prenom_hdpc VARCHAR(25),
    date_naissance_hdpc DATE,
    email VARCHAR(25) UNIQUE,
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
    ID_espece INT(11),
    FOREIGN KEY(ID_espece) REFERENCES espece(ID_espece),
    date_semis DATE,
    code VARCHAR(8) UNIQUE,
    lien VARCHAR(100) UNIQUE
);



CREATE TABLE IF NOT EXISTS possession (
    ID_plante INT(11),
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    ID_hdpc INT(11),
    FOREIGN KEY (ID_hdpc) REFERENCES hdpc(ID_hdpc),
    nom_possession VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS c_ensoleillement (
    ID_plante INT(11),
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    lux INT(2),
    date_recuperation DATETIME 
);

CREATE TABLE IF NOT EXISTS c_humidite_sol (
    ID_plante INT(11),
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    HR FLOAT(5),
    date_recuperation DATETIME 
);

CREATE TABLE IF NOT EXISTS c_pH (
    ID_plante INT(11),
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    pH INT(1),
    date_recuperation DATETIME 
);

CREATE TABLE IF NOT EXISTS c_temperature (
    ID_plante INT(11),
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    temperature INT(1),
    date_recuperation DATETIME 
);
CREATE TABLE IF NOT EXISTS c_niveau_eau (
    ID_plante INT(11),
    FOREIGN KEY (ID_plante) REFERENCES plante(ID_plante),
    niveau INT(1),
    date_recuperation DATETIME 
);

INSERT INTO hdpc(nom_hdpc, prenom_hdpc, date_naissance_hdpc, email, mdp) VALUES ('BENED', 'Julie', '2005-09-17','julie.bened@utbm.fr', 'juliebened17'),
('BEAUCHAMP', 'Manon', '2005-01-13','manon.beauchamp@utbm.fr', 'manonbeauchamp17'),
('DESMARET','Mathéo','2005-09-08','matheo.desmaret@utbm.fr','matheodesmaret08'),
('LAMBERT','Gabin','2005-07-14','gabin.lambert@utbm.fr','gabinlambert14'),
('FOUICH','Noa','2004-08-18','noa.fouich@utbm.fr','noafouich18');

INSERT INTO espece(nom_plante, type_plante, type_de_substrat) VALUES ('Lentilles', 'Légume', 'Terreau');

INSERT INTO plante(ID_espece, date_semis, code, lien) VALUES (1, '2024-05-02', '24062024', 'http://127.0.0.1:1880/ui/#!/0?socketid=4LWsIzQMaFQt0F8AAAAB');

INSERT INTO possession(ID_plante, ID_hdpc, nom_possession) VALUES (1, 1, 'Lentilles de Julie'),(1, 2, 'Lentilles de Manon'),(1, 3, 'Lentilles de Mathéo'), (1, 4, 'Lentilles de Gabin'), (1, 5, 'Lentilles de Noa');
