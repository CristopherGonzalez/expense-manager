@echo off
echo -----------------------------
echo Instalador de base de datos en Localhost y Windows
echo -----------------------------
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\expense-manager\scriptsql\schema.sql
echo -----------------------------
echo Insertando Datos (esto puede demorar varios minutos, no cierre la terminal...)
echo -----------------------------
\xampp\mysql\bin\mysql.exe -uroot < \xampp\htdocs\expense-manager\scriptsql\data_bbdd.sql
echo Instalacion exitosa
pause
