# Quick Start Guide - Render Deployment

## 🚀 Fast Track Deployment

### Option 1: Using render.yaml (Recommended)

1. **Push code to GitHub** (already done ✅)
2. **Go to Render Dashboard** → Click "New +" → "Blueprint"
3. **Connect GitHub repository**: `remaruru/LaundryRender`
4. **Render will automatically detect `render.yaml`** and create all services
5. **Wait for deployment** (10-15 minutes)
6. **Run migrations**:
   - Go to backend service → Shell tab
   - Run: `cd laundry-backend && php artisan migrate --force && php artisan db:seed --class=AdminSeeder`

### Option 2: Manual Setup

Follow the detailed guide in `RENDER_DEPLOYMENT.md`

## 📋 What Gets Deployed

1. **PostgreSQL Database** - Stores all data
2. **Laravel Backend API** - Handles business logic
3. **React Frontend** - User interface

## 🔗 URLs After Deployment

- **Frontend**: `https://laundry-frontend.onrender.com`
- **Backend API**: `https://laundry-backend.onrender.com/api`

## ⚙️ Environment Variables

### Backend (Auto-configured by Render)
- Database connection (auto-linked)
- APP_ENV=production
- APP_DEBUG=false

### Frontend
- API_URL=https://laundry-backend.onrender.com (set after backend deploys)

## 🔐 Default Admin Login

Check `laundry-backend/database/seeders/AdminSeeder.php` for default credentials.

## ⚠️ Important Notes

1. **Free tier services spin down** after 15 minutes of inactivity
2. **First request** after spin-down takes ~30 seconds
3. **Database migrations** must be run manually after first deployment
4. **Update API_URL** in frontend after backend URL is known

## 🐛 Troubleshooting

See `RENDER_DEPLOYMENT.md` for detailed troubleshooting steps.

---

**Need help?** Check the full guide in `RENDER_DEPLOYMENT.md`

