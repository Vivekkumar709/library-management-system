<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Gender-wise Student Report</h3>
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
                                <form method="get" action="<?= site_url('students/reports/gender-wise') ?>">
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

                <!-- Gender-wise Statistics -->
                <div class="row">
                    <div class="QA_section">
                        <div class="QA_table mb_30">
                            <?php if (!empty($content_data['data']['genderStats'])): ?>                                
                                <!-- <table class="table table-striped" id="genderWiseTable"> -->
                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">    
                                    <thead>
                                        <tr>
                                            <th>Class</th>
                                            <th>Male</th>
                                            <th>Female</th>
                                            <th>Other</th>
                                            <th>Total</th>
                                            <th>Male %</th>
                                            <th>Female %</th>
                                            <th>Other %</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $totalMale = 0;
                                        $totalFemale = 0;
                                        $totalOther = 0;
                                        $grandTotal = 0;
                                        ?>
                                        <?php foreach ($content_data['data']['genderStats'] as $className => $stats): ?>
                                            <tr>
                                                <td><strong><?= esc($className) ?></strong></td>
                                                <td><?= $stats['Male'] ?></td>
                                                <td><?= $stats['Female'] ?></td>
                                                <td><?= $stats['Other'] ?></td>
                                                <td><strong><?= $stats['Total'] ?></strong></td>
                                                <td>
                                                    <?php if ($stats['Total'] > 0): ?>
                                                        <?= number_format(($stats['Male'] / $stats['Total']) * 100, 1) ?>%
                                                    <?php else: ?>
                                                        0%
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($stats['Total'] > 0): ?>
                                                        <?= number_format(($stats['Female'] / $stats['Total']) * 100, 1) ?>%
                                                    <?php else: ?>
                                                        0%
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($stats['Total'] > 0): ?>
                                                        <?= number_format(($stats['Other'] / $stats['Total']) * 100, 1) ?>%
                                                    <?php else: ?>
                                                        0%
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                            $totalMale += $stats['Male'];
                                            $totalFemale += $stats['Female'];
                                            $totalOther += $stats['Other'];
                                            $grandTotal += $stats['Total'];
                                            ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <td><strong>Grand Total</strong></td>
                                            <td><strong><?= $totalMale ?></strong></td>
                                            <td><strong><?= $totalFemale ?></strong></td>
                                            <td><strong><?= $totalOther ?></strong></td>
                                            <td><strong><?= $grandTotal ?></strong></td>
                                            <td>
                                                <?php if ($grandTotal > 0): ?>
                                                    <?= number_format(($totalMale / $grandTotal) * 100, 1) ?>%
                                                <?php else: ?>
                                                    0%
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($grandTotal > 0): ?>
                                                    <?= number_format(($totalFemale / $grandTotal) * 100, 1) ?>%
                                                <?php else: ?>
                                                    0%
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($grandTotal > 0): ?>
                                                    <?= number_format(($totalOther / $grandTotal) * 100, 1) ?>%
                                                <?php else: ?>
                                                    0%
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table> 
                                <!-- Chart Section -->
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Gender Distribution (Overall)</h5>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="genderChart" width="400" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Class-wise Distribution</h5>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="classGenderChart" width="400" height="200"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-venus-mars fa-3x text-muted mb-3"></i>
                                    <h5>No Data Found</h5>
                                    <p class="text-muted">No gender-wise data available for the selected criteria.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#genderWiseTable').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "pageLength": 25,
        "order": [[0, 'asc']]
    });

    // Gender Distribution Chart
    <?php if (!empty($content_data['data']['genderStats'])): ?>
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    const genderChart = new Chart(genderCtx, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female', 'Other'],
            datasets: [{
                data: [<?= $totalMale ?>, <?= $totalFemale ?>, <?= $totalOther ?>],
                backgroundColor: ['#8acaf4', '#ff99af', '#ffd466'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Class-wise Gender Chart
    const classGenderCtx = document.getElementById('classGenderChart').getContext('2d');
    const classGenderChart = new Chart(classGenderCtx, {
        type: 'bar',
        data: {
            labels: [<?php 
                $classes = array_keys($content_data['data']['genderStats']);
                echo "'" . implode("','", $classes) . "'";
            ?>],
            datasets: [
                {
                    label: 'Male',
                    data: [<?php 
                        $maleData = [];
                        foreach ($content_data['data']['genderStats'] as $stats) {
                            $maleData[] = $stats['Male'];
                        }
                        echo implode(',', $maleData);
                    ?>],
                    backgroundColor: '#8acaf4'
                },
                {
                    label: 'Female',
                    data: [<?php 
                        $femaleData = [];
                        foreach ($content_data['data']['genderStats'] as $stats) {
                            $femaleData[] = $stats['Female'];
                        }
                        echo implode(',', $femaleData);
                    ?>],
                    backgroundColor: '#ff99af'
                },
                {
                    label: 'Other',
                    data: [<?php 
                        $otherData = [];
                        foreach ($content_data['data']['genderStats'] as $stats) {
                            $otherData[] = $stats['Other'];
                        }
                        echo implode(',', $otherData);
                    ?>],
                    backgroundColor: '#ffd466'
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            }
        }
    });
    <?php endif; ?>
});
</script>