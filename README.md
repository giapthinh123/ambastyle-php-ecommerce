# 🛍️ Ambastyle Fashion Store

An online men's clothing store built with pure PHP and MySQL.

## 📋 Project Description

Ambastyle is a complete e-commerce system for a men's fashion store with the following features:

- **Customer Pages**: Browse products, search, add to cart, place orders
- **Admin Panel**: Manage products, categories, orders, customers, and revenue

## 🗂️ Project Structure

```
├── Pages/              # Customer-facing pages
│   ├── index.php           # Homepage
│   ├── Shop.php            # Shop page (product listing)
│   ├── productDetail.php   # Product details
│   ├── cart.php            # Shopping cart
│   ├── Checkout.php        # Checkout
│   ├── confirm_payment.php # Payment confirmation
│   ├── order_success.php   # Order success page
│   ├── order_list.php      # Order history
│   ├── login.php           # Login
│   ├── register.php        # Registration
│   ├── account.php         # Account management
│   ├── search.php          # Product search
│   └── sale_products.php   # Sale products
│
├── admin/              # Admin panel
│   ├── admin_index.php         # Admin dashboard
│   ├── admin_products.php      # Product management
│   ├── ad_categories.php       # Category management
│   ├── admin_order_manage.php  # Order management
│   ├── admin_accounts.php      # Account management
│   ├── admin_revenue.php       # Revenue statistics
│   ├── admin_promotions.php    # Promotion management
│   ├── admin_reviews.php       # Review management
│   ├── admin_best_sellers.php  # Best-selling products
│   └── admin_Shipping.php      # Shipping management
│
├── config/             # Configuration
│   └── db.php              # MySQL database connection
│
├── includes/           # Shared components
│   ├── header.php          # Customer page header
│   ├── footer.php          # Footer
│   ├── admin_header.php    # Admin page header
│   ├── admin_functions.php # Admin utility functions
│   └── adminShipping.php   # Shipping functions
│
├── process/            # Backend logic
│   ├── login_process.php       # Login handler
│   ├── register_process.php    # Registration handler
│   ├── add_to_cart.php         # Add to cart handler
│   ├── update_cart.php         # Update cart handler
│   ├── remove_from_cart.php    # Remove from cart handler
│   ├── process_order.php       # Order handler
│   ├── process_payment.php     # Payment handler
│   ├── process_product.php     # Product handler
│   ├── process_category.php    # Category handler
│   ├── process_promotion.php   # Promotion handler
│   ├── process_account.php     # Account handler
│   ├── process_revenue.php     # Revenue handler
│   ├── search_ajax.php         # AJAX search
│   ├── submit_review.php       # Submit review
│   └── submit_response.php     # Review response
│
├── css/                # Stylesheets
│   ├── style.css           # Customer page CSS
│   └── admin_style.css     # Admin page CSS
│
├── js/                 # JavaScript
│   └── script.js           # Main scripts
│
├── images/             # Images
├── uploads/            # Upload directory
└── data/               # Data
    └── vietnamAddress.json # Vietnam address list
```

## 🚀 Features

### For Customers
- ✅ Account registration / login
- ✅ Browse product categories
- ✅ View product details
- ✅ Search products (AJAX-powered)
- ✅ Add products to cart
- ✅ Select product sizes (S, M, L, XL, XXL)
- ✅ Update cart quantities
- ✅ Checkout orders
- ✅ Apply discount codes
- ✅ Select shipping address (Province/District/Ward)
- ✅ View order history
- ✅ Product reviews

### For Admin
- ✅ Product management (CRUD)
- ✅ Category management
- ✅ Order management (Confirm/Cancel)
- ✅ Payment management
- ✅ Customer account management
- ✅ Revenue statistics
- ✅ Promotion management
- ✅ Product review management
- ✅ View best-selling products

## 💻 System Requirements

- PHP >= 7.4
- MySQL >= 5.7
- Web Server (Apache/Nginx)
- XAMPP / WAMP / LAMP (recommended)

## ⚙️ Installation

### Step 1: Clone the repository

```bash
git clone https://github.com/giapthinh123/php.git
cd php
```

### Step 2: Configure the database

1. Create a new database in MySQL named: `webquanaonam`
2. Import the SQL file (if available) or create the necessary tables
3. Update the connection information in `config/db.php`:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "webquanaonam";
```

### Step 3: Run the application

1. Copy the entire project to the `htdocs` folder (XAMPP) or `www` folder (WAMP)
2. Start Apache and MySQL
3. Access: `http://localhost/php/Pages/index.php`

## 📊 Database Structure (Suggested)

Main tables:
- `users` - User information
- `products` - Products
- `product_images` - Product detail images
- `categories` - Product categories
- `cart` - Shopping cart
- `orders` - Orders
- `order_details` - Order details
- `payments` - Payments
- `reviews` - Reviews
- `promotions` - Promotions

## 🌐 Demo

### Customer Pages
- Homepage: `/Pages/index.php`
- Shop: `/Pages/Shop.php`

### Admin Panel
- Dashboard: `/admin/admin_index.php`

## 📞 Contact

- **Hotline:** 0985 032 589
- **Email:** info@goldievietnam.com

## 📄 License

This project was developed for educational purposes.

---

⭐ **Ambastyle** - *Fashion for Vietnamese Youth* ⭐
