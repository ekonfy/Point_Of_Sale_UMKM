# 🛒 Simple POS (Point of Sale) System

A lightweight, web-based Point of Sale application built with **PHP Native (REST API)** and **MySQL**. This project demonstrates a decoupled architecture where the Frontend (Single Page Application logic) interacts with the Backend via JSON APIs.

## 🚀 Key Features

* **Secure Authentication**: Login system using PHP Sessions and `bcrypt` password hashing.
* **Transactional Integrity**: Uses MySQL **Database Transactions** (`beginTransaction` / `commit`) to ensure stock is only deducted when a transaction is fully successful.
* **Real-time Cart**: Interactive shopping cart built with Vanilla JavaScript (no heavy frontend frameworks).
* **Thermal Printing Support**: Auto-formatted receipts for 58mm/80mm thermal printers using CSS `@media print`.
* **Sales Dashboard**: Real-time reporting of daily revenue, total transactions, and top-selling products.

## 🛠️ Tech Stack

* **Backend**: PHP 8.x (PDO Driver), MySQL/MariaDB.
* **Frontend**: HTML5, Bootstrap 5 CSS, Vanilla JavaScript (Fetch API).
* **Architecture**: RESTful API.

## 📂 Project Structure

```text
pos-app/
├── api/                  # Backend Logic (REST API Endpoints)
│   ├── checkout.php      # Transaction processing & stock updates
│   ├── login.php         # User authentication
│   ├── products.php      # Product fetching
│   └── reports.php       # Dashboard analytics
├── config.php            # Database Connection
├── database.sql          # Database Schema & Dummy Data
├── index.php             # Main POS Interface (Frontend)
├── dashboard.php         # Sales Report Interface
├── login.php             # Login Page
└── README.md             # Project Documentation
