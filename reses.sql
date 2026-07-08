/*
SQLyog Ultimate
MySQL - 8.0.12 : Database - reses
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `dewans` */

CREATE TABLE `dewans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `komisi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pimpinan_dprd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_fraksi` bigint(20) unsigned DEFAULT NULL,
  `id_kecamatan` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dewans_id_fraksi_foreign` (`id_fraksi`),
  KEY `dewans_id_kecamatan_foreign` (`id_kecamatan`),
  CONSTRAINT `dewans_id_fraksi_foreign` FOREIGN KEY (`id_fraksi`) REFERENCES `fraksis` (`id`) ON DELETE SET NULL,
  CONSTRAINT `dewans_id_kecamatan_foreign` FOREIGN KEY (`id_kecamatan`) REFERENCES `kecamatans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `dewans` */

insert  into `dewans`(`id`,`nama`,`komisi`,`pimpinan_dprd`,`id_fraksi`,`id_kecamatan`,`created_at`,`updated_at`) values (1,'H. Ahmad','Komisi A','Tidak',NULL,NULL,'2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `dewans`(`id`,`nama`,`komisi`,`pimpinan_dprd`,`id_fraksi`,`id_kecamatan`,`created_at`,`updated_at`) values (2,'Budi Santoso','Komisi B','Ya',NULL,NULL,'2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `dewans`(`id`,`nama`,`komisi`,`pimpinan_dprd`,`id_fraksi`,`id_kecamatan`,`created_at`,`updated_at`) values (3,'Siti Aminah','Komisi C','Tidak',NULL,NULL,'2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `dewans`(`id`,`nama`,`komisi`,`pimpinan_dprd`,`id_fraksi`,`id_kecamatan`,`created_at`,`updated_at`) values (4,'Tatang','A',NULL,1,1,'2026-07-02 08:22:15','2026-07-02 08:22:15');
insert  into `dewans`(`id`,`nama`,`komisi`,`pimpinan_dprd`,`id_fraksi`,`id_kecamatan`,`created_at`,`updated_at`) values (5,'Yudo Widiatmoko','b',NULL,2,1,'2026-07-02 08:26:23','2026-07-02 08:26:23');

/*Table structure for table `failed_jobs` */

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `fraksis` */

CREATE TABLE `fraksis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_fraksi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `fraksis` */

insert  into `fraksis`(`id`,`nama_fraksi`,`created_at`,`updated_at`) values (1,'Fraksi PDI-P',NULL,NULL);
insert  into `fraksis`(`id`,`nama_fraksi`,`created_at`,`updated_at`) values (2,'Fraksi Gerindra',NULL,NULL);
insert  into `fraksis`(`id`,`nama_fraksi`,`created_at`,`updated_at`) values (3,'Fraksi PKS',NULL,NULL);
insert  into `fraksis`(`id`,`nama_fraksi`,`created_at`,`updated_at`) values (4,'Fraksi Golkar',NULL,NULL);

/*Table structure for table `kecamatans` */

CREATE TABLE `kecamatans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_kecamatan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kecamatans` */

insert  into `kecamatans`(`id`,`nama_kecamatan`,`created_at`,`updated_at`) values (1,'Cilincing','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kecamatans`(`id`,`nama_kecamatan`,`created_at`,`updated_at`) values (2,'Kelapa Gading','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kecamatans`(`id`,`nama_kecamatan`,`created_at`,`updated_at`) values (3,'Koja','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kecamatans`(`id`,`nama_kecamatan`,`created_at`,`updated_at`) values (4,'Pademangan','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kecamatans`(`id`,`nama_kecamatan`,`created_at`,`updated_at`) values (5,'Penjaringan','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kecamatans`(`id`,`nama_kecamatan`,`created_at`,`updated_at`) values (6,'Tanjung Priok','2026-07-02 08:24:52','2026-07-02 08:24:52');

/*Table structure for table `kelurahans` */

CREATE TABLE `kelurahans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_kecamatan` bigint(20) unsigned NOT NULL,
  `nama_kelurahan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelurahans_id_kecamatan_foreign` (`id_kecamatan`),
  CONSTRAINT `kelurahans_id_kecamatan_foreign` FOREIGN KEY (`id_kecamatan`) REFERENCES `kecamatans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `kelurahans` */

insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (1,1,'Cilincing','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (2,1,'Semper Barat','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (3,2,'Kelapa Gading Barat','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (4,2,'Pegangsaan Dua','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (5,3,'Koja','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (6,3,'Lagoa','2026-07-02 08:09:45','2026-07-02 08:09:45');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (7,1,'Kali Baru','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (8,1,'Semper Timur','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (9,1,'Sukapura','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (10,1,'Rorotan','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (11,1,'Marunda','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (12,2,'Kelapa Gading Timur','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (13,3,'Tugu Utara','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (14,3,'Tugu Selatan','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (15,3,'Rawa Badak Utara','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (16,3,'Rawa Badak Selatan','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (17,4,'Pademangan Timur','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (18,4,'Pademangan Barat','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (19,4,'Ancol','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (20,5,'Penjaringan','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (21,5,'Pluit','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (22,5,'Pejagalan','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (23,5,'Kapuk Muara','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (24,5,'Kamal Muara','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (25,6,'Tanjung Priok','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (26,6,'Kebon Bawang','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (27,6,'Sungai Bambu','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (28,6,'Papanggo','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (29,6,'Warakas','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (30,6,'Sunter Agung','2026-07-02 08:24:52','2026-07-02 08:24:52');
insert  into `kelurahans`(`id`,`id_kecamatan`,`nama_kelurahan`,`created_at`,`updated_at`) values (31,6,'Sunter Jaya','2026-07-02 08:24:52','2026-07-02 08:24:52');

/*Table structure for table `migrations` */

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values (1,'2014_10_12_000000_create_users_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (2,'2014_10_12_100000_create_password_reset_tokens_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (3,'2019_08_19_000000_create_failed_jobs_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (4,'2019_12_14_000001_create_personal_access_tokens_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (5,'2026_07_02_072540_create_fraksis_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (6,'2026_07_02_072541_create_dewans_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (7,'2026_07_02_072541_create_kecamatans_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (8,'2026_07_02_072541_create_kelurahans_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (9,'2026_07_02_072542_create_pekerjaan_sdas_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (10,'2026_07_02_072542_create_survei_reses_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (11,'2026_07_02_072543_create_surat_permohonans_table',1);
insert  into `migrations`(`id`,`migration`,`batch`) values (12,'2026_07_02_080753_add_fields_to_survei_reses_table',2);
insert  into `migrations`(`id`,`migration`,`batch`) values (13,'2026_07_02_081944_add_id_kecamatan_to_dewans_table',3);
insert  into `migrations`(`id`,`migration`,`batch`) values (14,'2026_07_02_083230_change_foto_column_to_text_in_survei_reses_table',4);
insert  into `migrations`(`id`,`migration`,`batch`) values (15,'2026_07_02_084126_add_keterangan_to_survei_reses_table',5);
insert  into `migrations`(`id`,`migration`,`batch`) values (16,'2026_07_02_132029_update_pekerjaan_sdas_table_add_survei_id',6);
insert  into `migrations`(`id`,`migration`,`batch`) values (17,'2026_07_02_133608_update_surat_permohonans_table_add_pekerjaan_id',7);
insert  into `migrations`(`id`,`migration`,`batch`) values (18,'2026_07_02_141026_add_lat_long_to_pekerjaan_sdas_table',8);

/*Table structure for table `password_reset_tokens` */

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `pekerjaan_sdas` */

CREATE TABLE `pekerjaan_sdas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_survei_reses` bigint(20) unsigned DEFAULT NULL,
  `no_skpd` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_monev` int(11) DEFAULT NULL,
  `sumber_data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_input` date DEFAULT NULL,
  `rincian_sumber_data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_tracking` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_dewan` bigint(20) unsigned DEFAULT NULL,
  `id_kecamatan` bigint(20) unsigned DEFAULT NULL,
  `id_kelurahan` bigint(20) unsigned DEFAULT NULL,
  `rt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `lingkup_kewenangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori_prioritas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_tindak_lanjut` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tgl_survei` date DEFAULT NULL,
  `volume_panjang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_dikerjakan` int(11) DEFAULT NULL,
  `metode_pekerjaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checklist_perencanaan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimasi_tgl_realisasi` date DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `progress` int(11) NOT NULL DEFAULT '0',
  `photo` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pekerjaan_sdas_kode_tracking_unique` (`kode_tracking`),
  KEY `pekerjaan_sdas_id_dewan_foreign` (`id_dewan`),
  KEY `pekerjaan_sdas_id_kelurahan_foreign` (`id_kelurahan`),
  KEY `pekerjaan_sdas_id_survei_reses_foreign` (`id_survei_reses`),
  KEY `pekerjaan_sdas_id_kecamatan_foreign` (`id_kecamatan`),
  CONSTRAINT `pekerjaan_sdas_id_dewan_foreign` FOREIGN KEY (`id_dewan`) REFERENCES `dewans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pekerjaan_sdas_id_kecamatan_foreign` FOREIGN KEY (`id_kecamatan`) REFERENCES `kecamatans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pekerjaan_sdas_id_kelurahan_foreign` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pekerjaan_sdas_id_survei_reses_foreign` FOREIGN KEY (`id_survei_reses`) REFERENCES `survei_reses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pekerjaan_sdas` */

insert  into `pekerjaan_sdas`(`id`,`id_survei_reses`,`no_skpd`,`tahun_monev`,`sumber_data`,`tgl_input`,`rincian_sumber_data`,`kode_tracking`,`id_dewan`,`id_kecamatan`,`id_kelurahan`,`rt`,`rw`,`alamat`,`deskripsi`,`lingkup_kewenangan`,`kategori_pekerjaan`,`kategori_prioritas`,`status_tindak_lanjut`,`tgl_survei`,`volume_panjang`,`tahun_dikerjakan`,`metode_pekerjaan`,`checklist_perencanaan`,`estimasi_tgl_realisasi`,`tgl_mulai`,`tgl_selesai`,`progress`,`photo`,`created_at`,`updated_at`,`latitude`,`longitude`) values (1,1,'Suku Dinas SDA Jakarta Utara',2026,'Reses','2026-07-02',NULL,'1797 (011/RW-10/7/IX/2025 22 September 2025)',1,4,18,NULL,NULL,'Jl Pademangan 4 gg 21 RT 0014 & RT 0015 RW 008','Pengurasan saluran','Satuan Pelaksana','Pengurasan Saluran',NULL,NULL,NULL,'216.00',2026,'Swakelola',NULL,NULL,NULL,NULL,40,'[\"pekerjaan_photos\\/KGMBF9X7GAco11pFMShYKTlSDn3hQTsE30zx0Rwq.webp\",\"pekerjaan_photos\\/QV0prtfjEi098c06yMrTTLZifmd1wEuEWnLqYa6t.jpg\"]','2026-07-02 13:33:06','2026-07-02 14:37:38','-6.134204','106.836102');
insert  into `pekerjaan_sdas`(`id`,`id_survei_reses`,`no_skpd`,`tahun_monev`,`sumber_data`,`tgl_input`,`rincian_sumber_data`,`kode_tracking`,`id_dewan`,`id_kecamatan`,`id_kelurahan`,`rt`,`rw`,`alamat`,`deskripsi`,`lingkup_kewenangan`,`kategori_pekerjaan`,`kategori_prioritas`,`status_tindak_lanjut`,`tgl_survei`,`volume_panjang`,`tahun_dikerjakan`,`metode_pekerjaan`,`checklist_perencanaan`,`estimasi_tgl_realisasi`,`tgl_mulai`,`tgl_selesai`,`progress`,`photo`,`created_at`,`updated_at`,`latitude`,`longitude`) values (2,2,'Suku Dinas SDA Jakarta Utara',2026,'Reses','2026-07-02',NULL,'KODETRX',4,1,1,NULL,NULL,'JL KALIBARU','Pengerukan Crossingan','Satuan Pelaksana','Pengurasan Saluran',NULL,NULL,NULL,'4.00',2026,'Swakelola',NULL,NULL,NULL,NULL,0,'[\"pekerjaan_photos\\/5YBk22GoOmxUBkrOZjcBBOUFQ9B2gtRhXEPm7LLq.jpg\",\"pekerjaan_photos\\/bpoeKYEhNcUXwGthokqTyiN8FZKf2ZQVjGRO3bXi.jpg\",\"pekerjaan_photos\\/J0w8GAsznXS292nZAHoMXIKG3rET2hHgeKCkZBFU.jpg\"]','2026-07-02 13:53:50','2026-07-02 14:30:22','-6.104045','106.923253');

/*Table structure for table `personal_access_tokens` */

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

/*Table structure for table `surat_permohonans` */

CREATE TABLE `surat_permohonans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_pekerjaan_sda` bigint(20) unsigned DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `nomor_surat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dari` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_kelurahan` bigint(20) unsigned DEFAULT NULL,
  `id_kecamatan` bigint(20) unsigned DEFAULT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail_pemohon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `hasil_survei` text COLLATE utf8mb4_unicode_ci,
  `photo` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Diterima',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `surat_permohonans_id_kelurahan_foreign` (`id_kelurahan`),
  KEY `surat_permohonans_id_pekerjaan_sda_foreign` (`id_pekerjaan_sda`),
  KEY `surat_permohonans_id_kecamatan_foreign` (`id_kecamatan`),
  CONSTRAINT `surat_permohonans_id_kecamatan_foreign` FOREIGN KEY (`id_kecamatan`) REFERENCES `kecamatans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `surat_permohonans_id_kelurahan_foreign` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahans` (`id`) ON DELETE SET NULL,
  CONSTRAINT `surat_permohonans_id_pekerjaan_sda_foreign` FOREIGN KEY (`id_pekerjaan_sda`) REFERENCES `pekerjaan_sdas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `surat_permohonans` */

insert  into `surat_permohonans`(`id`,`id_pekerjaan_sda`,`tanggal`,`nomor_surat`,`dari`,`id_kelurahan`,`id_kecamatan`,`lokasi`,`detail_pemohon`,`deskripsi`,`hasil_survei`,`photo`,`status`,`catatan`,`created_at`,`updated_at`) values (1,1,'2026-07-02','333/RR.01.01','Kepala Suku Dinas Perumahan Rakyat dan Kawasan Pemukiman Kota Adm. Jakarta Utara',1,1,'Jl Pademangan 4 gg 21 RT 0014 & RT 0015 RW 008','Permohonan percepatan perbaikan saluran/dekker dibawah inrit pada Jl. Lodan Raya','Pengurasan saluran','Info dari Bp. Heri Ekbang Kel. Pluit, saluran tidak bermasalah, yang ada hanya terkait keberatan pengelola Pompa Swadaya (RW15) yang aliran airnya dari usaha kuliner (Jakpro) yang mengarah ke Pompa Swadaya tsb.','[\"surat_photos\\/VBYG6KHx73cl45kZFDYaantacYA3DBcPATepQ8ja.jpg\",\"surat_photos\\/nFfAzUMneSq89hdL9MsWCrH9EspjLwMfvzDVZgIA.png\",\"surat_photos\\/6JgHSyI8HUniCiq0sp5aaXtNaKJ5ODw6SlrsQnaa.jpg\"]','Disetujui',NULL,'2026-07-02 13:51:13','2026-07-02 13:59:15');
insert  into `surat_permohonans`(`id`,`id_pekerjaan_sda`,`tanggal`,`nomor_surat`,`dari`,`id_kelurahan`,`id_kecamatan`,`lokasi`,`detail_pemohon`,`deskripsi`,`hasil_survei`,`photo`,`status`,`catatan`,`created_at`,`updated_at`) values (2,2,'2026-07-02','002RW/10/SB/II/2026','Kepala Suku Dinas Perumahan Rakyat dan Kawasan Pemukiman Kota Adm. Jakarta Utara',2,1,'JL KALIBARU','1. Melakukan perbaikan teknis terhadap deker di area metros 1 agar aliran air menuju pembuangan akhir menjadi lebih lancar 2. Memaksimalkan fungsi elevasi air sehingga genangan tidak kembali terjadi saat curah hujan tinggi','Pengerukan Crossingan','sasas','[\"surat_photos\\/FIvDAPpjGRTHn0tHL2yniHGdjtmJ7jd68xUB62vO.jpg\",\"surat_photos\\/jDR4hUnJWqOewjC8SPL2LYQHycnFdgqDswFTyNek.jpg\",\"surat_photos\\/O4UuHUVReqZ5nKuQDzCtfvuVzlyH9FFJBlgtl7js.png\"]','Diajukan','sasa','2026-07-02 13:58:15','2026-07-02 13:58:15');

/*Table structure for table `survei_reses` */

CREATE TABLE `survei_reses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_dewan` bigint(20) unsigned NOT NULL,
  `id_kecamatan` bigint(20) unsigned NOT NULL,
  `id_kelurahan` bigint(20) unsigned DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `keluhan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `permintaan` text COLLATE utf8mb4_unicode_ci,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `foto` text COLLATE utf8mb4_unicode_ci,
  `lebar` decimal(10,2) DEFAULT NULL,
  `tinggi` decimal(10,2) DEFAULT NULL,
  `panjang` decimal(10,2) DEFAULT NULL,
  `volume` decimal(10,2) DEFAULT NULL,
  `estimasi_biaya` bigint(20) DEFAULT NULL,
  `tanggal_reses` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baru',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `survei_reses_id_dewan_foreign` (`id_dewan`),
  KEY `survei_reses_id_kecamatan_foreign` (`id_kecamatan`),
  KEY `survei_reses_id_kelurahan_foreign` (`id_kelurahan`),
  CONSTRAINT `survei_reses_id_dewan_foreign` FOREIGN KEY (`id_dewan`) REFERENCES `dewans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `survei_reses_id_kecamatan_foreign` FOREIGN KEY (`id_kecamatan`) REFERENCES `kecamatans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `survei_reses_id_kelurahan_foreign` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahans` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `survei_reses` */

insert  into `survei_reses`(`id`,`id_dewan`,`id_kecamatan`,`id_kelurahan`,`alamat`,`keluhan`,`permintaan`,`keterangan`,`foto`,`lebar`,`tinggi`,`panjang`,`volume`,`estimasi_biaya`,`tanggal_reses`,`status`,`created_at`,`updated_at`) values (1,1,1,1,'PRIOK','Pembuatan','Pembuatan',NULL,'reses_photos/fxOHiXXoHPYYp7Xq8lvuGURXAuQ0CpRTdSh6nzTl.jpg',6.00,6.00,6.00,216.00,12000,'2026-07-03','Diproses','2026-07-02 08:18:20','2026-07-02 08:18:20');
insert  into `survei_reses`(`id`,`id_dewan`,`id_kecamatan`,`id_kelurahan`,`alamat`,`keluhan`,`permintaan`,`keterangan`,`foto`,`lebar`,`tinggi`,`panjang`,`volume`,`estimasi_biaya`,`tanggal_reses`,`status`,`created_at`,`updated_at`) values (2,4,1,2,'JL KALIBARU','Pengerukan Crossingan','Pengerukan Crossingan',NULL,'[\"reses_photos\\/AmKlx5Iz7MV3cSLYfhOX8T2jgBAqshyauxB3yZej.jpg\",\"reses_photos\\/UXwFc27TuYEVR68kgDbOOYhHh8vK5SMWgpBj7JSW.jpg\",\"reses_photos\\/V2vm76OvPinqcWETds74Hx2mCIQUjy77OgvhJYp3.jpg\"]',2.00,2.00,1.00,4.00,122222,'2026-07-02','Disurvei','2026-07-02 08:35:48','2026-07-02 13:56:34');

/*Table structure for table `users` */

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
