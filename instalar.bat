@echo off
echo -----------------------------
echo Instalador de base de datos en Localhost y Windows para la aplicacion MiNegocio
echo -----------------------------
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\MiNegocio\scriptsql\schema.sql
echo -----------------------------
echo Insertando Datos (esto puede demorar varios minutos, no cierre la terminal...)
echo -----------------------------
echo Insertar Datos de paises
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\MiNegocio\scriptsql\data_country.sql
echo -----------------------------
echo Insertar Datos de ciudades
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\MiNegocio\scriptsql\data_city.sql
echo -----------------------------
echo Insertar Datos de faltantes
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\MiNegocio\scriptsql\data_bbdd.sql
echo Instalacion terminada
pause
