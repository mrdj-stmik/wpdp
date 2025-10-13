<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h2 class="mb-4">Dashboard WPDP BPS Kab. Tasikmalaya</h2>
            <div class="row mb-4">
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title fs-5">Pagu Anggaran Awal</h5>
                            <p class="card-text display-6 fs-5">Rp <?= number_format(14342788000 ?? 0, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-info">
                        <div class="card-body">
                            <h5 class="card-title fs-5">Pagu Revisi Terakhir</h5>
                            <p class="card-text display-6 fs-5">Rp <?= number_format($summary['pagu_revisi_terakhir'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                 <div class="col-md-4 mb-3">
                    <div class="card text-white bg-secondary">
                        <div class="card-body">
                            <h5 class="card-title fs-5">Total RPD sampai Bulan <?= esc($nama_bulan_sekarang ?? ''); ?></h5>
                            <p class="card-text display-6 fs-5">Rp <?= number_format($summary['total_rpd_setahun'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                 <div class="col-md-4 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title fs-5">Total Realisasi sampai Bulan <?= esc($nama_bulan_sekarang ?? ''); ?></h5>
                            <p class="card-text display-6 fs-5">Rp <?= number_format($summary['total_realisasi_wpdp_setahun'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title fs-5">Sisa Pagu Anggaran</h5>
                            <p class="card-text display-6 fs-5">Rp <?= number_format($summary['sisa_pagu'] ?? 0, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>
                 <div class="col-md-4 mb-3">
                    <div class="card text-dark bg-light">
                        <div class="card-body">
                            <h5 class="card-title fs-5">Tahun Anggaran Aktif</h5>
                            <p class="card-text display-6 fs-5"><?= esc($tahun_anggaran_aktif ?? date('Y')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    Progres RPD vs Realisasi Bulanan
                </div>
                <div class="card-body" style="height: 500px;">
                    <canvas id="rpdRealisasiChartCanvas"></canvas>
                    <p class="text-center text-muted">Memuat grafik...</p>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    Akses Cepat
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap">
                        <a href="<?= base_url('rpd'); ?>" class="btn btn-outline-primary btn-lg mr-2 mb-2">Lihat Laporan RPD</a>
                        <a href="<?= base_url('masterdata/ro'); ?>" class="btn btn-outline-info btn-lg mr-2 mb-2">Input/Edit WPDP Bulanan</a>
                        </div>
                </div>
            </div>
<div class="card mb-4">
    <div class="card-header">
        Highlight & Peringatan
    </div>
    <div class="card-body">
        <?php if (!empty($highlights)): ?>
            <ul class="list-group">
                <?php foreach ($highlights as $item): ?>
                    <li class="list-group-item">
                        <strong>Kode RO:</strong> <?= esc($item['kode_ro']); ?> - <?= esc($item['nama_ro']); ?>
                        <br>
                        <small class="text-muted">Bulan: <?= esc($item['bulan']); ?>, Tahun: <?= esc($item['tahun']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="card-text text-muted">Tidak ada peringatan atau highlight saat ini.</p>
        <?php endif; ?>
    </div>
</div>
            </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let rpdRealisasiChart; // Variabel global untuk menyimpan instansiasi grafik

    const monthlyRpdData = {
        labels: <?= json_encode($monthly_data['labels'] ?? []); ?>,
        rpd_data: <?= json_encode(array_map(fn($value) => $value / 1_000_000, $monthly_data['rpd_data'] ?? [])); ?>,
        realisasi_data: <?= json_encode(array_map(fn($value) => $value / 1_000_000, $monthly_data['realisasi_data'] ?? [])); ?>
    };

    console.log(monthlyRpdData); // Debugging data

    const ctx = document.getElementById('rpdRealisasiChartCanvas');

    if (ctx) {
        // Hancurkan grafik jika sudah ada
        if (rpdRealisasiChart) {
            rpdRealisasiChart.destroy();
        }

        // Buat grafik baru
        rpdRealisasiChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyRpdData.labels,
                datasets: [
                    {
                        label: 'Total RPD (Juta)',
                        data: monthlyRpdData.rpd_data,
                        backgroundColor: 'rgba(187, 199, 17, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Total Realisasi (Juta)',
                        data: monthlyRpdData.realisasi_data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100, // Batasi nilai maksimum (dalam jutaan)
                        ticks: {
                            callback: function(value) {
                                return value + ' Juta'; // Tambahkan label "Juta"
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + (context.parsed.y * 1_000_000).toLocaleString('id-ID'); // Kembalikan ke nilai asli
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    } else {
        console.error("Canvas element for chart not found!");
    }
</script>
<?= $this->endSection(); ?>