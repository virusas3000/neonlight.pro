# HKTMS ePayment Gateway — Integration Reference (v1.10)

Extracted and verified from `HKTMS_ePayment_Gateway_Integration_Guide_v1_10_For_Visa_Mastercard.pdf`

---

## 1. API Endpoints

### Base URLs
- **UAT / Sandbox:** `https://gateway.sandbox.tapngo.com.hk`
- **Production:** `https://gateway2.tapngo.com.hk`

### Create Payment URL (POST)
| Payment Method | Endpoint |
|---|---|
| Visa / Mastercard / Apple Pay | `/ePaymentGateway/visamastercard/v2/transactions/paymentUrl` |
| AlipayHK | `/ePaymentGateway/alipayhk/transactions/paymentUrl` |
| AlipayCN | `/ePaymentGateway/alipaycn/transactions/paymentUrl` |
| WeChatPay | `/ePaymentGateway/wechatpay/transactions/paymentUrl` |

### Fetch Order Status (POST)
| Payment Method | Endpoint |
|---|---|
| Visa / Mastercard / Apple Pay | `/ePaymentGateway/visamastercard/v2/transactions/orderStatus` |
| AlipayHK | `/ePaymentGateway/alipayhk/transactions/orderStatus` |
| AlipayCN | `/ePaymentGateway/alipaycn/transactions/orderStatus` |
| WeChatPay | `/ePaymentGateway/wechatpay/transactions/orderStatus` |

### Transaction History (POST)
| Payment Method | Endpoint |
|---|---|
| Visa / Mastercard | `/ePaymentGateway/visamastercard/v2/transactions/queryHistory` |

### Card Tokenization (Visa/MC only)
- **Fetch Token:** `GET /ePaymentGateway/visamastercard/v2/tokens/{tokenId}`
- **Delete Token:** `DELETE /ePaymentGateway/visamastercard/v2/tokens/{tokenId}`

---

## 2. Authentication — JWT HS512

### JWT Structure
```
Header:  {"alg":"HS512","typ":"JWT"}
Payload: {"sub":"<App_ID>","iat":<epoch_timestamp>}
Secret:  base64_decode(App Secret)
Signature: HMACSHA512(base64UrlEncode(header) + "." + base64UrlEncode(payload), base64Decode(secret))
```

### HTTP Header
```
Authorization: Bearer <encoded_jwt>
Content-Type: application/json
```

**Important:** The App Secret is **base64-encoded 512-bit secret**. You must `base64_decode()` it before passing to HMAC.

---

## 3. Create Payment URL — Request Body

### Mandatory Fields (all payment methods)
| Field | Type | Max Length | Description |
|---|---|---|---|
| `currency` | String | 3 | ISO 4217 code (e.g. HKD) |
| `chargeTotal` | Number | — | Amount to charge |
| `merchantTransactionId` | String | 40 | Unique merchant txn ID |
| `customerEmail` | String | — | **MANDATORY as of v1.10** |
| `responseFailUrl` | String | — | URL for declined/failed payments |
| `responseSuccessUrl` | String | — | URL for successful payments |

### Optional Fields
| Field | Type | Max Length | Description |
|---|---|---|---|
| `customerId` | String | 32 | Merchant customer ID |
| `notificationUrl` | String | — | HTTPS webhook URL |
| `invoiceNumber` | String | 48 | Invoice number |
| `poNumber` | String | 50 | Purchase order number |
| `language` | String | — | Hosted page language (Visa/MC only): cs_CZ, da_DK, de_DE, el_GR, en_GB, en_US, es_ES, es_MX, fi_FI, fr_FR, hu_HU, it_IT, ja_JP, nb_NO, nl_NL, pl_PL, pt_BR, sk_SK, sl_SI, sr_RS, sv_SE, zh_CN, zh_TW. Default: en_US |
| `tokenId` | String | 50 | Pre-fill card data (Visa/MC) |

### WeChatPay Specific
| Field | Type | Description |
|---|---|---|
| `appId` | String | WeChatPay App ID (required for WeChatPay SDK integration) |

---

## 4. Response Format

### Success Response
```json
{
  "status": "0",
  "message": "Success",
  "timestamp": "2021-01-25T10:33:13.547+0000",
  "payload": {
    "orderId": "3c933e47-64d6-4bb9-8b01-8f4c51b758ba",
    "merchantTransactionId": "MER-TXN-2021012500001",
    "currency": "HKD",
    "chargeTotal": 110,
    "transactionType": "PAYMENT_URL",
    "transactionState": "TEMPLATE",
    "transactionTimestamp": "2021-01-25T10:33:13.540+0000",
    "submissionComponent": "API",
    "paymentUrl": "https://gateway.sandbox.tapngo.com.hk/ePaymentGateway/visamastercard/v2/app4247518988/html/payments/...",
    "paymentUrlExpireTimestamp": "2021-01-25T10:53:13.542+0000",
    "notificationUrl": "https://your-domain.com/wc-api/hktms-webhook/",
    "responseFailUrl": "...",
    "responseSuccessUrl": "...",
    "createTimestamp": "2021-01-25T10:33:13.542+0000",
    "updateTimestamp": "2021-01-25T10:33:13.542+0000"
  }
}
```

### Error Responses
- `status: "1"` — Validation error (e.g. missing fields)
- `status: "3"` — Unauthorized (bad JWT, wrong App ID/Secret)
- HTTP 400/403/500 — Various server-side errors

---

## 5. Transaction States

### Visa / Mastercard / Apple Pay
| State | Meaning | Action |
|---|---|---|
| `TEMPLATE` | Payment URL created, awaiting customer | Keep pending |
| `CAPTURED` | Payment authorized & captured | Mark complete |
| `DECLINED` | Declined by bank / acquirer | Mark failed |
| `FAILED` | Technical failure | Mark failed |

### AlipayHK / AlipayCN / WeChatPay
| State | Meaning | Action |
|---|---|---|
| `PENDING` | Awaiting customer action | Keep pending |
| `COMPLETED` | Payment successful | Mark complete |
| `FAILED` | Payment failed | Mark failed |

---

## 6. Webhook / Callback (Order Updated Event)

### Webhook Endpoint
```
POST /wc-api/hktms-webhook/
```

### Request Headers
```
x-hub-signature: sha512=<hex-digest>
Content-Type: application/json
```

### Signature Verification
```
expected_sig = hexEncode( HMACSHA512( raw_json_payload, base64Decode(app_secret) ) )
```
Compare `expected_sig` to the value after `sha512=` in the header.

### Payload Structure
Same as Fetch Order Status response — contains `payload.orderId`, `payload.transactions[]` with latest state.

---

## 7. Merchant Onboarding Steps

### Step 1 — Apply
1. Go to **https://www.hktmerchantservices.com/online-payment-gateway.html**
2. Fill in the **Online Payment Gateway Application Form**
3. Submit supporting documents:
   - Business Registration Certificate
   - Recent Bank Statement
   - Director/Owner HKID / Passport
   - Website URL and business description

### Step 2 — HKT Review
- HKT reviews your application (typically 1–2 weeks)
- They may request additional documents or a test transaction

### Step 3 — Receive Credentials
- HKT emails you an **API Key file** containing:
  - **App ID** (also called "Payment Method ID") — one per payment method
  - **App Secret** (also called "API Key") — base64-encoded 512-bit secret
- Separate App ID/Secret pairs for:
  - Visa / Mastercard
  - AlipayHK
  - AlipayCN
  - WeChatPay
  - Apple Pay (requires additional Apple Developer verification)

### Step 4 — Configure Webhook
1. Log in to **HKT Merchant Portal**
2. Add your **Notification URL** (e.g. `https://your-domain.com/wc-api/hktms-webhook/`)
3. Whitelist your domain and IP addresses
4. The webhook must respond HTTP 200 with JSON `{"status":"ok"}`

### Step 5 — Test in UAT
1. Set plugin to **Test Mode (UAT)**
2. Use test card numbers provided by HKT
3. Verify payment flow, webhook delivery, and order status updates

### Step 6 — Go Live
1. Switch plugin to **Production**
2. Replace UAT App ID/Secret with Production credentials
3. Update notification URL to production domain
4. Run a small live transaction to verify

---

## 8. Plugin Settings Quick Reference

| Setting | Description | Required |
|---|---|---|
| App ID | Payment Method ID from HKT | Yes |
| App Secret | API Key from HKT (base64-encoded) | Yes |
| Test Mode | Use UAT environment | Yes (for testing) |
| Notification URL | Auto-generated — copy to HKT Portal | Yes |
| Language | Payment page language (Visa/MC) | No |
| Success URL | Redirect after success | Auto |
| Fail URL | Redirect after failure | Auto |
| Auto Capture | Mark order complete on CAPTURED/COMPLETED | Yes |

---

## 9. Changelog (v1.10 vs previous versions)

| Version | Key Changes |
|---|---|
| v1.0–v1.5 | Initial release, card tokenization |
| v1.6 | Alipay/WeChat SDK appendix |
| v1.7 | Transaction History API |
| v1.8 | Apple Pay support, more languages |
| v1.9 | Field length limits, new acquirer integration |
| v1.9.1 | Google Pay removed |
| v1.9.2 | Revised acquirer integration |
| **v1.10** | **customerEmail is now mandatory; updated request/response schemas** |

---

## 10. File Structure

```
wp-content/plugins/hktms-gateway/
├── hktms-gateway.php          # Plugin bootstrap + onboarding docs
├── includes/
│   ├── class-gateway.php      # WC_Payment_Gateway implementation
│   ├── class-api.php          # JWT + REST client
│   └── class-webhook.php      # Async callback handler
├── assets/
│   ├── images/                # Payment method logos
│   └── js/
│       └── hktms-checkout.js  # Frontend checkout enhancements
└── languages/                 # .po / .mo files
```
