<?php /** Kalender Akademik Dinamis (FullCalendar) */ ?>
<div class="animate-fade-in">
    <div class="page-header d-flex justify-between align-center">
        <div>
            <h1>Kalender Akademik</h1>
            <p class="text-muted">Agenda kegiatan akademik kampus.</p>
        </div>
    </div>

    <!-- Include FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />

    <div class="dashboard-grid" style="grid-template-columns: 1fr <?= has_role('admin') ? '300px' : '0px' ?>;">
        <!-- Calendar View -->
        <div class="card">
            <div class="card-body">
                <div id='calendar'></div>
            </div>
        </div>

        <?php if (has_role('admin')): ?>
            <!-- Form Tambah Event -->
            <div class="card" style="height:fit-content;">
                <div class="card-header">
                    <h3>Tambah Agenda</h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= url('/calendar/events') ?>">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label class="form-label">Judul Agenda</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe</label>
                            <select name="event_type" class="form-control" required>
                                <option value="perkuliahan">Perkuliahan (Hijau)</option>
                                <option value="ujian">Ujian (Kuning)</option>
                                <option value="libur">Libur (Merah)</option>
                                <option value="lainnya">Lainnya (Biru)</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Tgl Mulai</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Tgl Selesai</label>
                                <input type="date" name="end_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Keterangan</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;">Simpan Agenda</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal untuk Detail Event -->
<div id="eventModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div class="card" style="width:400px;max-width:90%;">
        <div class="card-header d-flex justify-between align-center">
            <h3 id="modalTitle">Judul</h3>
            <button onclick="closeModal()" style="background:none;border:none;font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <div class="card-body">
            <p id="modalDesc"></p>
            <div id="deleteFormContainer" style="margin-top:20px;display:none;">
                <form id="deleteForm" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus agenda ini?')">Hapus Agenda</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var isAdmin = <?= has_role('admin') ? 'true' : 'false' ?>;
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            events: '<?= url('/calendar/events') ?>',
            eventClick: function(info) {
                document.getElementById('modalTitle').innerText = info.event.title;
                document.getElementById('modalDesc').innerText = info.event.extendedProps.description || 'Tidak ada keterangan.';
                
                if (isAdmin) {
                    var deleteForm = document.getElementById('deleteForm');
                    deleteForm.action = '<?= url('/calendar/events/') ?>' + info.event.id + '/delete';
                    document.getElementById('deleteFormContainer').style.display = 'block';
                }
                
                document.getElementById('eventModal').style.display = 'flex';
            }
        });
        
        calendar.render();
    });
    
    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
    }
</script>
