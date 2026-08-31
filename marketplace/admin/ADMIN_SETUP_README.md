# ADMIN SYSTEM - SETUP GUIDE

## 🔐 Security Features
- Passwords are NOW hashed (encrypted)
- Public cannot see admin credentials
- Only logged-in admins can create new admins

---

## 📋 SETUP STEPS (DO THIS FIRST)

### Step 1: Secure the Admin Password
1. Open in browser: `http://localhost/xampp/htdocs/Krushi-Manch/marketplace/admin/admin_setup.php`
2. Click the "Secure Admin Password Now" button
3. You'll see: "✓ Admin password secured!"
4. **DELETE the file** `admin_setup.php` after running (for security)

### Step 2: Login as Admin
1. Go to: `http://localhost/xampp/htdocs/Krushi-Manch/marketplace/admin/admin_login.php`
2. **Email:** `sataparaabhi123@gmail.com`
3. **Password:** `abhi12345`
4. Click "Login"

### Step 3: Create More Admin Accounts
1. After login, go to: `http://localhost/xampp/htdocs/Krushi-Manch/marketplace/admin/admin_register.php`
2. Only logged-in admins can access this page
3. Enter new admin email and password
4. Click "Create Admin Account"

---

## 🔑 Admin Credentials Format

| Field | Value |
|-------|-------|
| **Type** | Email & Password |
| **Stored** | Database (encrypted) |
| **Visible** | NO - Only in database |
| **Access** | Admin login page only |

---

## ✅ Current Admin Account

| Field | Value |
|-------|-------|
| Email | sataparaabhi123@gmail.com |
| Password | abhi12345 |

---

## 📍 Important Files

| File | Purpose | Delete? |
|------|---------|---------|
| `admin_login.php` | Login page | NO |
| `admin_register.php` | Create new admins (protected) | NO |
| `admin_setup.php` | One-time setup | **YES - Delete after running** |
| `admin_logout.php` | Logout | NO |
| `admin_dashboard.php` | Admin dashboard | NO |

---

## 🛡️ How It Works

### Before (INSECURE)
```
Password stored: abhi12345 (plaintext - anyone can see it)
Query: "SELECT * FROM admin WHERE email='X' AND password='X'"
Risk: Password visible in code
```

### After (SECURE)
```
Password stored: $2y$10$kLJo... (hashed - encrypted)
Query: Uses password_verify() function to check
Risk: Even if database is hacked, password is encrypted
```

---

## 🚀 Test It

1. **First Login:**
   - Go to admin login
   - Email: `sataparaabhi123@gmail.com`
   - Password: `abhi12345`
   - You should see the dashboard

2. **Create New Admin:**
   - Click the link in admin dashboard
   - Add new admin email and password
   - New admin can now login with their credentials

3. **Verify Security:**
   - Check database (phpMyAdmin)
   - Admin password is now hashed (not plaintext)
   - Password starts with `$2y$10$` (indicates hashing)

---

## ⚠️ REMEMBER

1. ✅ Run `admin_setup.php` first (one time only)
2. ✅ DELETE `admin_setup.php` after running
3. ✅ Share login link only with trusted people
4. ✅ Change the initial password after first login
5. ✅ Create new admin accounts for each person

---

## 📞 Need Help?

If you forget admin password, you need to:
1. Go to phpMyAdmin
2. Find `admin` table in `krushi-manch` database
3. Update password with new hashed password using admin_setup.php again

