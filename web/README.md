# Frontend - Angular Web

## Prerrequisitos
Node.js 18+
Angular CLI

## Instalación
# Navegar al directorio
cd web

# Instalar dependencias
npm install

# Configurar API URL (si es diferente)
# En src/environments/environment.ts
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api'
};

# Ejecutar en desarrollo
ng serve

# Abrir en navegador
http://localhost:4200

# Estructura
src/
├── app/
│   ├── services/
│   │   ├── team.service.ts
│   │   └── standings.service.ts
│   ├── components/
│   │   ├── team-list/
│   │   ├── team-form/
│   │   └── standings-list/
│   └── modules/
│       ├── teams/
│       └── standings/

# Funcionalidades
- Lista de equipos
- Formulario de alta de equipos
- Tabla de clasificación
- Diseño responsive con Bootstrap

# Comandos Útiles
## Desarrollo
ng serve

## Build producción
ng build