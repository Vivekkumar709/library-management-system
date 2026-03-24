<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Class-wise Student Report</h3>
                    </div>                    
                    <!-- <div class="box_right d-flex lms_block">                                    
                        <div class="add_button ms-2">
                            <a href="<?= site_url('students/reports/export/class_wise?' . http_build_query($content_data['data']['filters'])) ?>" class="btn btn-success">
                                <i class="fas fa-download"></i> Export Excel
                            </a>                                        
                        </div>
                    </div> -->
                </div>
            </div>
            
            <div class="white_card_body">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Filters</h5>
                            </div>
                            <div class="card-body">
                                <form method="get" action="<?= site_url('students/reports/class-wise') ?>">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <select class="nice_Select2 nice_Select_line wide mb_15" name="financial_year_id">
                                                <option value="">All Financial Years</option>
                                                <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                                    <option value="<?= $id ?>" <?= ($content_data['data']['filters']['financial_year_id'] == $id) ? 'selected' : '' ?>>
                                                        <?= esc($name) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <select class="nice_Select2 nice_Select_line wide mb_15" name="class_id" id="class_id">
                                                <option value="">All Classes</option>
                                                <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                                    <option value="<?= $id ?>" <?= ($content_data['data']['filters']['class_id'] == $id) ? 'selected' : '' ?>>
                                                        <?= esc($name) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <select class="nice_Select2 nice_Select_line wide mb_15" name="section_id" id="section_id">
                                                <option value="">All Sections</option>
                                                <!-- Sections will be loaded via AJAX -->
                                            </select>
                                        </div>
                                        <div class="col-lg-3">
                                            <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Statistics -->
                <?php if ($content_data['data']['summary']): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="border rounded p-3 text-white" style="background-color:#26c75aa6">
                                            <h4><?= $content_data['data']['summary']['total_students'] ?></h4>
                                            <!-- <p class="mb-0">Total Students</p> -->
                                            <div>Total Students</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3 bg-info text-white">
                                            <h4><?= $content_data['data']['summary']['male_count'] ?></h4>
                                            <!-- <p class="mb-0">Male Students</p> -->
                                            <div>Male Students</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3 text-white" style="background-color:#ff4dac">
                                            <h4><?= $content_data['data']['summary']['female_count'] ?></h4>
                                            <!-- <p class="mb-0">Female Students</p> -->
                                             <div>Female Students</div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3 bg-warning text-white">
                                            <h4><?= $content_data['data']['summary']['other_count'] ?></h4>
                                            <!-- <p class="mb-0">Other</p> -->
                                            <div>Other</div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Students List -->
                <!-- <div class="row">
                    <div class="col-12"> -->
                    <div class="QA_section">
                        <div class="QA_table mb_30">
                            <?php if (!empty($content_data['data']['students'])): ?>                                
                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                    <!-- <table class="table table-striped" id="classWiseTable"> -->
                                        <thead>
                                            <tr>
                                                <th>Admission No</th>
                                                <th>Roll No</th>
                                                <th>Student Name</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Gender</th>
                                                <th>Date of Birth</th>
                                                <th>Father Name</th>
                                                <th>Contact</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($content_data['data']['students'] as $student): ?>
                                                <tr>
                                                    <td><?= esc($student['admission_no']) ?></td>
                                                    <td><?= esc($student['roll_no']) ?></td>
                                                    <td><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                                    <td><?= esc($student['class_name']) ?></td>
                                                    <td><?= esc($student['section_name']) ?></td>
                                                    <td><?= esc($student['gender']) ?></td>
                                                    <td><?= date('d M Y', strtotime($student['date_of_birth'])) ?></td>
                                                    <td><?= esc($student['father_name'] ?: '-') ?></td>
                                                    <td><?= esc($student['father_mobile'] ?: $student['mobile_no'] ?: '-') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No Students Found</h5>
                                    <p class="text-muted">No students match the selected criteria.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() { 
    // Initialize DataTable
    // $('#classWiseTable').DataTable({
    //     responsive: true,
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ],
    //     "pageLength": 25
    // });
    
    // Load sections when class is selected
    $('#class_id').change(function() {        
        var classId = $(this).val(); 
        alert(classId);
        var financialYearId = $('select[name="financial_year_id"]').val();
        
        if (classId && financialYearId) {            
            $.ajax({
                url: '<?= site_url('students/get-sections/') ?>' + classId + '/' + financialYearId,
                type: 'GET',
                success: function(data) {
                    $('#section_id').html('<option value="">All Sections</option>');
                    $.each(data, function(key, value) {
                        $('#section_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                }
            });
        } else {
            $('#section_id').html('<option value="">All Sections</option>');
            if (typeof $.fn.niceSelect !== 'undefined') {
                $('#section_id').niceSelect('update');
            }
        }
    });
});
</script>