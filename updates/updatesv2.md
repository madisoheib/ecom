Module 1: Countries, Regions, Languages, and Currencies

The dashboard must allow adding countries.

Each country belongs to a region.

Each country has:

a currency

a list of spoken languages

one default language

Example countries: Algeria, Canada, France, UAE.

The system must support translation keys.

If the website is shown in Arabic, Algeria must display as الجزائر.

If in French, the region "Europe" must display as Europe.

Claude must decide the best data structure and translation approach.

Module 2: Frontend Design System

The frontend must use a simple, clean structure.

Respect principles:

Single responsibility for each component (navbar, slider, product listing, footer).

Keep it simple (KISS).

Colors:

The system has a default primary and secondary color.

Colors must be updatable from the dashboard.

Changing colors should apply everywhere automatically without editing multiple files.

Claude must decide how theming should be managed.

Module 3: Google Analytics

Google Analytics must be integrated.

The tracking ID should be manageable from the dashboard.

Claude must decide:

The cleanest way to add analytics.

Whether to use an existing package or a direct integration.