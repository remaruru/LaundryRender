# Render Deployment Guide for Laundry Management System

This guide will walk you through deploying your Laundry Management System to Render step by step.

## 📋 Prerequisites

1. **GitHub Account** - Your code should be pushed to GitHub (already done ✅)
2. **Render Account** - Sign up at [render.com](https://render.com) (free account works)
3. **Database** - PostgreSQL will be created automatically on Render

## 🚀 Step-by-Step Deployment

### Step 1: Sign Up/Login to Render

1. Go to [https://render.com](https://render.com)
2. Sign up or log in with your GitHub account
3. Authorize Render to access your GitHub repositories

### Step 2: Create PostgreSQL Database

1. In Render dashboard, click **"New +"** button
2. Select **"PostgreSQL"**
3. Configure the database:
   - **Name**: `laundry-database`
   - **Database**: `laundry_db`
   - **User**: `laundry_user`
   - **Region**: `Oregon` (or closest to you)
   - **Plan**: `Free` (or upgrade if needed)
4. Click **"Create Database"**
5. **⚠️ IMPORTANT**: Wait for the database to be fully created (green status)
6. **Copy the Internal Database URL** - you'll need this later

### Step 3: Deploy Backend (Laravel API)

1. In Render dashboard, click **"New +"** button
2. Select **"Web Service"**
3. Connect your GitHub repository:
   - Select **"remaruru/LaundryRender"** from the list
   - Or paste: `https://github.com/remaruru/LaundryRender`
4. Configure the service:
   - **Name**: `laundry-backend`
   - **Region**: `Oregon` (same as database)
   - **Branch**: `main`
   - **Root Directory**: `laundry-backend`
   - **Environment**: `PHP`
   - **Build Command**:
     ```bash
     composer install --no-dev --optimize-autoloader && php artisan key:generate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache
     ```
   - **Start Command**:
     ```bash
     php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT
     ```
5. Add Environment Variables:
   - Click **"Advanced"** → **"Add Environment Variable"**
   - Add these variables:
     ```
     APP_ENV=production
     APP_DEBUG=false
     LOG_LEVEL=error
     DB_CONNECTION=pgsql
     ```
   - For database connection, click **"Link Database"** and select `laundry-database`
   - Render will automatically add:
     - `DB_HOST`
     - `DB_PORT`
     - `DB_DATABASE`
     - `DB_USERNAME`
     - `DB_PASSWORD`
   - Add these additional variables:
     ```
     CACHE_DRIVER=file
     SESSION_DRIVER=file
     QUEUE_CONNECTION=sync
     ```
6. Click **"Create Web Service"**
7. Wait for the build to complete (this may take 5-10 minutes)

### Step 4: Get Backend URL

1. Once the backend is deployed, Render will provide a URL like:
   `https://laundry-backend.onrender.com`
2. **Copy this URL** - you'll need it for the frontend

### Step 5: Deploy Frontend (React App)

1. In Render dashboard, click **"New +"** button
2. Select **"Static Site"**
3. Connect your GitHub repository:
   - Select **"remaruru/LaundryRender"** from the list
4. Configure the static site:
   - **Name**: `laundry-frontend`
   - **Branch**: `main`
   - **Root Directory**: `laundry-frontend`
   - **Build Command**:
     ```bash
     npm install && REACT_APP_API_URL=$API_URL npm run build
     ```
   - **Publish Directory**: `build`
5. Add Environment Variable:
   - **Key**: `API_URL`
   - **Value**: Your backend URL from Step 4 (e.g., `https://laundry-backend.onrender.com`)
   - **⚠️ IMPORTANT**: Make sure to include `https://` in the URL
6. Click **"Create Static Site"**
7. Wait for the build to complete

### Step 6: Run Database Migrations

1. Go to your backend service (`laundry-backend`)
2. Click on **"Shell"** tab
3. Run the following commands:
   ```bash
   cd laundry-backend
   php artisan migrate --force
   php artisan db:seed --class=AdminSeeder
   ```
4. This will create all tables and seed the admin user

### Step 7: Test Your Deployment

1. **Frontend URL**: Your frontend will be available at something like:
   `https://laundry-frontend.onrender.com`
2. **Backend API**: Your API will be available at:
   `https://laundry-backend.onrender.com/api`
3. Test the following:
   - Visit the frontend URL
   - Try searching for orders (customer view)
   - Try logging in as admin (use the seeded admin credentials)

## 🔧 Troubleshooting

### Backend Issues

**Problem**: Build fails with "composer not found"
- **Solution**: Make sure you selected "PHP" as the environment

**Problem**: Database connection errors
- **Solution**: 
  1. Check that database is fully created (green status)
  2. Verify all DB_* environment variables are set
  3. Make sure database is linked to the backend service

**Problem**: 500 Internal Server Error
- **Solution**:
  1. Check logs in Render dashboard
  2. Make sure `APP_KEY` is set (should be auto-generated)
  3. Verify database migrations ran successfully

### Frontend Issues

**Problem**: Frontend can't connect to backend
- **Solution**:
  1. Check that `API_URL` environment variable is set correctly
  2. Make sure backend URL includes `https://`
  3. Check browser console for CORS errors
  4. If CORS errors, add CORS middleware to Laravel backend

**Problem**: Build fails
- **Solution**:
  1. Check Node.js version (should be 18+)
  2. Make sure all dependencies are in package.json
  3. Check build logs in Render dashboard

### Database Issues

**Problem**: Migrations fail
- **Solution**:
  1. Check database is accessible
  2. Verify DB credentials in environment variables
  3. Check database logs in Render dashboard

## 🔐 Admin Credentials

After running the seeder, you can log in with:
- **Email**: `admin@laundry.com`
- **Password**: `admin123`

**⚠️ Security Note**: Change the default admin password after first login!

## 📝 Additional Configuration

### CORS Configuration (if needed)

If you encounter CORS errors, add this to `laundry-backend/config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['https://laundry-frontend.onrender.com'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

### Environment Variables Summary

**Backend Required Variables:**
- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_CONNECTION=pgsql`
- `DB_HOST` (auto-set by Render)
- `DB_PORT` (auto-set by Render)
- `DB_DATABASE` (auto-set by Render)
- `DB_USERNAME` (auto-set by Render)
- `DB_PASSWORD` (auto-set by Render)

**Frontend Required Variables:**
- `API_URL=https://your-backend-url.onrender.com`

## 🎉 You're Done!

Your Laundry Management System should now be live on Render!

- **Frontend**: `https://laundry-frontend.onrender.com`
- **Backend API**: `https://laundry-backend.onrender.com/api`

## 📚 Useful Links

- [Render Documentation](https://render.com/docs)
- [Laravel Deployment Guide](https://render.com/docs/deploy-laravel)
- [React Static Sites](https://render.com/docs/static-sites)

## 💡 Tips

1. **Free Tier Limitations**: 
   - Services spin down after 15 minutes of inactivity
   - First request after spin-down takes ~30 seconds
   - Consider upgrading for production use

2. **Custom Domains**: 
   - You can add custom domains in Render dashboard
   - Update `API_URL` in frontend if you change domains

3. **Monitoring**: 
   - Use Render's built-in logs and metrics
   - Set up alerts for errors

4. **Backups**: 
   - Render automatically backs up PostgreSQL databases
   - Consider exporting data regularly

---

**Need Help?** Check Render's documentation or their support team!

