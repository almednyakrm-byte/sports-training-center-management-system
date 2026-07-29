# نظام إدارة مراكز تدريب رياضي مع حجز مواعيد ودورات
==========================

## Overview & Project Purpose
---------------------------

نظام إدارة مراكز تدريب رياضي مع حجز مواعيد ودورات هو تطبيق يهدف إلى تسهيل إدارة مراكز التدريب الرياضي من خلال حجز مواعيد ودورات، مما يسهل على الأفراد الحصول على الخدمات الرياضية المناسبة لهم.

### Project Purpose
- تسهيل إدارة مراكز التدريب الرياضي
- حجز مواعيد ودورات الرياضية
- تسهيل الحصول على الخدمات الرياضية

## Project Structure Mapping
---------------------------

### Directory Structure

.
├── docker-compose.yml
├── docker
│   ├── Dockerfile
│   └── ...
├── src
│   ├── app
│   │   ├── config
│   │   ├── controllers
│   │   ├── models
│   │   ├── routes
│   │   └── ...
│   └── ...
├── tests
│   ├── unit
│   │   └── ...
│   └── integration
│       └── ...
└── README.md


### Module Description

- `app`: تطبيق النظام
- `docker`: ملفات docker
- `tests`: اختبارات الوحدة والدمج

## Step-by-Step Instructions for Running the Environment
---------------------------------------------------------

### Using Docker-Compose
1. تأكد من أن docker-compose مثبت على جهازك.
2. افتح المجلد الرئيسي للنظام.
3. استخدم الأمر التالي لتشغيل النظام:
bash
docker-compose up

4. افتح متصفحك واكتب `http://localhost:8080` لفتح النظام.

### Using Docker-Compose with Detached Mode
1. استخدم الأمر التالي لتشغيل النظام في وضع المنفذ:
bash
docker-compose up -d

2. افتح متصفحك واكتب `http://localhost:8080` لفتح النظام.

## Listing of Modules, Tables, and Roles
--------------------------------------

### Modules

- `users`: إدارة المستخدمين
- `trainings`: إدارة التدريبات
- `appointments`: إدارة المواعيد

### Tables

- `users`: معلومات المستخدمين
- `trainings`: معلومات التدريبات
- `appointments`: معلومات المواعيد

### Roles

- `admin`: إدارة النظام
- `trainer`: إدارة التدريبات
- `user`: إدارة حساب المستخدم

## Contact Developer Details
---------------------------

### Developer Name
- محمد أحمد

### Email
- mohamedahmed@example.com

### Phone
- 0123456789

### LinkedIn
- linkedin.com/in/mohamedahmed

### GitHub
- github.com/mohamedahmed

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
