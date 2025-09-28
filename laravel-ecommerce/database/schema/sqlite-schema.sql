CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "password_reset_tokens"(
  "email" varchar not null,
  "token" varchar not null,
  "created_at" datetime,
  primary key("email")
);
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "job_batches"(
  "id" varchar not null,
  "name" varchar not null,
  "total_jobs" integer not null,
  "pending_jobs" integer not null,
  "failed_jobs" integer not null,
  "failed_job_ids" text not null,
  "options" text,
  "cancelled_at" integer,
  "created_at" integer not null,
  "finished_at" integer,
  primary key("id")
);
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "email" varchar not null,
  "email_verified_at" datetime,
  "password" varchar not null,
  "remember_token" varchar,
  "created_at" datetime,
  "updated_at" datetime,
  "phone" varchar,
  "country_code" varchar,
  "region_id" integer,
  foreign key("region_id") references "regions"("id") on delete set null
);
CREATE UNIQUE INDEX "users_email_unique" on "users"("email");
CREATE INDEX "users_country_code_index" on "users"("country_code");
CREATE TABLE IF NOT EXISTS "categories"(
  "id" integer primary key autoincrement not null,
  "name" text not null,
  "slug" varchar not null,
  "description" text,
  "image_path" varchar,
  "parent_id" integer,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("parent_id") references "categories"("id") on delete cascade
);
CREATE INDEX "categories_slug_index" on "categories"("slug");
CREATE INDEX "categories_parent_id_index" on "categories"("parent_id");
CREATE INDEX "categories_sort_order_index" on "categories"("sort_order");
CREATE INDEX "categories_is_active_index" on "categories"("is_active");
CREATE UNIQUE INDEX "categories_slug_unique" on "categories"("slug");
CREATE TABLE IF NOT EXISTS "brands"(
  "id" integer primary key autoincrement not null,
  "name" text not null,
  "slug" varchar not null,
  "description" text,
  "logo_path" varchar,
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "brands_slug_index" on "brands"("slug");
CREATE INDEX "brands_sort_order_index" on "brands"("sort_order");
CREATE INDEX "brands_is_active_index" on "brands"("is_active");
CREATE UNIQUE INDEX "brands_slug_unique" on "brands"("slug");
CREATE TABLE IF NOT EXISTS "products"(
  "id" integer primary key autoincrement not null,
  "name" text not null,
  "slug" varchar not null,
  "sku" varchar,
  "description" text,
  "short_description" text,
  "price" numeric not null,
  "compare_price" numeric,
  "stock_quantity" integer not null default '0',
  "brand_id" integer,
  "is_active" tinyint(1) not null default '1',
  "is_featured" tinyint(1) not null default '0',
  "views_count" integer not null default '0',
  "sales_count" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  "track_quantity" tinyint(1) not null default '1',
  "quantity" integer not null default '0',
  "allow_backorder" tinyint(1) not null default '0',
  "cost_price" numeric,
  "barcode" varchar,
  "is_digital" tinyint(1) not null default '0',
  "weight" numeric,
  "width" numeric,
  "height" numeric,
  "length" numeric,
  "name_translations" text,
  "description_translations" text,
  "specifications_translations" text,
  "short_description_translations" text,
  "meta_title" text,
  "meta_description" text,
  "meta_keywords" text,
  "focus_keyword" varchar,
  "schema_markup" text,
  "canonical_url" varchar,
  "index_follow" tinyint(1) not null default '1',
  "content_score" integer not null default '0',
  "seo_updated_at" datetime,
  foreign key("brand_id") references "brands"("id") on delete set null
);
CREATE INDEX "products_slug_index" on "products"("slug");
CREATE INDEX "products_sku_index" on "products"("sku");
CREATE INDEX "products_brand_id_index" on "products"("brand_id");
CREATE INDEX "products_is_active_index" on "products"("is_active");
CREATE INDEX "products_is_featured_index" on "products"("is_featured");
CREATE INDEX "products_price_index" on "products"("price");
CREATE INDEX "products_stock_quantity_index" on "products"("stock_quantity");
CREATE UNIQUE INDEX "products_slug_unique" on "products"("slug");
CREATE UNIQUE INDEX "products_sku_unique" on "products"("sku");
CREATE TABLE IF NOT EXISTS "product_categories"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "category_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade,
  foreign key("category_id") references "categories"("id") on delete cascade
);
CREATE UNIQUE INDEX "product_categories_product_id_category_id_unique" on "product_categories"(
  "product_id",
  "category_id"
);
CREATE TABLE IF NOT EXISTS "product_images"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "image_path" varchar not null,
  "alt_text" varchar,
  "sort_order" integer not null default '0',
  "is_primary" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE INDEX "product_images_product_id_index" on "product_images"(
  "product_id"
);
CREATE INDEX "product_images_sort_order_index" on "product_images"(
  "sort_order"
);
CREATE INDEX "product_images_is_primary_index" on "product_images"(
  "is_primary"
);
CREATE TABLE IF NOT EXISTS "orders"(
  "id" integer primary key autoincrement not null,
  "order_number" varchar not null,
  "user_id" integer,
  "status" varchar not null default 'pending',
  "subtotal" numeric not null,
  "tax_amount" numeric not null default '0',
  "shipping_amount" numeric not null default '0',
  "discount_amount" numeric not null default '0',
  "total_amount" numeric not null,
  "shipping_data" text not null,
  "billing_data" text,
  "notes" text,
  "payment_method" varchar,
  "payment_status" varchar not null default 'pending',
  "shipped_at" datetime,
  "delivered_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete set null
);
CREATE INDEX "orders_order_number_index" on "orders"("order_number");
CREATE INDEX "orders_user_id_index" on "orders"("user_id");
CREATE INDEX "orders_status_index" on "orders"("status");
CREATE INDEX "orders_payment_status_index" on "orders"("payment_status");
CREATE INDEX "orders_created_at_index" on "orders"("created_at");
CREATE UNIQUE INDEX "orders_order_number_unique" on "orders"("order_number");
CREATE TABLE IF NOT EXISTS "order_items"(
  "id" integer primary key autoincrement not null,
  "order_id" integer not null,
  "product_id" integer not null,
  "product_name" varchar not null,
  "product_sku" varchar,
  "quantity" integer not null,
  "unit_price" numeric not null,
  "total_price" numeric not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("order_id") references "orders"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete restrict
);
CREATE INDEX "order_items_order_id_index" on "order_items"("order_id");
CREATE INDEX "order_items_product_id_index" on "order_items"("product_id");
CREATE TABLE IF NOT EXISTS "cart_items"(
  "id" integer primary key autoincrement not null,
  "session_id" varchar,
  "user_id" integer,
  "product_id" integer not null,
  "quantity" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("product_id") references "products"("id") on delete cascade
);
CREATE INDEX "cart_items_session_id_index" on "cart_items"("session_id");
CREATE INDEX "cart_items_user_id_index" on "cart_items"("user_id");
CREATE INDEX "cart_items_product_id_index" on "cart_items"("product_id");
CREATE UNIQUE INDEX "cart_items_session_id_product_id_unique" on "cart_items"(
  "session_id",
  "product_id"
);
CREATE UNIQUE INDEX "cart_items_user_id_product_id_unique" on "cart_items"(
  "user_id",
  "product_id"
);
CREATE TABLE IF NOT EXISTS "seo_meta"(
  "id" integer primary key autoincrement not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  "meta_title" text,
  "meta_description" text,
  "meta_keywords" text,
  "canonical_url" varchar,
  "og_title" text,
  "og_description" text,
  "og_image" varchar,
  "twitter_card" varchar not null default 'summary',
  "twitter_title" text,
  "twitter_description" text,
  "twitter_image" varchar,
  "custom_meta" text,
  "noindex" tinyint(1) not null default '0',
  "nofollow" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "seo_meta_model_type_model_id_index" on "seo_meta"(
  "model_type",
  "model_id"
);
CREATE TABLE IF NOT EXISTS "redirects"(
  "id" integer primary key autoincrement not null,
  "from_url" varchar not null,
  "to_url" varchar not null,
  "status_code" integer not null default '301',
  "hits" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "redirects_from_url_unique" on "redirects"("from_url");
CREATE INDEX "redirects_is_active_index" on "redirects"("is_active");
CREATE TABLE IF NOT EXISTS "schema_markups"(
  "id" integer primary key autoincrement not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  "schema_type" varchar not null,
  "schema_data" text not null,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "schema_markups_model_type_model_id_index" on "schema_markups"(
  "model_type",
  "model_id"
);
CREATE INDEX "schema_markups_schema_type_index" on "schema_markups"(
  "schema_type"
);
CREATE INDEX "schema_markups_is_active_index" on "schema_markups"("is_active");
CREATE TABLE IF NOT EXISTS "permissions"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "guard_name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "permissions_name_guard_name_unique" on "permissions"(
  "name",
  "guard_name"
);
CREATE TABLE IF NOT EXISTS "roles"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "guard_name" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "roles_name_guard_name_unique" on "roles"(
  "name",
  "guard_name"
);
CREATE TABLE IF NOT EXISTS "model_has_permissions"(
  "permission_id" integer not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  foreign key("permission_id") references "permissions"("id") on delete cascade,
  primary key("permission_id", "model_id", "model_type")
);
CREATE INDEX "model_has_permissions_model_id_model_type_index" on "model_has_permissions"(
  "model_id",
  "model_type"
);
CREATE TABLE IF NOT EXISTS "model_has_roles"(
  "role_id" integer not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  foreign key("role_id") references "roles"("id") on delete cascade,
  primary key("role_id", "model_id", "model_type")
);
CREATE INDEX "model_has_roles_model_id_model_type_index" on "model_has_roles"(
  "model_id",
  "model_type"
);
CREATE TABLE IF NOT EXISTS "role_has_permissions"(
  "permission_id" integer not null,
  "role_id" integer not null,
  foreign key("permission_id") references "permissions"("id") on delete cascade,
  foreign key("role_id") references "roles"("id") on delete cascade,
  primary key("permission_id", "role_id")
);
CREATE TABLE IF NOT EXISTS "media"(
  "id" integer primary key autoincrement not null,
  "model_type" varchar not null,
  "model_id" integer not null,
  "uuid" varchar,
  "collection_name" varchar not null,
  "name" varchar not null,
  "file_name" varchar not null,
  "mime_type" varchar,
  "disk" varchar not null,
  "conversions_disk" varchar,
  "size" integer not null,
  "manipulations" text not null,
  "custom_properties" text not null,
  "generated_conversions" text not null,
  "responsive_images" text not null,
  "order_column" integer,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "media_model_type_model_id_index" on "media"(
  "model_type",
  "model_id"
);
CREATE UNIQUE INDEX "media_uuid_unique" on "media"("uuid");
CREATE INDEX "media_order_column_index" on "media"("order_column");
CREATE TABLE IF NOT EXISTS "settings"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "value" text,
  "type" varchar not null default 'string',
  "group" varchar not null default 'general',
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "settings_key_group_index" on "settings"("key", "group");
CREATE UNIQUE INDEX "settings_key_unique" on "settings"("key");
CREATE TABLE IF NOT EXISTS "website_settings"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "value" text,
  "type" varchar not null default 'string',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "website_settings_key_unique" on "website_settings"("key");
CREATE TABLE IF NOT EXISTS "site_settings"(
  "id" integer primary key autoincrement not null,
  "site_title" varchar not null default 'My Website',
  "site_logo" varchar,
  "meta_description" text,
  "meta_keywords" text,
  "default_currency" varchar not null default 'USD',
  "primary_color" varchar not null default '#3b82f6',
  "secondary_color" varchar not null default '#64748b',
  "accent_color" varchar not null default '#10b981',
  "created_at" datetime,
  "updated_at" datetime,
  "company_email" varchar,
  "company_phone" varchar,
  "company_address" text,
  "facebook_url" varchar,
  "twitter_url" varchar,
  "instagram_url" varchar,
  "linkedin_url" varchar,
  "youtube_url" varchar,
  "site_description" text
);
CREATE TABLE IF NOT EXISTS "guest_orders"(
  "id" integer primary key autoincrement not null,
  "product_id" integer not null,
  "region_id" integer,
  "full_name" varchar not null,
  "phone_number" varchar not null,
  "email" varchar,
  "address" text not null,
  "quantity" integer not null default '1',
  "unit_price" numeric not null,
  "total_price" numeric not null,
  "status" varchar check("status" in('pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled')) not null default 'pending',
  "notes" text,
  "order_number" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("product_id") references "products"("id") on delete cascade,
  foreign key("region_id") references "regions"("id") on delete set null
);
CREATE UNIQUE INDEX "guest_orders_order_number_unique" on "guest_orders"(
  "order_number"
);
CREATE TABLE IF NOT EXISTS "sliders"(
  "id" integer primary key autoincrement not null,
  "title" text not null,
  "subtitle" text,
  "description" text,
  "image_path" varchar,
  "button_text" varchar,
  "button_url" varchar,
  "background_color" varchar not null default '#ffffff',
  "text_color" varchar not null default '#000000',
  "sort_order" integer not null default '0',
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "currencies"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" text not null,
  "symbol" varchar not null,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "currencies_code_index" on "currencies"("code");
CREATE INDEX "currencies_is_active_index" on "currencies"("is_active");
CREATE UNIQUE INDEX "currencies_code_unique" on "currencies"("code");
CREATE TABLE IF NOT EXISTS "languages"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" text not null,
  "native_name" varchar not null,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "languages_code_index" on "languages"("code");
CREATE INDEX "languages_is_active_index" on "languages"("is_active");
CREATE UNIQUE INDEX "languages_code_unique" on "languages"("code");
CREATE TABLE IF NOT EXISTS "country_languages"(
  "id" integer primary key autoincrement not null,
  "country_id" integer not null,
  "language_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("country_id") references "countries"("id") on delete cascade,
  foreign key("language_id") references "languages"("id") on delete cascade
);
CREATE UNIQUE INDEX "country_languages_country_id_language_id_unique" on "country_languages"(
  "country_id",
  "language_id"
);
CREATE INDEX "country_languages_country_id_index" on "country_languages"(
  "country_id"
);
CREATE INDEX "country_languages_language_id_index" on "country_languages"(
  "language_id"
);
CREATE TABLE IF NOT EXISTS "regions"(
  "id" integer primary key autoincrement not null,
  "name" text not null,
  "code" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  "is_active" tinyint(1) not null default '1'
);
CREATE INDEX "regions_code_index" on "regions"("code");
CREATE INDEX "regions_is_active_index" on "regions"("is_active");
CREATE TABLE IF NOT EXISTS "theme_settings"(
  "id" integer primary key autoincrement not null,
  "primary_color" varchar not null default '#3B82F6',
  "secondary_color" varchar not null default '#64748B',
  "accent_color" varchar not null default '#10B981',
  "background_color" varchar not null default '#FFFFFF',
  "text_color" varchar not null default '#1F2937',
  "is_active" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  "name" varchar
);
CREATE INDEX "theme_settings_is_active_index" on "theme_settings"("is_active");
CREATE TABLE IF NOT EXISTS "analytics_settings"(
  "id" integer primary key autoincrement not null,
  "google_analytics_id" varchar,
  "google_tag_manager_id" varchar,
  "facebook_pixel_id" varchar,
  "is_active" tinyint(1) not null default '0',
  "track_ecommerce" tinyint(1) not null default '1',
  "anonymize_ip" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "analytics_settings_is_active_index" on "analytics_settings"(
  "is_active"
);
CREATE TABLE IF NOT EXISTS "cities"(
  "id" integer primary key autoincrement not null,
  "name" text not null,
  "country_id" integer not null,
  "is_active" tinyint(1) not null default '1',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("country_id") references "countries"("id") on delete cascade
);
CREATE INDEX "cities_country_id_index" on "cities"("country_id");
CREATE INDEX "cities_is_active_index" on "cities"("is_active");
CREATE TABLE IF NOT EXISTS "countries"(
  "id" integer primary key autoincrement not null,
  "code" varchar not null,
  "name" text not null,
  "region_id" integer not null,
  "currency_id" integer not null,
  "default_language_id" integer not null,
  "is_active" tinyint(1) not null default('1'),
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("default_language_id") references languages("id") on delete cascade on update no action,
  foreign key("currency_id") references currencies("id") on delete cascade on update no action
);
CREATE INDEX "countries_code_index" on "countries"("code");
CREATE UNIQUE INDEX "countries_code_unique" on "countries"("code");
CREATE INDEX "countries_currency_id_index" on "countries"("currency_id");
CREATE INDEX "countries_default_language_id_index" on "countries"(
  "default_language_id"
);
CREATE INDEX "countries_is_active_index" on "countries"("is_active");
CREATE INDEX "countries_region_id_index" on "countries"("region_id");

INSERT INTO migrations VALUES(1,'0001_01_01_000000_create_users_table',1);
INSERT INTO migrations VALUES(2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO migrations VALUES(4,'2025_09_21_000001_create_regions_table',1);
INSERT INTO migrations VALUES(5,'2025_09_21_000002_update_users_table',1);
INSERT INTO migrations VALUES(6,'2025_09_21_000003_create_categories_table',1);
INSERT INTO migrations VALUES(7,'2025_09_21_000004_create_brands_table',1);
INSERT INTO migrations VALUES(8,'2025_09_21_000005_create_products_table',1);
INSERT INTO migrations VALUES(9,'2025_09_21_000006_create_product_categories_table',1);
INSERT INTO migrations VALUES(10,'2025_09_21_000007_create_product_images_table',1);
INSERT INTO migrations VALUES(11,'2025_09_21_000008_create_orders_table',1);
INSERT INTO migrations VALUES(12,'2025_09_21_000009_create_order_items_table',1);
INSERT INTO migrations VALUES(13,'2025_09_21_000010_create_cart_items_table',1);
INSERT INTO migrations VALUES(14,'2025_09_21_000011_create_seo_meta_table',1);
INSERT INTO migrations VALUES(15,'2025_09_21_000012_create_redirects_table',1);
INSERT INTO migrations VALUES(16,'2025_09_21_000013_create_schema_markups_table',1);
INSERT INTO migrations VALUES(17,'2025_09_21_153547_create_permission_tables',1);
INSERT INTO migrations VALUES(18,'2025_09_21_154347_create_media_table',1);
INSERT INTO migrations VALUES(19,'2025_09_21_172246_create_settings_table',1);
INSERT INTO migrations VALUES(20,'2025_09_21_172516_create_website_settings_table',1);
INSERT INTO migrations VALUES(21,'2025_09_21_175650_create_site_settings_table',1);
INSERT INTO migrations VALUES(22,'2025_09_21_181018_add_missing_fields_to_products_table',1);
INSERT INTO migrations VALUES(23,'2025_09_21_182651_add_contact_social_fields_to_site_settings_table',1);
INSERT INTO migrations VALUES(24,'2025_09_21_185827_create_guest_orders_table',1);
INSERT INTO migrations VALUES(25,'2025_09_21_192248_create_sliders_table',1);
INSERT INTO migrations VALUES(26,'2025_09_22_001733_add_multilingual_seo_fields_to_products_table',1);
INSERT INTO migrations VALUES(27,'2025_09_25_193509_create_currencies_table',2);
INSERT INTO migrations VALUES(28,'2025_09_25_193513_create_countries_table',2);
INSERT INTO migrations VALUES(29,'2025_09_25_193513_create_languages_table',2);
INSERT INTO migrations VALUES(30,'2025_09_25_193522_create_country_languages_table',2);
INSERT INTO migrations VALUES(31,'2025_09_25_193522_update_regions_table_for_new_structure',2);
INSERT INTO migrations VALUES(32,'2025_09_25_193846_create_theme_settings_table',2);
INSERT INTO migrations VALUES(33,'2025_09_25_194053_create_analytics_settings_table',2);
INSERT INTO migrations VALUES(34,'2025_09_25_195813_create_cities_table',2);
INSERT INTO migrations VALUES(35,'2025_09_25_201610_add_name_to_theme_settings',3);
