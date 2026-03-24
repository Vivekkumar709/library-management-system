<section class="content">
<div class="white_card">
    <div class="white_card_header">
        <h4>Attendance Report</h4>
        <a href="<?= site_url('attendance/mark') ?>" class="btn btn-primary">Mark New Attendance</a>
    </div>
    <div class="white_card_body">
        <table class="table table-striped lms_table_active3" id="table-att">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Admission No</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Percentage</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($content_data['data']['report'] as $r): ?>
                <tr>
                    <td><?= esc($r['first_name'].' '.$r['last_name']) ?></td>
                    <td><?= esc($r['admission_no']) ?></td>
                    <td><?= $r['present'] ?></td>
                    <td><?= $r['absent'] ?></td>
                    <td><strong><?= $r['percentage'] ?>%</strong></td>
                    <td><a href="<?= site_url('attendance/report/'.$r['student_id']) ?>" class="btn btn-sm btn-info">Full Report</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</section>