$(document).ready(function() {
    $('select:not(.select2-multi)').niceSelect();    
    $('.select2-multi').select2({
        theme: 'bootstrap-5',        
        allowClear: true,
        closeOnSelect: false, 
        width: '100%',
        placeholder: $('.select2-multi').attr('placeholder')
    });

    // Add "Select All" button
    $('.select2-multi').on('select2:open', function() {
        let $dropdown = $('.select2-container--open .select2-dropdown');
        if (!$dropdown.find('.select-all-btn').length) {
            $dropdown.prepend(`<div class="select2-actions p-2 border-bottom">
                    <button type="button" class="btn btn-sm btn-outline-primary select-all-btn">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-danger clear-all-btn">Clear All</button>
                </div>`);
        }
    });

    // Handle "Select All"
    $(document).on('click', '.select-all-btn', function() {
        $('.select2-multi option').prop('selected', true);
        $('.select2-multi').trigger('change');
    });

    // Handle "Clear All"
    $(document).on('click', '.clear-all-btn', function() {
        $('.select2-multi').val(null).trigger('change');
    });
    // Style the select2 container
    $('#service_id').next('.select2').find('.select2-selection').css({
        'border': '1px solid #e5ecff',
        'padding': '0.6rem 0.75rem',
        'border-radius': '0.375rem',
        'background-color': '#fff',
        'min-height': '45px'
    });
    
    $('#plan_ids').next('.select2').find('.select2-selection').css({
        'border': '1px solid #e5ecff',
        'padding': '0.6rem 0.75rem',
        'border-radius': '0.375rem',
        'background-color': '#fff',
        'min-height': '45px'
    });
        
});