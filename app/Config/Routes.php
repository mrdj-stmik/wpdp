<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute Default
$routes->get('/', 'Dashboard::index');

//$routes->get('/dashboard', 'Dashboard::index'); // Atau URL /dashboard
// Rute untuk Halaman Lain (di luar modul WPDP utama)
$routes->get('tentang', 'Tentang::index'); // Contoh halaman tentang

//$routes->get('/', 'Dashboard::index'); // Mengarahkan root URL ke Dashboard::index


// =============================================================================
// Rute untuk Modul WPDP
// =============================================================================

// Halaman Daftar WPDP
$routes->get('/wpdp', 'Wpdp::index');

// Menampilkan Form Tambah WPDP (form kosong)
// URL: /wpdp/form
$routes->get('/wpdp/form', 'Wpdp::form');

// Menampilkan Form Edit WPDP (form dengan data terisi)
// URL: /wpdp/form/{id}
$routes->get('/wpdp/form/(:num)', 'Wpdp::form/$1');

// Menampilkan Form Tambah WPDP dengan data pre-fill dari RO
// URL: /wpdp/createFromRo/{roId} (Ini sudah benar di kode Anda)
$routes->get('/wpdp/createFromRo/(:num)', 'Wpdp::createFromRo/$1');

// Proses Simpan Data WPDP Baru
// URL: /wpdp/simpan (Metode POST)
$routes->post('/wpdp/simpan', 'Wpdp::simpan');
$routes->get('/wpdp/create', 'Wpdp::create');
$routes->get('wpdp/edit/(:num)', 'Wpdp::edit/$1');

// Proses Update Data WPDP
// URL: /wpdp/update/{id} (Metode POST)
$routes->post('/wpdp/update/(:num)', 'Wpdp::update/$1');

// Proses Hapus Data WPDP
// URL: /wpdp/delete/{id} (Sebaiknya gunakan metode POST/DELETE, tapi GET juga bisa jika memang desainnya begitu)
// Menggunakan POST lebih disarankan untuk operasi penghapusan
$routes->post('/wpdp/delete/(:num)', 'Wpdp::delete/$1');
// Jika Anda tetap ingin menggunakan GET (kurang disarankan untuk hapus):
// $routes->get('/wpdp/delete/(:num)', 'Wpdp::delete/$1');

$routes->get('/masterdata/detil', 'Laporan::rekapitulasiRoKomponen');

// Rute untuk Export Data
$routes->get('/wpdp/export', 'Wpdp::export'); // Export Bulanan (Excel)
$routes->get('/wpdp/export-triwulan', 'Wpdp::export_triwulan'); // Export Triwulan (Excel)

// --- Rute BARU untuk Laporan Komprehensif ---
$routes->get('/wpdp/laporan_komprehensif', 'Wpdp::laporanKomprehensif');
$routes->get('laporan/export-komprehensif/(:num)', 'Laporan::exportComprehensiveReport/$1');
$routes->get('laporan/export-komprehensif', 'Laporan::exportComprehensiveReport/'.date('Y')); // Contoh default tahun saat ini
// --- AKHIR Rute BARU ---

// Rute untuk Laporan Triwulan (Menampilkan View Rekap)
$routes->get('/wpdp/laporan-triwulan', 'Wpdp::laporanTriwulan');

// --- Rute untuk Panggilan AJAX Anggaran (dipindah dari Model ke Controller) ---
// Mengambil nilai anggaran berdasarkan Detil ID dan Bulan
// URL: /wpdp/getAnggaranByDetilMonthYear/{detilId}/{bulan}
$routes->get('/wpdp/getAnggaranByDetilMonthYear/(:num)/(:any)', 'Wpdp::getAnggaranByDetilMonthYear/$1/$2');
// --- AKHIR Rute AJAX Anggaran ---

// Rute untuk Halaman Kendala & Solusi (Jika ada halaman terpisah)
// Jika Kendala & Solusi diinput di form utama, rute ini mungkin tidak perlu
// $routes->get('/wpdp/kendala-solusi/(:num)', 'Wpdp::kendalaSolusi/$1'); // Contoh rute jika ada halaman terpisah
// $routes->post('/wpdp/update-kendala-solusi/(:num)', 'Wpdp::updateKendalaSolusi/$1'); // Contoh rute proses update

// =============================================================================
// Rute untuk Modul RPD (BARU)
// =============================================================================

// Halaman Laporan RPD
$routes->get('/rpd', 'Rpd::index');

// Rute untuk Export Laporan RPD (Excel)
$routes->get('/rpd/export', 'Rpd::export');

// Rute untuk Form Input RPD (akan dibuat nanti)
$routes->get('/rpd/form', 'Rpd::formRpd');
// $routes->get('/rpd/form/(:num)', 'Rpd::form/$1'); // Edit RPD
$routes->post('/rpd/simpan', 'Rpd::simpanRpd');
$routes->post('/rpd/update/(:num)', 'Rpd::update/$1');
$routes->post('/rpd/delete/(:num)', 'Rpd::delete/$1');
// Rute AJAX untuk mengambil data RPD existing
$routes->post('/rpd/getExistingRpd', 'Rpd::getExistingRpd'); // Rute POST untuk AJAX
// Rute AJAX untuk mendapatkan Pagu Revisi Tersedia
$routes->post('/rpd/getAvailablePaguRpd', 'Rpd::getAvailablePaguRpd'); // Rute POST untuk AJAX


// =============================================================================
// Rute untuk Panggilan AJAX (untuk dropdown dinamis) - DIPINDAH KE BAWAH
// =============================================================================


// =============================================================================
// Rute untuk Modul Masterdata (jika controller terpisah)
// =============================================================================
// Jika Anda punya controller terpisah untuk Masterdata, rute-rute ini bisa di sini
// Contoh:
$routes->get('masterdata/ro', 'MasterdataController::index');
$routes->get('masterdata/kegiatan/(:num)', 'MasterdataController::getKegiatan/$1');
$routes->get('masterdata/ro-list/(:num)', 'MasterdataController::getRoList/$1');
$routes->get('masterdata/total_pagu', 'MasterdataController::totalPagu');

// Rute untuk Modul Program, Kegiatan, KRO, RO (CRUD)
// Jika controller terpisah, rute-rute ini bisa di sini
// Contoh:
// $routes->group('program', function($routes) {
//     $routes->get('/', 'ProgramController::index');
//     $routes->get('create', 'ProgramController::create');
//     $routes->post('store', 'ProgramController::store');
//     $routes->get('edit/(:num)', 'ProgramController::edit/$1');
//     $routes->post('update/(:num)', 'ProgramController::update/$1');
//     $routes->post('delete/(:num)', 'ProgramController::delete/$1'); // Gunakan POST untuk delete
// });
// Ulangi untuk 'kegiatan', 'kro', 'ro' jika controllernya terpisah
// Jika CRUD Masterdata ada di controller yang sama, sesuaikan rutenya.
// Berdasarkan kode routes Anda, tampaknya CRUD masterdata ada di controller terpisah.
// Saya akan biarkan rute-rute CRUD masterdata seperti yang Anda berikan,
// tetapi disarankan menggunakan POST untuk delete dan mengelompokkannya (group).

// Rute CRUD Program (biarkan seperti yang Anda berikan)
$routes->get('program', 'ProgramController::index');
$routes->get('program/create', 'ProgramController::create');
$routes->post('program/store', 'ProgramController::store');
$routes->get('program/edit/(:num)', 'ProgramController::edit/$1');
$routes->post('program/update/(:num)', 'ProgramController::update/$1');
$routes->post('program/delete/(:num)', 'ProgramController::delete/$1'); // Gunakan POST

// Rute CRUD Kegiatan (biarkan seperti yang Anda berikan)
$routes->get('kegiatan', 'KegiatanController::index');
$routes->get('kegiatan/create', 'KegiatanController::create');
$routes->post('kegiatan/store', 'KegiatanController::store');
$routes->get('kegiatan/edit/(:num)', 'KegiatanController::edit/$1');
$routes->post('kegiatan/update/(:num)', 'KegiatanController::update/$1');
$routes->post('kegiatan/delete/(:num)', 'KegiatanController::delete/$1'); // Gunakan POST

// Rute CRUD KRO (biarkan seperti yang Anda berikan)
$routes->get('kro', 'KroController::index');
$routes->get('kro/create', 'KroController::create');
$routes->post('kro/store', 'KroController::store');
$routes->get('kro/edit/(:num)', 'KroController::edit/$1');
$routes->post('kro/update/(:num)', 'KroController::update/$1');
$routes->post('kro/delete/(:num)', 'KroController::delete/$1'); // Gunakan POST

// Rute CRUD RO (biarkan seperti yang Anda berikan)
$routes->get('ro', 'RoController::index');
$routes->get('ro/create', 'RoController::create');
$routes->post('ro/store', 'RoController::store');
$routes->get('ro/edit/(:num)', 'RoController::edit/$1');
$routes->post('ro/update/(:num)', 'RoController::update/$1');
$routes->post('ro/delete/(:num)', 'RoController::delete/$1'); // Gunakan POST

$routes->get('detil', 'DetilController::index');
$routes->get('detil/create', 'DetilController::create');
$routes->post('detil/store', 'DetilController::store');
$routes->get('detil/edit/(:num)', 'DetilController::edit/$1');
$routes->post('detil/update/(:num)', 'DetilController::update/$1');
$routes->post('detil/delete/(:num)', 'DetilController::delete/$1'); // Gunakan POST

$routes->get('ajaxcontroller/program/(:num)', 'AjaxController::program/$1');
$routes->get('ajaxcontroller/kegiatan/(:num)', 'AjaxController::kegiatan/$1');
$routes->get('ajaxcontroller/kro/(:num)', 'AjaxController::kro/$1');
$routes->get('ajaxcontroller/ro/(:num)', 'AjaxController::ro/$1');
$routes->get('ajaxcontroller/komponen/(:num)', 'AjaxController::komponen/$1');
$routes->get('ajaxcontroller/akun/(:num)', 'AjaxController::akun/$1');
$routes->get('ajaxcontroller/detil/(:num)', 'AjaxController::detil/$1');
$routes->get('ajaxcontroller/rpd/(:num)', 'AjaxController::rpd/$1');

$routes->get('detilrevisi/index/(:num)', 'DetilRevisi::index/$1');
$routes->get('detilrevisi/create/(:num)', 'DetilRevisi::create/$1');
$routes->post('detilrevisi/store', 'DetilRevisi::store');
$routes->get('detilrevisi/edit/(:num)', 'DetilRevisi::edit/$1');
$routes->post('detilrevisi/update/(:num)', 'DetilRevisi::update/$1');
$routes->post('detilrevisi/delete/(:num)', 'DetilRevisi::delete/$1');

// Rute AJAX Masterdata (jika ada di MasterdataController)
// Berdasarkan kode routes Anda, tampaknya beberapa AJAX endpoint ada di MasterdataController
// Jika Anda memindahkan semua AJAX dropdown ke WpdpController (seperti di bagian atas),
// maka rute-rute di bawah ini bisa dihapus.
// Jika tidak, pastikan rute ini mengarah ke controller yang benar.
// $routes->get('masterdata/kegiatan/(:num)', 'MasterdataController::getKegiatan/$1'); // Contoh
// $routes->get('masterdata/ro-list/(:num)', 'MasterdataController::getRoList/$1'); // Contoh
// $routes->get('masterdata/ro-list-by-program/(:num)', 'MasterdataController::roListByProgram/$1'); // Contoh

// Rute WPDP yang sepertinya duplikat atau tidak standar (hapus jika tidak digunakan)
// $routes->get('wpdp/create/(:num)', 'Wpdp::create/$1'); // Ini tumpang tindih dengan createFromRo?
// $routes->get('wpdp/edit/(:num)', 'Wpdp::form/$1'); // Sudah ada di /wpdp/form/(:num)
// $routes->post('wpdp/update/(:num)', 'Wpdp::update/$1'); // Sudah ada di atas


// Rute Kendala & Solusi (jika ada halaman terpisah, sesuaikan)
// $routes->get('wpdp/kendala-solusi/(:num)', 'Wpdp::kendalaSolusi/$1'); // Jika ada halaman terpisah
// $routes->post('wpdp/update-kendala-solusi/(:num)', 'Wpdp::updateKendalaSolusi/$1'); // Jika ada proses update terpisah

// Rute Hapus WPDP (duplikat, hapus salah satu)
// $routes->get('/wpdp/delete/(:num)', 'Wpdp::delete/$1'); // Duplikat, hapus jika pakai POST di atas
