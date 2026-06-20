# 🏙️ Estate Sphere - Real Estate Management System

A dynamic, commercial-ready real estate web application built to bridge the gap between property buyers, investors, and real estate agents. Developed for the Web Application Development (WAD) Evaluation 2 module.

## 👨‍💻 Developer
* **MFM Shahid** - Full-Stack Developer *(Frontend UI/UX, Backend Architecture, Database Security, Search Algorithms, and Core Business Logic)*

## 🚀 Key Features
* **Role-Based Access Control (RBAC):** Secure 3-tier hierarchy (Client, Admin, Super Admin).
* **Live Currency Switcher:** Global session-based toggle between LKR and USD with dynamic exchange rate calculations.
* **Smart Search Engine:** Dynamic SQL algorithm filtering properties by location, bedrooms, and price constraints.
* **Platform Monetization:** Automated 0.5% platform processing fee calculation integrated into the checkout pipeline.
* **Enterprise Security:** Full protection against SQL Injection using strict data sanitization and prepared statement logic.
* **Ghost Row Defense:** Aggressive UI rendering logic to prevent orphaned database records from breaking the public interface.

## 🛠️ Technology Stack
* **Frontend:** HTML5, CSS3, Vanilla JavaScript
* **Backend:** PHP (Procedural)
* **Database:** MySQL (Relational)
* **Environment:** XAMPP Server

## ⚙️ Installation & Setup
1. Clone this repository to your local server directory (`C:\xampp\htdocs\`).
2. Start Apache and MySQL via the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin` and create a database named `estate_sphere`.
4. Import the provided `estate_sphere.sql` file into the new database.
5. Launch the application at `http://localhost/estate_sphere`.
