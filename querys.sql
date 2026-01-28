CREATE DATABASE twitter_clone
DEFAULT CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE twitter_clone;
CREATE TABLE usuarios(
	 id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(32) NOT NULL
);

-- UPDATE usuarios set senha = md5(senha) where id in (1,2)

CREATE TABLE tweets(
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    id_usuario INT NOT NULL,
    tweet varchar(140) not null,
    data DATETIME DEFAULT CURRENT_TIMESTAMP
);