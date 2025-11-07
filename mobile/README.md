 # Mobile - Ionic + Capacitor
 
 ## Prerrequisitos
- Node.js 18+
- Ionic CLI
- Android Studio (para build Android)
- Xcode (para build iOS)

## Instalación

# Navegar al directorio
cd mobile

# Instalar dependencias
npm install

# Configurar API URL
# En src/environments/environment.ts
export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api'
};

# Ejecutar en navegador
ionic serve

# Para dispositivos nativos
ionic capacitor add android
ionic capacitor add ios

# Build para Dispositivos (Android)

## Build
ionic capacitor build android

## Abrir en Android Studio
ionic capacitor open android

# Estructura de la App
src/
├── app/
│   ├── tabs/
│   │   └── tabs.page.ts
│   ├── matches/
│   │   └── matches.page.ts
│   ├── match-result/
│   │   └── match-result.page.ts
│   ├── standings/
│   │   └── standings.page.ts
│   └── services/
│       └── match.service.ts


