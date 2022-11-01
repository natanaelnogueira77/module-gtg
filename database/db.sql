DROP TABLE IF EXISTS 
    config, 
    usuario, 
    usuario_meta, 
    usuario_tipo, 
    email_template, 
    menu;

CREATE TABLE config (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    meta VARCHAR(50) NOT NULL,
    value TEXT NULL
);

CREATE TABLE usuario (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    utip_id INT(1) NOT NULL,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    token VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    date_c DATE NOT NULL,
    date_m DATE NOT NULL
);

CREATE TABLE usuario_meta (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    usu_id INT(1) NOT NULL,
    meta VARCHAR(45) NOT NULL,
    value TEXT NULL
);

CREATE TABLE usuario_tipo (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    name_sing VARCHAR(45) NOT NULL,
    name_plur VARCHAR(45) NOT NULL
);

CREATE TABLE email_template (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    usu_id INT(1) NOT NULL,
    name VARCHAR(45) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    content TEXT NOT NULL,
    meta VARCHAR(45) NOT NULL,
    date_c DATETIME NOT NULL,
    date_m DATETIME NOT NULL
);

CREATE TABLE menu (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    utip_id INT(1) NOT NULL,
    name VARCHAR(45) NOT NULL,
    meta VARCHAR(45) NOT NULL,
    content TEXT NULL
);

-- Tabelas Exemplo
DROP TABLE IF EXISTS 
    social_usuario;

CREATE TABLE social_usuario (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    usu_id INT(1) NOT NULL,
    social_id VARCHAR(255),
    email VARCHAR(150),
    social VARCHAR(100),
    date_c DATETIME
);