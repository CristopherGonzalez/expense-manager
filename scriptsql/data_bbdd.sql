
use dm000397_Negocio;
/*Datos de Paises*/

/*Datos de tipos de negocio*/


INSERT INTO tipo_negocios
    (name)
VALUES
    ('Cafeteria'
),

    ('Carniceria'
),

    ('Cerveceria'),

    ('Comedor'
),

    ('Chocolateria'
),

    ('Delivery'
),

    ('FastFood'
),

    ('Ferreteria'
),

    ('Heladeria'
),

    ('Libreria'
),

    ('Market'
),

    ('MiniMarket'
),

    ('Panaderia'
),

    ('Restaurante'),
    ('Verduleria'
),

    ('Vineria');

/*Datos de skins*/

INSERT INTO skins
    (id, name, value)
VALUES
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

/*Datos de configuracion*/

insert into configuration
    (label,name,val)
values
    ("Empresa", "website", "Managment");
insert into configuration
    (label,name,val)
values
    ("Moneda", "coin", "$");
insert into configuration
    (label,name,val)
values
    ("E-Mail", "email", "waptoing7@gmail.com");
insert into configuration
    (label,name,val)
values
    ("Logotipo", "logo", "logo.png");

/*Prueba de empresa*/

INSERT INTO empresas
    (status, is_deleted, licenciaMRC, pais, ciudad, direccion,documento,telefono, tipo_negocio, name,  email, profile_pic, skin, created_at)
VALUES
    ('1', '0', 'minegocio', '2', '34', 'MR.COMANDA SOFTWARE',null,'+54(351)5693065','2', 'MRComanda', 'info@mrcomanda.com', 'default.jpg', '8', NOW());

/*Prueba de usuario*/
INSERT INTO user
    (status, is_deleted, name, password, email, profile_pic, skin, empresa, is_admin, created_at)
VALUES
    (3, 0, 'MRC Administrador', 'e6a51d1c4b6e2f8ecb19c2bda1521452e02f3aa9', 'info@mrcomanda.com', 'default.png', 1 , 1, 1, NOW());


/*Datos de seccion de tipos de egresos*/
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Alquileres', 'Arrendatario' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Compras infraestructura', 'Proveedor infraestructura' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Compras insumos', 'Proveedor insumos' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Honorarios', 'Profesional' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Impuestos', 'Impuesto' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Personal', 'Persona' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Publicidad y promocion', 'Medio'
);
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Servicios', 'Servicio' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Servicios financieros', 'Servicio financiero' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Egreso', 'Otros', 'Entidad' );
/*Datos de seccion de tipos de ingreso*/

INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Ingreso', 'Arriendos', 'Arriendo' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Ingreso', 'Comisiones', 'Comision' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Ingreso', 'Publicidad y promocion', 'publicidad' );


INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Ingreso', 'Ventas', 'Punto de Venta' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Ingreso', 'Otros', 'Entidad' );
/*Datos de seccion de tipos de socios*/

INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Socio', 'Socios', 'Socio' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Socio', 'Otros', 'Entidad' );
/*Datos de seccion de tipos de valores*/

INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Valores', 'Banco', 'Entidad' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Valores', 'Caja', 'Entidad' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Valores', 'Clientes', 'Entidad' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Valores', 'Tarjeta', 'Entidad' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Valores', 'Otros', 'Entidad' );
/*Datos de seccion de tipos de deudas*/

INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Deudas', 'Cheques', 'Cheque' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Deudas', 'Creditos', 'Credito' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Deudas', 'Impuestos', 'Impuesto' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Deudas', 'Proveedores', 'Proveedor' );
INSERT INTO tipos
    (tipo, name, entidad)
VALUES
    ('Deudas', 'Otros', 'Otro' );
