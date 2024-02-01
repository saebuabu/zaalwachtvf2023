# Ionic zaalwacht App met vuejs 3

Zaalwacht App, alleen Android, voor host medewerkers van de Verkadefabriek in Den Bosch met eenvoudige informatie mbt tot de diensten en een smoelenboek

## Table of Contents

- [Testen in browser](#testen-browser)
- [Testen op android device](#testen-op-android-device)
- [License](#license)

## Testen browser

Testen en starten in de browser

```bash
ionic serve
```

Testen en starten in een Android device (koppel je Android via een USB kabel)

## Testen op android device

1. **Builden en synchroniseren:**
   Zorg ervoor dat je de app opnieuw hebt gebouwd en gesynchroniseerd met Capacitor. Voer de volgende commando's uit:

   ```bash
   npm run build
   npx cap sync
   ```

    Hiermee wordt de app opnieuw gebouwd en de nieuwste wijzigingen gesynchroniseerd met de native projecten.

2. **Opnieuw opstarten:**
   Stop de Android-app en start deze opnieuw met:

   ```bash
   npx cap run android
   ```

    Zorg ervoor dat je alle lopende instanties van de app op het apparaat/emulator hebt afgesloten voordat je deze opnieuw start.

## License

Information about the project's license.
