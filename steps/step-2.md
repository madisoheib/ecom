# Module 2: Models & Relations - Complete ✅

## Migrations Créées

### Tables Principales
1. ✅ `regions` - Gestion des régions géographiques
2. ✅ `users` (update) - Ajout phone, country_code, region_id
3. ✅ `categories` - Catégories de produits avec support multilingue
4. ✅ `brands` - Marques avec traductions
5. ✅ `products` - Produits avec prix, stock, traductions
6. ✅ `product_categories` - Relation many-to-many produits/catégories
7. ✅ `product_images` - Images des produits
8. ✅ `orders` - Commandes avec statuts et données de livraison
9. ✅ `order_items` - Articles des commandes
10. ✅ `cart_items` - Panier d'achat

### Tables SEO
1. ✅ `seo_meta` - Métadonnées SEO multilingues
2. ✅ `redirects` - Gestion des redirections 301/302
3. ✅ `schema_markups` - Données structurées Schema.org

## Modèles Eloquent Créés

### Modèles Principaux
```php
App\Models\
├── Region.php           # Relations: hasMany(User)
├── Category.php         # Traits: HasSlug, HasTranslations, HasMedia
├── Brand.php            # Traits: HasSlug, HasTranslations, HasMedia
├── Product.php          # Relations complexes, scopes: active, featured, inStock
├── ProductImage.php     # Gestion des images produits
├── Order.php            # Constantes de statut, auto-génération order_number
├── OrderItem.php        # Articles de commande
└── CartItem.php         # Scopes: forSession, forUser
```

### Modèles SEO
```php
App\Models\
├── SeoMeta.php          # Traductions multilingues des meta
├── Redirect.php         # Gestion des redirections
└── SchemaMarkup.php     # Données structurées JSON-LD
```

### User Model Amélioré
- ✅ Ajout de HasRoles (Spatie Permission)
- ✅ Implémentation FilamentUser
- ✅ Relations: region, orders, cartItems
- ✅ Méthode canAccessPanel pour l'accès admin

## Traits Créés

### HasSeo Trait
```php
App\Traits\HasSeo
```
- Relations morphiques pour SeoMeta et SchemaMarkup
- Génération automatique des méta par défaut
- Accesseurs pour meta_title et meta_description

## Features Implémentées

### Multilingue
- ✅ Utilisation de Spatie\Translatable
- ✅ Champs traduisibles: name, description, meta_title, etc.
- ✅ Langues: FR, EN, AR

### Media Management
- ✅ Spatie MediaLibrary configuré
- ✅ Collections: category_image, brand_logo, product_images
- ✅ Types MIME acceptés définis

### SEO Features
- ✅ Slug automatique avec Spatie\Sluggable
- ✅ Relations polymorphiques pour SEO
- ✅ Support Open Graph et Twitter Cards
- ✅ Robots meta (index/noindex, follow/nofollow)

### Scopes Utiles
```php
// Product
Product::active()
Product::featured()
Product::inStock()

// Order
Order::status('pending')
Order::paid()

// CartItem
CartItem::forSession($sessionId)
CartItem::forUser($userId)

// Redirect
Redirect::active()
```

## Relations Principales

```
User
├── belongsTo: Region
├── hasMany: Orders
└── hasMany: CartItems

Category
├── belongsTo: Category (parent)
├── hasMany: Category (children)
├── belongsToMany: Products
└── morphOne: SeoMeta, SchemaMarkup

Brand
├── hasMany: Products
└── morphOne: SeoMeta, SchemaMarkup

Product
├── belongsTo: Brand
├── belongsToMany: Categories
├── hasMany: ProductImages, OrderItems, CartItems
└── morphOne: SeoMeta, SchemaMarkup

Order
├── belongsTo: User
└── hasMany: OrderItems

OrderItem
├── belongsTo: Order
└── belongsTo: Product

CartItem
├── belongsTo: User
└── belongsTo: Product
```

## Constantes Importantes

### Order Status
- STATUS_PENDING = 'pending'
- STATUS_PROCESSING = 'processing'
- STATUS_CONFIRMED = 'confirmed'
- STATUS_SHIPPED = 'shipped'
- STATUS_DELIVERED = 'delivered'
- STATUS_CANCELLED = 'cancelled'
- STATUS_REFUNDED = 'refunded'

### Payment Status
- PAYMENT_STATUS_PENDING = 'pending'
- PAYMENT_STATUS_PAID = 'paid'
- PAYMENT_STATUS_FAILED = 'failed'
- PAYMENT_STATUS_REFUNDED = 'refunded'

## Commandes pour Appliquer les Migrations

```bash
# Appliquer toutes les migrations
php artisan migrate

# Publier les migrations de MediaLibrary
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"

# Créer les tables de permissions (déjà publié)
php artisan migrate
```

## Prochaines Étapes (Module 3: SEO Core System)

1. Implémenter les services SEO
2. Créer les middlewares de redirection
3. Générer les sitemaps automatiquement
4. Implémenter les breadcrumbs
5. Créer les helpers pour Schema.org

## Statut: Module 2 Complété ✅

Tous les modèles, migrations et relations sont créés et configurés. Base de données prête pour le développement.