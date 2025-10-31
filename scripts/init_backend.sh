# ...existing code...
#!/usr/bin/env bash
set -e

BACKEND_DIR="backend"

echo "Preparando carpeta backend..."

# Si no hay proyecto Laravel, intenta crear uno
if [ ! -f "$BACKEND_DIR/artisan" ]; then
  echo "No se encontró Laravel en $BACKEND_DIR. Creando nuevo proyecto Laravel..."
  if command -v composer >/dev/null 2>&1; then
    composer create-project --prefer-dist laravel/laravel "$BACKEND_DIR"
  elif command -v laravel >/dev/null 2>&1; then
    laravel new "$BACKEND_DIR"
  else
    echo "Composer o Laravel installer no disponible. Instala Composer o crea el proyecto manualmente en $BACKEND_DIR."
    exit 1
  fi
fi

cd "$BACKEND_DIR"

echo "Configurando SQLite para rapidez..."
# Asegura que exista .env
if [ ! -f .env ]; then
  cp .env.example .env || true
fi

mkdir -p database
touch database/database.sqlite

# Usa php -r con comillas dobles para evitar problemas de quoting
php -r "file_put_contents('.env', preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=sqlite', file_get_contents('.env')));"
php -r "file_put_contents('.env', preg_replace('/^DB_DATABASE=.*/m', 'DB_DATABASE=' . __DIR__ . '/database/database.sqlite', file_get_contents('.env')));"

echo "Creando migraciones, modelos, seeders y tests..."
php artisan make:model Team -m || true
php artisan make:model MatchGame -m || true
php artisan make:seeder TeamsAndMatchesSeeder || true
php artisan make:test StandingsTest || true

echo "Generando controlador API..."
php artisan make:controller Api/TeamController --api || true
php artisan make:controller Api/MatchController --api || true
php artisan make:controller Api/StandingsController || true

echo "Añadiendo rutas API..."
API_ROUTES="routes/api.php"
if [ -f "$API_ROUTES" ]; then
  grep -q 'MINILIGA_ROUTES' "$API_ROUTES" || cat >> "$API_ROUTES" <<'PHP'

// === MINILIGA_ROUTES (auto) ===
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\StandingsController;

Route::get('/teams', [TeamController::class, 'index']);
Route::post('/teams', [TeamController::class, 'store']);
Route::post('/matches/{id}/result', [MatchController::class, 'result']);
Route::get('/standings', [StandingsController::class, 'index']);
// === /MINILIGA_ROUTES ===
PHP
else
  echo "Aviso: $API_ROUTES no existe. Crea el proyecto Laravel correctamente o añade las rutas manualmente."
fi

echo "Listo. Ejecuta ahora:"
echo "  php artisan migrate --seed"
echo "  php artisan serve"
# ...existing code...