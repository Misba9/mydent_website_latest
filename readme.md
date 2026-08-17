# Clinic Appointment Management System

## 1. How to Run the Application

### Backend Development Server
The application is accessible locally at: [http://127.0.0.1:8000](http://127.0.0.1:8000)

To start or restart the server manually:
```bash
php artisan serve --port=8000
```

### Frontend Asset Build (Optional / Development)
If you modify styles or scripts:
```bash
npm run dev     # Single build
npm run watch   # Auto-recompile on file save
```

---

## 2. Default Login Credentials

You can test different user roles on the login page ([http://127.0.0.1:8000/login](http://127.0.0.1:8000/login)):

| Role | Email | Password |
| --- | --- | --- |
| **Super Admin** | `admin@mydent.in` | `123456` |
| **Doctor** | `doctor@mydent.in` | `123456` |
| **Patient** | `patient@mydent.in` | `123456` |
| **Staff** | `john@gamil.com` | `123456` |

---

## 3. How to Run Automated Tests

To execute the PHPUnit test suite:
```bash
php artisan test
```
*(or `./vendor/bin/phpunit`)*
