<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Section-wise Student Report</h3>
                    </div>
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
                                <form method="get" action="<?= site_url('students/reports/section-wise') ?>">
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <select class="nice_Select2 nice_Select_line wide mb_15" name="financial_year_id">
                                                <option value="">All Financial Years</option>
                                                <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                                    <option value="<?= $id ?>" <?= ($content_data['data']['filters']['financial_year_id'] == $id) ? 'selected' : '' ?>>
                                                        <?= esc($name) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <select class="nice_Select2 nice_Select_line wide mb_15" name="class_id">
                                                <option value="">All Classes</option>
                                                <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                                    <option value="<?= $id ?>" <?= ($content_data['data']['filters']['class_id'] == $id) ? 'selected' : '' ?>>
                                                        <?= esc($name) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <button type="submit" class="btn btn-primary btn-block">Apply Filters</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section-wise Data -->
                <!-- <div class="row">
                    <div class="col-12"> -->
                    <div class="QA_section">
                        <div class="QA_table mb_30">
                            <?php if (!empty($content_data['data']['sections'])): ?> 
                                    <!-- Summary Cards -->
                                    <div class="row mb-4 mt-4">
                                        <div class="col-md-4">
                                            <div class="card bg-success text-white">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h4 class="mb-0"><?= array_sum(array_column($content_data['data']['sections'], 'total_students')) ?></h4>
                                                            <div>Total Students</div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-users fa-2x"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-info text-white">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h4 class="mb-0"><?= array_sum(array_column($content_data['data']['sections'], 'male_count')) ?></h4>
                                                            <div>Male Students</div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-male fa-2x"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-success1 text-white" style="background-color:rgb(230, 151, 213) !important;">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1">
                                                            <h4 class="mb-0"><?= array_sum(array_column($content_data['data']['sections'], 'female_count')) ?></h4>
                                                            <div>Female Students</div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-female fa-2x"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                               
                                    <!-- <table class="table table-striped" id="sectionWiseTable"> -->
                                    <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                        <thead>
                                            <tr>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Total Students</th>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>Male %</th>
                                                <th>Female %</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($content_data['data']['sections'] as $section): ?>
                                                <tr>
                                                    <td><?= esc($section['class_name']) ?></td>
                                                    <td><?= esc($section['section_name']) ?></td>
                                                    <td><strong><?= $section['total_students'] ?></strong></td>
                                                    <td><?= $section['male_count'] ?></td>
                                                    <td><?= $section['female_count'] ?></td>
                                                    <td>
                                                        <?php if ($section['total_students'] > 0): ?>
                                                            <?= number_format(($section['male_count'] / $section['total_students']) * 100, 1) ?>%
                                                        <?php else: ?>
                                                            0%
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($section['total_students'] > 0): ?>
                                                            <?= number_format(($section['female_count'] / $section['total_students']) * 100, 1) ?>%
                                                        <?php else: ?>
                                                            0%
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-secondary">
                                                <td colspan="2"><strong>Total</strong></td>
                                                <td><strong><?= array_sum(array_column($content_data['data']['sections'], 'total_students')) ?></strong></td>
                                                <td><strong><?= array_sum(array_column($content_data['data']['sections'], 'male_count')) ?></strong></td>
                                                <td><strong><?= array_sum(array_column($content_data['data']['sections'], 'female_count')) ?></strong></td>
                                                <td colspan="2"></td>
                                            </tr>
                                        </tfoot>
                                    </table>                                    
                                    
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                    <h5>No Data Found</h5>
                                    <p class="text-muted">No section-wise data available for the selected criteria.</p>
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
    // $('#sectionWiseTable').DataTable({
    //     responsive: true,
    //     dom: 'Bfrtip',
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ],
    //     "pageLength": 25,
    //     "order": [[0, 'asc'], [1, 'asc']]
    // });
});
</script>