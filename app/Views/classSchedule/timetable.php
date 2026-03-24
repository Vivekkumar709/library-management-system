<link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/extra.css') ?>" />
<!-- <link rel="stylesheet" href="<?= base_url('assets/adminAssets/css/timetable.css') ?>" /> -->

<div class="row">
    <div class="col-12">
        <div class="white_card card_height_100 mb_30">
            <div class="white_card_header">
                <div class="box_header m-0">
                    <div class="main-title">
                        <h3 class="m-0">Class Timetable</h3>
                    </div>
                </div>
            </div>
            
            <div class="white_card_body">
                <?php if (session()->has('success')): ?>
                    <div class="alert alert-success"><?= esc(session('success')) ?></div>
                <?php endif; ?>
                
                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger"><?= esc(session('error')) ?></div>
                <?php endif; ?>                
                
                <form method="get" action="<?= site_url('class-schedules/timetable') ?>" class="mb-4 timetable-filter">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="financial_year_id">Financial Year</label>
                                <select class="nice_Select2 nice_Select_line wide" name="financial_year_id" id="financial_year_id">
                                    <option value="">Select Financial Year</option>
                                    <?php foreach ($content_data['data']['financial_years'] as $id => $name): ?>
                                        <option value="<?= $id ?>" <?= ($id == ($content_data['data']['selectedFinancialYear'] ?? FINANCIAL_YEAR_ID)) ? 'selected' : '' ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="class_id">Class</label>
                                <select class="nice_Select2 nice_Select_line wide" name="class_id" id="class_id" required>
                                    <option value="">Select Class</option>
                                    <?php foreach ($content_data['data']['classes'] as $id => $name): ?>
                                        <option value="<?= $id ?>" <?= ($id == ($content_data['data']['selectedClass'] ?? '')) ? 'selected' : '' ?>>
                                            <?= esc($name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="section_id">Section</label>
                                <select class="nice_Select2 nice_Select_line wide" name="section_id" id="section_id" required>
                                    <option value="">Select Section</option>
                                    <?php if (isset($content_data['data']['sections'])): ?>
                                        <?php foreach ($content_data['data']['sections'] as $id => $name): ?>
                                            <option value="<?= $id ?>" <?= ($id == ($content_data['data']['selectedSection'] ?? '')) ? 'selected' : '' ?>>
                                                <?= esc($name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn_1 btn-primary btn-block">View Timetable</button>
                        </div>
                    </div>
                </form>
                
                <?php if (isset($content_data['data']['formattedTimetable'])): ?>
                    <div class="timetable-container mt-4">
                        <div class="timetable-header">
                            <?php //echo "<pre>"; print_r($content_data['data']); echo "</pre>"; die;?>
                            <h4>Timetable for 
                                <u><?= $content_data['data']['classes'][$content_data['data']['selectedClass']] ?></u> 
                                                               
                                <b>Section <font color="#0d6efd"><?= 
                                    (isset($content_data['data']['selectedSectionName']) ? 
                                    $content_data['data']['selectedSectionName'] : 
                                    'ID ' . $content_data['data']['selectedSectionName'])
                                ?></font>
                                (<i><?= 
                                    (isset($content_data['data']['selectedSectionFor']) ? 
                                    $content_data['data']['selectedSectionFor'] : 
                                    'Timetable Not exist for this section')
                                ?></i>)</b>
                                
                            </h4>
                            <button class="btn btn-sm btn-outline-secondary" id="printTimetable">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table timetable-table">
                                <thead>
                                    <tr>
                                        <th class="time-column">Time/Day</th>
                                        <?php foreach ($content_data['data']['formattedTimetable'] as $day): ?>
                                            <th><?= $day['name'] ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Extract all unique time periods from the timetable
                                    $allTimes = [];
                                    foreach ($content_data['data']['formattedTimetable'] as $day) {
                                        foreach ($day['periods'] as $period) {
                                            $allTimes[$period['start_time']] = $period['start_time'];
                                            $allTimes[$period['end_time']] = $period['end_time'];
                                        }
                                    }
                                    $timeSlots1 = [
                                        '08:00:00', '08:30:00', '09:00:00', '09:30:00', 
                                        '10:00:00', '10:30:00', '11:00:00', '11:30:00',
                                        '12:00:00', '12:30:00', '13:00:00', '13:30:00',
                                        '14:00:00', '14:30:00', '15:00:00'
                                    ];
                                    // Sort the times
                                    if (!empty($allTimes)) {
                                        ksort($allTimes);
                                        $timeSlots = array_values($allTimes);
                                    } else {
                                        // Default time slots if no periods exist
                                        $timeSlots = [
                                            '08:00:00', '08:30:00', '09:00:00', '09:30:00', 
                                            '10:00:00', '10:30:00', '11:00:00', '11:30:00',
                                            '12:00:00', '12:30:00', '13:00:00', '13:30:00',
                                            '14:00:00', '14:30:00', '15:00:00'
                                        ];
                                    }                                   
                                    
                                    // Create time intervals
                                    for ($i = 0; $i < count($timeSlots) - 1; $i++):
                                        $startTime = $timeSlots[$i];
                                        $endTime = $timeSlots[$i + 1];
                                    ?>
                                        <tr>
                                            <td class="time-slot">
                                                <?= date('h:i A', strtotime($startTime)) . ' - ' . date('h:i A', strtotime($endTime)) ?>
                                            </td>
                                            
                                            <?php foreach ($content_data['data']['formattedTimetable'] as $dayId => $day): ?>
                                                <td>
                                                    <?php
                                                    $periodContent = '';
                                                    
                                                    foreach ($day['periods'] as $period) {
                                                        if ($period['start_time'] == $startTime && $period['end_time'] == $endTime) {
                                                            // Generate a consistent color based on subject
                                                            $subjectColor = 'hsl(' . (crc32($period['subject_id']) % 360) . ', 70%, 90%)';
                                                            $borderColor = 'hsl(' . (crc32($period['subject_id']) % 360) . ', 70%, 50%)';
                                                            
                                                            // Get subject and teacher names (you might need to adjust these based on your data structure)
                                                            $subjectName = isset($period['subject_name']) ? 'Subject: '.$period['subject_name'] : 'Subject ' . $period['subject_id'];
                                                            $teacherName = isset($period['teacher_name']) ? 'Teacher: ' .$period['teacher_name'] : 'Teacher ' . $period['teacher_id'];
                                                                                                                        
                                                            $periodContent = '
                                                                <div class="period-card" style="background-color: ' . $subjectColor . '; border-left-color: ' . $borderColor . '">
                                                                    <strong>' . esc($subjectName) . '</strong>
                                                                    <div class="teacher-name">' . esc($teacherName) . '</div>                                                                    
                                                                    ' . (!empty($period['room_number']) ? '<div class="room-number">Room: ' . esc($period['room_number']) . '</div>' : '') . '
                                                                </div>
                                                            ';
                                                            break;
                                                        }
                                                    }
                                                    
                                                    echo $periodContent ?: '<div class="free-period">Free Period</div>';
                                                    ?>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php elseif (isset($content_data['data']['selectedClass']) && $content_data['data']['selectedClass'] && isset($content_data['data']['selectedSection']) && $content_data['data']['selectedSection']): ?>
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle"></i> No timetable found for the selected class and section.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/adminAssets/js/formValidation.js') ?>"></script>
<script>
$(document).ready(function() {

    var selectedSectionId = '<?= isset($content_data['data']['selectedSection']) ? $content_data['data']['selectedSection'] : '' ?>';
    var dataAttrSectionId = $('#section_id').data('selected-section');
    
    // Use data attribute if PHP variable is empty
    if (!selectedSectionId && dataAttrSectionId) {
        selectedSectionId = dataAttrSectionId;
    }
    $('#class_id').change(function() {
        var classId = $(this).val();
        var financial_year_Id = $('#financial_year_id').val();

        if (classId && financial_year_Id) {
            $.ajax({
                url: '<?= site_url('class-schedules/get-sections/') ?>' + classId + '/' + financial_year_Id,
                type: 'GET',
                beforeSend: function() {
                    $('#section_id').html('<option value="">Loading...</option>');
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                },
                success: function(data) {
                    $('#section_id').html('<option value="">Select Section</option>');
                    $.each(data, function(key, value) {
                        // Create option and select if it matches the stored section ID
                        var isSelected = (key == selectedSectionId);
                        var option = $('<option>', {
                            value: key,
                            text: value
                        });
                        
                        if (isSelected) {
                            option.attr('selected', 'selected');
                        }
                        
                        $('#section_id').append(option);
                    });
                    
                    // Update niceSelect if available
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                },
                error: function() {
                    $('#section_id').html('<option value="">Error loading sections</option>');
                    if (typeof $.fn.niceSelect !== 'undefined') {
                        $('#section_id').niceSelect('update');
                    }
                }
            });
        } else {
            $('#section_id').html('<option value="">Select Section</option>');
            if (typeof $.fn.niceSelect !== 'undefined') {
                $('#section_id').niceSelect('update');
            }
        }
    });
    
    // If we have a selected class, trigger the change to load sections
    <?php if (isset($content_data['data']['selectedClass'])): ?>
        // Set a small timeout to ensure DOM is ready
        setTimeout(function() {
            $('#class_id').trigger('change');
        }, 100);
    <?php endif; ?>
    
    // Print functionality
    $('#printTimetable').click(function() {
        window.print();
    });    
    // Trigger change if class is already selected
    // <?php //if (isset($content_data['data']['selectedClass'])): ?>
    //     $('#class_id').trigger('change');
    // <?php //endif; ?>   
});
</script>
<style>
.timetable-filter .form-group {
    margin-bottom: 1rem;
}

.timetable-filter label {
    font-weight: 500;
    margin-bottom: 0.5rem;
    display: block;
}

.timetable-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.timetable-table {
    border-collapse: separate;
    border-spacing: 0;
}

.timetable-table th {
    background-color: #f8f9fa;
    text-align: center;
    vertical-align: middle;
    font-weight: 600;
    padding: 1rem 0.5rem;
}

.timetable-table td {
    padding: 0.5rem;
    vertical-align: top;
    height: 80px;
    min-width: 120px;
}

.time-column {
    width: 120px;
}

.time-slot {
    font-size: 0.85rem;
    font-weight: 500;
    text-align: center;
    background-color: #f8f9fa;
}

.period-card {
    padding: 8px;
    border-radius: 4px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-left: 4px solid;
}

.period-card strong {
    font-size: 0.9rem;
    display: block;
    margin-bottom: 4px;
}

.period-card .teacher-name {
    font-size: 0.8rem;
    color: #555;
}

.period-card .room-number {
    font-size: 0.75rem;
    color: #777;
}

.free-period {
    text-align: center;
    color: #999;
    font-style: italic;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
}

/* Print styles */
@media print {
    .timetable-filter, 
    .timetable-header button,
    .white_card_header {
        display: none !important;
    }
    
    .timetable-table {
        font-size: 12px;
    }
    
    .timetable-table td {
        height: 60px;
    }
}

/* Responsive styles */
@media (max-width: 768px) {
    .timetable-table {
        font-size: 0.8rem;
    }
    
    .timetable-table th, 
    .timetable-table td {
        padding: 0.25rem;
    }
    
    .time-slot {
        writing-mode: vertical-lr;
        text-orientation: mixed;
        transform: rotate(180deg);
        font-size: 0.7rem;
        width: 30px;
    }    
    .period-card {
        padding: 4px;
    }
    
    .period-card strong {
        font-size: 0.8rem;
    }    
    .period-card .teacher-name,
    .period-card .room-number {
        display: none;
    }
}
</style>