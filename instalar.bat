@echo off
echo -----------------------------
echo Instalador de base de datos en Localhost y Windows para la aplicacion MiNegocio
echo -----------------------------
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\MiNegocio\scriptsql\schema.sql
echo -----------------------------
echo Insertando Datos (esto puede demorar varios minutos, no cierre la terminal...)
echo -----------------------------
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\MiNegocio\scriptsql\data_bbdd.sql
echo Instalacion exitosa
pause
