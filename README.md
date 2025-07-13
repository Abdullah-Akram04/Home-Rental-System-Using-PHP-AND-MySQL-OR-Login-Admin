## ✅ Overview

This week focused on enhancing the security of a PHP-based user login system by implementing protection mechanisms such as API key authentication, rate limiting, and security headers to mitigate common web threats like brute-force attacks and unauthorized API access.

---

## 🔐 Implemented Features

### 1. API Security Hardening
- Added custom **API Key** validation via `X-API-KEY` HTTP header.
- API Key securely defined in `config.php` as `API_SECRET_KEY`.
- Unauthorized requests are rejected with `401 Unauthorized`.

### 2. Rate Limiting Protection
- Created IP-based rate limiting using JSON files.
- Allowed max **5 login attempts per minute**.
- Exceeding limit triggers `429 Too Many Requests` error.
- Rate limiter stores data inside `/auth/rate-limit/`.

### 3. CORS Policy Configuration
- Configured `Access-Control-Allow-Origin` header.
- Prevented unauthorized domain access to API.

### 4. Security Headers Implemented
- Applied `Content-Security-Policy` (CSP) to block script injections.
- Enabled `Strict-Transport-Security (HSTS)` to enforce HTTPS (for production).
- Planning to include `X-Frame-Options` and `X-Content-Type-Options` in future.

---

## 🧪 Testing & Results

- ✅ Valid login → Redirects to dashboard securely.
- ❌ Invalid API Key → Triggers `401 Unauthorized`.
- ❌ 6+ login attempts within 60s → Triggers `429 Too Many Requests`.
- ✅ Rate-limiting resets after 1 minute.
- ✅ CORS blocks cross-origin access from unknown domains.

---



---

## 📝 Notes

- JWT generation logic is included but commented for future use.
- Fail2Ban and OSSEC reserved for Linux-based deployment (optional).
- Ideal for local testing with XAMPP or live deployment with HTTPS.