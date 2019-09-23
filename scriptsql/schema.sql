/**
@author Amner Saucedo Sosa
@Abisoft Modelo de base de datos
**/
/**
paises
ciudades
tiponegocios
	Restaurante, FastFood, Delivery, Panaderia, Heladeria, 	Cafeteria, Cerveceria, Vineria, Comedor, MiniMarket, 	Market, Carniceria, Verduleria, Libreria, Ferreteria, 	Chocolateria
gastos
	Compras infraestructura, Compras insumos, Personal, 	Honorarios, Servicios, Alquileres, Impuestos, Socios, 	Otros


paises, ciudades, tiponegocios, gastos, con esta estructura:
create table ejemplo(
	id int not null auto_increment primary key,
	name varchar(100) not null unique
);

entidades
	Proveedor infraestructura, Proveedor insumos, Personal, 	Profesional, Servicio, Arrendatario, 	Impuesto, Socio, Otro
	**/


create database expense_manager;
use expense_manager;

/**
 * Seccion de paises y cuidades
 */
 create table country (
 	id int not null primary key,
	name varchar(100) NOT NULL
 );

 create table city(
 	id int not null primary key,
 	id_country int NOT NULL,
	name varchar(100) NOT NULL,
 	foreign key(id_country) references country(id)
 );


create table gastos(
	id int not null auto_increment primary key,
	desc_gastos varchar(100) not null unique
);
create table tipo_negocios(
	id int not null auto_increment primary key,
	desc_tipo_negocio varchar(100) not null unique
);




CREATE TABLE skins (
 	id int not null auto_increment primary key,
  	name varchar(20) NOT NULL,
  	value varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
INSERT INTO skins (id, name, value) VALUES
(1, 'Negro claro', 'skin-black'),
(2, 'Azul', 'skin-blue'),
(3, 'P&uacute;rpura', 'skin-purple'),
(4, 'Rojo', 'skin-red'),
(5, 'Verde', 'skin-green'),
(6, 'Amarillo', 'skin-yellow'),
(7, 'Azul claro', 'skin-blue-light'),
(8, 'Blanco', 'skin-black-light'),
(9, 'P&uacute;rpura claro', 'skin-purple-light'),
(10, 'Verde claro', 'skin-green-light'),
(11, 'Amarillo claro', 'skin-yellow-light'),
(12, 'Rojo claro', 'skin-red-light');

create table empresas(
	id int not null auto_increment primary key,
	status boolean not null default 1,
	is_deleted boolean not null default 0,
	licenciaMRC int not null,
	pais varchar(2) not null,
	ciudad int not null,
	tiponegocio int not null,
	name varchar(255) not null,
	password varchar(100) not null,
	email varchar(255) not null,
	profile_pic varchar(250) not null,
	skin int not null,
	created_at datetime not null,
	foreign key(skin) references skins(id),
	foreign key(pais) references paises(cod_pais),
	foreign key(ciudad) references ciudades(id),
	foreign key(tiponegocio) references tiponegocios(id)
);

create table user(
	id int not null auto_increment primary key,
	status boolean not null default 1,
	is_deleted boolean not null default 0,
	name varchar(255) not null,
	password varchar(100) not null,
	email varchar(255) not null,
	profile_pic varchar(250) not null,
	skin int not null,
	empresa int not null,
	is_admin boolean not null default 0,
	created_at datetime not null,
	foreign key(skin) references skins(id),
	foreign key(empresa) references empresas(id)
);
INSERT INTO user (status, is_deleted, name, password, email, profile_pic, skin, is_admin, created_at) VALUES
(1, 0, 'Amner Saucedo Sosa', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', 'admin@admin.com', 'default.png',5 , 1, NOW());

create table configuration(
	id int not null auto_increment primary key,
	label varchar(100) not null,
	name varchar(100) not null unique,
	val text,
	cfg_id int default 1
);
insert into configuration(label,name,val) values ("Empresa","website","Managment");
insert into configuration(label,name,val) values ("Moneda","coin","$");
insert into configuration(label,name,val) values ("E-Mail","email","waptoing7@gmail.com");
insert into configuration(label,name,val) values ("Logotipo","logo","logo.png");

create table category_expence (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	gasto int not null,
	foreign key(user_id) references user(id),
	foreign key(gasto) references gastos(id)
);

create table category_income (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	foreign key(user_id) references user(id)
);

create table expenses (
	id int not null auto_increment primary key,
	description text ,
	amount double not null,
	upload_receipt varchar(255),
	user_id int not null,
	category_id int not null,
	entidad int not null,
	created_at date not null,
	foreign key(user_id) references user(id),
	foreign key(category_id) references category_expence(id),
	foreign key(entidad) references entidades(id)
);

create table income (
	id int not null auto_increment primary key,
	description text ,
	amount double not null,
	user_id int not null,
	category_id int not null,
	created_at date not null,
	foreign key(user_id) references user(id),
	foreign key(category_id) references category_income(id)
);
create table entidades (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	gasto int not null,
	foreign key(user_id) references user(id),
	foreign key(gasto) references gastos(id)
);
