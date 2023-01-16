DROP TABLE IF EXISTS 
    config, 
    usuario, 
    usuario_meta, 
    usuario_tipo, 
    social_usuario;

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
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
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
    name_plur VARCHAR(45) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);

CREATE TABLE social_usuario (
    id INT(1) AUTO_INCREMENT PRIMARY KEY,
    usu_id INT(1) NOT NULL,
    social_id VARCHAR(255),
    email VARCHAR(150),
    social VARCHAR(100),
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
);