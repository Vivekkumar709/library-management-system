<div class="white_card">
    <div class="white_card_header">
        <h4>Result Management</h4>
        <a href="<?= site_url('result/enter') ?>" class="btn btn-primary">Enter New Marks</a>
    </div>
    <div class="white_card_body">
        <table class="table table-striped lms_table_active3">
            <thead>
                <tr>
                    <th>Student</th><th>Exam</th><th>Total</th><th>Obtained</th><th>%</th><th>Grade</th><th>Status</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($content_data['data']['results'] as $r): ?>
                <tr>
                    <td><?= esc($r['first_name']) ?> (<?= $r['admission_no'] ?>)</td>
                    <td><?= esc($r['exam_name']) ?></td>
                    <td><?= $r['total_marks'] ?></td>
                    <td><?= $r['obtained_marks'] ?></td>
                    <td><strong><?= $r['percentage'] ?>%</strong></td>
                    <td><?= $r['grade'] ?></td>
                    <td><span class="badge bg-<?= $r['result_status']=='Pass' ? 'success' : 'danger' ?>"><?= $r['result_status'] ?></span></td>
                    <td>
                        <a href="<?= site_url('result/printReport/'.$r['student_id'].'/'.$r['exam_type_id']) ?>" class="btn btn-sm btn-info" target="_blank">📄 Print</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>