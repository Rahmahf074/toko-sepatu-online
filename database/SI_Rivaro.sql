CREATE DATABASE Rivaro_shoes;
USE Rivaro_shoes;

-- Tabel USER (pengguna biasa)
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    nama_depan VARCHAR(50),
    nama_belakang VARCHAR(50),
    email VARCHAR(255),
    no_hp VARCHAR(20),
    tgl_lahir DATE,
    jenis_kelamin VARCHAR(20),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Tabel ADMIN (khusus admin)
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_admin VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Produk
CREATE TABLE produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10, 2) NOT NULL,
    diskon DECIMAL(5,2) DEFAULT 0,
    ukuran VARCHAR(50),
    stok INT NOT NULL DEFAULT 0,
    brand VARCHAR(50),
    kategori ENUM('sport', 'casual', 'sneakers'),
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Pesanan
CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_harga DECIMAL(12, 2) NOT NULL,
    status ENUM('pending', 'paid', 'shipped', 'completed', 'cancelled') DEFAULT 'pending',
    alamat_pengiriman TEXT,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);
CREATE TABLE alamat_pengiriman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_penerima VARCHAR(100),
    alamat_lengkap TEXT NOT NULL,
    kota VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    no_hp VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

-- Tabel Keranjang
CREATE TABLE keranjang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    produk_id INT,
    jumlah INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES user(id),
    FOREIGN KEY (produk_id) REFERENCES produk(id)
);
ALTER TABLE keranjang
ADD COLUMN ukuran VARCHAR(10);

-- Tabel Pesanan Detail
CREATE TABLE pesanan_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pesanan_id INT,
    produk_id INT,
    jumlah INT DEFAULT 1,
    harga DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id),
    FOREIGN KEY (produk_id) REFERENCES produk(id)
);

