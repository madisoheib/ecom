# Docker Setup for Laravel E-Commerce

This Docker setup provides a complete development and production environment for the Laravel e-commerce application.

## Services Included

- **Nginx** - Web server (ports 8080/8443)
- **PHP-FPM** - Laravel application server
- **MySQL 8.0** - Database server (port 3306)
- **Redis** - Cache and session storage (port 6379)
- **Queue Worker** - Background job processing
- **Scheduler** - Laravel task scheduling
- **Mailhog** - Email testing (ports 1025/8025)
- **Node.js** - Frontend development (port 5173, development profile)

## Quick Start

1. **Copy environment file**:
   ```bash
   cp .env.docker .env
   ```

2. **Generate application key**:
   ```bash
   php artisan key:generate
   ```
   Or set it manually in `.env`:
   ```
   APP_KEY=base64:your-generated-key-here
   ```

3. **Build and start containers**:
   ```bash
   docker-compose up -d
   ```

4. **Install dependencies and setup database**:
   ```bash
   # Enter PHP container
   docker-compose exec php bash
   
   # Run migrations
   php artisan migrate
   
   # Seed database (if seeders exist)
   php artisan db:seed
   
   # Create admin user for Filament
   php artisan make:filament-user
   ```

5. **Access the application**:
   - Frontend: http://localhost:8080
   - Admin Panel: http://localhost:8080/admin
   - Mailhog: http://localhost:8025

## Development Setup

For development with hot reloading, use the development profile:

```bash
# Start with Node.js development server
docker-compose --profile development up -d

# Access Vite dev server
# Frontend: http://localhost:5173
```

## Container Management

### Start containers:
```bash
docker-compose up -d
```

### Stop containers:
```bash
docker-compose down
```

### View logs:
```bash
# All services
docker-compose logs -f

# Specific service
docker-compose logs -f php
docker-compose logs -f nginx
```

### Execute commands in containers:
```bash
# PHP container
docker-compose exec php bash
docker-compose exec php php artisan migrate

# MySQL container
docker-compose exec mysql mysql -u laravel_user -p laravel_ecommerce
```

## Database Access

### From host machine:
- Host: localhost
- Port: 3306
- Database: laravel_ecommerce
- Username: laravel_user
- Password: laravel_password

### From application:
- Host: mysql
- Port: 3306
- Database: laravel_ecommerce
- Username: laravel_user
- Password: laravel_password

## Redis Access

### From host machine:
- Host: localhost
- Port: 6379
- Password: redis_password

### From application:
- Host: redis
- Port: 6379
- Password: redis_password

## File Permissions

The containers are configured to handle file permissions automatically. If you encounter permission issues:

```bash
# Fix storage and cache permissions
docker-compose exec php chown -R www-data:www-data /var/www/html/storage
docker-compose exec php chown -R www-data:www-data /var/www/html/bootstrap/cache
```

## Production Deployment

1. **Update environment variables**:
   - Set `APP_ENV=production`
   - Set `APP_DEBUG=false`
   - Generate secure passwords
   - Configure email settings
   - Set proper `APP_URL`

2. **Build production images**:
   ```bash
   docker-compose build --no-cache
   ```

3. **Deploy with production settings**:
   ```bash
   docker-compose -f docker-compose.yml up -d
   ```

## Backup and Restore

### Database Backup:
```bash
docker-compose exec mysql mysqldump -u laravel_user -p laravel_ecommerce > backup.sql
```

### Database Restore:
```bash
docker-compose exec -i mysql mysql -u laravel_user -p laravel_ecommerce < backup.sql
```

### Storage Backup:
```bash
docker-compose exec php tar -czf /tmp/storage-backup.tar.gz storage/
docker cp ecommerce_php:/tmp/storage-backup.tar.gz ./storage-backup.tar.gz
```

## Troubleshooting

### Container won't start:
```bash
# Check logs
docker-compose logs [service-name]

# Rebuild containers
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Database connection issues:
```bash
# Check if MySQL is ready
docker-compose exec mysql mysqladmin ping -h localhost

# Verify credentials
docker-compose exec mysql mysql -u laravel_user -p
```

### Permission issues:
```bash
# Reset permissions
docker-compose exec php chown -R www-data:www-data /var/www/html
docker-compose exec php chmod -R 755 /var/www/html/storage
```

### Clear Laravel caches:
```bash
docker-compose exec php php artisan cache:clear
docker-compose exec php php artisan config:clear
docker-compose exec php php artisan route:clear
docker-compose exec php php artisan view:clear
```

## Monitoring

### Check service status:
```bash
docker-compose ps
```

### Monitor resource usage:
```bash
docker stats
```

### View container logs in real-time:
```bash
docker-compose logs -f --tail=100
```

## Security Notes

- Change default passwords in production
- Use environment-specific `.env` files
- Enable SSL/TLS for production deployments
- Regular security updates for base images
- Implement proper firewall rules