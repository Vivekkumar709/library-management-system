<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<section class="content">
<div class="main_content_iner1">
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-12">
                <div class="white_card card_height_100 mb_30">
                    <div class="white_card_header">
                        <h4><?= $content_data['data']['student_id'] ? 'Student Fees' : 'All Fees' ?></h4>
                        <a href="<?= site_url('fee/collect') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Collect Fee</a>
                    </div>

                    <div class="white_card_body">
                        <table class="table table-striped lms_table_active3" id="table-fee_list">
                            <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Student</th>
                                    <th>Fee Head</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Status</th>
                                    <th class="no-export">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; foreach($content_data['data']['fees'] as $f): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= esc($f['first_name'].' '.$f['last_name']) ?> <small>(<?= $f['admission_no'] ?>)</small></td>
                                    <td><?= esc($f['fee_head']) ?></td>
                                    <td>₹<?= number_format($f['total_amount'],2) ?></td>
                                    <td>₹<?= number_format($f['paid_amount'],2) ?></td>
                                    <td class="text-danger">₹<?= number_format($f['due_amount'],2) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $f['status']==1 ? 'success' : 'warning' ?>">
                                            <?= $f['status']==1 ? 'Paid' : 'Due' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('fee/receipt/'.$f['id']) ?>" class="btn btn-sm btn-info" target="_blank">Receipt</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<script>
$(document).ready(function(){
    // DataTable same as your students list
});
</script>