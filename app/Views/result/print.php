<!DOCTYPE html>
<html><head><title>Marksheet</title><style>@page{size:A4;margin:15mm}</style></head>
<body style="font-family:Arial; text-align:center">
    <h2><?= esc($result[0]['school_name'] ?? 'SCHOOL NAME') ?> - Mark Sheet</h2>
    <h3><?= esc($result[0]['first_name']) ?> | Class <?= $result[0]['class_name'] ?></h3>
    <table border="1" cellpadding="8" style="width:100%">
        <tr><th>Subject</th><th>Max</th><th>Obtained</th></tr>
        <?php foreach($result as $sub): ?>
        <tr><td><?= $sub['subject_name'] ?></td><td><?= $sub['max_marks'] ?></td><td><?= $sub['marks_obtained'] ?></td></tr>
        <?php endforeach; ?>
    </table>
    <h3>Percentage: <?= $result[0]['percentage'] ?>% | Grade: <?= $result[0]['grade'] ?> | Result: <b><?= $result[0]['result_status'] ?></b></h3>
    <button onclick="window.print()">🖨️ Print Marksheet</button>
</body></html>