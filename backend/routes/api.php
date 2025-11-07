<?php
use App\Presentation\Http\Controllers\Api\TeamController;
use App\Presentation\Http\Controllers\Api\MatchController;
use App\Presentation\Http\Controllers\Api\StandingsController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::get('/teams', [TeamController::class, 'index']);
Route::post('/teams', [TeamController::class, 'store']);
Route::get('/matches', [MatchController::class, 'index']);
Route::post('/matches/{id}/result', [MatchController::class, 'updateResult']);
Route::get('/standings', [StandingsController::class, 'index']);
Route::get('/dev/clear-cache', function () {
    if (!app()->environment('local')) {
        return response()->json(['error' => 'Solo disponible en entorno local'], 403);
    }

    $results = [];
    
    try {
        // Limpiar cache de configuración
        Artisan::call('config:clear');
        $results['config_clear'] = 'Config cache cleared successfully';
        
        // Limpiar cache de aplicación
        Artisan::call('cache:clear');
        $results['cache_clear'] = 'Application cache cleared successfully';
        
        // Limpiar cache de rutas
        Artisan::call('route:clear');
        $results['route_clear'] = 'Route cache cleared successfully';
        
        // Limpiar cache de vistas (opcional)
        Artisan::call('view:clear');
        $results['view_clear'] = 'View cache cleared successfully';
        
        return response()->json([
            'success' => true,
            'message' => 'All caches cleared successfully',
            'results' => $results
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'results' => $results
        ], 500);
    }
});
