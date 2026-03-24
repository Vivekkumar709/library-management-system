<div class="row">
    <div class="col-12">
        <div class="white_card">
            <div class="white_card_header"><h3>Collect Fee</h3></div>
            <div class="white_card_body">
                <form method="post" action="<?= site_url('fee/savePayment') ?>">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-lg-6">
                            <select class="nice_Select2" name="student_id" required>
                                <option value="">Select Student</option>
                                <?php foreach(model('StudentModel')->getStudents() as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= $s['first_name'] ?> (<?= $s['admission_no'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <select class="nice_Select2" name="fee_structure_id" required>
                                <?php foreach($content_data['data']['fee_heads'] as $head): ?>
                                    <option value="<?= $head['id'] ?>"><?= $head['fee_head'] ?> - ₹<?= $head['amount'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <input type="number" name="amount" class="form-control" placeholder="Amount Paying" required>
                        </div>
                        <div class="col-lg-4">
                            <select name="payment_mode" class="nice_Select2">
                                <option value="Cash">Cash</option>
                                <option value="Online">UPI / Bank</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-success">💰 Collect & Generate Receipt</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>