# NEON LIGHT HK — WordPress / WooCommerce Clone

> Complete rebuild of [www.neonlighthk.com](https://www.neonlighthk.com) as a multilingual WordPress + WooCommerce site with HK payment gateway integration.

## Features

- **Workshop** — 霓虹燈工作坊 booking system
- **Neon Rental** — Daily/weekly neon sign rental
- **Neon Retail** — Ready-made neon signs shop
- **Hanfu** — 漢服 collection
- **Balloon & Magic** — 氣球 & 魔術 supplies
- **Custom Orders** — Bespoke neon sign design

## Payment Methods

| Method | Provider | Gateway |
|--------|----------|---------|
| Visa / Mastercard | HKTMS | `hktms-gateway` |
| AlipayHK | HKTMS | `hktms-gateway` |
| AlipayCN | HKTMS | `hktms-gateway` |
| WeChat Pay | HKTMS | `hktms-gateway` |
| Tap & Go | HKTPL | `hktpl-gateway` |
| FPS | HKTPL | `hktpl-gateway` |

## Languages

- English (EN)
- Traditional Chinese 繁體中文 (zh-HK)
- Simplified Chinese 简体中文 (zh-CN)

## Quick Start

### 1. Start Docker
```bash
cd ~/Desktop/neonlighthk-wp/docker
docker-compose up -d
```

Services:
- WordPress: http://localhost:8080
- MariaDB: port 3307
- Redis: port 6380

### 2. Install WordPress
```bash
docker-compose exec wpcli wp core install \
  --url="http://localhost:8080" \
  --title="NEON LIGHT HK" \
  --admin_user="admin" \
  --admin_password="neonpass123" \
  --admin_email="admin@neonlighthk.com"
```

### 3. Install Plugins
```bash
docker-compose exec wpcli wp plugin install woocommerce --activate
docker-compose exec wpcli wp plugin install polylang --activate
docker-compose exec wpcli wp plugin install redis-cache --activate
docker-compose exec wpcli wp plugin install advanced-custom-fields --activate
```

### 4. Activate Theme
```bash
docker-compose exec wpcli wp theme activate neonlighthk
```

### 5. Configure WooCommerce
```bash
docker-compose exec wpcli wp wc tool run install \
  --user=1 \
  --store_address="35 Aberdeen Street" \
  --store_address_2="PMQ Block B, HG19" \
  --store_city="Central" \
  --store_postcode="" \
  --store_country="HK" \
  --currency=HKD
```

### 6. Configure Payment Gateways

Go to **WooCommerce → Settings → Payments**:

**HKTMS:**
- Enable HKTMS gateway
- Enter App ID + App Secret (from HKT)
- Enable Test Mode for UAT
- Set notification URL to `https://yourdomain.com/wc-api/hktms-webhook/`

**HKTPL:**
- Enable HKTPL gateway
- Enter Merchant ID, App ID, API Key
- Upload HKTPL public key
- Set private key path outside web root
- Enable Test Mode for UAT

## Project Structure

```
neonlighthk-wp/
├── AGENTS.md                          # Project context for AI agents
├── docker/
│   ├── docker-compose.yml             # WordPress + MariaDB + Redis + Nginx
│   ├── nginx.conf                     # Nginx reverse proxy config
│   ├── php.ini                        # PHP 8.2 settings
│   └── wp-config-docker.php           # WP config with Redis
├── docs/
│   ├── hktms-integration.md           # HKTMS API documentation
│   └── hktpl-integration.md           # HKTPL API documentation
├── wp-content/
│   ├── plugins/
│   │   ├── hktms-gateway/             # Visa/MC/Alipay/WeChat Pay
│   │   │   ├── hktms-gateway.php
│   │   │   ├── includes/
│   │   │   │   ├── class-gateway.php
│   │   │   │   ├── class-api.php
│   │   │   │   └── class-webhook.php
│   │   │   └── assets/js/hktms-checkout.js
│   │   └── hktpl-gateway/             # Tap&Go / FPS
│   │       ├── hktpl-gateway.php
│   │       ├── includes/
│   │       │   ├── class-gateway.php
│   │       │   ├── class-api.php
│   │       │   ├── class-crypto.php
│   │       │   └── class-webhook.php
│   │       └── assets/js/hktpl-checkout.js
│   └── themes/neonlighthk/           # Custom theme
│       ├── style.css                   # Dark neon aesthetic, cyan accent
│       ├── functions.php               # Theme setup, Customizer
│       ├── header.php                  # Top bar + nav + cart
│       ├── footer.php                  # 4-column footer
│       ├── index.php                   # Fallback loop
│       ├── page-home.php               # Homepage template
│       ├── inc/
│       │   ├── cpt.php                 # 5 custom post types
│       │   ├── acf-fields.php          # ACF field groups
│       │   ├── woocommerce.php         # WC customizations
│       │   └── i18n.php                # Polylang integration
│       ├── template-parts/
│       │   ├── section-hero.php        # Hero banner
│       │   ├── section-shop.php        # 2×2 service cards
│       │   ├── section-lookbook.php    # Instagram gallery
│       │   ├── section-visit.php       # PMQ showroom + map
│       │   ├── section-clients.php     # Client logos
│       │   └── section-contact.php     # Contact form
│       └── assets/js/
│           ├── main.js                 # Mobile menu, animations
│           └── woocommerce.js          # Checkout enhancements
```

## Custom Post Types

| CPT | Slug | Purpose |
|-----|------|---------|
| Workshop | `nl_workshop` | Workshop listings with booking |
| Rental | `nl_rental` | Rental items with daily rates |
| Custom Order | `nl_custom_order` | Bespoke order submissions |
| Project | `nl_project` | Event/portfolio showcase |
| Lookbook | `nl_lookbook` | Gallery entries |

## Design Tokens

| Token | Value |
|-------|-------|
| Primary | `#00FFD1` (cyan neon) |
| Dark BG | `#0A0A0A` |
| Light BG | `#FFFFFF` |
| Display Font | Noto Sans TC / Noto Sans SC |
| Body Font | Inter |
| Max Width | 1440px |

## Payment Gateway Security

- JWT HS512 tokens with time-based replay protection
- RSA-encrypted payloads for HKTPL
- HMAC-SHA512 signature verification on all callbacks
- App secrets encrypted at rest via WordPress options
- Private keys stored outside web root

## Next Steps

1. **Add real product images** to `assets/images/`
2. **Configure HKT merchant credentials** (App ID / Secret)
3. **Whitelist webhook URLs** with HKT support
4. **Set up Polylang languages** (EN, 繁體, 简体)
5. **Import/translate WooCommerce products**
6. **Configure SSL** for production (Let's Encrypt)
7. **Deploy** to live hosting (Kinsta, Cloudways, or VPS)

## License
MIT — Vick Hung / Cheezo Group Limited
