/*Creacion de base de datos*/
DROP DATABASE IF EXISTS dm000397_Negocio;
create database dm000397_Negocio;
use dm000397_Negocio;
/**Seccion de ciudades y paises**/
create table pais (
 id int not null auto_increment primary key,
 name varchar(100) NOT NULL
) CHARACTER SET = utf8 COLLATE = utf8_bin;

create table ciudad(
 id int not null auto_increment primary key,
 name varchar(100) NOT NULL,
 id_pais int NOT NULL,
 foreign key(id_pais) references pais(id)
) CHARACTER SET = utf8 COLLATE = utf8_bin;



/**Seccion de tipos de negocios**/
create table tipo_negocios(
 id int not null auto_increment primary key,
 name varchar(100) not null unique
) CHARACTER SET = utf8 COLLATE = utf8_bin;


/**Seccion de Skins**/

CREATE TABLE skins (
 	id int not null auto_increment primary key,
  	name varchar(20) NOT NULL,
  	value varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/**Seccion de configuraciones**/

create table configuration(
	id int not null auto_increment primary key,
	label varchar(100) not null,
	name varchar(100) not null unique,
	val text,
	cfg_id int default 1
) CHARACTER SET = utf8 COLLATE = utf8_bin;


/*Seccion de empresas*/

create table empresas(
  id int not null auto_increment primary key,
  status boolean not null default 1,
  is_deleted boolean not null default 0,
  licenciaMRC varchar(10) not null UNIQUE,
  pais int not null,
  ciudad int not null,
  tipo_negocio int not null,
  name varchar(255) not null,
  email varchar(255) not null,
  profile_pic LONGTEXT,
  skin int not null,
  created_at datetime not null
) CHARACTER SET = utf8 COLLATE = utf8_bin;



/**Seccion de usuarios**/
create table user(
  id int not null auto_increment primary key,
  status boolean not null default 1,
  is_deleted boolean not null default 0,
  name varchar(255) not null,
  password varchar(100) not null,
  email varchar(255) not null,
  profile_pic varchar(250) not null,
  skin int not null,
  empresa int,
  is_admin boolean not null default 0,
  created_at datetime not null
) CHARACTER SET = utf8 COLLATE = utf8_bin;
  ALTER TABLE `user` ADD UNIQUE( `email`, `empresa`);

/*Seccion de tipos de egresos/ingreso*/
create table tipos(
	id int not null auto_increment primary key,
	tipo varchar(20) not null,
	name varchar(100) not null ,
	entidad varchar(100) not null 
) CHARACTER SET = utf8 COLLATE = utf8_bin;


/*Seccion de Categorias de egresos e ingresos*/
create table category_expense (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	tipo int not null,
	empresa int not null
) CHARACTER SET = utf8 COLLATE = utf8_bin;

create table category_income (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	tipo int not null,
	empresa int not null
) CHARACTER SET = utf8 COLLATE = utf8_bin;



/*Seccion de entidades*/
create table entidades (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	tipo int not null,
	category_id int not null,
	empresa int not null,
	description text ,
	document_number text ,
	documento LONGTEXT,
	active boolean not null
) CHARACTER SET = utf8 COLLATE = utf8_bin;


/*Seccion de Egresos e Ingresos*/

  create table expenses (
  	id int not null auto_increment primary key,
  	description text ,
  	amount double not null,
  	upload_receipt varchar(255),
  	user_id int not null,
  	category_id int not null,
	tipo int not null,
  	entidad int not null,
  	created_at datetime not null,
  	fecha date not null,
  	fecha_vence date not null,
	pagado boolean not null default 1,
  	document_number text ,
	documento LONGTEXT,
	pago LONGTEXT,
  	pagado_con text ,
	empresa int not null,
	active boolean not null,
	payment_date date null,
	payment_specific_date date null,
	deuda_id int null
  ) CHARACTER SET = utf8 COLLATE = utf8_bin;

  create table income (
  	id int not null auto_increment primary key,
  	description text ,
  	amount double not null,
  	user_id int not null,
  	category_id int not null,
	tipo int not null,
  	entidad int not null,
  	created_at datetime not null,
	fecha date not null,
	pagado boolean not null default 1,
  	document_number text ,
	documento LONGTEXT,
	pago LONGTEXT,
  	pagado_con text ,
	empresa int not null,
	active boolean not null,
	payment_date date null,
	payment_specific_date date null
  ) CHARACTER SET = utf8 COLLATE = utf8_bin;


  create table resultado (
  	id int not null auto_increment primary key,
  	description text ,
  	amount double not null,
  	user_id int not null,
  	entidad int not null,
  	created_at datetime not null,
	fecha date not null,
	pagado boolean not null default 1,
	documento LONGTEXT,
	pago LONGTEXT,
  	pagado_con text ,
	empresa int not null,
	active boolean not null,
	payment_date date null,
	payment_specific_date date null,
	deuda_id int null

  ) CHARACTER SET = utf8 COLLATE = utf8_bin;
	create table deudas (
		id int not null auto_increment primary key,
		description text ,
		amount double not null,
		upload_receipt varchar(255),		
		user_id int not null,
		entidad int not null,
		created_at date not null,
		fecha date not null,
		fecha_pago date not null,
		pagado boolean not null default 1,
  		document_number text ,
		documento LONGTEXT,
		pago LONGTEXT,
		empresa int not null,
		active boolean not null,
		payment_specific_date date null,
		egreso_id int null,
		socio_id int null
	)CHARACTER
SET = utf8
COLLATE = utf8_bin;
	create table valores (
		id int not null auto_increment primary key,
		description text ,
		amount double not null,
		upload_receipt varchar(255),		
		user_id int not null,
		entidad int not null,
		created_at date not null,
		fecha date not null,
		fecha_pago date not null,
		pagado boolean not null default 1,
  		document_number text ,
		documento LONGTEXT,
		pago LONGTEXT,
		empresa int not null,
		active boolean not null,
		payment_specific_date date null
	)CHARACTER
SET = utf8
COLLATE = utf8_bin;
	create table logcambios (
		id int not null auto_increment primary key,
		tabla varchar(50) not null,
		registro_id int not null,
		description text not null,
		amount double null,
		entidad int null,
		fecha date null,
		pagado boolean null default 1,
  		document_number text null,
		created_at datetime not null,
		user_id int not null,
		active boolean null,
		payment_date date null,
		payment_specific_date date null,
		tipo int null,
		foreign key(user_id) references user(id)
	) CHARACTER SET = utf8 COLLATE = utf8_bin;

/*Foreign key empresas*/
ALTER TABLE empresas
ADD foreign key
(skin) references skins
(id);
ALTER TABLE empresas
ADD foreign key
(pais) references pais
(id);
ALTER TABLE empresas
ADD foreign key
(ciudad) references ciudad
(id);
ALTER TABLE empresas
ADD foreign key
(tipo_negocio) references tipo_negocios
(id);

  /*Foreign key usuarios*/
ALTER TABLE user
ADD foreign key
(skin) references skins
(id);
ALTER TABLE user
ADD foreign key
(empresa) references empresas
(id);


/*Foreign key category_expense*/
ALTER TABLE category_expense
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE category_expense
ADD foreign key
(user_id) references user
(id);
ALTER TABLE category_expense
ADD foreign key
(tipo) references tipos
(id);

/*Foreign key category_income*/
ALTER TABLE category_income
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE category_income
ADD foreign key
(user_id) references user
(id);
ALTER TABLE category_income
ADD foreign key
(tipo) references tipos
(id);

/*Foreign key entidades*/
ALTER TABLE entidades
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE entidades
ADD foreign key
(user_id) references user
(id);
ALTER TABLE entidades
ADD foreign key
(tipo) references tipos
(id);

/*Foreign key expenses*/
ALTER TABLE expenses
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE expenses
ADD foreign key
(user_id) references user
(id);
ALTER TABLE expenses
ADD foreign key
(tipo) references tipos
(id);
ALTER TABLE expenses
ADD foreign key
(deuda_id) references deudas
(id);
ALTER TABLE expenses
ADD foreign key
(category_id) references category_expense
(id);
ALTER TABLE expenses
ADD foreign key
(entidad) references entidades
(id);

/*Foreign key income*/
ALTER TABLE income
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE income
ADD foreign key
(user_id) references user
(id);
ALTER TABLE income
ADD foreign key
(tipo) references tipos
(id);

ALTER TABLE income
ADD foreign key
(category_id) references category_income
(id);
ALTER TABLE income
ADD foreign key
(entidad) references entidades
(id);



/*Foreign key resultado*/
ALTER TABLE resultado
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE resultado
ADD foreign key
(user_id) references user
(id);
ALTER TABLE resultado
ADD foreign key
(deuda_id) references deudas
(id);

ALTER TABLE resultado
ADD foreign key
(entidad) references entidades
(id);


/*Foreign key deudas*/
ALTER TABLE deudas
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE deudas
ADD foreign key
(user_id) references user
(id);
ALTER TABLE deudas
ADD foreign key
(egreso_id) references expenses
(id);

ALTER TABLE deudas
ADD foreign key
(socio_id) references resultado
(id);
ALTER TABLE deudas
ADD foreign key
(entidad) references entidades
(id);



/*Foreign key valores*/
ALTER TABLE valores
ADD foreign key
(empresa) references empresas
(id);
ALTER TABLE valores
ADD foreign key
(user_id) references user
(id);

ALTER TABLE valores
ADD foreign key
(entidad) references entidades
(id);

/*Foreign key logcambios*/

ALTER TABLE logcambios
ADD foreign key
(user_id) references user
(id);
