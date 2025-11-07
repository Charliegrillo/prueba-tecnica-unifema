import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.tuapp.unifema',
  appName: 'Tu App',
  webDir: 'www',
 server: {
    androidScheme: 'https',
    cleartext: true,
    allowNavigation: [
      'unifema.server-testing.website',
      '*.server-testing.website'
    ]
  },
  plugins: {
    CapacitorHttp: {
      enabled: false
    }
  }
};

export default config;
