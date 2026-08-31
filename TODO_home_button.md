# Home Button Addition Plan

## Goal
Add a **Home** button to all services that redirects to `welcome.php`.

## Excluded Pages (NO Home button)
- `Auth/login/login.php` - login page
- `Auth/regitration/signup.php` - register page
- `marketplace/user/login.php` - login page
- `marketplace/user/register.php` - register page

## Services & Files to Edit

### 1. Rental Service (already has navbars)
- `rental-service/index.html`
- `rental-service/tractor/tractor.php`
- `rental-service/tractor/tractor details/index.php`
- `rental-service/harvester/harvester.php`
- `rental-service/harvester/harvester-details/index.php`
- `rental-service/Plough/Plough.php`
- `rental-service/Plough/Plough-details/index.php`
- `rental-service/Cultivator/Cultivator.php`
- `rental-service/Cultivator/Cultivator-details/index.php`

**Edit:** Add `<li><a href="/krushi-manch/welcome.php">Home</a></li>` to each existing `<nav>` menu.

### 2. Marketplace
- `marketplace/index.php`
- `marketplace/user/dashboard.php`
- `marketplace/user/add_product.php`
- `marketplace/user/place_order.php`
- `marketplace/user/orders_history.php`
- `marketplace/user/view_orders.php`
- `marketplace/user/confirm_order.php`
- `marketplace/user/forgot_password.php`
- `marketplace/admin/admin_dashboard.php`
- `marketplace/admin/manage_users.php`
- `marketplace/admin/manage_products.php`
- `marketplace/admin/manage_orders.php`
- `marketplace/admin/manage_admins.php`
- `marketplace/admin/add_admin.php`

**Edit:** Add a consistent top Home button/link redirecting to `/krushi-manch/welcome.php`.

### 3. Farm Expense Tracker (no nav currently)
- `farm-expense-tracker/index.php`
- `farm-expense-tracker/add_entry.php`
- `farm-expense-tracker/summary.php`

**Edit:** Add a top navigation bar with a Home button.

### 4. INFOHUB (no nav currently)
- `INFOHUB/index.html`
- `INFOHUB/explore.html`
- `INFOHUB/crops.html`
- `INFOHUB/crop_diseases.html`
- `INFOHUB/crop_management.html`
- `INFOHUB/farming_tips.html`
- `INFOHUB/herbicides.html`
- `INFOHUB/irrigation_systems.html`
- `INFOHUB/pesticides.html`
- `INFOHUB/soil_health.html`

**Edit:** Add a fixed top navigation bar with a Home button.

### 5. EMI Calculator
- `EMI calculator/index.html`

**Edit:** Add a top Home button.

### 6. RTC (Real-Time Chat)
- `RTC/index.html`
- `RTC/chat.php`

**Edit:** Add a top Home button.

### 7. Auth Pages
- `Auth/Profile/Profile.php`

**Edit:** Add Home link to existing dropdown/nav area.

## Link Path
Use absolute path: `/krushi-manch/welcome.php`

## Design Theme
- Background: `#00796B` or `#2e7d32`
- Text: white
- Clean, minimal top bar or nav link


