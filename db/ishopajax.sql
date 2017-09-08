DROP DATABASE IF EXISTS ishop;
CREATE database ishop
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE ishop;

CREATE TABLE categoria (
  id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(70) NOT NULL
) ENGINE=InnoDB;

INSERT INTO categoria (id, nombre) VALUES
(1, 'Remeras'),
(2, 'Buzos'),
(3, 'Campera'),
(4, 'Pantalones'),
(5, 'Medias'),
(6, 'Zapatillas'),
(7, 'Bufandas');

CREATE TABLE categoria_sexo (
  id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(20) NOT NULL
) ENGINE=InnoDB;

INSERT INTO categoria_sexo (id, nombre) VALUES
(1, 'Hombre'),
(2, 'Mujer');

CREATE TABLE nivel (
  id int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(20) NOT NULL
) ENGINE=InnoDB;

INSERT INTO nivel (id, nombre) VALUES
(1, 'admin'),
(2, 'editor');

CREATE TABLE productos (
  id int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre varchar(70) NOT NULL DEFAULT '',
  descripcion text,
  precio float(6,2) unsigned NOT NULL DEFAULT '0.00',
  imagen varchar(255) NOT NULL DEFAULT '',
  stock int(10) unsigned DEFAULT '0',
  id_categoria int(11) unsigned NOT NULL,
  id_cat_sexo int(11) unsigned NOT NULL,

  FOREIGN KEY (id_categoria) REFERENCES categoria (id) ON UPDATE CASCADE ON DELETE CASCADE,
  FOREIGN KEY (id_cat_sexo) REFERENCES categoria_sexo (id) ON UPDATE CASCADE ON DELETE CASCADE

) ENGINE=InnoDB;

INSERT INTO productos (id, nombre, descripcion, precio, imagen, stock, id_categoria, id_cat_sexo) VALUES
(1, 'Tamaris', 'Botas - Negro - Material Especial', 109.00, 'TA111M00D-Q11@3.jpg', 30, 6, 2),
(2, 'Levi´s', 'STANDARD GRAPHIC CREW GOOD/BETTER - Camiseta print - blanco', 12.25, 'LE222D02S-002@1.1.jpg', 40, 1, 1),
(3, 'Tom Tailor', 'Camiseta básica - negro', 7.95, 'TO222D05P-802@1.1.jpg', 50, 1, 1),
(4, 'Tom Tailor Denim', 'Pantalón chino - amarillo', 49.95, 'TO722A01Y-705@6.jpg', 60, 4, 1),
(5, 'Vans', 'AUTHENTIC - Zapatillas - turquesa', 74.95, 'VA211A05E-354@1.1.jpg', 20, 6, 2),
(6, 'Merc', 'Tobias - Parca - Azul - Grueso', 174.80, '3ME22G00E-503@1.2.jpg', 30, 3, 1),
(7, 'Camano', 'Calcetines - Azul exelente para invierno', 9.95, 'C5154J00T-502@1.1.jpg', 45, 5, 1);

CREATE TABLE usuarios (
  id int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  usuario varchar(20) NOT NULL,
  password varchar(255) NOT NULL,
  nombre varchar(50) NOT NULL,
  apellido varchar(50) NOT NULL,
  email varchar(100) NOT NULL UNIQUE,
  sexo varchar(1) DEFAULT NULL,
  imagen varchar(100) DEFAULT NULL,
  id_nivel int(10) unsigned NOT NULL,
  FOREIGN KEY (id_nivel) REFERENCES nivel (id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO usuarios (id, usuario, password, nombre, apellido, email, sexo, imagen, id_nivel) VALUES
(1, 'admin', '123123', 'admin', 'admin', 'admin@davinci.edu.ar', 'm', '', 1),
(2, 'editor', '123123', 'editor', 'editor', 'editor@davinci.edu.ar', 'f', '', 2);