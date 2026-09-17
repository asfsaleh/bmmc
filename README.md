# বাংলাদেশ মার্চেন্ট মেরিনার্স কমিউনিটি (BMMC)
## Bangladesh Merchant Mariners Community — Official Universal Platform & Blood Portal

একটি সম্পূর্ণ অরাজনৈতিক, অলাভজনক এবং শতভাগ স্বেচ্ছাসেবী মেরিটাইম ওয়েব প্ল্যাটফর্ম।

[![GitHub Repo](https://img.shields.io/badge/GitHub-Public_Repository-brightgreen.svg)](https://github.com/asfsaleh/bmmc)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![Live Site](https://img.shields.io/badge/Live_Site-bmmc.skillsetup.org-cyan.svg)](https://bmmc.skillsetup.org)

---

## 📦 কোড ডাউনলোড ও ক্লোন করার লিংক (Download & Clone)

যেকোনো ডেভেলপার বা ব্যবহারকারী এই কোড সহজেই ডাউনলোড বা ক্লোন করতে পারেন:

### ১. সরাসরি ১-ক্লিকে জিপ (ZIP) ডাউনলোড:
👉 **[⬇️ Download Source Code (ZIP)](https://github.com/asfsaleh/bmmc/archive/refs/heads/main.zip)**

### ২. গিট ক্লোন (Git Clone):
```bash
git clone https://github.com/asfsaleh/bmmc.git
```

---

## ✨ প্রধান প্রধান ফিচারসমূহ (Platform Highlights)
- **সার্বজনীন ২১টি মেরিটাইম সেবা হাব:** ৫টি স্তম্ভে বিভক্ত (স্বাস্থ্যসেবা, মেরিটাইম ক্যারিয়ার, আইনি অধিকার, সমাজকল্যাণ, ডিজিটাল ইউটিলিটি)।
- **রক্তদান নেটওয়ার্ক (Live Portal):** রোগী ও ডোনারদের সরাসরি সংযোগ, স্বয়ংক্রিয় এলাকাভিত্তিক ম্যাচিং ও ৪ মাসের বিশ্রাম সুরক্ষা।
- **স্বয়ংক্রিয় ডুয়াল এনরোলমেন্ট:** ভলান্টিয়ার রেজিস্ট্রেশনের সময় 'Agree to donate blood' নির্বাচন করলে একই সাথে ডোনার নেটওয়ার্কেও যুক্ত হয়।
- **ডে ও নাইট থিম (Day & Night Mode):** লাক্সারিয়াস মেরিটাইম ডে থিম, ১-ক্লিক সুইচ টগল বাটন এবং **GMT+6 (বাংলাদেশ মান সময়)** অনুযায়ী সকাল-সন্ধ্যা অটো থিম বদল।
- **মোবাইল ১.৫ কলাম হরিজন্টাল স্ক্রোল:** মোবাইলে সেবা কার্ডগুলোর মসৃণ টাচ সোয়াইপ অভিজ্ঞতা।
- **মেকানিক্যাল মেইনটেন্যান্স মোড (`/m`):** অ্যানিমেটেড গিয়ার ও স্পার্ক এফেক্ট সহ পূর্ণাঙ্গ মেইনটেন্যান্স কন্ট্রোল।

---

## 💻 লোকাল কম্পিউটারে রান করার নিয়ম (Local Quick Start)
কোনো জটিল সেটআপ ছাড়াই পিএইচপি-র বিল্ট-ইন সার্ভার দিয়ে তৎক্ষণাৎ টেস্ট করা যায়:
```bash
cd bmmc
php -S localhost:8080
```
ব্রাউজারে ভিজিট করুন: `http://localhost:8080`

---

## 🚀 লাইভ সার্ভার ডেপ্লয়মেন্ট গাইড (Live Deployment Guide)

### ধাপ ১: cPanel-এ ডাটাবেস তৈরি
1. আপনার cPanel-এ লগইন করে **MySQL Database Wizard**-এ যান।
2. একটি নতুন ডাটাবেস এবং ইউজার তৈরি করুন (যেমন: `username_bmmc` ও `username_dbuser`)।
3. ইউজারের সাথে ডাটাবেস লিঙ্ক করে **ALL PRIVILEGES** দিন।
4. **phpMyAdmin**-এ যান এবং `database/schema.sql` ফাইলটি ইম্পোর্ট (Import) করুন।

### ধাপ ২: Git রিপোজিটরি ক্লোন / ডিপ্লয়
cPanel-এর **Git Version Control**-এ গিয়ে আপনার এই রেপোটি ক্লোন করুন:
- **Repository URL**: `https://github.com/asfsaleh/bmmc.git`
- **Deployment**: `.cpanel.yml` কনফিগার করা আছে, যা সরাসরি আপনার সাইট ফোল্ডারে ফাইল কপি করে দেবে।

### ধাপ ৩: Environment (.env) কনফিগারেশন
1. cPanel File Manager-এ যান।
2. `.env.example` ফাইলটি কপি করে `.env` নাম দিন।
3. আপনার cPanel ডাটাবেসের সঠিক নাম, ইউজার ও পাসওয়ার্ড বসান:
```ini
APP_ENV=production
APP_DOMAIN=bmmc.skillsetup.org
APP_URL=https://bmmc.skillsetup.org

DB_HOST=localhost
DB_PORT=3306
DB_NAME=your_cpanel_dbname
DB_USER=your_cpanel_dbuser
DB_PASS=your_cpanel_password

MAIL_SIMULATE=false
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_gmail_app_password
```

> ⚠️ **গুরুত্বপূর্ণ:** `.env` ফাইলটি `.gitignore`-এ অন্তর্ভুক্ত রয়েছে। তাই পরবর্তীতে যখনই গিট আপডেট বা `git pull` করবেন, আপনার লাইভ ডাটাবেসের কনফিগারেশন এবং ডাটাবেসের ডাটা **শতভাগ সুরক্ষিত ও অপরিবর্তিত থাকবে**।

---

## 🔄 ডাটাবেস ঠিক রেখে সাইট আপডেট করার নিয়ম (Updating Without Affecting Database)

যখন আপনি ভবিষ্যতে কোডে নতুন ফিচার বা ডিজাইন আপডেট করবেন:
1. লোকাল পিসিতে কোড এডিট করে গিটহাবে পুশ করুন:
   ```bash
   git add .
   git commit -m "Update new features"
   git push origin main
   ```
2. cPanel-এর Git Version Control-এ গিয়ে **"Pull or Deploy"** বাটনে ক্লিক করুন (অথবা SSH টার্মিনালে `git pull origin main` চালান)।
3. **ডাটাবেস নিয়ে কী হবে?**
   - লাইভ ডাটাবেসের কোনো টেবিল ড্রপ হবে না বা ডাটা মুছে যাবে না।
   - যদি নতুন কোনো টেবিল যুক্ত করা হয়, তবে অ্যাডমিন হিসেবে লগইন থাকা অবস্থায় ব্রাউজারে `https://bmmc.skillsetup.org/database/migrate.php` ভিজিট করুন অথবা টার্মিনালে `php database/migrate.php` রান করুন। এটি নিরাপদে নতুন স্কিমা আপডেট করবে কিন্তু বর্তমান রক্তদাতা ও রোগীদের পূর্বের সব ডাটা অবিকৃত রাখবে!

---

## ⏰ ক্রন জব সেটআপ (cPanel Cron Jobs)

রক্তদাতাদের ৪ মাসের বিশ্রাম বিরতি শেষ হলে স্বয়ংক্রিয়ভাবে প্রস্তুত (Available) করতে cPanel **Cron Jobs**-এ নিচের কমান্ডটি প্রতিদিন সকাল ৬টায় রান করার জন্য দিন:
```bash
0 6 * * * /usr/bin/php /home/username/public_html/cron/check_resting_period.php >> /home/username/public_html/cron/cron.log 2>&1
```

---

## 🔑 ডিফল্ট টেস্ট লগইন
- **অ্যাডমিন প্যানেল**: `/admin/dashboard.php`
- **ইমেইল**: `admin@bmmc.org`
- **পাসওয়ার্ড**: `admin123`
