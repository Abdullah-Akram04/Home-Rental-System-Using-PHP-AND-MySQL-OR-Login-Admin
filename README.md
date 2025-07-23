# 🔐 Cybersecurity Internship Project – Secure Web Application  
**Intern Name:** Abdullah Akram  
**Intern ID:** DHC-3606  
**Project Duration:** Week 1–6  
**Final Submission:** July 24, 2025  
**Application Stack:** PHP, MySQL, HTML, Node.js (test cases), Kali Linux, XAMPP  

---

## 📌 Overview

This project demonstrates the application of cybersecurity best practices on a vulnerable web-based **User Management System**. The internship focused on discovering vulnerabilities, applying modern security mechanisms, and performing full-stack auditing and hardening using industry tools and methodologies.  

---

## 📅 Weekly Breakdown

---

### ✅ Week 1 – Vulnerability Assessment  

- **Explored a mock user management system** (GitHub-sourced).
- Conducted **manual testing** and **automated scanning** using:
  - OWASP ZAP (Auto Vulnerability Scanner)
  - Browser DevTools for **XSS testing**
  - SQL Injection strings (e.g., `admin' OR '1'='1`)
- **Findings:**
  - Stored and Reflected XSS in multiple forms.
  - SQL Injection on login form.
  - No input validation or HTTPS usage.

---

### 🔧 Week 2 – Implementing Security Measures  

- **Input Sanitization:**
  - Used `validator` npm package for validating user email, name, etc.
- **Password Hashing:**
  - Applied `bcrypt` for strong password hashing and salting.
- **Token-based Authentication:**
  - Integrated `jsonwebtoken (JWT)` for protected routes.
- **Secured Headers:**
  - Implemented `Helmet.js` to set HTTP security headers.

---

### 🛡️ Week 3 – Penetration Testing & Logging  

- Ran basic penetration tests using **Nmap** and browser simulation.
- Integrated **Winston** logger to record suspicious activity and errors.
- Created a **Best Practices Checklist**:
  - HTTPS only
  - Strict input validation
  - Avoid inline JS
  - Secure cookies with `HttpOnly` and `Secure` flags

---

### 🧠 Week 4 – Advanced Threat Detection  

- Deployed **Fail2Ban** for intrusion monitoring (simulated setup on Windows).
- Enabled rate limiting with `express-rate-limit`.
- Configured **CORS** policies and applied basic **OAuth token security**.
- Implemented:
  - **Content Security Policy (CSP)**
  - **Strict Transport Security (HSTS)**

---

### 🧪 Week 5 – Ethical Hacking & Exploitation  

- Conducted recon using **Kali Linux** + **Burp Suite**.
- Discovered:
  - SQLi vulnerability in login POST
  - CSRF vulnerability in user update form
- Used **SQLMap** for automated SQLi exploitation.
- Applied:
  - **Prepared statements** in PHP backend (PDO)
  - **CSRF token-based protection** (`$_SESSION['csrf_token']`)
- Updated `login.php`, `register.php`, and `config.php` accordingly.

---

### ✅ Week 6 – Final Audit & Deployment  

- Security Audit Tools:
  - **OWASP ZAP** – Confirmed no injection or XSS left
  - **Nikto** – Checked server misconfigurations
  - **Lynis** – Simulated Unix-level hardening analysis
- **Compliant with OWASP Top 10**: Proper handling of A1 to A10 vulnerabilities.
- Applied:
  - **Automatic security updates**
  - Docker image scanning (simulated)
- Final Pentest via **Metasploit** and Burp Suite – No exploitable points.
- Delivered:
  - Final audit reports
  - Complete secured PHP app
  - Video walkthrough with voice explanation

---

## 🔒 Code Snippet Highlights

### ✅ Prepared Statement in `login.php`

```php
$stmt = $connect->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
