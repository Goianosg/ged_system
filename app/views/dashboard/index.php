
<?php require APPROOT . '/views/inc/header.php'; ?>

<?php require APPROOT . '/views/inc/sidebar.php'; ?>


<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= URLROOT; ?>/dashboard">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>-
            </ol>
        </nav>
    </div><section class="section dashboard">
        <div class="row">

            <div class="col-lg-8">
                <div class="row">

                    <div class="col-md-6">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Colaboradores <span>| Total</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person-badge"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $data['colaboradorCount']; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">cadastrados</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><div class="col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Arquivos <span>| Total</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $data['fileCount']; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">no sistema</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><div class="col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Usuários <span>| Sistema</span></h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $data['userCount']; ?></h6>
                                        <span class="text-muted small pt-2 ps-1">com acesso</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div></div>
            </div><div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Uso do Disco do Servidor</h5>
                        
                        <div id="diskSpaceChart"></div>

                        <div class="text-center small mt-2">
                            <strong>Total:</strong> <?= $data['diskTotalFormatted']; ?> |
                            <strong>Livre:</strong> <?= $data['diskFreeFormatted']; ?>
                        </div>
                    </div>
                </div>
            </div></div>
    </section>

</main><script src="<?= URLROOT; ?>/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        new ApexCharts(document.querySelector("#diskSpaceChart"), {
            series: [<?= $data['diskUsed']; ?>, <?= $data['diskFree']; ?>],
            chart: {
                height: 300,
                type: 'donut',
                toolbar: { show: true }
            },
            labels: ['Espaço Usado', 'Espaço Livre'],
            colors: ['#007bff', '#28a745'], // Azul para usado, Verde para livre
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            tooltip: {
                y: {
                    formatter: function (val) {
                        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
                        const bytes = val;
                        if (bytes === 0) return '0 B';
                        const i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + units[i];
                    }
                }
            }
        }).render();
    });
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
