-- Script para configurar PostgreSQL
-- Ejecuta este script en pgAdmin o desde psql

-- 1. Crear la base de datos (si no existe)
CREATE DATABASE "sistema-facultad"
    WITH 
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'Spanish_Bolivia.1252'
    LC_CTYPE = 'Spanish_Bolivia.1252'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1;

-- 2. Conectarse a la base de datos
\c sistema-facultad

-- 3. Verificar la conexión
SELECT current_database();

-- 4. Opcional: Crear un usuario específico para la aplicación
-- CREATE USER sistema_user WITH PASSWORD 'tu_contraseña_segura';
-- GRANT ALL PRIVILEGES ON DATABASE "sistema-facultad" TO sistema_user;
