-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 06, 2014 at 10:01 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `medrec`
--
CREATE DATABASE `medrec` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `medrec`;

-- --------------------------------------------------------

--
-- Table structure for table `t_fisik`
--

CREATE TABLE IF NOT EXISTS `t_fisik` (
  `c_id` int(10) NOT NULL AUTO_INCREMENT,
  `c_noreg` varchar(15) NOT NULL,
  `t_waktu` varchar(10) NOT NULL,
  `t_berat` varchar(10) NOT NULL,
  `t_tinggi` varchar(10) NOT NULL,
  `t_tekanan` varchar(10) NOT NULL,
  `t_denyut` varchar(10) NOT NULL,
  `t_frequensi` varchar(10) NOT NULL,
  `t_suhu` varchar(10) NOT NULL,
  `n_mata` varchar(200) NOT NULL,
  `n_tht` varchar(200) NOT NULL,
  `n_gigi` varchar(200) NOT NULL,
  `n_leher` varchar(200) NOT NULL,
  `n_jantung` varchar(200) NOT NULL,
  `n_paru` varchar(200) NOT NULL,
  `n_perut` varchar(200) NOT NULL,
  `n_gerak` varchar(200) NOT NULL,
  `n_gizi` varchar(200) DEFAULT NULL,
  `n_potensi` varchar(200) DEFAULT NULL,
  `n_mental` varchar(200) DEFAULT NULL,
  `n_reproduksi` varchar(200) DEFAULT NULL,
  `n_kematangan` varchar(200) DEFAULT NULL,
  `n_hb` varchar(200) DEFAULT NULL,
  `n_feses` varchar(200) DEFAULT NULL,
  `n_jasmani` varchar(200) DEFAULT NULL,
  `c_golongan` varchar(5) DEFAULT NULL,
  `c_jantung` varchar(5) DEFAULT NULL,
  `c_lain` varchar(5) DEFAULT NULL,
  `c_asma` varchar(5) DEFAULT NULL,
  `c_thalassimia` varchar(5) DEFAULT NULL,
  `c_jiwa` varchar(5) DEFAULT NULL,
  `c_darting` varchar(5) DEFAULT NULL,
  `c_manis` varchar(5) DEFAULT NULL,
  `c_kanker` varchar(5) DEFAULT NULL,
  `c_golongani` varchar(5) DEFAULT NULL,
  `c_jantungi` varchar(5) DEFAULT NULL,
  `c_laini` varchar(5) DEFAULT NULL,
  `c_asmai` varchar(5) DEFAULT NULL,
  `c_thalassimiai` varchar(5) DEFAULT NULL,
  `c_jiwai` varchar(5) DEFAULT NULL,
  `c_dartingi` varchar(5) DEFAULT NULL,
  `c_manisi` varchar(5) DEFAULT NULL,
  `c_kankeri` varchar(5) DEFAULT NULL,
  `c_golongan1` varchar(5) DEFAULT NULL,
  `n_rhesus` varchar(200) DEFAULT NULL,
  `c_jantung1` varchar(5) DEFAULT NULL,
  `n_lain` varchar(200) DEFAULT NULL,
  `asma` varchar(200) DEFAULT NULL,
  `c_thalassimia1` varchar(5) DEFAULT NULL,
  `c_jiwa1` varchar(5) DEFAULT NULL,
  `c_menular` varchar(5) DEFAULT NULL,
  `n_sebut` varchar(200) DEFAULT NULL,
  `c_rokok` varchar(11) DEFAULT NULL,
  `c_minum` varchar(11) DEFAULT NULL,
  `c_narkoba` varchar(11) DEFAULT NULL,
  `n_lain1` varchar(200) DEFAULT NULL,
  `n_haid` varchar(200) DEFAULT NULL,
  `c_teratur` varchar(11) DEFAULT NULL,
  `n_mimpi` varchar(200) DEFAULT NULL,
  `c_dasar` varchar(11) DEFAULT NULL,
  `c_lengkap` varchar(11) DEFAULT NULL,
  `t_berat2` varchar(10) DEFAULT NULL,
  `t_tinggi2` varchar(10) DEFAULT NULL,
  `t_tekanan2` varchar(10) DEFAULT NULL,
  `t_denyut2` varchar(10) DEFAULT NULL,
  `t_frequensi2` varchar(10) DEFAULT NULL,
  `t_suhu2` varchar(10) DEFAULT NULL,
  `n_mata2` varchar(200) DEFAULT NULL,
  `n_tht2` varchar(200) DEFAULT NULL,
  `n_gigi2` varchar(200) DEFAULT NULL,
  `n_jantung2` varchar(200) DEFAULT NULL,
  `n_paru2` varchar(200) DEFAULT NULL,
  `n_perut2` varchar(200) DEFAULT NULL,
  `n_gerak2` varchar(200) DEFAULT NULL,
  `n_gizi2` varchar(200) DEFAULT NULL,
  `n_darah` text,
  `n_hemoglobin` text,
  `n_urin` text,
  `n_faeces` text,
  `n_thalassimia` text,
  `n_diagnosa` text,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `t_fisik`
--

INSERT INTO `t_fisik` (`c_id`, `c_noreg`, `t_waktu`, `t_berat`, `t_tinggi`, `t_tekanan`, `t_denyut`, `t_frequensi`, `t_suhu`, `n_mata`, `n_tht`, `n_gigi`, `n_leher`, `n_jantung`, `n_paru`, `n_perut`, `n_gerak`, `n_gizi`, `n_potensi`, `n_mental`, `n_reproduksi`, `n_kematangan`, `n_hb`, `n_feses`, `n_jasmani`, `c_golongan`, `c_jantung`, `c_lain`, `c_asma`, `c_thalassimia`, `c_jiwa`, `c_darting`, `c_manis`, `c_kanker`, `c_golongani`, `c_jantungi`, `c_laini`, `c_asmai`, `c_thalassimiai`, `c_jiwai`, `c_dartingi`, `c_manisi`, `c_kankeri`, `c_golongan1`, `n_rhesus`, `c_jantung1`, `n_lain`, `asma`, `c_thalassimia1`, `c_jiwa1`, `c_menular`, `n_sebut`, `c_rokok`, `c_minum`, `c_narkoba`, `n_lain1`, `n_haid`, `c_teratur`, `n_mimpi`, `c_dasar`, `c_lengkap`, `t_berat2`, `t_tinggi2`, `t_tekanan2`, `t_denyut2`, `t_frequensi2`, `t_suhu2`, `n_mata2`, `n_tht2`, `n_gigi2`, `n_jantung2`, `n_paru2`, `n_perut2`, `n_gerak2`, `n_gizi2`, `n_darah`, `n_hemoglobin`, `n_urin`, `n_faeces`, `n_thalassimia`, `n_diagnosa`) VALUES
(23, '000', '----1--0--', '1', '2', '3', '3', '4', '4', '45', '5', '5', '5', '5', '6', '6', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(24, '000', '14--2-25-0', '1', '2', '32', '3', '3', '4', '4', '4', '5', '5', '5', '56', '6', '6', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, '000', '13-08-2014', '1', '2', '3', '4', '4', '5', '56', '6', '67', '7', '7', '8', '8', '9', '', '', '', '', '', '', '', '', 'A', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'B	', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'A', 'q', 'Y', 'q', 'Y', 'Y', 'Y', 'Y', 'q', 'Y', 'Y', 'Y', 'q', 'q', 'Y', 'q', 'Y', 'Y', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(26, '000', '14-08-2014', '1', '2', '3', '4', '4', '5', '5', '56', '6', '6', '6', '76', '7', '7', '', '', '', '', '', '', '', '', 'A', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'B	', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'B	', '09', 'Y', 'q', 'Y', 'Y', 'Y', 'Y', 'q', 'Y', 'Y', 'Y', 'q', 'q', 'Y', 'q', 'Y', 'Y', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(27, '000', '18-08-2014', '1', '2', '3', '4', '5', '5', '6', '6', '7', '7', '87', '8', '8', '8', '', '', '', '', '', '', '', '', 'B	', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'B	', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'O', 'm', 'Y', 'a', 'Y', 'Y', 'Y', 'Y', 'a', 'Y', NULL, NULL, 'a', 'a', 'Y', 'a', 'Y', 'Y', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(35, '1', '26-08-2014', '0', '9', '8', '7', '6', '6', '5', '4', '4', '3', '3', '2', '0', '3', '4', '455', '56', '6', '6', '7', '7', '8', 'B	', 'Y', 'Y', 'Y', NULL, NULL, 'Y', 'Y', 'Y', 'B	', NULL, 'Y', 'Y', 'Y', NULL, 'Y', 'Y', NULL, 'B	', 'q', 'Y', 'q', 'Y', 'Y', 'Y', NULL, '', NULL, NULL, NULL, '', '', NULL, '', NULL, NULL, '0', '9', '8', '7', '6', '65', '5', '4', '4', '34', '3', '3', '34', '4', '0', '9', '8', '7', '6', '6'),
(34, '1', '02-09-2014', '0', '9', '8', '7', '6', '56', '54', '34', '23', '12', '1', '2', '3', '3', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '0', NULL, NULL, '1', '2', '3', '4', '5', '5', '6', '67', '7', '7', '8', '8', '8', '0', '09', '9', '8', '7', '6', '5'),
(36, '1', '02-09-2014', '0', '9', '8', '7', '6', '56', '54', '34', '23', '12', '1', '2', '3', '3', '', '', '', '', '', '', '', '', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '0', NULL, NULL, '1', '2', '3', '4', '5', '5', '6', '67', '7', '7', '8', '8', '8', '0', '09', '9', '8', '7', '6', '5'),
(30, '890', '03-08-2014', '0', '9', '8', '7', '6', '5', '4', '3', '2', '1', '23', '0', '4', '4', '', '', '', '', '', '', '', '', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '', NULL, NULL, '3', '4', '5', '6', '7', '78', '8', '9', '0', '0', '9', NULL, '7', '6', '', '', '', '', '', ''),
(31, '890', '13-08-2014', '1', '2', '3', '4', '5', '6', '6', '7', '8', '8', '9', '9', '9', '0', '', '', '', '', '', '', '', '', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '9', '8', '7', NULL, '56'),
(32, '0009', '05-08-2014', '1', '2', '3', '3', '4', '4', '5', '5', '56', '6', '6', '6', '7', '7', '', '', '', '', '', '', '', '', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '0', '9', '8', '7', '67', '56'),
(33, '000', '15-08-2014', '1', '2', '3', '3', '4', '45', '5', '5', '6', '6', '76', '7', '7', '7', '8', '8', '8', '8', '8', '9', '9', '9', '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '-', '', NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', NULL, '', NULL, NULL, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `t_medrec`
--

CREATE TABLE IF NOT EXISTS `t_medrec` (
  `id_medrec` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode_pasien` varchar(6) NOT NULL,
  `n_nama` varchar(50) NOT NULL,
  `d_medrec` datetime NOT NULL,
  `t_badan` float NOT NULL,
  `b_badan` float NOT NULL,
  `n_tensi` varchar(20) NOT NULL,
  `n_foto` varchar(50) NOT NULL,
  `n_diagnosis` varchar(100) NOT NULL,
  `c_klasifikasi` int(4) NOT NULL,
  `c_tindakan` int(4) NOT NULL,
  `n_terapi` varchar(100) NOT NULL,
  `c_status` char(1) NOT NULL DEFAULT 'A',
  `c_hematologi` varchar(1) NOT NULL,
  `c_kimiahati` varchar(1) NOT NULL,
  `c_glukosa` varchar(1) NOT NULL,
  `c_cholesterol` varchar(1) NOT NULL,
  `c_alergi` varchar(1) NOT NULL,
  `c_rematik` varchar(1) NOT NULL,
  `v_hemoglobin` varchar(20) NOT NULL,
  `v_leukosit` varchar(20) NOT NULL,
  `v_trombosit` varchar(20) NOT NULL,
  `v_eritrosit` varchar(20) NOT NULL,
  `v_got` varchar(20) NOT NULL,
  `v_gpt` varchar(20) NOT NULL,
  `v_glukosa` varchar(20) NOT NULL,
  `v_cholesterol` varchar(20) NOT NULL,
  `v_igetotal` varchar(3) NOT NULL,
  `v_igeatopi` varchar(3) NOT NULL,
  `n_igeket` varchar(350) NOT NULL,
  `v_asto` varchar(3) NOT NULL,
  `v_anaif` varchar(3) NOT NULL,
  `v_anaelisa` varchar(3) NOT NULL,
  `v_letest` varchar(3) NOT NULL,
  `v_antidsdna` varchar(3) NOT NULL,
  `v_antiparietalsel` varchar(3) NOT NULL,
  `v_imun` varchar(3) NOT NULL,
  `v_imunka` varchar(3) NOT NULL,
  `cdate` datetime NOT NULL,
  `cuid` int(11) NOT NULL,
  `mdate` datetime NOT NULL,
  `muid` char(1) NOT NULL,
  PRIMARY KEY (`id_medrec`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `t_medrec`
--

INSERT INTO `t_medrec` (`id_medrec`, `kode_pasien`, `n_nama`, `d_medrec`, `t_badan`, `b_badan`, `n_tensi`, `n_foto`, `n_diagnosis`, `c_klasifikasi`, `c_tindakan`, `n_terapi`, `c_status`, `c_hematologi`, `c_kimiahati`, `c_glukosa`, `c_cholesterol`, `c_alergi`, `c_rematik`, `v_hemoglobin`, `v_leukosit`, `v_trombosit`, `v_eritrosit`, `v_got`, `v_gpt`, `v_glukosa`, `v_cholesterol`, `v_igetotal`, `v_igeatopi`, `n_igeket`, `v_asto`, `v_anaif`, `v_anaelisa`, `v_letest`, `v_antidsdna`, `v_antiparietalsel`, `v_imun`, `v_imunka`, `cdate`, `cuid`, `mdate`, `muid`) VALUES
(1, 'S-0001', 'Sita Purnadewi', '2014-05-05 00:00:00', 168, 64, '100/20', '1-@_MG_0043.JPG', 'Menghilangkan kerut', 1, 1, '', 'A', 'N', 'N', 'N', 'N', 'N', 'T', '2', '2', '2', '2', '33', '45', '33', '200', '3', '3', '', '3', '4', '5', '3', '4', '5', '2', '2', '2014-05-13 00:00:00', 0, '2014-05-21 00:00:00', ''),
(2, 'S-0001', 'Sita Purnadewi', '2014-05-01 00:00:00', 164, 65, '100/20', '20140606194137.jpg', '', 1, 1, '', 'A', 'N', 'N', 'N', 'N', 'N', 'N', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2014-05-13 00:00:00', 0, '2014-05-21 00:00:00', '');

-- --------------------------------------------------------

--
-- Table structure for table `t_pasien`
--

CREATE TABLE IF NOT EXISTS `t_pasien` (
  `id_pasien` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode_pasien` varchar(13) NOT NULL,
  `n_nama` varchar(100) NOT NULL,
  `t_lahir` varchar(20) NOT NULL,
  `d_lahir` date DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `n_pekerjaan` varchar(50) NOT NULL,
  `c_agama` int(1) DEFAULT NULL,
  `c_marital` int(1) DEFAULT NULL,
  `t_badan` float DEFAULT NULL,
  `b_badan` float DEFAULT NULL,
  `c_goldar` int(11) DEFAULT NULL,
  `n_alamat` varchar(350) DEFAULT NULL,
  `c_propinsi` int(11) DEFAULT NULL,
  `c_kota` int(11) DEFAULT NULL,
  `n_kota` varchar(50) DEFAULT NULL,
  `n_phone` varchar(20) DEFAULT NULL,
  `n_hp` varchar(20) DEFAULT NULL,
  `n_email` varchar(30) DEFAULT NULL,
  `c_status` char(1) NOT NULL DEFAULT 'A',
  `cdate` datetime DEFAULT NULL,
  `cuid` int(11) DEFAULT NULL,
  `mdate` datetime NOT NULL,
  `muid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pasien`),
  UNIQUE KEY `kode_pasien` (`kode_pasien`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `t_pasien`
--

INSERT INTO `t_pasien` (`id_pasien`, `kode_pasien`, `n_nama`, `t_lahir`, `d_lahir`, `gender`, `n_pekerjaan`, `c_agama`, `c_marital`, `t_badan`, `b_badan`, `c_goldar`, `n_alamat`, `c_propinsi`, `c_kota`, `n_kota`, `n_phone`, `n_hp`, `n_email`, `c_status`, `cdate`, `cuid`, `mdate`, `muid`) VALUES
(1, 'S-0001', 'Sita Purnadewi', 'Madiun', '2014-05-13', 'P', 'PNS', NULL, 1, NULL, NULL, 1, 'Jl Raya Bandung no. 345', NULL, NULL, NULL, '08562325292', '08562325292', 'sitapurnadewi@gmail.com', 'A', NULL, NULL, '2014-06-04 00:00:00', NULL),
(2, 'R-0001', 'Raden Wulandari', 'Madiun', '0001-06-06', 'P', '', 1, 2, 167, 68, 1, 'Puri Dahlia no 67', 12, 0, NULL, '08562325292', '08562325292', 'sitapurnadewi@gmail.com', 'A', '2014-05-11 00:00:00', NULL, '2014-05-11 00:00:00', NULL),
(3, '251/A/7/2011', 'AMANDA ARGADINATA', '', '1991-05-09', 'P', 'Mahasiswa', NULL, 0, NULL, NULL, 0, 'Jl. Gama No. 11', NULL, NULL, NULL, '', '', '', 'A', '2014-06-04 00:00:00', NULL, '0000-00-00 00:00:00', NULL),
(4, '1111', 'bambangs', 'Bandung', '2014-08-05', 'L', 'PNS', NULL, 2, NULL, NULL, 4, 'Cimahi', NULL, NULL, NULL, '999999', '', 'eeeee', 'A', '2014-08-19 00:00:00', NULL, '0000-00-00 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_siswa`
--

CREATE TABLE IF NOT EXISTS `t_siswa` (
  `c_noreg` varchar(15) NOT NULL,
  `n_nama` varchar(100) NOT NULL,
  `c_kelamin` varchar(20) NOT NULL,
  `t_waktu` date NOT NULL,
  `n_tempat` text NOT NULL,
  `t_berat` varchar(10) NOT NULL,
  `t_tinggi` varchar(10) NOT NULL,
  `t_tekanan` varchar(10) NOT NULL,
  `t_denyut` varchar(10) NOT NULL,
  `t_frequensi` varchar(10) NOT NULL,
  `t_suhu` varchar(10) NOT NULL,
  `n_mata` varchar(200) NOT NULL,
  `n_tht` varchar(200) NOT NULL,
  `n_gigi` varchar(200) NOT NULL,
  `n_leher` varchar(200) NOT NULL,
  `n_jantung` varchar(200) NOT NULL,
  `n_paru` varchar(200) NOT NULL,
  `n_perut` varchar(200) NOT NULL,
  `n_gerak` varchar(200) NOT NULL,
  `n_gizi` varchar(200) NOT NULL,
  `n_potensi` varchar(200) NOT NULL,
  `n_mental` varchar(200) NOT NULL,
  `n_reproduksi` varchar(200) NOT NULL,
  `n_kematangan` varchar(200) NOT NULL,
  `n_hb` varchar(200) NOT NULL,
  `n_feses` varchar(200) NOT NULL,
  `n_jasmani` varchar(200) NOT NULL,
  `c_bayi` varchar(5) DEFAULT NULL,
  `c_imunisasi1` varchar(5) DEFAULT NULL,
  `c_imunisasi2` varchar(5) DEFAULT NULL,
  `c_imunisasi3` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`c_noreg`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `t_siswa`
--

INSERT INTO `t_siswa` (`c_noreg`, `n_nama`, `c_kelamin`, `t_waktu`, `n_tempat`, `t_berat`, `t_tinggi`, `t_tekanan`, `t_denyut`, `t_frequensi`, `t_suhu`, `n_mata`, `n_tht`, `n_gigi`, `n_leher`, `n_jantung`, `n_paru`, `n_perut`, `n_gerak`, `n_gizi`, `n_potensi`, `n_mental`, `n_reproduksi`, `n_kematangan`, `n_hb`, `n_feses`, `n_jasmani`, `c_bayi`, `c_imunisasi1`, `c_imunisasi2`, `c_imunisasi3`) VALUES
('1', '23', 'L', '0000-00-00', '1', '2', '3', '3', '4', '4', '45', '5', '6', '6', '6', '7', '7', '7', '7', '78', '8', '8', '8', '8', '8', '8', '8', 'tidak', 'ya', 'tidak', 'ya'),
('g', 'g', 'L', '0000-00-00', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g', 'tidak', 'tidak', 'tidak', 'tidak');

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE IF NOT EXISTS `t_user` (
  `userid` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `c_group` int(1) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `c_status` char(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`userid`, `username`, `c_group`, `pass`, `nama`, `c_status`) VALUES
(1, 'admin', 1, '21232f297a57a5a743894a0e4a801fc3', 'Sita', 'A'),
(2, 'operator', 2, '4b583376b2767b923c3e1da60d10de59', 'Suradi', 'T');

-- --------------------------------------------------------

--
-- Table structure for table `tr_agama`
--

CREATE TABLE IF NOT EXISTS `tr_agama` (
  `id_agama` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `n_agama` varchar(20) NOT NULL,
  PRIMARY KEY (`id_agama`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tr_agama`
--

INSERT INTO `tr_agama` (`id_agama`, `n_agama`) VALUES
(1, 'Islam'),
(2, 'Kristen'),
(3, 'Katolik'),
(4, 'Hindu'),
(5, 'Budha');

-- --------------------------------------------------------

--
-- Table structure for table `tr_diagnosis`
--

CREATE TABLE IF NOT EXISTS `tr_diagnosis` (
  `id_diagnosis` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `n_diagnosis` varchar(50) NOT NULL,
  PRIMARY KEY (`id_diagnosis`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tr_diagnosis`
--

INSERT INTO `tr_diagnosis` (`id_diagnosis`, `n_diagnosis`) VALUES
(1, 'Atopik Dermatitis'),
(2, 'LE'),
(3, 'Psoriasis'),
(4, 'Aczil');

-- --------------------------------------------------------

--
-- Table structure for table `tr_goldar`
--

CREATE TABLE IF NOT EXISTS `tr_goldar` (
  `id_goldar` int(11) NOT NULL,
  `n_goldar` varchar(20) NOT NULL,
  `c_status` varchar(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id_goldar`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tr_goldar`
--

INSERT INTO `tr_goldar` (`id_goldar`, `n_goldar`, `c_status`) VALUES
(1, 'O', 'A'),
(2, 'A', 'A'),
(3, 'B', 'A'),
(4, 'AB', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tr_group`
--

CREATE TABLE IF NOT EXISTS `tr_group` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `n_group` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tr_group`
--

INSERT INTO `tr_group` (`id`, `n_group`) VALUES
(1, 'Administrator'),
(2, 'Operator'),
(3, 'Pimpinan');

-- --------------------------------------------------------

--
-- Table structure for table `tr_jenis_pemeriksaan`
--

CREATE TABLE IF NOT EXISTS `tr_jenis_pemeriksaan` (
  `id_jenis_pem` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `n_jenis_pem` varchar(50) NOT NULL,
  PRIMARY KEY (`id_jenis_pem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tr_kabupaten`
--

CREATE TABLE IF NOT EXISTS `tr_kabupaten` (
  `id_kab` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_prop` int(11) NOT NULL,
  `n_kab` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kab`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=443 ;

--
-- Dumping data for table `tr_kabupaten`
--

INSERT INTO `tr_kabupaten` (`id_kab`, `id_prop`, `n_kab`) VALUES
(1, 1, 'Aceh Barat'),
(2, 1, 'Aceh Barat Daya'),
(3, 1, 'Aceh Besar'),
(4, 1, 'Aceh Jaya'),
(5, 1, 'Aceh Selatan'),
(6, 1, 'Aceh Singkil'),
(7, 1, 'Aceh Tamiang'),
(8, 1, 'Aceh Tengah'),
(9, 1, 'Aceh Tenggara'),
(10, 1, 'Aceh Timur'),
(11, 1, 'Aceh Utara'),
(12, 1, 'Bener Meriah'),
(13, 1, 'Bireuen'),
(14, 1, 'Gayo Lues'),
(15, 1, 'Nagan Raya'),
(16, 1, 'Pidi'),
(17, 1, 'Simeulue'),
(18, 1, 'Kota Banda Aceh'),
(19, 1, 'Kota Langsa'),
(20, 1, 'Kota Lhokseumawe'),
(21, 1, 'Kota Sabang'),
(22, 2, 'Asahan'),
(23, 2, 'Dairi'),
(24, 2, 'Deli Serdang'),
(25, 2, 'Humbang Hasundutan'),
(26, 2, 'Karo'),
(27, 2, 'Labuhan Batu'),
(28, 2, 'Langkat'),
(29, 2, 'Mandailing Natal'),
(30, 2, 'Nias'),
(31, 2, 'Nias Selatan'),
(32, 2, 'Pakpak Barat'),
(33, 2, 'Samosir'),
(34, 2, 'Serdang Bedagai'),
(35, 2, 'Simalungun'),
(36, 2, 'Tapanuli Selatan'),
(37, 2, 'Tapanuli Tengah'),
(38, 2, 'Tapanuli Utara'),
(39, 2, 'Toba Samosir'),
(40, 2, 'Kota Binjai'),
(41, 2, 'Kota Medan'),
(42, 2, 'Kota Padang Sidempuan'),
(43, 2, 'Kota Pematang Siantar'),
(44, 2, 'Kota Sibolga'),
(45, 2, 'Kota Tanjung Balai'),
(46, 2, 'Kota Tebing Tinggi'),
(47, 3, 'Agam'),
(48, 3, 'Dharmas Raya'),
(49, 3, 'Kepulauan Mentawai'),
(50, 3, 'Lima Puluh Kota'),
(51, 3, 'Padang Pariaman'),
(52, 3, 'Pasaman'),
(53, 3, 'Pasaman Barat'),
(54, 3, 'Pesisir Selatan'),
(55, 3, 'Sawah Lunto Sijunjung'),
(56, 3, 'Solok'),
(57, 3, 'Solok Selatan'),
(58, 3, 'Tanah Datar'),
(59, 3, 'Kota Bukit Tinggi'),
(60, 3, 'Kota Padang'),
(61, 3, 'Kota Padang Panjang'),
(62, 3, 'Kota Pariaman'),
(63, 3, 'Kota Payakumbuh'),
(64, 3, 'Kota Sawah Lunto'),
(65, 3, 'Kota Solok'),
(66, 4, 'Bengkalis'),
(67, 4, 'Indragiri Hilir'),
(68, 4, 'Indragiri Hulu'),
(69, 4, 'Kampar'),
(70, 4, 'Kuantan Singingi'),
(71, 4, 'Pelalawan'),
(72, 4, 'Rokan Hilir'),
(73, 4, 'Rokan Hulu'),
(74, 4, 'Siak'),
(75, 4, 'Kota Dumai'),
(76, 4, 'Kota.Pekanbaru'),
(77, 5, 'Karimun'),
(78, 5, 'Kepulauan Riau'),
(79, 5, 'Lingga'),
(80, 5, 'Natuna'),
(81, 5, 'Kota.Batam'),
(82, 5, 'Kota.Tanjung Pinang'),
(83, 6, 'Banyuasin'),
(84, 6, 'Lahat'),
(85, 6, 'Muara Enim'),
(86, 6, 'Musi Banyuasin'),
(87, 6, 'Musi Rawas'),
(88, 6, 'Ogan Ilir'),
(89, 6, 'Ogan Komering Ilir'),
(90, 6, 'Ogan Komering Ulu'),
(91, 6, 'Oku Selatan'),
(92, 6, 'Oku Timur'),
(93, 6, 'Kota Lubuk Linggau'),
(94, 6, 'Kota Pagar Alam'),
(95, 6, 'Kota Palembang'),
(96, 6, 'Kota Prabumulih'),
(97, 7, 'Bengkulu Selatan'),
(98, 7, 'Bengkulu Utara'),
(99, 7, 'Kaur'),
(100, 7, 'Kepahyang'),
(101, 7, 'Lebong'),
(102, 7, 'Muko-Muko'),
(103, 7, 'Rejang Lebong'),
(104, 7, 'Seluma'),
(105, 7, 'Kota Bengkulu'),
(106, 8, 'Batanghari'),
(107, 8, 'Bungo'),
(108, 8, 'Kerinci'),
(109, 8, 'Merangin'),
(110, 8, 'Muaro Jambi'),
(111, 8, 'Sarolangun'),
(112, 8, 'Tanjung Jabung Barat'),
(113, 8, 'Tanjung Jabung TImur'),
(114, 8, 'Tebo'),
(115, 8, 'Kota Jambi'),
(116, 9, 'Lampung Barat'),
(117, 9, 'Lampung Selatan'),
(118, 9, 'Lampung Tengah'),
(119, 9, 'Lampung Timur'),
(120, 9, 'Lampung Utara'),
(121, 9, 'Tenggamus'),
(122, 9, 'Tulang Bawang'),
(123, 9, 'Way Kanan'),
(124, 9, 'Kota Bandarlampung'),
(125, 9, 'Kota Metro'),
(126, 10, 'Bangka'),
(127, 10, 'Bangka Barat'),
(128, 10, 'Bangka Selatan'),
(129, 10, 'Bangka Tengah'),
(130, 10, 'Belitung'),
(131, 10, 'Belitung Timur'),
(132, 10, 'Kota Pangkal Pinang'),
(133, 11, 'Kepulauan Seribu'),
(134, 11, 'Kota Jakarta Barat'),
(135, 11, 'Kota Jakarta Pusat'),
(136, 11, 'Kota Jakarta Selatan'),
(137, 11, 'Kota Jakarta Timur'),
(138, 11, 'Kota Jakarta Utara'),
(139, 12, 'Bandung'),
(140, 12, 'Bekasi'),
(141, 12, 'Bogor'),
(142, 12, 'Ciamis'),
(143, 12, 'Cianjur'),
(144, 12, 'Cirebon'),
(145, 12, 'Garut'),
(146, 12, 'Indramayu'),
(147, 12, 'Karawang'),
(148, 12, 'Kuningan'),
(149, 12, 'Majalengka'),
(150, 4, 'Purwakarta'),
(151, 12, 'Subang'),
(152, 12, 'Sukabumi'),
(153, 12, 'Sumedang'),
(154, 12, 'Tasikmalaya'),
(155, 12, 'Kota Bandung'),
(156, 12, 'Kota Banjar'),
(157, 4, 'Kota Bekasi'),
(158, 4, 'Kota Bogor'),
(159, 12, 'Kota Cimahi'),
(160, 12, 'Kota Cirebon'),
(161, 4, 'Kota Depok'),
(162, 12, 'Kota Sukabumi'),
(163, 12, 'Kota Tasikmalaya'),
(164, 13, 'Banjarnegara'),
(165, 13, 'Banyumas'),
(166, 13, 'Batang'),
(167, 13, 'Blora'),
(168, 13, 'Boyolali'),
(169, 13, 'Brebes'),
(170, 13, 'Cilacap'),
(171, 13, 'Demak'),
(172, 13, 'Grobogan'),
(173, 13, 'Jepara'),
(174, 13, 'Karanganyar'),
(175, 13, 'Kebumen'),
(176, 13, 'Kendal'),
(177, 13, 'Klaten'),
(178, 13, 'Kudus'),
(179, 13, 'Magelang'),
(180, 13, 'Pati'),
(181, 13, 'Pekalongan'),
(182, 13, 'Pemalang'),
(183, 13, 'Purbalingga'),
(184, 13, 'Purworejo'),
(185, 13, 'Rembang'),
(186, 13, 'Semarang'),
(187, 13, 'Sragen'),
(188, 13, 'Sukoharjo'),
(189, 13, 'Tegal'),
(190, 13, 'Temanggung'),
(191, 13, 'Wonogiri'),
(192, 13, 'Wonosobo'),
(193, 13, 'Kota Magelang'),
(194, 13, 'Kota Pekalongan'),
(195, 13, 'Kota Salatiga'),
(196, 13, 'Kota Semarang'),
(197, 13, 'Kota Surakarta'),
(198, 13, 'Kota Tegal'),
(199, 14, 'Bantul'),
(200, 14, 'Gunung Kidul'),
(201, 14, 'Kulon Progo'),
(202, 14, 'Sleman'),
(203, 14, 'Kota Jogjakarta'),
(204, 15, 'Bangkalan'),
(205, 15, 'Banyuwangi'),
(206, 15, 'Blitar'),
(207, 15, 'Bojonegoro'),
(208, 15, 'Bondowoso'),
(209, 15, 'Gresik'),
(210, 15, 'Jember'),
(211, 15, 'Jombang'),
(212, 15, 'Kediri'),
(213, 15, 'Lamongan'),
(214, 15, 'Lumajang'),
(215, 15, 'Madiun'),
(216, 15, 'Magetan'),
(217, 15, 'Malang'),
(218, 15, 'Mojokerto'),
(219, 15, 'Nganjuk'),
(220, 15, 'Ngawi'),
(221, 15, 'Pacitan'),
(222, 15, 'Pamekasan'),
(223, 15, 'Pasuruan'),
(224, 15, 'Ponorogo'),
(225, 15, 'Probolinggo'),
(226, 15, 'Sampang'),
(227, 15, 'Sidoarjo'),
(228, 15, 'Situbondo'),
(229, 15, 'Sumenep'),
(230, 15, 'Trenggalek'),
(231, 15, 'Tuban'),
(232, 15, 'Tulungagung'),
(233, 15, 'Kota Batu'),
(234, 15, 'Kota Blitar'),
(235, 15, 'Kota Kediri'),
(236, 15, 'Kota Madiun'),
(237, 15, 'Kota Malang'),
(238, 15, 'Kota Mojokerto'),
(239, 15, 'Kota Pasuruan'),
(240, 15, 'Kota Probolinggo'),
(241, 15, 'Kota Surabaya'),
(242, 16, 'Kab Tangerang'),
(243, 16, 'Lebak'),
(244, 16, 'Pandeglang'),
(245, 16, 'Serang'),
(246, 16, 'Kota Cilegon'),
(247, 16, 'Kota Tangerang'),
(248, 17, 'Badung'),
(249, 17, 'Bangli'),
(250, 17, 'Buleleng'),
(251, 17, 'Gianyar'),
(252, 17, 'Jembrana'),
(253, 17, 'Karangasem'),
(254, 17, 'Klungkung'),
(255, 17, 'Tabanan'),
(256, 17, 'Kota Denpasar'),
(257, 18, 'Bengkayang'),
(258, 18, 'Kapuas Hulu'),
(259, 18, 'Ketapang'),
(260, 18, 'Landak'),
(261, 18, 'Melawi'),
(262, 18, 'Pontianak'),
(263, 18, 'Sambas'),
(264, 18, 'Sanggau'),
(265, 18, 'Sekadau'),
(266, 18, 'Sintang'),
(267, 18, 'Kota Pontianak'),
(268, 18, 'Kota Singkawang'),
(269, 19, 'Balangan'),
(270, 19, 'Banjar'),
(271, 19, 'Barito Kuala'),
(272, 19, 'Hulu Sungai Selatan'),
(273, 19, 'Hulu Sungai Tengah'),
(274, 19, 'Hulu Sungai Utara'),
(275, 19, 'Kotabaru'),
(276, 19, 'Tabalong'),
(277, 19, 'Tanah Bumbu'),
(278, 19, 'Tanah Laut'),
(279, 19, 'Tapin'),
(280, 19, 'Kota Banjar Baru'),
(281, 19, 'Kota Banjarmasin'),
(282, 20, 'Barito Selatan'),
(283, 20, 'Barito Timur'),
(284, 20, 'Barito Utara'),
(285, 20, 'Gunung Mas'),
(286, 20, 'Kapuas'),
(287, 20, 'Katingan'),
(288, 20, 'Kotawaringin Barat'),
(289, 20, 'Kotawaringin TImur'),
(290, 20, 'Lamandau'),
(291, 20, 'Murung Raya'),
(292, 20, 'Pulang Pisau'),
(293, 20, 'Seruyan'),
(294, 20, 'Sukamara'),
(295, 20, 'Kota.Palangkaraya'),
(296, 21, 'Berau'),
(297, 21, 'Bulungan'),
(298, 21, 'Kutai Barat'),
(299, 21, 'Kutai Kartanegara'),
(300, 21, 'Kutai Timur'),
(301, 21, 'Malinau'),
(302, 21, 'Nunukan'),
(303, 21, 'Pasir'),
(304, 21, 'Penajam Paser utara'),
(305, 21, 'Kota Balikpapan'),
(306, 21, 'Kota Bontang'),
(307, 21, 'Kota Samarinda'),
(308, 21, 'Kota Tarakan'),
(309, 22, 'Bolaang Mongondow'),
(310, 22, 'Kepulauan Talaud'),
(311, 22, 'Minahasa'),
(312, 22, 'Minahasa selatan'),
(313, 22, 'Minahasa Utara'),
(314, 22, 'sangihe'),
(315, 22, 'Kota Bitung'),
(316, 22, 'Kota Menado'),
(317, 22, 'Kota Tomohon'),
(318, 23, 'Banggai'),
(319, 23, 'Banggai Kepulauan'),
(320, 23, 'Buol'),
(321, 23, 'Donggala'),
(322, 23, 'Morowali'),
(323, 23, 'Parigi Moutong'),
(324, 23, 'Poso'),
(325, 23, 'Tojo Una- Una'),
(326, 23, 'Toli - toli'),
(327, 23, 'Kota Palu'),
(328, 24, 'Bombana'),
(329, 24, 'Buton'),
(330, 24, 'Kolaka'),
(331, 24, 'Kolaka Utara'),
(332, 24, 'Konawe'),
(333, 24, 'Konawe Selatan'),
(334, 24, 'Muna'),
(335, 24, 'Wakatobi'),
(336, 24, 'Kota Bau-Bau'),
(337, 24, 'Kota Kendari'),
(338, 25, 'Bantaeng'),
(339, 25, 'Barru'),
(340, 25, 'Bone'),
(341, 25, 'Bulukumba'),
(342, 25, 'Enrekang'),
(343, 25, 'Gowa'),
(344, 25, 'Janeponto'),
(345, 25, 'Luwu'),
(346, 25, 'Luwu Timur'),
(347, 25, 'Luwu Utara'),
(348, 25, 'Maros'),
(349, 25, 'Pangkajene Kepulauan'),
(350, 25, 'Pinrang'),
(351, 25, 'Selayar'),
(352, 25, 'Sindenren Rappang'),
(353, 25, 'Polewali Mamasa'),
(354, 25, 'Sinjai'),
(355, 25, 'Soppeng'),
(356, 25, 'Takalar'),
(357, 25, 'Tana Toraja'),
(358, 25, 'Wajo'),
(359, 25, 'Kota Makasar'),
(360, 25, 'Kota Pare-Pare'),
(361, 25, 'Kota.Palopo'),
(362, 26, 'Boalemo'),
(363, 26, 'Bone Bolango'),
(364, 26, 'Gorontalo'),
(365, 26, 'Pahuwato'),
(366, 26, 'Kota Gorontalo'),
(367, 27, 'Bima'),
(368, 27, 'Dompu'),
(369, 27, 'Lombok Barat'),
(370, 27, 'Lombok Tengah'),
(371, 27, 'Lombok Timur'),
(372, 27, 'Sumbawa'),
(373, 27, 'Sumbawa Barat'),
(374, 27, 'Kota Mataram'),
(375, 27, 'Kota.Bima'),
(376, 28, 'Alor'),
(377, 28, 'Belu'),
(378, 28, 'Ende'),
(379, 28, 'Flores TImur'),
(380, 28, 'Kupang'),
(381, 28, 'Lembata'),
(382, 28, 'Manggarai'),
(383, 28, 'Manggarai Barat'),
(384, 28, 'Ngada'),
(385, 28, 'Rote Ndao'),
(386, 28, 'Sikka'),
(387, 28, 'Sumba Barat'),
(388, 28, 'Sumba Timur'),
(389, 28, 'Timor Tengah Selatan'),
(390, 28, 'Timor Tengah Utara'),
(391, 28, 'Kota Kupang'),
(392, 29, 'Aru'),
(393, 29, 'Buru'),
(394, 29, 'Maluku Tengah'),
(395, 29, 'Maluku Tenggara'),
(396, 29, 'Maluku Tenggara Barat'),
(397, 29, 'Seram Bagian Barat'),
(398, 29, 'Seram Bagian Timur'),
(399, 29, 'Kota.Ambon'),
(400, 30, 'Halmahera Barat'),
(401, 30, 'Halmahera Selatan'),
(402, 30, 'Halmahera Tengah'),
(403, 30, 'Halmahera Timur'),
(404, 30, 'Halmahera Utara'),
(405, 30, 'Kepulauan Sula'),
(406, 30, 'Maluku Utara'),
(407, 30, 'Kota Ternate'),
(408, 30, 'Kota.Tidore Kepualauan'),
(409, 31, 'Asmat'),
(410, 31, 'Biak Numfor'),
(411, 31, 'Bovel Digoel'),
(412, 31, 'Jayapura'),
(413, 31, 'Jayawijaya'),
(414, 31, 'Keerom'),
(415, 31, 'Mappi'),
(416, 31, 'Merauke'),
(417, 31, 'Mimika'),
(418, 31, 'Nabire'),
(419, 31, 'Paniai'),
(420, 31, 'Pegunungan Bintang'),
(421, 31, 'Puncak Jaya'),
(422, 31, 'Sarmi'),
(423, 31, 'Supriori'),
(424, 31, 'Tolikara'),
(425, 31, 'Waropen'),
(426, 31, 'Yahukimo'),
(427, 31, 'Yapen Waropen'),
(428, 31, 'Kota Jayapura'),
(429, 31, 'Teluk Wondama'),
(430, 32, 'Fak-Fak'),
(431, 32, 'Kaimana'),
(432, 32, 'Manokwari'),
(433, 32, 'Raja Ampat'),
(434, 32, 'Teluk Bintuni'),
(435, 32, 'Sorong'),
(436, 32, 'Sorong Selatan'),
(437, 32, 'Kota Sorong'),
(438, 33, 'Majene'),
(439, 33, 'Mamasa'),
(440, 33, 'Mamuju'),
(441, 33, 'Mamuju Utara'),
(442, 33, 'Polewali Mandar');

-- --------------------------------------------------------

--
-- Table structure for table `tr_klasifikasi_med`
--

CREATE TABLE IF NOT EXISTS `tr_klasifikasi_med` (
  `id_klasifikasi` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `n_klasifikasi` varchar(50) NOT NULL,
  `c_status` varchar(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id_klasifikasi`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tr_klasifikasi_med`
--

INSERT INTO `tr_klasifikasi_med` (`id_klasifikasi`, `n_klasifikasi`, `c_status`) VALUES
(1, 'Dermatology Umum', 'A'),
(2, 'Alergi Imunologi', 'A'),
(3, 'Gender Dermatology', 'A'),
(4, 'Medical Estetik', 'A'),
(5, 'Revenewsasi (Memudakan Kembali)', 'A'),
(6, 'Penyakit Kelamin', 'A'),
(7, 'Lainnya', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tr_lab`
--

CREATE TABLE IF NOT EXISTS `tr_lab` (
  `id_lab` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `n_lab` varchar(30) NOT NULL,
  PRIMARY KEY (`id_lab`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tr_lab`
--

INSERT INTO `tr_lab` (`id_lab`, `n_lab`) VALUES
(1, 'Lab. I'),
(2, 'Lab. II');

-- --------------------------------------------------------

--
-- Table structure for table `tr_lab_spec`
--

CREATE TABLE IF NOT EXISTS `tr_lab_spec` (
  `id_lab_spec` int(11) NOT NULL AUTO_INCREMENT,
  `n_lab_spec` varchar(30) NOT NULL,
  PRIMARY KEY (`id_lab_spec`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tr_lab_spec`
--

INSERT INTO `tr_lab_spec` (`id_lab_spec`, `n_lab_spec`) VALUES
(1, 'Iq E Total'),
(2, 'Iq E Spesifi');

-- --------------------------------------------------------

--
-- Table structure for table `tr_propinsi`
--

CREATE TABLE IF NOT EXISTS `tr_propinsi` (
  `id_prop` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `n_prop` varchar(50) NOT NULL,
  PRIMARY KEY (`id_prop`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `tr_propinsi`
--

INSERT INTO `tr_propinsi` (`id_prop`, `n_prop`) VALUES
(1, 'Nanggroe Aceh Darussalam'),
(2, 'Sumatera Utara'),
(3, 'Sumatera Barat'),
(4, 'Riau'),
(5, 'Kepulauan Riau'),
(6, 'Sumatera Selatan'),
(7, 'Bengkulu'),
(8, 'Jambi'),
(9, 'Lampung'),
(10, 'Bangka Belitung'),
(11, 'DKI Jakarta'),
(12, 'Jawa Barat'),
(13, 'Jawa Tengah'),
(14, 'DI Jogjakarta'),
(15, 'Jawa Timur'),
(16, 'Banten'),
(17, 'Bali'),
(18, 'Kalimantan Barat'),
(19, 'Kalimantan Selatan'),
(20, 'Kalimantan Tengah'),
(21, 'Kalimantan Timur'),
(22, 'Sulawesi Utara'),
(23, 'Sulawesi Tengah'),
(24, 'Sulawesi Tenggara'),
(25, 'Sulawesi Selatan'),
(26, 'Gorontalo'),
(27, 'Nusa Tenggara Barat'),
(28, 'Nusa Tenggara Timur'),
(29, 'Maluku'),
(30, 'Maluku Utara'),
(31, 'Papua'),
(32, 'Irian Jaya Barat'),
(33, 'Sulawesi Barat');

-- --------------------------------------------------------

--
-- Table structure for table `tr_status`
--

CREATE TABLE IF NOT EXISTS `tr_status` (
  `id_status` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `n_status` varchar(10) NOT NULL,
  `c_status` varchar(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id_status`),
  FULLTEXT KEY `n_status` (`n_status`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tr_status`
--

INSERT INTO `tr_status` (`id_status`, `n_status`, `c_status`) VALUES
(1, 'Singel', 'A'),
(2, 'Kawin', 'A'),
(3, 'Duda', 'A'),
(4, 'Janda', 'A'),
(5, 'Lainnya', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `tr_tindakan`
--

CREATE TABLE IF NOT EXISTS `tr_tindakan` (
  `id_tindakan` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `n_tindakan` varchar(50) NOT NULL,
  `c_status` varchar(1) NOT NULL DEFAULT 'A',
  PRIMARY KEY (`id_tindakan`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tr_tindakan`
--

INSERT INTO `tr_tindakan` (`id_tindakan`, `n_tindakan`, `c_status`) VALUES
(1, 'Medik', 'A'),
(2, 'Skin Rejuve', 'A'),
(3, 'Surgey', 'A'),
(4, 'Skin Care', 'A'),
(5, 'Tindakan Kosmetik', 'A');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
