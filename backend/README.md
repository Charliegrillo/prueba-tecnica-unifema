# Backend - Laravel 12

## Estructura del Proyecto

├── backend/          # API Laravel
├── frontend/         # Web Angular
├── mobile/          # App Ionic + Capacitor

## Prerrequisitos
PHP 8.1+
Composer
SQLite
Laravel 12

## Instalación
## Clonar o navegar al directorio
cd backend

## Instalar dependencias
composer install

## Configurar entorno
cp .env.example .env
php artisan key:generate

## Configurar base de datos (SQLite recomendado)
# En .env:
DB_CONNECTION=sqlite
# DB_DATABASE=//backend/database/database.sqlite

# Crear archivo de base de datos SQLite
touch database/database.sqlite

# Ejecutar migraciones y seeders
php artisan migrate --seed

## Ejecutar tests
php artisan test

## Iniciar servidor
php artisan serve

## Endpoints API
Método	Endpoint    	    Descripción
GET 	/api/teams      	Listar equipos
POST	/api/teams          Crear equipo
GET 	/api/matches    	Listar partidos
GET	    /api/matches?played=false	Partidos pendientes
GET 	/api/matches?played=true	Partidos completados
POST	/api/matches/{id}/result	Reportar resultado
GET     /api/standings	            Clasificación

## Tests
# Ejecutar tests
php artisan test

# Test específico del servicio de clasificación
php artisan test tests/Unit/StandingsServiceTest.php

Datos de Prueba
El seeder crea:
4 equipos: Dragons, Sharks, Tigers, Wolves
3 partidos programados
1 partido completado