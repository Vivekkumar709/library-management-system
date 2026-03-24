<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <h3>Student Summary : <?= esc($content_data['data']['student']['first_name'].' '.$content_data['data']['student']['last_name']) ?></h3>
                <a href="<?= site_url('students') ?>" class="btn btn-secondary">← Back to List</a>
            </div>

            <div class="white_card_body">
                <ul class="nav nav-tabs" id="summaryTab">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#profile">Profile</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#docs">Documents</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#academic">Academic</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#fee">Fees</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#att">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#result">Result</a></li>
                </ul>

                <div class="tab-content">
                    <!-- Profile Tab -->
                    <div class="tab-pane fade show active" id="profile">
                        <!-- Same beautiful layout as your add.php but in read-only + photo big -->
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="<?= base_url($content_data['data']['student']['profile_image'] ?? 'uploads/profile_images/default_user.png') ?>" class="img-fluid rounded" style="max-height:220px">
                            </div>
                            <div class="col-md-9">
                                <table class="table table-bordered">
                                    <tr><th>Admission No</th><td><?= esc($content_data['data']['student']['admission_no']) ?></td></tr>
                                    <tr><th>Roll No</th><td><?= esc($content_data['data']['student']['roll_no']) ?></td></tr>
                                    <tr><th>Class - Section</th><td><?= esc($content_data['data']['student']['class_name'].' - '.$content_data['data']['student']['section_name']) ?></td></tr>
                                    <!-- add more rows -->
                                </table>
                                <a href="<?= site_url('students/add/'.$content_data['data']['student']['id']) ?>" class="btn btn-primary">Edit Student</a>
                                <a href="<?= site_url('students/view/'.$content_data['data']['student']['id']) ?>" class="btn btn-info">Full View</a>
                            </div>
                        </div>
                    </div>

                    <!-- Other tabs similar - Documents list with download, etc. -->
                    <!-- I kept it short for now. Want full expanded tabs? Say "expand summary tabs" -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add link in your list.php dropdown -->
<!-- Inside dropdown-menu add -->
<a class="dropdown-item" href="<?= site_url('students/summary/' . $student['id']) ?>"><i class="fas fa-chart-bar"></i> Summary</a>