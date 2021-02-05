-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Feb 2021 pada 06.40
-- Versi server: 10.3.16-MariaDB
-- Versi PHP: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sakura`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bahan_baku`
--

CREATE TABLE `bahan_baku` (
  `id_bahan_baku` int(11) NOT NULL,
  `nama_bahan_baku` varchar(128) NOT NULL,
  `harga_terakhir` double NOT NULL,
  `stok_bahan_baku` double NOT NULL,
  `satuan_bahan_baku` varchar(16) NOT NULL,
  `minimal_pembelian` double NOT NULL,
  `ketersediaan` int(11) NOT NULL,
  `tipe` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `bahan_baku`
--

INSERT INTO `bahan_baku` (`id_bahan_baku`, `nama_bahan_baku`, `harga_terakhir`, `stok_bahan_baku`, `satuan_bahan_baku`, `minimal_pembelian`, `ketersediaan`, `tipe`) VALUES
(1, 'Tepung', 11000, 200, 'Kg', 300, 5, 1),
(2, 'Bawang Putih', 40000, 125, 'Kg', 12, 5, 2),
(3, 'Minyak Ikan', 80000, 700, 'Ml', 5, 3, 2),
(4, 'Terasi Udang', 25000, 16, 'Kg', 14, 4, 2),
(5, 'Sari Udang', 80000, 30, 'Kg', 5, 4, 2),
(6, 'Kayu', 40000, 30, 'Ikat', 9, 3, 2),
(7, 'Bumbu Masak', 25000, 13, 'Kg', 14, 5, 2),
(8, 'Garam', 3000, 108, 'Kg', 117, 5, 2),
(9, 'Baking Soda', 15000, 35, 'Kg', 24, 5, 2),
(10, 'Sakarin', 10000, 40, 'Kg', 35, 3, 2),
(26, 'test', 25000, 16, 'terserah', 14, 4, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(128) NOT NULL,
  `metode_kriteria` int(11) NOT NULL,
  `bobot_kriteria` double NOT NULL,
  `jenis_kriteria` int(11) NOT NULL,
  `deskripsi_kriteria` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id_kriteria`, `nama_kriteria`, `metode_kriteria`, `bobot_kriteria`, `jenis_kriteria`, `deskripsi_kriteria`) VALUES
(1, 'Harga Barang', 1, 4, 1, 'Bobot dari 1 - 5, Jenis kriteria price yang berarti semakin rendah harga terakhir barang semakin tinggi prioritasnya'),
(2, 'Stok Barang', 1, 5, 1, 'Bobot dari 1 - 5, Jenis kriteria price yang berarti semakin rendah stok barang di gudang semakin tinggi prioritasnya'),
(3, 'Kebutuhan Barang', 1, 3, 2, 'Bobot dari 1 - 5, Jenis kriteria benefit yang berarti semakin tinggi kebutuhan barang semakin tinggi prioritasnya'),
(4, 'Ketersediaan Barang', 1, 2, 2, 'Bobot dari 1 - 5, Jenis kriteria benefit yang berarti semakin tinggi ketersediaan barang di pasar semakin tinggi prioritasnya'),
(5, 'Harga Penawaran', 2, 0.3, 1, 'Bobot prestentase dari 100% keseluruhan metode SAW, Jenis kriteria price berarti semakin rendah harga penawaran supplier semakin tinggi prioritasnya'),
(6, 'Kualitas Penawaran', 2, 0.35, 2, 'Bobot prestentase dari 100% keseluruhan metode SAW, Jenis kriteria benefit berarti semakin tinggi kualitas barang penawaran semakin tinggi prioritasnya'),
(7, 'Stok Penawaran', 2, 0.15, 2, 'Bobot prestentase dari 100% keseluruhan metode SAW, Jenis kriteria benefit berarti semakin banyak stok penawaran barang semakin tinggi prioritasnya'),
(8, 'Ongkos Penawaran', 2, 0.2, 1, 'Bobot prestentase dari 100% keseluruhan metode SAW, Jenis kriteria price berarti semakin rendah ongkos pengiriman barang penawaran semakin tinggi prioritasnya');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penawaran`
--

CREATE TABLE `penawaran` (
  `id_penawaran` int(11) NOT NULL,
  `id_bahan_baku` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `harga_penawaran` double NOT NULL,
  `ongkos_penawaran` double NOT NULL,
  `kualitas_penawaran` int(11) NOT NULL,
  `stok_penawaran` double NOT NULL,
  `tanggal_penawaran` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `penawaran`
--

INSERT INTO `penawaran` (`id_penawaran`, `id_bahan_baku`, `id_supplier`, `harga_penawaran`, `ongkos_penawaran`, `kualitas_penawaran`, `stok_penawaran`, `tanggal_penawaran`) VALUES
(5, 1, 3, 11500, 15000, 5, 500, '2020-08-30'),
(6, 2, 7, 31000, 25000, 4, 115, '2020-08-31'),
(7, 2, 8, 30500, 24000, 4, 120, '2020-08-31'),
(8, 2, 9, 31800, 26000, 5, 100, '2020-08-24'),
(9, 2, 9, 32000, 26000, 5, 100, '2020-08-31'),
(10, 2, 10, 29000, 20000, 3, 130, '2020-08-31'),
(11, 2, 11, 30000, 30000, 4, 120, '2020-08-31'),
(12, 2, 3, 30000, 15000, 4, 110, '2020-08-31'),
(15, 2, 9, 30000, 15000, 4, 110, '2020-09-09'),
(17, 4, 3, 30000, 20000, 4, 110, '2020-12-02'),
(18, 4, 7, 31000, 25000, 4, 115, '2020-12-02'),
(19, 4, 8, 30500, 15000, 4, 120, '2020-12-02'),
(20, 4, 9, 32000, 20000, 5, 95, '2020-12-02'),
(21, 4, 10, 29000, 20000, 3, 130, '2020-12-02'),
(22, 4, 11, 30000, 20000, 3, 120, '2020-12-02'),
(23, 4, 12, 31800, 25000, 5, 100, '2020-12-02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produksi`
--

CREATE TABLE `produksi` (
  `id_produksi` int(11) NOT NULL,
  `id_bahan_baku` int(11) NOT NULL,
  `kepentingan_bahan_baku` int(11) NOT NULL,
  `standart_produksi` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `produksi`
--

INSERT INTO `produksi` (`id_produksi`, `id_bahan_baku`, `kepentingan_bahan_baku`, `standart_produksi`) VALUES
(1, 1, 5, 400),
(2, 2, 5, 4),
(3, 3, 4, 200),
(4, 4, 4, 4),
(5, 5, 4, 4),
(6, 6, 4, 6),
(7, 7, 3, 4),
(8, 8, 3, 18),
(9, 9, 2, 4),
(10, 10, 2, 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_saw`
--

CREATE TABLE `riwayat_saw` (
  `id_saw` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_bahan_baku` int(11) NOT NULL,
  `harga_saw` double NOT NULL,
  `kualitas_saw` int(11) NOT NULL,
  `stok_saw` double NOT NULL,
  `ongkos_saw` double NOT NULL,
  `nilai_prefensi` double NOT NULL,
  `tanggal_penawaran` date NOT NULL,
  `tanggal_saw` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `riwayat_saw`
--

INSERT INTO `riwayat_saw` (`id_saw`, `id_supplier`, `id_bahan_baku`, `harga_saw`, `kualitas_saw`, `stok_saw`, `ongkos_saw`, `nilai_prefensi`, `tanggal_penawaran`, `tanggal_saw`) VALUES
(2, 9, 2, 30000, 4, 110, 15000, 0.97, '2020-09-09', '2020-09-09'),
(3, 3, 2, 30000, 4, 110, 15000, 0.97, '2020-08-31', '2020-09-09'),
(10, 3, 1, 11500, 5, 500, 15000, 1, '2020-08-30', '2020-09-17'),
(11, 9, 2, 30000, 4, 110, 15000, 0.97, '2020-09-09', '2020-09-23'),
(12, 3, 2, 30000, 4, 110, 15000, 0.97, '2020-08-31', '2020-09-23'),
(13, 9, 2, 30000, 4, 110, 15000, 0.97, '2020-09-09', '2020-10-12'),
(14, 3, 2, 30000, 4, 110, 15000, 0.97, '2020-08-31', '2020-10-12'),
(15, 9, 4, 32000, 5, 95000, 20000, 0.92, '2020-12-02', '2020-12-02'),
(16, 8, 4, 30500, 4, 120, 15000, 0.91, '2020-12-02', '2020-12-02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_topsis`
--

CREATE TABLE `riwayat_topsis` (
  `id_topsis` int(11) NOT NULL,
  `id_bahan_baku` int(11) NOT NULL,
  `harga_topsis` double NOT NULL,
  `stok_topsis` double NOT NULL,
  `kebutuhan_topsis` int(11) NOT NULL,
  `ketersediaan_topsis` int(11) NOT NULL,
  `nilai_prefensi` double NOT NULL,
  `tanggal_topsis` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `riwayat_topsis`
--

INSERT INTO `riwayat_topsis` (`id_topsis`, `id_bahan_baku`, `harga_topsis`, `stok_topsis`, `kebutuhan_topsis`, `ketersediaan_topsis`, `nilai_prefensi`, `tanggal_topsis`) VALUES
(42, 4, 25000, 16, 4, 4, 0.84, '2020-09-08'),
(43, 4, 25000, 16, 4, 4, 0.83, '2020-09-09'),
(44, 26, 25000, 16, 4, 4, 0.83, '2020-09-09'),
(53, 4, 25000, 16, 4, 4, 0.83, '2020-09-10'),
(54, 26, 25000, 16, 4, 4, 0.83, '2020-09-10'),
(55, 4, 25000, 16, 4, 4, 0.83, '2020-09-23'),
(56, 26, 25000, 16, 4, 4, 0.83, '2020-09-23'),
(57, 4, 25000, 16, 4, 4, 0.83, '2020-10-12'),
(58, 26, 25000, 16, 4, 4, 0.83, '2020-10-12'),
(59, 4, 25000, 16, 4, 4, 0.83, '2020-10-12'),
(60, 26, 25000, 16, 4, 4, 0.83, '2020-10-12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_keluar`
--

CREATE TABLE `stok_keluar` (
  `id_stok_keluar` int(11) NOT NULL,
  `id_bahan_baku` int(11) NOT NULL,
  `jumlah_stok_keluar` double NOT NULL,
  `tanggal_keluar` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `stok_keluar`
--

INSERT INTO `stok_keluar` (`id_stok_keluar`, `id_bahan_baku`, `jumlah_stok_keluar`, `tanggal_keluar`) VALUES
(4, 1, 400, '2020-09-23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id_stok_masuk` int(11) NOT NULL,
  `id_bahan_baku` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `harga_beli` double NOT NULL,
  `jumlah_stok_masuk` double NOT NULL,
  `tanggal_masuk` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `stok_masuk`
--

INSERT INTO `stok_masuk` (`id_stok_masuk`, `id_bahan_baku`, `id_supplier`, `harga_beli`, `jumlah_stok_masuk`, `tanggal_masuk`) VALUES
(10, 26, 3, 25000, 14, '2020-07-28'),
(11, 2, 3, 40000, 100, '2020-10-12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(128) NOT NULL,
  `alamat_supplier` varchar(1000) NOT NULL,
  `no_telp_supplier` varchar(32) NOT NULL,
  `status_supplier` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `alamat_supplier`, `no_telp_supplier`, `status_supplier`) VALUES
(3, 'Bambang', 'Madiun, Jawa Timur', '488467', 1),
(7, 'Suyadi', 'Magetan, Jawa Timur', '480987', 1),
(8, 'Mariadi', 'Magetan, Jawa Timur', '488123', 1),
(9, 'Margono', 'Magetan, Jawa Timur', '487654', 1),
(10, 'Sutoyo', 'Ngawi, Jawa Timur', '484487', 1),
(11, 'Sugeng', 'Pacitan, Jawa Timur', '498765', 1),
(12, 'Bigiyo', 'Ngawi, Jawa Timur', '0', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `username` varchar(32) NOT NULL,
  `password` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`username`, `password`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bahan_baku`
--
ALTER TABLE `bahan_baku`
  ADD PRIMARY KEY (`id_bahan_baku`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indeks untuk tabel `penawaran`
--
ALTER TABLE `penawaran`
  ADD PRIMARY KEY (`id_penawaran`),
  ADD KEY `id_bahan_baku` (`id_bahan_baku`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `produksi`
--
ALTER TABLE `produksi`
  ADD PRIMARY KEY (`id_produksi`),
  ADD KEY `produksi_ibfk_1` (`id_bahan_baku`);

--
-- Indeks untuk tabel `riwayat_saw`
--
ALTER TABLE `riwayat_saw`
  ADD PRIMARY KEY (`id_saw`),
  ADD KEY `id_supplier` (`id_supplier`),
  ADD KEY `id_bahan_baku` (`id_bahan_baku`);

--
-- Indeks untuk tabel `riwayat_topsis`
--
ALTER TABLE `riwayat_topsis`
  ADD PRIMARY KEY (`id_topsis`),
  ADD KEY `id_bahan_baku` (`id_bahan_baku`);

--
-- Indeks untuk tabel `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD PRIMARY KEY (`id_stok_keluar`),
  ADD KEY `id_bahan_baku` (`id_bahan_baku`);

--
-- Indeks untuk tabel `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id_stok_masuk`),
  ADD KEY `id_bahan_baku` (`id_bahan_baku`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bahan_baku`
--
ALTER TABLE `bahan_baku`
  MODIFY `id_bahan_baku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `penawaran`
--
ALTER TABLE `penawaran`
  MODIFY `id_penawaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `produksi`
--
ALTER TABLE `produksi`
  MODIFY `id_produksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `riwayat_saw`
--
ALTER TABLE `riwayat_saw`
  MODIFY `id_saw` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `riwayat_topsis`
--
ALTER TABLE `riwayat_topsis`
  MODIFY `id_topsis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT untuk tabel `stok_keluar`
--
ALTER TABLE `stok_keluar`
  MODIFY `id_stok_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id_stok_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penawaran`
--
ALTER TABLE `penawaran`
  ADD CONSTRAINT `penawaran_ibfk_1` FOREIGN KEY (`id_bahan_baku`) REFERENCES `bahan_baku` (`id_bahan_baku`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penawaran_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`);

--
-- Ketidakleluasaan untuk tabel `produksi`
--
ALTER TABLE `produksi`
  ADD CONSTRAINT `produksi_ibfk_1` FOREIGN KEY (`id_bahan_baku`) REFERENCES `bahan_baku` (`id_bahan_baku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_saw`
--
ALTER TABLE `riwayat_saw`
  ADD CONSTRAINT `riwayat_saw_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `riwayat_saw_ibfk_2` FOREIGN KEY (`id_bahan_baku`) REFERENCES `bahan_baku` (`id_bahan_baku`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_topsis`
--
ALTER TABLE `riwayat_topsis`
  ADD CONSTRAINT `riwayat_topsis_ibfk_1` FOREIGN KEY (`id_bahan_baku`) REFERENCES `bahan_baku` (`id_bahan_baku`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stok_keluar`
--
ALTER TABLE `stok_keluar`
  ADD CONSTRAINT `stok_keluar_ibfk_1` FOREIGN KEY (`id_bahan_baku`) REFERENCES `bahan_baku` (`id_bahan_baku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD CONSTRAINT `stok_masuk_ibfk_1` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `stok_masuk_ibfk_2` FOREIGN KEY (`id_bahan_baku`) REFERENCES `bahan_baku` (`id_bahan_baku`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
