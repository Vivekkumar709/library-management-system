<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />

<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <h3>Manage Subjects by Class</h3>
            </div>

            <div class="white_card_body">
                <!-- Add / Edit Form -->
                <form method="post" action="<?= site_url('academics/saveSubject') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= old('id') ?>">

                    <div class="row">
                        <div class="col-lg-3">
                            <select name="class_id" class="nice_Select2" required>
                                <option value="">Select Class</option>
                                <?= $content_data['data']['classes'] ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" name="subject_name" class="form-control" placeholder="Subject Name" required value="<?= old('subject_name') ?>">
                        </div>
                        <div class="col-lg-2">
                            <input type="text" name="subject_code" class="form-control" placeholder="Code (optional)" value="<?= old('subject_code') ?>">
                        </div>
                        <div class="col-lg-2">
                            <input type="number" name="max_marks" class="form-control" placeholder="Max Marks" value="<?= old('max_marks', 100) ?>" required>
                        </div>
                        <div class="col-lg-2">
                            <button type="submit" class="btn btn-success">+ Add / Update Subject</button>
                        </div>
                    </div>
                </form>

                <hr>

                <!-- Subjects List Table -->
                <table class="table table-striped lms_table_active3" id="table-subjects">
                    <thead>
                        <tr>
                            <th>S.N</th>
                            <th>Class</th>
                            <th>Subject Name</th>
                            <th>Code</th>
                            <th>Max Marks</th>
                            <th class="no-export">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach($content_data['data']['subjects'] as $sub): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= esc($sub['class_name']) ?></td>
                            <td><?= esc($sub['subject_name']) ?></td>
                            <td><?= esc($sub['subject_code'] ?: '-') ?></td>
                            <td><?= $sub['max_marks'] ?></td>
                            <td>
                                <a href="#" class="btn btn-sm btn-warning edit-subject" 
                                   data-id="<?= $sub['id'] ?>" 
                                   data-class="<?= $sub['class_id'] ?>" 
                                   data-name="<?= esc($sub['subject_name']) ?>" 
                                   data-code="<?= esc($sub['subject_code']) ?>" 
                                   data-max="<?= $sub['max_marks'] ?>">
                                    Edit
                                </a>
                                <a href="<?= site_url('academics/delete/' . $sub['id']) ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Delete this subject?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.edit-subject').click(function(e){
        e.preventDefault();
        var btn = $(this);
        $('input[name="id"]').val(btn.data('id'));
        $('select[name="class_id"]').val(btn.data('class')).trigger('change');
        $('input[name="subject_name"]').val(btn.data('name'));
        $('input[name="subject_code"]').val(btn.data('code'));
        $('input[name="max_marks"]').val(btn.data('max'));
        $('button[type="submit"]').text('Update Subject');
    });
});
</script>