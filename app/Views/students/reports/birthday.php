<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Student Birthday Report - <?= $content_data['data']['months'][$content_data['data']['currentMonth']] ?></h3>
                    </div>
                    
                    <!-- <div class="box_right d-flex lms_block">                                    
                        <div class="add_button ms-2">
                            <a href="<?= site_url('students/reports/export/birthday?month=' . $content_data['data']['currentMonth']) ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Export Excel
                            </a>                                        
                        </div>
                    </div> -->
                </div>
            </div>
            
            <div class="white_card_body">
                <!-- Month Filter -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Select Month</h5>
                            </div>
                            <div class="card-body">
                                <form method="get" action="<?= site_url('students/reports/birthday') ?>">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <select class="nice_Select2 nice_Select_line wide mb_15" name="month" required>
                                                <?php foreach ($content_data['data']['months'] as $key => $name): ?>
                                                    <option value="<?= $key ?>" <?= ($content_data['data']['currentMonth'] == $key) ? 'selected' : '' ?>>
                                                        <?= esc($name) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <button type="submit" class="btn btn-primary btn-block">Show Birthdays</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Birthday Students -->
                <div class="QA_section">   
                                                    
                        <div class="QA_table mb_30">
                            <?php if (!empty($content_data['data']['students'])): ?>                                 
                                <!-- Birthday Summary -->
                                <div class="row mb-4 mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Birthday Summary - <?= $content_data['data']['months'][$content_data['data']['currentMonth']] ?></h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-primary1 text-white" style="background-color: #97ade6 !important;">
                                                            <h4><?= count($content_data['data']['students']) ?></h4>
                                                            <!-- <p class="mb-0">Total Birthdays</p> -->
                                                            <div>Total Birthdays</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-success text-white">
                                                            <h4>
                                                                <?php
                                                                $minorCount = 0;
                                                                foreach ($content_data['data']['students'] as $student) {
                                                                    $age = date_diff(date_create($student['date_of_birth']), date_create('today'))->y;
                                                                    if ($age < 18) $minorCount++;
                                                                }
                                                                echo $minorCount;
                                                                ?>
                                                            </h4>
                                                            <!-- <p class="mb-0">Below 18 Years</p> -->
                                                            <div>Below 18 Years</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-info1 text-white" style="background-color: #73ddf2 !important;">
                                                            <h4>
                                                                <?php
                                                                $adultCount = 0;
                                                                foreach ($content_data['data']['students'] as $student) {
                                                                    $age = date_diff(date_create($student['date_of_birth']), date_create('today'))->y;
                                                                    if ($age >= 18) $adultCount++;
                                                                }
                                                                echo $adultCount;
                                                                ?>
                                                            </h4>
                                                            <!-- <p class="mb-0">18+ Years</p> -->
                                                            <div>18+ Years</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-warning1 text-white" style="background-color: #ffcc4d !important;">
                                                            <h4>
                                                                <?php
                                                                $classes = array_unique(array_column($content_data['data']['students'], 'class_name'));
                                                                echo count($classes);
                                                                ?>
                                                            </h4>
                                                            <!-- <p class="mb-0">Classes</p> -->
                                                            <div>Classes</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Student Name</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Date of Birth</th>
                                                <th>Age</th>
                                                <th>Father Name</th>
                                                <th>Contact</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($content_data['data']['students'] as $student): ?>
                                                <?php
                                                $birthDate = new DateTime($student['date_of_birth']);
                                                $today = new DateTime();
                                                $age = $birthDate->diff($today)->y;
                                                $birthDay = $birthDate->format('d');
                                                ?>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-primary"><?= $birthDay ?></span>
                                                    </td>
                                                    <td>
                                                        <strong><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?= esc($student['admission_no']) ?></small>
                                                    </td>
                                                    <td><?= esc($student['class_name']) ?></td>
                                                    <td><?= esc($student['section_name']) ?></td>
                                                    <td><?= date('d M Y', strtotime($student['date_of_birth'])) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $age < 18 ? 'success' : 'info' ?>">
                                                            <?= $age ?> years
                                                        </span>
                                                    </td>
                                                    <td><?= esc($student['father_name'] ?: '-') ?></td>
                                                    <td><?= esc($student['father_mobile'] ?: $student['mobile_no'] ?: '-') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>                                

                                
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-birthday-cake fa-3x text-muted mb-3"></i>
                                    <h5>No Birthdays Found</h5>
                                    <p class="text-muted">No students have birthdays in <?= $content_data['data']['months'][$content_data['data']['currentMonth']] ?>.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#birthdayTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "pageLength": 25,
        "order": [[0, 'asc']]
    });
});
</script>