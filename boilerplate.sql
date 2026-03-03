-- --------------------------------------------------------
-- Online CCTV Management System
-- Boilerplate Database Schema
-- Created: 2026-03-01
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- ============================================================
-- Table: cv_identitas
-- App identity / settings
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_identitas` (
  `id_profile` int NOT NULL AUTO_INCREMENT,
  `apps_name` varchar(225) NOT NULL DEFAULT 'Online CCTV System',
  `apps_version` varchar(50) NOT NULL DEFAULT '1.0.0',
  `agency` varchar(225) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `telephon` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(225) DEFAULT NULL,
  `logo` varchar(225) DEFAULT 'assets/media/logos/logo.svg',
  `icon` varchar(225) DEFAULT 'assets/media/icons/icon.png',
  `footer` varchar(500) DEFAULT '© Online CCTV Management System',
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cv_identitas` (`id_profile`, `apps_name`, `apps_version`, `agency`, `address`, `city`, `telephon`, `email`, `website`, `logo`, `icon`, `footer`) VALUES
(1, 'Online CCTV System', '1.0.0', 'PT. Security Solutions', 'Jl. Teknologi No. 1', 'Jakarta', '021-1234567', 'admin@cctv.com', 'https://cctv.example.com', 'assets/media/logos/AEROVIEW_APPS.svg', 'assets/media/icons/AEROVIEW_APPS.png', '© Online CCTV Management System 2026');


-- ============================================================
-- Table: cv_hak_akses
-- Roles with module access + cctv group access permission
-- cctv_group_akses: JSON array of allowed cv_lokasi_group IDs
--   NULL or empty array = access ALL groups
--   [1,2,3] = only those groups
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_hak_akses` (
  `id_hak_akses` int NOT NULL AUTO_INCREMENT,
  `nama_hak_akses` varchar(225) NOT NULL,
  `deskripsi` varchar(500) DEFAULT NULL,
  `modul_akses` text NOT NULL,
  `parent_modul_akses` text DEFAULT NULL,
  `cctv_group_akses` text DEFAULT NULL COMMENT 'JSON array of allowed group IDs, NULL = all groups',
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hak_akses`),
  UNIQUE KEY `nama_hak_akses` (`nama_hak_akses`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cv_hak_akses` (`id_hak_akses`, `nama_hak_akses`, `deskripsi`, `modul_akses`, `parent_modul_akses`, `cctv_group_akses`) VALUES
(1, 'superuser', 'Super Administrator - Akses penuh ke semua fitur dan semua grup CCTV',
'{"modul":["daftarPengguna","tambahPengguna","updatePengguna","hapusPengguna","daftarHakAkses","tambahHakAkses","updateHakAkses","hapusHakAkses","daftarEzvizAkun","tambahEzvizAkun","updateEzvizAkun","hapusEzvizAkun","daftarGrupLokasi","tambahGrupLokasi","updateGrupLokasi","hapusGrupLokasi","detailGrupLokasi","daftarLokasi","tambahLokasi","updateLokasi","hapusLokasi","detailLokasi","daftarCCTV","tambahCCTV","updateCCTV","hapusCCTV","detailCCTV","streamCCTV","pengaturanSistem","logAktivitas"]}',
'{"parent_modul":["Dashboard","GrupLokasi","Lokasi","CCTV","MasterData","Pengaturan"]}',
NULL),

(2, 'admin', 'Administrator - Akses ke semua grup CCTV namun tidak bisa kelola hak akses',
'{"modul":["daftarPengguna","daftarEzvizAkun","tambahEzvizAkun","updateEzvizAkun","daftarGrupLokasi","tambahGrupLokasi","updateGrupLokasi","detailGrupLokasi","daftarLokasi","tambahLokasi","updateLokasi","detailLokasi","daftarCCTV","tambahCCTV","updateCCTV","hapusCCTV","detailCCTV","streamCCTV","logAktivitas"]}',
'{"parent_modul":["Dashboard","GrupLokasi","Lokasi","CCTV","MasterData","Pengaturan"]}',
NULL),

(3, 'operator', 'Operator - Bisa melihat dan monitor CCTV semua grup',
'{"modul":["daftarGrupLokasi","detailGrupLokasi","daftarLokasi","detailLokasi","daftarCCTV","detailCCTV","streamCCTV"]}',
'{"parent_modul":["Dashboard","GrupLokasi","Lokasi","CCTV"]}',
NULL),

(4, 'viewer_divisi_a', 'Viewer Divisi A - Hanya bisa akses grup CCTV yang diizinkan',
'{"modul":["daftarGrupLokasi","detailGrupLokasi","daftarLokasi","detailLokasi","daftarCCTV","detailCCTV","streamCCTV"]}',
'{"parent_modul":["Dashboard","GrupLokasi","Lokasi","CCTV"]}',
'[1]');


-- ============================================================
-- Table: cv_pengguna
-- Users / accounts
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_pengguna` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `hak_akses` varchar(225) NOT NULL DEFAULT 'viewer_divisi_a',
  `foto` varchar(225) DEFAULT NULL,
  `status` enum('actived','nonactived') NOT NULL DEFAULT 'actived',
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `activity_status` enum('online','offline') DEFAULT 'offline',
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default superuser: username=admin, password=admin123 (sha1)
INSERT INTO `cv_pengguna` (`id_pengguna`, `nama_lengkap`, `username`, `password`, `email`, `hak_akses`, `status`) VALUES
(1, 'Super Administrator', 'admin', SHA1('admin123'), 'admin@cctv.com', 'superuser', 'actived');


-- ============================================================
-- Table: cv_parent_modul
-- Parent menu items for dynamic sidebar
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_parent_modul` (
  `id_parent_modul` int NOT NULL AUTO_INCREMENT,
  `nama_parent_modul` varchar(150) NOT NULL,
  `class` varchar(100) NOT NULL COMMENT 'Used for URL segment matching and access check',
  `icon` varchar(255) DEFAULT 'fas fa-circle',
  `link` varchar(255) DEFAULT '#',
  `child_module` enum('Y','N') DEFAULT 'Y' COMMENT 'Y = has sub-menus, N = direct link',
  `urutan` int DEFAULT 0,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  PRIMARY KEY (`id_parent_modul`),
  UNIQUE KEY `class` (`class`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cv_parent_modul` (`id_parent_modul`, `nama_parent_modul`, `class`, `icon`, `link`, `child_module`, `urutan`) VALUES
(1, 'Dashboard', 'Dashboard', 'fas fa-tachometer-alt', '/panel/dashboard', 'N', 1),
(2, 'Grup Lokasi', 'GrupLokasi', 'fas fa-map-marker-alt', '#', 'Y', 2),
(3, 'Lokasi', 'Lokasi', 'fas fa-map', '#', 'Y', 3),
(4, 'CCTV', 'CCTV', 'fas fa-video', '#', 'Y', 4),
(5, 'Master Data', 'MasterData', 'fas fa-database', '#', 'Y', 5),
(6, 'Pengaturan', 'Pengaturan', 'fas fa-cog', '#', 'Y', 6);


-- ============================================================
-- Table: cv_modul
-- Sub menu items
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_modul` (
  `id_modul` int NOT NULL AUTO_INCREMENT,
  `nama_modul` varchar(150) NOT NULL,
  `controller_modul` varchar(100) NOT NULL COMMENT 'Used for access check, matches route name',
  `class_parent_modul` varchar(100) NOT NULL,
  `link_modul` varchar(255) NOT NULL,
  `tampil_sidebar` enum('Y','N') DEFAULT 'Y',
  `urutan` int DEFAULT 0,
  PRIMARY KEY (`id_modul`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cv_modul` (`id_modul`, `nama_modul`, `controller_modul`, `class_parent_modul`, `link_modul`, `tampil_sidebar`, `urutan`) VALUES
-- GrupLokasi
(1, 'Daftar Grup Lokasi', 'daftarGrupLokasi', 'GrupLokasi', '/panel/grupLokasi/daftarGrupLokasi', 'Y', 1),
(2, 'Tambah Grup Lokasi', 'tambahGrupLokasi', 'GrupLokasi', '/panel/grupLokasi/tambahGrupLokasi', 'N', 2),
(3, 'Update Grup Lokasi', 'updateGrupLokasi', 'GrupLokasi', '#', 'N', 3),
(4, 'Hapus Grup Lokasi', 'hapusGrupLokasi', 'GrupLokasi', '#', 'N', 4),
(29, 'Detail Grup Lokasi', 'detailGrupLokasi', 'GrupLokasi', '#', 'N', 5),
-- Lokasi
(5, 'Daftar Lokasi', 'daftarLokasi', 'Lokasi', '/panel/lokasi/daftarLokasi', 'Y', 1),
(6, 'Tambah Lokasi', 'tambahLokasi', 'Lokasi', '/panel/lokasi/tambahLokasi', 'N', 2),
(7, 'Update Lokasi', 'updateLokasi', 'Lokasi', '#', 'N', 3),
(8, 'Hapus Lokasi', 'hapusLokasi', 'Lokasi', '#', 'N', 4),
(30, 'Detail Lokasi', 'detailLokasi', 'Lokasi', '#', 'N', 5),
-- CCTV
(9, 'Daftar CCTV', 'daftarCCTV', 'CCTV', '/panel/cctv/daftarCCTV', 'Y', 1),
(10, 'Tambah CCTV', 'tambahCCTV', 'CCTV', '/panel/cctv/tambahCCTV', 'N', 2),
(11, 'Update CCTV', 'updateCCTV', 'CCTV', '#', 'N', 3),
(12, 'Hapus CCTV', 'hapusCCTV', 'CCTV', '#', 'N', 4),
(13, 'Detail CCTV', 'detailCCTV', 'CCTV', '#', 'N', 5),
(14, 'Stream CCTV', 'streamCCTV', 'CCTV', '#', 'N', 6),
-- MasterData
(15, 'Daftar Pengguna', 'daftarPengguna', 'MasterData', '/panel/masterData/daftarPengguna', 'Y', 1),
(16, 'Tambah Pengguna', 'tambahPengguna', 'MasterData', '/panel/masterData/tambahPengguna', 'N', 2),
(17, 'Update Pengguna', 'updatePengguna', 'MasterData', '#', 'N', 3),
(18, 'Hapus Pengguna', 'hapusPengguna', 'MasterData', '#', 'N', 4),
(19, 'Daftar Hak Akses', 'daftarHakAkses', 'MasterData', '/panel/masterData/daftarHakAkses', 'Y', 5),
(20, 'Tambah Hak Akses', 'tambahHakAkses', 'MasterData', '/panel/masterData/tambahHakAkses', 'N', 6),
(21, 'Update Hak Akses', 'updateHakAkses', 'MasterData', '#', 'N', 7),
(22, 'Hapus Hak Akses', 'hapusHakAkses', 'MasterData', '#', 'N', 8),
(23, 'Akun Ezviz', 'daftarEzvizAkun', 'MasterData', '/panel/masterData/daftarEzvizAkun', 'Y', 9),
(24, 'Tambah Akun Ezviz', 'tambahEzvizAkun', 'MasterData', '/panel/masterData/tambahEzvizAkun', 'N', 10),
(25, 'Update Akun Ezviz', 'updateEzvizAkun', 'MasterData', '#', 'N', 11),
(26, 'Hapus Akun Ezviz', 'hapusEzvizAkun', 'MasterData', '#', 'N', 12),
-- Pengaturan
(27, 'Pengaturan Sistem', 'pengaturanSistem', 'Pengaturan', '/panel/pengaturan/pengaturanSistem', 'Y', 1),
(28, 'Log Aktivitas', 'logAktivitas', 'Pengaturan', '/panel/pengaturan/logAktivitas', 'Y', 2);


-- ============================================================
-- Table: cv_ezviz_akun
-- EZVIZ API accounts (multi-account support)
-- Each account corresponds to a Gmail registered on EZVIZ app
-- app_key & app_secret from EZVIZ open platform
-- access_token refreshed via API call
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_ezviz_akun` (
  `id_ezviz_akun` int NOT NULL AUTO_INCREMENT,
  `nama_akun` varchar(100) NOT NULL COMMENT 'Friendly name for this account/node',
  `deskripsi` varchar(500) DEFAULT NULL,
  `email_terdaftar` varchar(150) DEFAULT NULL COMMENT 'Gmail used to register on EZVIZ app',
  `app_key` varchar(100) NOT NULL COMMENT 'From EZVIZ Open Platform',
  `app_secret` varchar(255) NOT NULL COMMENT 'From EZVIZ Open Platform',
  `access_token` text DEFAULT NULL COMMENT 'Auto-retrieved via API',
  `token_expiry` datetime DEFAULT NULL COMMENT 'When the access token expires',
  `api_url` varchar(255) DEFAULT 'https://open.ys7.com' COMMENT 'EZVIZ API base URL (may differ by region)',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `last_sync` datetime DEFAULT NULL COMMENT 'Last time token was refreshed',
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ezviz_akun`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- Table: cv_lokasi_group
-- Groups of locations (e.g. "Gedung A", "Area Parkir", "Cabang Jakarta")
-- access controlled via cv_hak_akses.cctv_group_akses
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_lokasi_group` (
  `id_group` int NOT NULL AUTO_INCREMENT,
  `nama_group` varchar(150) NOT NULL,
  `kode_group` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL COMMENT 'Thumbnail image for group card',
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `urutan` int DEFAULT 0,
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_group`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cv_lokasi_group` (`id_group`, `nama_group`, `kode_group`, `deskripsi`, `alamat`, `kota`, `status`) VALUES
(1, 'Gedung Utama', 'GRP-001', 'Grup CCTV untuk area gedung utama', 'Jl. Teknologi No. 1', 'Jakarta', 'aktif'),
(2, 'Area Parkir', 'GRP-002', 'Grup CCTV untuk area parkiran', 'Jl. Teknologi No. 1', 'Jakarta', 'aktif'),
(3, 'Cabang Surabaya', 'GRP-003', 'Grup CCTV cabang Surabaya', 'Jl. Raya No. 10', 'Surabaya', 'aktif');


-- ============================================================
-- Table: cv_lokasi
-- Individual locations within a group
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_lokasi` (
  `id_lokasi` int NOT NULL AUTO_INCREMENT,
  `id_group` int NOT NULL,
  `nama_lokasi` varchar(150) NOT NULL,
  `kode_lokasi` varchar(20) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `lantai` varchar(50) DEFAULT NULL COMMENT 'Floor / area description',
  `koordinat_lat` decimal(10,8) DEFAULT NULL,
  `koordinat_lng` decimal(11,8) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `urutan` int DEFAULT 0,
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_lokasi`),
  KEY `FK_lokasi_group` (`id_group`),
  CONSTRAINT `FK_lokasi_group` FOREIGN KEY (`id_group`) REFERENCES `cv_lokasi_group` (`id_group`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `cv_lokasi` (`id_lokasi`, `id_group`, `nama_lokasi`, `kode_lokasi`, `deskripsi`, `lantai`, `status`) VALUES
(1, 1, 'Lobby Lantai 1', 'LOC-001', 'Area lobby depan', 'Lantai 1', 'aktif'),
(2, 1, 'Ruang Server', 'LOC-002', 'Ruang server utama', 'Lantai 2', 'aktif'),
(3, 2, 'Parkir Basement', 'LOC-003', 'Area parkir basement', 'B1', 'aktif'),
(4, 3, 'Pintu Masuk Cabang', 'LOC-004', 'Pintu utama cabang Surabaya', 'Lantai 1', 'aktif');


-- ============================================================
-- Table: cv_cctv
-- CCTV camera devices
-- Each camera belongs to a location and linked to an EZVIZ account
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_cctv` (
  `id_cctv` int NOT NULL AUTO_INCREMENT,
  `id_lokasi` int NOT NULL,
  `id_ezviz_akun` int NOT NULL COMMENT 'Which EZVIZ account has this device',
  `nama_cctv` varchar(150) NOT NULL,
  `device_serial` varchar(100) NOT NULL COMMENT 'EZVIZ device serial number',
  `channel_no` int NOT NULL DEFAULT 1 COMMENT 'Camera channel number (usually 1)',
  `stream_type` int NOT NULL DEFAULT 1 COMMENT '1=main stream (HD), 2=sub stream (SD)',
  `validCode` varchar(50) DEFAULT NULL COMMENT 'EZVIZ device validation code (6-digit from device sticker)',
  `gambar_thumb` varchar(255) DEFAULT NULL COMMENT 'Device thumbnail image URL',
  `deskripsi` text DEFAULT NULL,
  `posisi` varchar(100) DEFAULT NULL COMMENT 'Camera position description (e.g. corner, north-side)',
  `status` enum('online','offline','nonaktif') DEFAULT 'offline',
  `last_online` datetime DEFAULT NULL,
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cctv`),
  KEY `FK_cctv_lokasi` (`id_lokasi`),
  KEY `FK_cctv_ezviz` (`id_ezviz_akun`),
  CONSTRAINT `FK_cctv_lokasi` FOREIGN KEY (`id_lokasi`) REFERENCES `cv_lokasi` (`id_lokasi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cctv_ezviz` FOREIGN KEY (`id_ezviz_akun`) REFERENCES `cv_ezviz_akun` (`id_ezviz_akun`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ============================================================
-- Table: cv_log_aktivitas
-- Activity log for audit trail
-- ============================================================
CREATE TABLE IF NOT EXISTS `cv_log_aktivitas` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `aksi` varchar(255) NOT NULL COMMENT 'Action description',
  `modul` varchar(100) DEFAULT NULL,
  `detail` text DEFAULT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_pengguna` (`id_pengguna`),
  KEY `idx_created` (`created_time`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
