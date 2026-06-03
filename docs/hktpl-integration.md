# HKTPL Payment Integration — Reference (v1.0.62)

Extracted and verified from `HKTPL Payment Integration Specification_v1.0.62 2.pdf`

---

## 1. API Endpoints

### Base URLs
- **UAT / Sandbox:** `https://gateway.sandbox.tapngo.com.hk`
- **Production:** `https://gateway.tapngo.com.hk`

### Do Single Payment (Client-to-Server)
| Method | Endpoint | Description |
|---|---|---|
| POST | `/web/payments` | Initiates payment. Desktop → QR code page. Mobile → App launch |

### Server-to-Server APIs
| Function | Endpoint | Auth |
|---|---|---|
| Do Recurrent Payment | `/paymentApi/payment/recurrent` | Signed (appId + recurrentToken + payload + timestamp + sign) |
| Invalidate Recurrent Token | `/paymentApi/payment/recurrent/token/invalidation` | Signed |
| Query Payment Status | `/paymentApi/payment/status` | Signed (appId + merTradeNo + timestamp + sign) |
| Register Access Token | `/paymentApi/register/accessToken` | Signed (appId + timestamp + sign) |
| Query Transaction History | `/paymentApi/query/transaction/history` | accessToken + signed |
| Generate HKQR Bill | `/paymentApi/create/hkqr` | accessToken + signed |
| Generate HKQR Sales | `/paymentApi/create/hkqr/sales` | accessToken + signed |

---

## 2. Authentication

### 2.1 Payload Encryption (RSA)
- **Algorithm:** RSA/ECB/OAEPWithSHA-1AndMGF1Padding
- **Public Key:** Base64-encoded X.509, 4096-bit (provided by HKT)
- **Process:** JSON payload → RSA encrypt → Base64 encode → send as `payload` param

### 2.2 Request Signature (HMAC-SHA512)
1. Sort all parameters alphabetically (`ksort`)
2. Exclude `sign` parameter and any null/empty values
3. Join as `key1=value1&key2=value2`
4. HMAC-SHA512 with API Key (raw binary output)
5. Base64-encode (NOT base64url)

### 2.3 Response Signature Verification
Same algorithm — response contains `content` object + `sign` field. Sign the JSON string of `content`.

### 2.4 Callback Signature Verification (Return URL / Webhook)
HKTPL POSTs form data to callback URL:
```
merTradeNo=...&tradeNo=...&tradeStatus=S&msg=SUCCESS&resultCode=000&sign=...
```

Verification steps:
1. Arrange all POST params alphabetically
2. Exclude `sign` and null/empty params
3. Join as query string: `key1=value1&key2=value2`
4. HMAC-SHA512 with API Key
5. Base64-encode
6. Compare with received `sign`

---

## 3. Payment Information (paymentInfo / payload)

### Mandatory Fields (all payment types)
| Field | Type | Max Length | Description |
|---|---|---|---|
| `merTradeNo` | String | 64 | Unique merchant transaction ID (alphanumeric) |
| `currency` | String | 3 | ISO 4217 code (currently only **HKD**) |
| `totalPrice` | Number | 8,2 | Price with exactly 2 decimal places |
| `returnUrl` | String | 256 | URL for HKTPL to redirect after payment |
| `lang` | String | 2 | Language: `en` or `zh` |

### Optional Fields
| Field | Type | Max Length | Description |
|---|---|---|---|
| `remark` | String | 256 | Remarks displayed in Tap & Go wallet |
| `paymentNetwork` | String | 50 | `ALL` (default), `FPS`, `Tapngo` |

### Single Payment Example (paymentType = S)
```json
{
  "totalPrice": "500.00",
  "currency": "HKD",
  "merTradeNo": "12345678901234567890123456789012345678901234567890",
  "returnUrl": "https://merchant.domain.com/wc-api/hktpl-return/",
  "remark": "This user has special request",
  "lang": "en"
}
```

### Recurrent Payment (paymentType = R)
```json
{
  "totalPrice": "0.00",
  "currency": "HKD",
  "merTradeNo": "REC-TOKEN-001",
  "returnUrl": "https://merchant.domain.com/wc-api/hktpl-return/",
  "lang": "en"
}
```

---

## 4. Request Format (Do Single Payment)

### HTTP Headers
```
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
Accept: text/html,application/json
```

### Form Parameters
| Parameter | Mandatory | Description |
|---|---|---|
| `appId` | Y | Application ID |
| `merTradeNo` | Y | Unique merchant transaction ID |
| `paymentType` | Y | `S` = Single, `R` = Recurrent Token, `SR` = Both |
| `payload` | Y | RSA-encrypted paymentInfo (Base64) |
| `extras` | N | RSA-encrypted extra data: notifyUrl, custMail, custPhone |
| `transactionType` | N | `CR` = Purchase (default), `DB` = Top-up, `DC` = Both |
| `sign` | Y | HMAC-SHA512 signature of all above fields |

---

## 5. Response Format

### Success (Do Single Payment)
Returns **HTML page** (Content-Type: text/html):
- Desktop browser → QR code display page
- Mobile browser → Auto-redirect to Tap & Go app

### Error (JSON)
```json
{
  "content": {
    "resultCode": "400",
    "chiMessage": "不正確請求。 (Ref: 400)",
    "engMessage": "Bad request. (Ref: 400)",
    "internal": "Bad request. (Ref: 400)"
  }
}
```

### Server-to-Server Response (Query Status, Recurrent Payment, etc.)
```json
{
  "content": {
    "resultCode": "0",
    "chiMessage": "請求完成",
    "engMessage": "Request Success",
    "internal": "Request Success",
    "payload": {
      "merTradeNo": "123",
      "tradeNo": "12345678901234",
      "tradeStatus": "TRADE_FINISHED"
    }
  },
  "sign": "150411BaJ123JHGGggB"
}
```

---

## 6. Trade Status Values

### Query Payment Status / API Response
| Status | Meaning | Action |
|---|---|---|
| `TRADE_FINISHED` | Payment success | Mark order complete |
| `TRADE_CLOSED` | Payment cancelled / failed | Mark order failed |
| `WAIT_TO_PAY` | Payment is processing | Keep pending |

### Callback (Return URL / Webhook) — tradeStatus field
| Status | Meaning | Action |
|---|---|---|
| `S` | Success | Mark order complete |
| `F` | Failed | Mark order failed |
| `C` | Cancelled | Mark order failed |
| `U` | Unknown / processing | Keep pending |

### Result Codes
| Code | Description |
|---|---|
| `0` / `000` | Success |
| `400` | Bad request |
| `403` | Forbidden (IP not registered) |
| `461` | Invalid timestamp |
| `462` | Invalid signature |
| `463` | Invalid IP address |
| `489` | Cannot obtain apiKey |
| `498` / `499` | Unexpected errors |

---

## 7. Webhook / Callback

### Endpoint (notifyUrl)
```
POST /wc-api/hktpl-webhook/
```

HKTPL also sends identical callback to `returnUrl`.

### POST Parameters
| Parameter | Description |
|---|---|
| `merTradeNo` | Merchant trade number |
| `tradeNo` | Tap & Go transaction ID |
| `tradeStatus` | `S`, `F`, `C`, `U` |
| `msg` | Message (e.g. SUCCESS) |
| `resultCode` | Result code (e.g. 000) |
| `sign` | HMAC-SHA512 signature |

### Signature Verification
```php
$sign_params = [
    'merTradeNo'  => $_POST['merTradeNo'],
    'tradeNo'     => $_POST['tradeNo'],
    'tradeStatus' => $_POST['tradeStatus'],
    'msg'         => $_POST['msg'],
    'resultCode'  => $_POST['resultCode'],
];
// Remove null/empty values
$sign_params = array_filter($sign_params);
$expected = base64_encode(hash_hmac('sha512', implode('&', $sign_params), $api_key, true));
// Compare with $_POST['sign']
```

### Response Requirements
- Must respond HTTP 200
- Can return any body text (e.g. "OK")
- HKTPL may retry if non-200 response

---

## 8. Merchant Onboarding Steps

### Step 1 — Apply
1. Visit https://www.tapngo.com.hk/merchants or contact HKT Merchant Services
2. Submit: Business Registration, Bank Statement, Director ID, Website URL
3. For FPS: also register with HKICL (Hong Kong Interbank Clearing Limited)

### Step 2 — Receive Credentials
- HKT provides per application:
  - **App ID** (e.g. 70136705)
  - **API Key** (for HMAC-SHA512 signing)
  - **RSA Public Key** (4096-bit, Base64 X.509) — for encrypting payloads

### Step 3 — Generate Key Pair
- Merchant generates RSA 4096-bit private key
- Submits public key to HKT
- Keeps private key secure (used for decrypting if needed)

### Step 4 — Configure IP Whitelist
- Log in to HKT Merchant Portal
- Whitelist server IP addresses that will call HKTPL APIs
- Add callback URLs (notifyUrl and returnUrl)

### Step 5 — Test in UAT
1. Set plugin to **Test Mode**
2. Use sandbox base URL: `gateway.sandbox.tapngo.com.hk`
3. Test single payment flow
4. Verify webhook/callback delivery
5. Check order status updates

### Step 6 — Go Live
1. Switch plugin to **Production**
2. Replace sandbox App ID/API Key with production credentials
3. Update callback URLs to production domain
4. Whitelist production server IPs
5. Run small test transaction

---

## 9. Plugin Settings Quick Reference

| Setting | Description | Required |
|---|---|---|
| App ID | HKTPL Application ID | Yes |
| API Key | HMAC signing secret | Yes |
| RSA Public Key | 4096-bit X.509 for payload encryption | Yes |
| Test Mode | Use sandbox environment | Yes (for testing) |
| Payment Network | ALL / FPS / Tapngo | No (default ALL) |
| Language | `en` or `zh` | No (default en) |
| Auto Capture | Auto-complete on success | Yes |

---

## 10. File Structure

```
wp-content/plugins/hktpl-gateway/
├── hktpl-gateway.php          # Plugin bootstrap + onboarding docs
├── includes/
│   ├── class-gateway.php      # WC_Payment_Gateway implementation
│   ├── class-api.php          # HKTPL API client
│   ├── class-crypto.php       # RSA + HMAC-SHA512 + Base64
│   └── class-webhook.php      # Callback handler
├── assets/
│   ├── images/                # tapngo.png, fps.png
│   └── js/
│       └── hktpl-checkout.js  # Frontend enhancements
└── languages/                 # .po / .mo files
```

---

## 11. Known Limitations

- **No public refund API** — Refunds must be processed via HKT Merchant Portal
- **Currency: HKD only** — Other currencies not supported
- **IP whitelisting required** — Server IP must be registered with HKT
- **Access Token valid 30 days** — Must re-register for transaction history APIs
- **Desktop vs Mobile detection** — HKTPL server decides based on User-Agent
