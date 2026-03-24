<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <h3><?= ucfirst($content_data['data']['type']) ?> Certificate</h3>
                <small>Student: <?= esc($content_data['data']['student']['first_name'] . ' ' . $content_data['data']['student']['last_name']) ?></small>
            </div>

            <div class="white_card_body">
                <div class="preview-box border p-4 mb-4" style="min-height:400px; background:#fff;">
                    <?php
                    $content = $content_data['data']['template']['template_content'];

                    // Simple placeholder replacement (you can make it more advanced)
                    $replacements = [
                        '{school_name}'     => 'Your School Name', // fetch from settings if you have
                        '{student_name}'    => $content_data['data']['student']['first_name'] . ' ' . $content_data['data']['student']['last_name'],
                        '{admission_no}'    => $content_data['data']['student']['admission_no'],
                        '{roll_no}'         => $content_data['data']['student']['roll_no'],
                        '{father_name}'     => $content_data['data']['student']['father_name'],
                        '{class_name}'      => $content_data['data']['student']['class_name'],
                        '{financial_year}'  => $content_data['data']['student']['financial_year_name'],
                        '{issue_date}'      => $content_data['data']['issue_date']
                    ];

                    foreach ($replacements as $key => $value) {
                        $content = str_replace($key, esc($value), $content);
                    }

                    echo $content;
                    ?>
                </div>

                <div class="text-center">
                    <a href="<?= site_url('certificate/print/' . $content_data['data']['student']['id'] . '/' . $content_data['data']['type']) ?>" 
                       class="btn btn-success btn-lg" target="_blank">
                        🖨️ Print Certificate
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>