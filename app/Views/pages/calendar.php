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
            <div id="deleteFormContainer" style="margin-top:20px;display:none; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-primary btn-sm" id="btnEditEvent" onclick="toggleEditForm()">Edit Agenda</button>
                <form id="deleteForm" method="POST" action="">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus agenda ini?')">Hapus Agenda</button>
                </form>
            </div>
            
            <div id="editFormContainer" style="display:none; margin-top:20px; padding-top:20px; border-top:1px solid var(--border-color);">
                <form id="editForm" method="POST" action="">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label">Judul Agenda</label>
                        <input type="text" name="title" id="editTitle" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tipe</label>
                        <select name="event_type" id="editType" class="form-control" required>
                            <option value="perkuliahan">Perkuliahan</option>
                            <option value="ujian">Ujian</option>
                            <option value="libur">Libur</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Tgl Mulai</label>
                            <input type="date" name="start_date" id="editStart" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tgl Selesai</label>
                            <input type="date" name="end_date" id="editEnd" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Keterangan</label>
                        <textarea name="description" id="editDesc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-between">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="toggleEditForm()">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                    </div>
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
                    
                    var editForm = document.getElementById('editForm');
                    editForm.action = '<?= url('/calendar/events/') ?>' + info.event.id + '/update';
                    
                    document.getElementById('editTitle').value = info.event.title;
                    document.getElementById('editType').value = info.event.extendedProps.event_type || 'lainnya';
                    
                    // Format dates for inputs (YYYY-MM-DD)
                    var startStr = info.event.startStr.split('T')[0];
                    var endStr = info.event.endStr ? info.event.endStr.split('T')[0] : startStr;
                    
                    // FullCalendar exclusive end date means endStr is technically the day after visually
                    // We need to subtract 1 day from end date for the form if it exists
                    if (info.event.end) {
                        var d = new Date(info.event.end);
                        d.setDate(d.getDate() - 1);
                        endStr = d.toISOString().split('T')[0];
                    }
                    
                    document.getElementById('editStart').value = startStr;
                    document.getElementById('editEnd').value = endStr;
                    document.getElementById('editDesc').value = info.event.extendedProps.description || '';
                    
                    document.getElementById('deleteFormContainer').style.display = 'flex';
                    document.getElementById('editFormContainer').style.display = 'none';
                    document.getElementById('modalDesc').style.display = 'block';
                }
                
                document.getElementById('eventModal').style.display = 'flex';
            }
        });
        
        calendar.render();
    });
    
    function closeModal() {
        document.getElementById('eventModal').style.display = 'none';
    }
    
    function toggleEditForm() {
        var editContainer = document.getElementById('editFormContainer');
        var descContainer = document.getElementById('modalDesc');
        var btnEdit = document.getElementById('btnEditEvent');
        
        if (editContainer.style.display === 'none') {
            editContainer.style.display = 'block';
            descContainer.style.display = 'none';
            btnEdit.style.display = 'none';
        } else {
            editContainer.style.display = 'none';
            descContainer.style.display = 'block';
            btnEdit.style.display = 'block';
        }
    }
</script>
