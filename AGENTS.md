# NEON LIGHT HK — WordPress/WooCommerce Clone

## Project Overview
Rebuild of [www.neonlighthk.com](https://www.neonlighthk.com) as a WordPress + WooCommerce site with full payment integration and multilingual support.

**Features:**
- Workshop (霓虹燈工作坊)
- Neon Rental (租借)
- Neon Retail / Products (現貨)
- Hanfu (漢服)
- Balloon & Magic (氣球 & 魔術)
- Custom Orders (訂製)

**Payment Methods:**
- HKTMS: Visa, Mastercard, AlipayHK, AlipayCN, WeChat Pay
- HKTPL: Tap & Go, FPS

**Languages:** English (EN), Traditional Chinese (繁體, zh-HK), Simplified Chinese (简体, zh-CN)

## Architecture

### Stack
- WordPress 6.7 + WooCommerce 9.x
- MariaDB (MySQL 8 compatible)
- PHP 8.2 + FPM
- Nginx (reverse proxy)
- Redis (object cache + session)
- WP CLI (management)

### Docker Services
- `wordpress` — PHP-FPM + WordPress
- `mariadb` — Database
- `nginx` — Web server
- `redis` — Object cache + WooCommerce sessions
- `wpcli` — WP-CLI management container

### Theme: `neonlighthk`
- Custom theme duplicating neonlighthk.com design
- Dark neon aesthetic, cyan (#00FFD1) accent
- Bilingual Chinese/English labels throughout
- Sections: Hero, Shop, Rental, Workshop, Projects, Lookbook, Contact, Footer

### Custom Post Types
| CPT | Purpose |
|-----|---------|
| `nl_workshop` | Workshop listings with booking form |
| `nl_rental` | Neon rental items (daily/weekly rates) |
| `nl_custom_order` | Custom neon order submissions |
| `nl_project` | Project/event portfolio |
| `nl_lookbook` | Gallery/lookbook entries |

### WooCommerce Product Categories
- Neon Signs (Ready-made)
- Workshop Tickets
- Rental Items
- Hanfu Collection
- Balloon & Magic Supplies
- Custom Orders (deposit-based)

### Payment Gateway Plugins

#### `hktms-gateway/` — HKTMS ePayment (Visa/MC/Alipay/WeChat)
- Implements WooCommerce `WC_Payment_Gateway` interface
- API endpoints (UAT vs Production)
- JWT HS512 authentication (App ID + App Secret)
- Hosted payment page redirect
- Webhook handling for order status updates
- APIs: Create Payment URL, Fetch Order Status, Transaction History

**HKTMS Endpoints:**
- UAT: `gateway.sandbox.tapngo.com.hk`
- Prod: `gateway2.tapngo.com.hk`

**Key APIs:**
- `POST /ePaymentGateway/visamastercard/v2/transactions/paymentUrl`
- `POST /ePaymentGateway/visamastercard/v2/transactions/orderStatus`
- `POST /ePaymentGateway/alipayhk/transactions/paymentUrl`
- `POST /ePaymentGateway/alipaycn/transactions/paymentUrl`
- `POST /ePaymentGateway/wechatpay/transactions/paymentUrl`

#### `hktpl-gateway/` — HKTPL (Tap&Go / FPS)
- Implements WooCommerce `WC_Payment_Gateway` interface
- RSA encryption for payment payload
- HMAC-SHA512 signature verification
- QR code generation (HKQR) for FPS
- Access token registration flow

**HKTPL Endpoints:**
- Web payment: `POST /web/payments`
- Recurrent payment: `POST /paymentApi/payment/recurrent`
- Query status: `POST /paymentApi/query/payment/status`
- Register token: `POST /paymentApi/oauth/token`
- Transaction history: `POST /paymentApi/query/transaction/history`
- Generate HKQR (bill): `POST /paymentApi/bill/generate/hkqr`
- Generate HKQR (sales): `POST /paymentApi/sales/generate/hkqr`

### Multilingual
- WPML or Polylang (Polylang recommended — free, lightweight)
- Languages: en (default), zh-hant, zh-hans
- String translation for all custom labels

## Directory Layout
```
neonlighthk-wp/
├── docker/
│   ├── docker-compose.yml
│   ├── nginx.conf
│   ├── php.ini
│   └── wp-config-docker.php
├── wp-content/
│   ├── themes/neonlighthk/
│   │   ├── style.css
│   │   ├── functions.php
│   │   ├── index.php
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── page-home.php
│   │   ├── page-shop.php
│   │   ├── page-workshop.php
│   │   ├── page-rental.php
│   │   ├── page-hanfu.php
│   │   ├── page-balloon.php
│   │   ├── single-nl_workshop.php
│   │   ├── single-nl_rental.php
│   │   ├── woocommerce/
│   │   │   ├── checkout/payment.php
│   │   │   └── checkout/thankyou.php
│   │   ├── inc/
│   │   │   ├── cpt.php
│   │   │   ├── acf-fields.php
│   │   │   ├── woocommerce.php
│   │   │   └── i18n.php
│   │   └── template-parts/
│   │       ├── section-hero.php
│   │       ├── section-shop.php
│   │       ├── section-workshop.php
│   │       ├── section-rental.php
│   │       ├── section-projects.php
│   │       ├── section-lookbook.php
│   │       ├── section-contact.php
│   │       └── section-footer.php
│   └── plugins/
│       ├── hktms-gateway/
│       │   ├── hktms-gateway.php
│       │   ├── class-wc-gateway-hktms.php
│       │   ├── class-hktms-api.php
│       │   ├── class-hktms-webhook.php
│       │   ├── assets/js/hktms-checkout.js
│       │   └── includes/settings.php
│       └── hktpl-gateway/
│           ├── hktpl-gateway.php
│           ├── class-wc-gateway-hktpl.php
│           ├── class-hktpl-api.php
│           ├── class-hktpl-crypto.php
│           ├── class-hktpl-webhook.php
│           ├── assets/js/hktpl-checkout.js
│           └── includes/settings.php
├── sql/
│   └── schema.sql
├── docs/
│   ├── hktms-integration.md
│   └── hktpl-integration.md
└── AGENTS.md
```

## Development Workflow

### Local Setup
```bash
cd ~/Desktop/neonlighthk-wp/docker
docker-compose up -d
docker-compose exec wpcli wp core install \
  --url="http://localhost:8080" \
  --title="NEON LIGHT HK" \
  --admin_user="admin" \
  --admin_password="password" \
  --admin_email="admin@neonlighthk.com"
```

### Install Plugins
```bash
# WooCommerce
docker-compose exec wpcli wp plugin install woocommerce --activate
docker-compose exec wpcli wp plugin install polylang --activate
docker-compose exec wpcli wp plugin install redis-cache --activate
# ACF Pro (manual upload)
# Elementor or custom blocks (optional)
```

### Theme Activation
```bash
docker-compose exec wpcli wp theme activate neonlighthk
```

### Payment Gateway Setup
1. HKTMS: Enter App ID + App Secret per payment method (Visa/MC, AlipayHK, AlipayCN, WeChatPay)
2. HKTPL: Enter Merchant ID + App ID + API Key + Public Key
3. Configure webhook URLs in HKT merchant portals
4. Test with UAT credentials first

## Design Tokens
| Token | Value |
|-------|-------|
| Primary | `#00FFD1` (cyan neon) |
| Dark BG | `#0A0A0A` |
| Light BG | `#FFFFFF` |
| Text Dark | `#111111` |
| Text Light | `#FFFFFF` |
| Accent Hover | `#00D4B0` |
| Font Display | "Noto Sans TC", "Noto Sans SC", sans-serif |
| Font Body | "Inter", -apple-system, sans-serif |

## Security
- JWT secrets stored as WordPress options (encrypted at rest)
- RSA private keys stored outside web root (`/var/secure/`)
- Webhook signatures verified on every callback
- SQL injection prevention via `$wpdb->prepare()`
- XSS prevention via `esc_html()`, `esc_attr()`

## Notes
- PMQ showroom address hardcoded in theme
- Instagram feed via Smash Balloon or custom oEmbed
- Contact form uses Fluent Forms or WPForms
- Workshop booking: WooCommerce product + Gravity Forms/ACF
- Rental: bookable product (WooCommerce Bookings or custom)
- Hanfu & Balloon: standard WooCommerce products
