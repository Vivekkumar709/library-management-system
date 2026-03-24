<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Category-wise Student Report</h3>
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
                                <form method="get" action="<?= site_url('students/reports/category-wise') ?>">
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

                <!-- Category-wise Statistics -->
                <!-- <div class="row"> -->
                <div class="QA_section"> 
                        <div class="QA_table mb_30">
                            <?php if (!empty($content_data['data']['categoryStats'])): ?>
                                
                                <!-- Category Summary -->
                                <div class="row mb-4 mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Category Summary</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <?php
                                                    $allCategories = [];
                                                    $totalStudents = 0;
                                                    $totalClasses = 0;
                                                    $topCategories = [];
                                                    
                                                    foreach ($content_data['data']['categoryStats'] as $class => $categories) {
                                                        $totalClasses++;
                                                        foreach ($categories as $category => $count) {
                                                            if (!isset($allCategories[$category])) {
                                                                $allCategories[$category] = 0;
                                                            }
                                                            $allCategories[$category] += $count;
                                                            $totalStudents += $count;
                                                        }
                                                    }
                                                    
                                                    arsort($allCategories);
                                                    $topCategories = array_slice($allCategories, 0, 4);
                                                    $otherCategories = array_slice($allCategories, 4);
                                                    $otherCount = array_sum($otherCategories);
                                                    ?>
                                                    
                                                    <!-- Total Categories -->
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-primary1 text-white" style="background-color: #dc97e6 !important;">
                                                            <h4><?= count($allCategories) ?></h4>
                                                            <div>Total Categories</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Total Students -->
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-success text-white">
                                                            <h4><?= $totalStudents ?></h4>
                                                            <div>Total Students</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Total Classes -->
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-info1 text-white" style="background-color: #73ddf2 !important;">
                                                            <h4><?= $totalClasses ?></h4>
                                                            <div>Classes Covered</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Top Category -->
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3 bg-warning1 text-white" style="background-color: #ff4d7c !important;">
                                                            <h4>
                                                                <?php 
                                                                if (!empty($topCategories)) {
                                                                    $topCategory = array_keys($topCategories)[0];
                                                                    echo $topCategories[$topCategory];
                                                                } else {
                                                                    echo '0';
                                                                }
                                                                ?>
                                                            </h4>
                                                            <div>
                                                                <?php 
                                                                if (!empty($topCategories)) {
                                                                    $topCategoryName = array_keys($topCategories)[0];
                                                                    echo strlen($topCategoryName) > 15 ? substr($topCategoryName, 0, 15).'...' : $topCategoryName;
                                                                } else {
                                                                    echo 'Top Category';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Top Categories Breakdown -->
                                                <?php if (!empty($allCategories)): ?>
                                                <div class="row mt-4">
                                                    <div class="col-12">
                                                        <h6 class="mb-3">Category Distribution</h6>
                                                        <div class="row">
                                                            <?php 
                                                            $displayCategories = array_slice($allCategories, 0, 6);
                                                            $colors = ['#97ade6', '#6cce7a', '#73ddf2', '#ffcc4d', '#ff6b6b', '#a8e6cf'];
                                                            $colorIndex = 0;
                                                            ?>
                                                            <?php foreach ($displayCategories as $category => $count): ?>
                                                                <div class="col-md-4 mb-3">
                                                                    <div class="border rounded p-3 d-flex justify-content-between align-items-center" 
                                                                        style="border-left: 4px solid <?= $colors[$colorIndex % count($colors)] ?> !important;">
                                                                        <div>
                                                                            <h6 class="mb-1 text-truncate" style="max-width: 200px;" title="<?= esc($category) ?>">
                                                                                <?= esc($category) ?>
                                                                            </h6>
                                                                            <small class="text-muted"><?= number_format(($count / $totalStudents) * 100, 1) ?>%</small>
                                                                        </div>
                                                                        <span class="h5 mb-0 font-weight-bold" style="color: <?= $colors[$colorIndex % count($colors)] ?>">
                                                                            <?= $count ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <?php $colorIndex++; ?>
                                                            <?php endforeach; ?>
                                                            
                                                            <?php if (count($allCategories) > 6): ?>
                                                                <div class="col-md-4 mb-3">
                                                                    <div class="border rounded p-3 d-flex justify-content-between align-items-center bg-light">
                                                                        <div>
                                                                            <h6 class="mb-1">Other Categories</h6>
                                                                            <small class="text-muted"><?= count($allCategories) - 6 ?> more</small>
                                                                        </div>
                                                                        <span class="h5 mb-0 font-weight-bold text-muted">
                                                                            <?= array_sum(array_slice($allCategories, 6)) ?>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php foreach ($content_data['data']['categoryStats'] as $className => $categories): ?>
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h5 class="card-title mb-0"><?= esc($className) ?></h5>
                                        </div>
                                        <div class="card-body">                                            
                                                <!-- <table class="table table-sm table-striped"> -->
                                                <table class="table table-striped lms_table_active3" id="table-<?=$content_data['data']['distinctiveID']; ?>">
                                                    <thead>
                                                        <tr>
                                                            <th>Category (Caste - Religion)</th>
                                                            <th>Student Count</th>
                                                            <th>Percentage</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php 
                                                        $classTotal = array_sum($categories);
                                                        $categoryCount = 0;
                                                        ?>
                                                        <?php foreach ($categories as $category => $count): ?>
                                                            <tr>
                                                                <td><?= esc($category) ?></td>
                                                                <td><?= $count ?></td>
                                                                <td>
                                                                    <?php if ($classTotal > 0): ?>
                                                                        <?= number_format(($count / $classTotal) * 100, 1) ?>%
                                                                    <?php else: ?>
                                                                        0%
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                            <?php $categoryCount += $count; ?>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="table-secondary">
                                                            <td><strong>Total</strong></td>
                                                            <td><strong><?= $categoryCount ?></strong></td>
                                                            <td><strong>100%</strong></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>                                            
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                    <h5>No Data Found</h5>
                                    <p class="text-muted">No category-wise data available for the selected criteria.</p>
                                </div>
                            <?php endif; ?>
                        </div>                    
                </div>
            </div>
        </div>
    </div>
</div>