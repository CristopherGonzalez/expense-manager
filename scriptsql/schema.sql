/*Creacion de base de datos*/
DROP DATABASE IF EXISTS expense_manager;
create database expense_manager;
use expense_manager;
/**Seccion de ciudades y paises**/
create table pais (
 id int not null auto_increment primary key,
 name varchar(100) NOT NULL
);

create table ciudad(
 id int not null auto_increment primary key,
 name varchar(100) NOT NULL,
 id_pais int NOT NULL,
 foreign key(id_pais) references pais(id)
);



/**Seccion de tipos de negocios**/
create table tipo_negocios(
 id int not null auto_increment primary key,
 name varchar(100) not null unique
);


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
);


/*Seccion de empresas*/

create table empresas(
  id int not null auto_increment primary key,
  status boolean not null default 1,
  is_deleted boolean not null default 0,
  licenciaMRC int not null,
  pais int not null,
  ciudad int not null,
  tipo_negocio int not null,
  name varchar(255) not null,
  password varchar(100) not null,
  email varchar(255) not null,
  profile_pic varchar(250) not null,
  skin int not null,
  created_at datetime not null,
  foreign key(skin) references skins(id),
  foreign key(pais) references pais(id),
  foreign key(ciudad) references ciudad(id),
  foreign key(tipo_negocio) references tipo_negocios(id)
);



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
  created_at datetime not null,
  foreign key(skin) references skins(id),
  foreign key(empresa) references empresas(id)
);

/*Seccion de tipos de gastos/ingreso*/
create table tipos(
	id int not null auto_increment primary key,
	tipo varchar(20) not null,
	name varchar(100) not null ,
	entidad varchar(100) not null 
);


/*Seccion de Categorias de egresos e ingresos*/
create table category_expence (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	tipo int not null,
	foreign key(user_id) references user(id),
	foreign key(tipo) references tipos(id)
);

create table category_income (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	tipo int not null,
	created_at datetime not null,
	foreign key(tipo) references tipos(id),
	foreign key(user_id) references user(id)
);



/*Seccion de entidades*/
create table entidades (
	id int not null auto_increment primary key,
	name varchar(255) not null,
	user_id int not null,
	created_at datetime not null,
	tipo int not null,
	category_id int not null,
	foreign key(user_id) references user(id),
	foreign key(tipo) references tipos(id),
	foreign key(category_id) references category_expence(id)
);


/*Seccion de Egresos e Ingresos*/

  create table expenses (
  	id int not null auto_increment primary key,
  	description text ,
  	amount double not null,
  	upload_receipt varchar(255),
  	user_id int not null,
  	category_id int not null,
  	entidad int not null,
  	created_at date not null,
  	fecha date not null,
	pagado boolean not null default 1,
	documento MEDIUMBLOB,
	pago MEDIUMBLOB,
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
  	entidad int not null,
  	created_at date not null,
	fecha date not null,
	pagado boolean not null default 1,
	documento MEDIUMBLOB,
	pago MEDIUMBLOB,
  	foreign key(user_id) references user(id),
  	foreign key(category_id) references category_income(id),
  	foreign key(entidad) references entidades(id)

  );
  create table resultado (
  	id int not null auto_increment primary key,
  	description text ,
  	amount double not null,
  	user_id int not null,
  	entidad int not null,
  	created_at date not null,
	fecha date not null,
	pagado boolean not null default 1,
	documento MEDIUMBLOB,
	pago MEDIUMBLOB,
  	foreign key(user_id) references user(id),
  	foreign key(entidad) references entidades(id)

  );