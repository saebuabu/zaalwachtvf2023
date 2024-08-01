# PWA, Ionic zaalwacht App met vuejs 3

PWA Zaalwacht App voor host medewerkers van de Verkadefabriek in Den Bosch met eenvoudige informatie mbt tot de diensten en een smoelenboek

Web App die geinstalleerd kan worden op iedere device als PWA

## Node versie

   d.d. Februari 2024 ontwikkelend op node met versie 18.14.0

## Testen browser

Testen en starten in de browser

```bash
npm run dev
```

Klaarmaken voor build en deploy op firebase

## Builden en deployen via Firebase in een GCloud omgeving

1. **Builden:**

   ```bash
   npm run build
   ```

    Hiermee wordt de dist map gedeployed naar de GCloud met firebase


2. **Deploy:**


   ```bash

   firebase login
   
   # na de keuze van het juiste GCloud project

   firebase deploy
   ```

Hierna zal de PWA web app werken op `GCloudproject-naam`.web.app

## License

