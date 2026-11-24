# Online Shoe Store (Rivaro Shoes)

An online shoe store web application built with native PHP and MySQL. Developed as a final project for the Advanced Database Project (_Proyek Basis Data Lanjut_) course at Universitas Bengkulu (Group 5), where I served as the team lead.

**Demo:** [FILL: https://rivaro-shoes.freehosting.dev/sepatu_online/index.php]

![Store home view](docs/home.png)

## Features

### Customer

- User registration and login
- Product catalog and detail pages
- Shopping cart and checkout
- Order history
- Live chat with admin

### Admin

- Admin dashboard
- Product management (add/manage products)
- Order and user management
- Customer chat support

## Tech Stack

- PHP (Native)
- MySQL
- HTML, CSS

## Running Locally

Prerequisites: XAMPP or Laragon with Apache and MySQL running.

1. Clone or copy this project folder into `htdocs` (e.g., `C:\xampp\htdocs\toko-sepatu-online`).
2. Open `http://localhost/phpmyadmin`, create a new database named `rivaro_shoes`, and import `database/rivaro_shoes.sql`.
3. Copy `config/koneksi.example.php` to `config/koneksi.php`, then configure your database host, username, password, and database name.
4. Open `http://localhost/toko-sepatu-online/sepatu_online/index.php` in your browser.

## Demo Accounts

| Role     | Username                  | Password                  |
| :------- | :------------------------ | :------------------------ |
| Admin    | [FILL: admin_rivaro]      | [FILL: admin123]          |
| Customer | [FILL: customer username] | [FILL: customer password] |

## Folder Structure

├── admin/ Dashboard, products, orders, users, chat
├── config/ Database connection
├── css/ Stylesheets (style.css)
├── database/ SQL database dump (rivaro_shoes.sql)
├── Gambar/ Image assets
├── proses/ Backend logic (auth, cart, orders, chat)
└── sepatu_online/ Customer pages (index, detail, cart, checkout, login)

## Team (Group 5 - Class B2)

| Name                      | Student ID | Role           |
| :------------------------ | :--------- | :------------- |
| **Rahma Hidayati Fitrah** | G1A023074  | **Team Lead**, |
| Reyhan Maulana Ibrahim    | G1A023046  | [FILL: Team]   |
| Robi Septian Subhan       | G1A023060  | [FILL: Team]   |
| M. Ihtifanul Montaghib    | G1A023094  | [FILL: Team]   |

## Status

Coursework project (2025). Built for academic purposes and not intended for production use.
