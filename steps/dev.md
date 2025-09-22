# Cahier des Charges - Laravel E-commerce avec SEO

## 1. ARCHITECTURE TECHNIQUE

### 1.1 Stack Technologique
- **Backend**: Laravel 11
- **Admin Panel**: Filament PHP
- **Base de données**: MySQL 8.0
- **Cache**: Redis
- **Queue**: Redis/Database
- **Storage**: Local/S3
- **Frontend**: Blade + Alpine.js + Tailwind CSS

### 1.2 Packages Requis
```
filament/filament
spatie/laravel-sluggable
spatie/laravel-sitemap
spatie/laravel-translatable
mcamara/laravel-localization
torann/geoip
intervention/image
spatie/laravel-medialibrary
spatie/laravel-permission
```

## 2. STRUCTURE BASE DE DONNÉES

### 2.1 Tables Principales
```
users (id, name, email, phone, country_code, region_id, created_at)
categories (id, name, slug, description, image_path, parent_id, sort_order)
brands (id, name, slug, description, logo_path, sort_order)
products (id, name, slug, description, price, stock_quantity, brand_id)
product_categories (product_id, category_id)
product_images (id, product_id, image_path, alt_text, sort_order)
orders (id, user_id, total_amount, status, shipping_data, created_at)
order_items (id, order_id, product_id, quantity, unit_price)
cart_items (id, session_id, user_id, product_id, quantity)
regions (id, country_code, name, code)
```

### 2.2 Tables SEO
```
seo_meta (id, model_type, model_id, meta_title, meta_description, canonical_url)
redirects (id, from_url, to_url, status_code, created_at)
schema_markups (id, model_type, model_id, schema_data)
```

### 2.3 Tables Multilingues
```
category_translations (id, category_id, locale, name, description)
product_translations (id, product_id, locale, name, description)
brand_translations (id, brand_id, locale, name, description)
```

## 3. MODULES DE DÉVELOPPEMENT

### Module 1: Infrastructure & Setup
- Installation Laravel + Filament
- Configuration base de données
- Setup multilingue (FR, EN, AR)
- Configuration GeoIP
- Structure des dossiers

### Module 2: Modèles & Relations
- Modèles Eloquent avec relations
- Traits SEO (HasSEO, HasSlug, HasTranslations)
- Factories & Seeders
- Migrations complètes

### Module 3: SEO Core System
- Génération automatique des slugs
- Système de meta tags dynamiques
- Génération sitemap XML
- Breadcrumbs automatiques
- Schema.org markup
- Canonical URLs
- Hreflang tags

### Module 4: Filament Admin Panel
- Dashboard principal
- CRUD Categories avec SEO
- CRUD Products avec galerie images
- CRUD Brands
- Gestion utilisateurs
- Gestion commandes
- Settings SEO globaux
- Multilangue admin

### Module 5: Frontend Public
- Pages catégories
- Pages produits
- Pages marques
- Système de recherche
- Filtres avancés
- Responsive design
- Performance optimization

### Module 6: Système Panier & Commandes
- Ajout/suppression panier
- Persistance session/utilisateur
- Checkout process
- Formulaire commande avec détection pays/région
- Email confirmations
- Statuts commandes

### Module 7: Authentification & Profil
- Inscription par email/téléphone
- Vérification OTP
- Profil utilisateur
- Historique commandes
- Adresses de livraison

### Module 8: Multilingue & Localisation
- Détection automatique langue
- Switching languages
- Contenu traduit
- URLs localisées
- Devises multiples

## 4. SPÉCIFICATIONS SEO

### 4.1 Structure URLs
```
/
/categorie/{slug}
/marque/{slug}
/produit/{category-slug}/{product-slug}
/{locale}/categorie/{slug}
/{locale}/marque/{slug}
/{locale}/produit/{category-slug}/{product-slug}
```

### 4.2 Meta Tags Requis
- Title (50-60 caractères)
- Description (150-160 caractères)
- Keywords
- Canonical
- Open Graph (og:title, og:description, og:image)
- Twitter Cards
- Hreflang pour multilingue

### 4.3 Schema Markup
- Organization
- WebSite
- BreadcrumbList
- Product
- Offer
- Review/AggregateRating

### 4.4 Sitemap Structure
```xml
sitemap.xml (index)
├── sitemap-pages.xml
├── sitemap-categories.xml
├── sitemap-products.xml
└── sitemap-brands.xml
```

## 5. FONCTIONNALITÉS E-COMMERCE

### 5.1 Catalogue
- **Catégories**: Arborescente, images, SEO complet
- **Produits**: Galerie images, variations, stock, SEO
- **Marques**: Logo, description, produits associés
- **Recherche**: Full-text, filtres, suggestions
- **Filtres**: Prix, marque, catégorie, attributs

### 5.2 Panier & Commandes
- **Panier**: Session persistante, quantités
- **Checkout**: Formulaire simplifié (nom, téléphone, pays, région)
- **Détection géographique**: IP → Pays/Région automatique
- **Statuts**: En attente, confirmée, expédiée, livrée

### 5.3 Utilisateurs
- **Inscription**: Email ou téléphone
- **Profil**: Informations personnelles, adresses
- **Commandes**: Historique complet, tracking
- **Notifications**: Email confirmations

## 6. CONFIGURATION TECHNIQUE

### 6.1 Performance
- **Cache**: Pages statiques, queries fréquentes
- **Images**: Optimisation automatique, lazy loading
- **CDN**: Configuration pour assets statiques
- **Minification**: CSS/JS en production

### 6.2 Sécurité
- **HTTPS**: Obligatoire
- **CSRF**: Protection formulaires
- **Rate Limiting**: API et formulaires
- **Validation**: Stricte côté serveur

### 6.3 SEO Technique
- **Robots.txt**: Configuration appropriée
- **Sitemap**: Génération automatique
- **404**: Pages personnalisées avec suggestions
- **Redirections**: 301 pour anciennes URLs

## 7. LIVRABLES

### 7.1 Code Source
- Application Laravel complète
- Panel admin Filament configuré
- Tests unitaires de base
- Documentation technique

### 7.2 Déploiement
- Instructions d'installation
- Configuration serveur
- Variables d'environnement
- Scripts de migration

### 7.3 Formation
- Guide d'utilisation admin
- Bonnes pratiques SEO
- Gestion du contenu
- Maintenance de base

## 8. CONTRAINTES TECHNIQUES

### 8.1 Compatibilité
- **PHP**: 8.2+
- **MySQL**: 8.0+
- **Navigateurs**: Chrome, Firefox, Safari, Edge (2 dernières versions)
- **Mobile**: Responsive complet

### 8.2 Performance
- **Temps de chargement**: < 3 secondes
- **Score PageSpeed**: > 85/100
- **Images**: WebP avec fallback
- **Cache**: Redis pour sessions/cache

### 8.3 SEO
- **Core Web Vitals**: Conformité Google
- **Structured Data**: Validation schema.org
- **Mobile-First**: Indexation mobile priority
- **Multilingue**: Hreflang correct

## 9. PHASES DE DÉVELOPPEMENT

- **Phase 1** (Modules 1-3): Infrastructure & SEO Foundation
- **Phase 2** (Modules 4-5): Admin Panel & Frontend
- **Phase 3** (Modules 6-8): E-commerce Features & Finalization