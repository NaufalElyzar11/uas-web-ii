<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h1 class="mb-4">Riwayat Kunjungan</h1>
    
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> Fitur riwayat kunjungan sedang dalam pengembangan. Silakan kunjungi kembali nanti.
    </div>
    
    <div class="history-tabs">
        <ul class="nav nav-tabs" id="historyTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Yang Akan Datang</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Selesai</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled" type="button" role="tab" aria-controls="canceled" aria-selected="false">Dibatalkan</button>
            </li>
        </ul>
        <div class="tab-content" id="historyTabContent">
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                <div class="empty-history text-center py-5">
                    <div class="empty-icon">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <h3 class="mt-3">Belum Ada Rencana Perjalanan</h3>
                    <p class="text-muted">Anda belum memiliki jadwal perjalanan yang akan datang.</p>
                    <a href="<?= base_url('destinasi') ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-ticket-alt"></i> Booking Sekarang
                    </a>
                </div>
            </div>
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="empty-history text-center py-5">
                    <div class="empty-icon">
                        <i class="far fa-check-circle"></i>
                    </div>
                    <h3 class="mt-3">Belum Ada Riwayat Perjalanan</h3>
                    <p class="text-muted">Anda belum memiliki perjalanan yang selesai.</p>
                </div>
            </div>
            <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                <div class="empty-history text-center py-5">
                    <div class="empty-icon">
                        <i class="far fa-times-circle"></i>
                    </div>
                    <h3 class="mt-3">Tidak Ada Pembatalan</h3>
                    <p class="text-muted">Anda belum memiliki perjalanan yang dibatalkan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 900px;
    margin: 0 auto;
}
.empty-history {
    margin: 20px 0;
}
.empty-icon {
    font-size: 5rem;
    color: #ddd;
}
.btn-primary {
    background-color: #0066ff;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 20px;
}
.nav-tabs .nav-item {
    margin-bottom: -1px;
}
.nav-tabs .nav-link {
    border: 1px solid transparent;
    border-top-left-radius: 0.25rem;
    border-top-right-radius: 0.25rem;
    padding: 0.5rem 1rem;
    color: #495057;
    text-decoration: none;
    cursor: pointer;
}
.nav-tabs .nav-link.active {
    color: #0066ff;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    font-weight: 600;
}
.tab-content {
    padding: 20px 0;
}
.tab-pane {
    display: none;
}
.tab-pane.active {
    display: block;
}
.tab-pane.show {
    display: block;
}
.fade {
    transition: opacity 0.15s linear;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple tab functionality
    const tabs = document.querySelectorAll('.nav-link');
    const tabContents = document.querySelectorAll('.tab-pane');
    
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            tabs.forEach(t => t.classList.remove('active'));
            tabContents.forEach(c => {
                c.classList.remove('show');
                c.classList.remove('active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            
            // Show corresponding content
            const target = this.getAttribute('data-bs-target').substring(1);
            const content = document.getElementById(target);
            content.classList.add('show');
            content.classList.add('active');
        });
    });
});
</script>
<?= $this->endSection() ?>
