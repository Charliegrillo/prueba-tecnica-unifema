# Decisiones Técnicas

## Backend
- Se cambio le entidad match a matchGame porque el nombre "match" es reservado en laravel.
- Para claridad, separación de responsabilidades, servicios se uso la metologia clean architect y pattern repository al backend. Para la version web y movil no se aplico por lo simple de la estructura y por ser solo una prueba tecnica.

**Endpoints creado**
GET    /api/teams
POST   /api/teams                 { name }
POST   /api/matches/{id}/result   { home_score, away_score }
GET    /api/standings

### Testing realizado con exito en phpUnit ###
- Crea dos equipos, un partido y registra dos resultados (victoria y empate) asegurando que los puntos se calculan correctamente.

**php artisan test tests/Feature/StandingsCalculationTest.php**

Resultados correctos:
Team A:
Played: 2
Won: 1, Drawn: 1, Lost: 0
Goals: 5 for, 3 against
Goal Difference: +2
Points: 4
Position: 1

Team B:
Played: 2
Won: 0, Drawn: 1, Lost: 1
Goals: 3 for, 5 against
Goal Difference: -2
Points: 1
Position: 2

- Dos equipos creados
- Dos resultados registrados (victoria y empate)
- Puntos calculados correctamente (4 y 1)
- Ordenamiento verificado

## web
- Para la version web se decidio usar boostrap para maquetar para acelerar el desarrollo.
- Se realizaron 2 pestañas:
1) **Equipos**: listado + alta.
2) **Clasificación**: tabla desde `GET /api/standings`.

**UI**
- `TeamsComponent`: formulario reactivo `{ name }` y tabla al lado.
- `StandingsComponent`: tabla con `team`, `played`, `goals_for`, `goals_against`, `goal_diff`, `points` pero se colocaron abreviaturas para su mejor presentación.

## Movil
- Para la version movil se decidio colocar el html dentro del template del componente por lo pocos datos a mostrar.
- Página **Matches**: lista de próximos (sin resultado).
- Página **Report Result**: form `home_score`, `away_score` → POST a `/api/matches/{id}/result`.

## Trade-offs:##
- Elección de metodologías CSS (BEM)
- Aplicar estructura de carpeta a funcionales
- Aplicar metologia clean arquitect a la version web y movil.