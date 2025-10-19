# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PWA (Progressive Web App) built for Verkadefabriek (VF) in Den Bosch. The app provides host employees ("zaalwachten") with shift information, event schedules, and a photo directory of team members. The app includes interactive features like a photo quiz game.

Tech stack: Vue 3 + TypeScript + Ionic Framework + Vite

## Development Commands

### Local Development
```bash
npm run dev
```
Starts the development server at http://localhost:5173 (port configured in cypress.config.ts:9)

### Build
```bash
npm run build
```
Compiles TypeScript and builds the production bundle to the `dist/` directory. The build process runs `vue-tsc` for type checking before building with Vite.

### Testing
```bash
# Run unit tests (Vitest)
npm run test:unit

# Run e2e tests (Cypress)
npm run test:e2e
```
Note: Cypress tests are located in `tests/e2e/specs/` and configured via cypress.config.ts.

### Linting
```bash
npm run lint
```

### Firebase Deployment
After building, deploy to Google Cloud via Firebase:
```bash
firebase login
firebase deploy
```
The `dist/` directory is deployed as configured in firebase.json:3.

## Architecture

### Data Layer (src/services/Service.ts)

The app fetches data from three main sources:
1. **Custom PHP API** (https://itstudy.eu/zaalwacht/) for shifts ("diensten") and shows ("agenda")
2. **Verkadefabriek Scraper** (https://itstudy.eu/zaalwacht/agendascraper.php) for live agenda data from verkadefabriek.nl
3. **Cosmic CMS** (bucket: "smoelenboek-production") for employee profiles ("smoelen")
4. **Giphy API** for animated GIFs in the quiz game

API keys are stored in `src/services/keys.ts`. The keys file contains:
- `VUE_APP_BASE_URL`: Base URL for custom PHP API
- `VUE_APP_APIKEY`: Authentication key for custom API
- `VUE_APP_READKEY_COSMICJS`: Read-only key for Cosmic CMS
- `VUE_APP_GIPHY_API_KEY`: Giphy API key
- `VUE_APP_LANGUAGE`: Language setting (nl_NL)

### Routing (src/router/index.ts)

All routes use lazy-loaded components via dynamic imports. The app uses Ionic Vue Router with web history mode. Key routes:

- `/` → redirects to `/Smoelenboek` (default view)
- `/Smoelenboek` → Photo directory grid view
- `/Smoel/:slug` → Individual profile detail view
- `/Photoquiz` → "Wie is wie?" interactive quiz game
- `/WeekDiensten` → Weekly shift schedule (DienstenPerWeek.vue)
- `/Agenda` → VF event calendar (shows upcoming week via scraper)
- `/Voorstelling/:slug` → Event detail view
- `/AiVoorstelling/:slug` → AI-generated event information

### App Structure (src/App.vue)

The app uses Ionic's split-pane layout with a side menu. The menu contains navigation to:
1. Diensten (Shifts)
2. VF Agenda (Events)
3. Wie is wie? (Photo Quiz)
4. Smoelenboek (Photo Directory)

Menu state is managed via `selectedIndex` data property. A splash screen is controlled by the `aftersplash` boolean flag.

### Agenda Scraper (server-scripts/agendascraper.php)

The agenda scraper is a server-side PHP script that:
- Scrapes verkadefabriek.nl/agenda directly
- Handles pagination automatically (up to 10 pages)
- Parses Dutch date formats (e.g., "ma 20 okt. - 13:20")
- Filters events from today until next week (7 days)
- Extracts event data: time, day, name, type, URL, sold-out status, description
- Returns JSON in the same format as the legacy agenda.php API
- Hosted at https://itstudy.eu/zaalwacht/agendascraper.php

The Agenda.vue view uses `Service.getScrapedShows()` (src/services/Service.ts:32-40) to fetch data from the scraper. This provides real-time event information directly from the Verkadefabriek website, showing only the upcoming week of events.

### PWA Configuration

- Service worker registration in `src/registerServiceWorker.ts` (production only)
- PWA plugin configured in vite.config.ts:11 with `autoUpdate` mode
- Firebase hosting rewrites all routes to /index.html for SPA routing (firebase.json:9-13)

### TypeScript Configuration

Path alias `@/` maps to `src/` directory (tsconfig.json:16-17, vite.config.ts:14-16). All Vue files, TypeScript files are included in compilation.

### Node Version

Developed on Node v18.14.0 (as of February 2024). See README.md:9.

## Key Implementation Patterns

### Data Fetching Pattern
Views fetch data in the `created()` lifecycle hook using async/await with the Service module. Example from Smoelenboek.vue:78:
```typescript
async created() {
  this.smoelen = await Service.getSmoelenboek() as any;
}
```

### Cosmic CMS Integration
The Cosmic SDK client is instantiated per-request in Service.ts methods (lines 33-46, 44-54). Objects are fetched with specific props and depth:
```typescript
const smoelen = await cosmic.objects.find({"type": "smoelen"})
  .props("slug,title,metadata,thumbnail")
  .depth(1);
```

### Photo Quiz Game Logic
PhotoQuiz.vue implements a 10-question timed quiz with shuffled answer options. The game uses Giphy API to display celebratory/consolation GIFs based on final score. Timer logic controls question progression.

## Branch Strategy

- Main branch: `main`
- Current development branch: `main-pwa`
- Feature branches: `agenda-new` (for agenda scraper development)

## Important Notes

- The app is in Dutch (language: nl_NL)
- All API keys are committed in src/services/keys.ts (not using .env)
- The app redirects all routes to index.html for client-side routing via Firebase hosting rewrites
- The agenda scraper provides fresh data from verkadefabriek.nl, filtered to show only the upcoming week
- Server-side scripts are organized in `server-scripts/` directory
