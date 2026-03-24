<!DOCTYPE html>
<html>
<head>
    <title><?= ucfirst($type) ?> Certificate</title>
    <style>
        @page { size: A4 portrait; margin: 1.5cm; }
        body { font-family: Arial, sans-serif; font-size: 14pt; line-height: 1.6; }
        .center { text-align: center; }
        .stamp { margin-top: 60px; border: 2px dashed #000; padding: 20px; display: inline-block; }
    </style>
</head>
<body onload="window.print();">

    <div class="center">
        <h2 style="margin-bottom:0;">{school_name}</h2> <!-- Replace or use real school name -->
        <h3 style="margin-top:5px;"><?= ucfirst($type) ?> Certificate</h3>

        <div style="margin: 40px 0; text-align:left; max-width:800px; margin-left:auto; margin-right:auto;">
            <?= str_replace(
                ['{student_name}', '{admission_no}', '{roll_no}', '{father_name}', '{class_name}', '{financial_year}', '{issue_date}'],
                [
                    esc($student['first_name'] . ' ' . $student['last_name']),
                    esc($student['admission_no']),
                    esc($student['roll_no']),
                    esc($student['father_name'] ?? 'N/A'),
                    esc($student['class_name']),
                    esc($student['financial_year_name']),
                    $issue_date
                ],
                $template['template_content']
            ) ?>
        </div>

        <div class="stamp center">
            <p>Principal / Authorized Signatory</p>
            <p>Date: <?= $issue_date ?></p>
        </div>
    </div>

</body>
</html>