# Kumari's Store — PHP + MongoDB Backend

Complete backend for Kumari's Store shopping website.
Built with PHP 8 + MongoDB + JWT authentication.

---

## Project Structure

```
backendforassign/
├── index.php              ← Main router (all requests go here)
├── seed.php               ← Run once to load all 18 products
├── api.js                 ← Add to your HTML to connect frontend
├── collections/           ← MongoDB data stored here as JSON files
├── lib/
│   ├── MongoDB.php        ← Database connection (file or real MongoDB)
│   ├── JWT.php            ← Login token system
│   └── helpers.php        ← Shared utilities
└── api/
    ├── auth/
    │   ├── register.php   ← POST /api/auth/register
    │   └── login.php      ← POST /api/auth/login
    ├── products/
    │   ├── index.php      ← GET  /api/products
    │   ├── show.php       ← GET  /api/products/{id}
    │   ├── categories.php ← GET  /api/products/categories
    │   ├── store.php      ← POST /api/products (admin)
    │   ├── update.php     ← PUT  /api/products/{id} (admin)
    │   └── delete.php     ← DELETE /api/products/{id} (admin)
    ├── cart/
    │   ├── index.php      ← GET    /api/cart
    │   ├── add.php        ← POST   /api/cart/add
    │   ├── remove.php     ← DELETE /api/cart/{productId}
    │   └── clear.php      ← DELETE /api/cart
    └── orders/
        ├── place.php      ← POST /api/orders/place
        ├── my_orders.php  ← GET  /api/orders/my-orders
        └── show.php       ← GET  /api/orders/{id}
```

---

## How to Run (XAMPP)

### Step 1 — Copy project into XAMPP
Copy the entire `backendforassign` folder into:
```
C:\xampp\htdocs\backendforassign
```

### Step 2 — Start Apache
Open XAMPP Control Panel → Click Start next to Apache.
(Use port 8080 if you changed it)

### Step 3 — Seed the database with products
Open Command Prompt and run:
```
cd C:\xampp\htdocs\backendforassign
php seed.php
```
You should see: "Seeded 18 products into the products collection."

### Step 4 — Test in browser
Visit: http://localhost:8080/backendforassign
You should see the API welcome message with all endpoints listed.

### Step 5 — Test products endpoint
Visit: http://localhost:8080/backendforassign/api/products
You should see all 18 products as JSON.

---

## Connect to Your HTML Page

Copy api.js into your store folder, then add to your HTML:
```html
<script src="api.js"></script>
```

Make sure API_BASE in api.js matches your port:
```javascript
const API_BASE = 'http://localhost:8080/backendforassign/api';
```

---

## All API Endpoints

| Method | Endpoint | Login? | Description |
|--------|----------|--------|-------------|
| POST | /api/auth/register | No | Create account |
| POST | /api/auth/login | No | Login |
| GET | /api/products | No | All products |
| GET | /api/products?category=kurtha | No | Filter by category |
| GET | /api/products?q=blue | No | Search products |
| GET | /api/products?sort=price_asc | No | Sort products |
| GET | /api/products?minPrice=200&maxPrice=1000 | No | Filter by price |
| GET | /api/products/{id} | No | Single product |
| GET | /api/products/categories | No | All categories |
| POST | /api/products | Yes (admin) | Add product |
| PUT | /api/products/{id} | Yes (admin) | Update product |
| DELETE | /api/products/{id} | Yes (admin) | Delete product |
| GET | /api/cart | Yes | View cart |
| POST | /api/cart/add | Yes | Add to cart |
| DELETE | /api/cart/{productId} | Yes | Remove from cart |
| DELETE | /api/cart | Yes | Clear cart |
| POST | /api/orders/place | Yes | Place order |
| GET | /api/orders/my-orders | Yes | My orders |
| GET | /api/orders/{id} | Yes | Single order |

---

## Switch to Real MongoDB (Optional)

Once you want to use the real MongoDB driver:
1. Install: pecl install mongodb
2. Open lib/MongoDB.php
3. Change: define('USE_REAL_MONGO', false);
4. To:     define('USE_REAL_MONGO', true);

Everything else stays exactly the same!

---

## Notes

- Passwords are hashed with bcrypt — never stored as plain text
- Login uses JWT tokens (7 day expiry)
- Data is stored in collections/ folder as JSON files
- Built for learning — great for college projects!
