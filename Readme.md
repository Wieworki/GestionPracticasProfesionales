# Introducción

Repositorio para el Proyecto final de la carrera de Analista en Informática Aplicada.
El proyecto consiste en un sistema de gestión web para la administración de prácticas profesionales de los estudiantes de la facultad.

# Instructivo

## Instalación

docker-compose up --build

Levantamos el sistema: docker compose up -d 

Corremos las migraciones: docker compose exec app php artisan migrate

Corremos los seeds para cargar datos iniciales: docker compose exec app php artisan db:seed

## Accesos

Laravel	http://localhost:8000

Mailhog	http://localhost:8025

Vite dev	http://localhost:5173

PostgreSQL	localhost:5432 (internal: db)

Redis	localhost:6379 (internal: redis)

## Varios

Ejecutar los tests: docker compose exec app php artisan test

# Troubleshooting

- Error response from daemon: can't access specified distro mount service: stat /run/guest-services/distro-services/ubuntu.sock: no such file or directory: 
Reiniciar docker desktop
