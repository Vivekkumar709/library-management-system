document.addEventListener('DOMContentLoaded', function() {    
    // Get data attributes from the PHP file
    const container = document.querySelector('.white_card_body');
    const csrfTokenName = container ? container.getAttribute('data-csrf-token-name') : '';
    const csrfTokenValue = container ? container.getAttribute('data-csrf-token-value') : '';
    const siteUrlGetCities = container ? container.getAttribute('data-site-url') : '';
    const permanentCityId = container ? container.getAttribute('data-permanent-city-id') : '';
    const presentCityId = container ? container.getAttribute('data-present-city-id') : '';

    const sameAsPermanentCheckbox = document.getElementById('same_as_permanent');
    const permanentState = document.getElementById('permanent_state');
    const permanentCity = document.getElementById('permanent_city');
    const permanentAddress = document.getElementById('permanent_address');
    const permanentLandmark = document.getElementById('permanent_landmark');
    const permanentPincode = document.getElementById('permanent_pincode');

    const presentState = document.getElementById('present_state');
    const presentCityElem = document.getElementById('present_city');
    const presentAddress = document.getElementById('present_address');
    const presentLandmark = document.getElementById('present_landmark');
    const presentPincode = document.getElementById('present_pincode');
    
    // Get CSRF token from hidden input or use the value from data attribute
    const csrfTokenInput = document.querySelector(`input[name="${csrfTokenName}"]`);
    const csrfToken = csrfTokenInput ? csrfTokenInput.value : csrfTokenValue;
    
    if (!csrfToken) console.error("CSRF token not found!");

    // Initialize dropdowns
    initializeDropdowns();

    // Setup event listeners
    setupEventListeners();

    function initializeDropdowns() { 
        // Load permanent cities if state is selected
        if (permanentState && permanentState.value) {            
            loadCities(permanentState.value, permanentCity, permanentCityId);
        }
        
        // Load present cities if not same as permanent
        if (presentState && presentState.value && (!sameAsPermanentCheckbox || !sameAsPermanentCheckbox.checked)) {
            loadCities(presentState.value, presentCityElem, presentCityId);
        }
    }

    function setupEventListeners() {  
        if (permanentState) {
            permanentState.onchange = function() { 
                const stateId = this.value; 
                loadCities(stateId, permanentCity);
            };
        }

        if (presentState) {
            presentState.onchange = function() {   
                const presentStateId = this.value; 
                loadCities(presentStateId, presentCityElem);
            };
        }

        // Same as permanent checkbox handler
        if (sameAsPermanentCheckbox) {
            sameAsPermanentCheckbox.addEventListener('change', handleSameAsPermanent);
            
            // Initialize if checked on page load
            if (sameAsPermanentCheckbox.checked) {
                handleSameAsPermanent({ target: sameAsPermanentCheckbox });
            }
        }
    }

    async function handleSameAsPermanent(event) {
        const isChecked = event.target.checked;
        
        if (isChecked) {
            // Copy state and city
            if (presentState && permanentState) presentState.value = permanentState.value;
            if (presentCityElem && permanentCity) presentCityElem.value = permanentCity.value;            
            // Copy address fields
            if (presentAddress && permanentAddress) presentAddress.value = permanentAddress.value;
            if (presentLandmark && permanentLandmark) presentLandmark.value = permanentLandmark.value;
            if (presentPincode && permanentPincode) presentPincode.value = permanentPincode.value;
            
            // Remove invalid class if needed
            if (typeof $ !== 'undefined') {
                $('#present_state, #present_city, #present_address, #present_landmark, #present_pincode').removeClass('is-invalid');
            }
           
            // Load cities if permanent state has value
            if (permanentState && permanentState.value && presentCityElem) {
                try {
                    await loadCities(permanentState.value, presentCityElem, permanentCity.value);
                } catch (error) {
                    console.error("Error copying cities:", error);
                }
            } 
        }
        
        // Update niceSelect if available
        if (typeof $.fn !== 'undefined' && typeof $.fn.niceSelect === 'function') {
            if (presentState) $(presentState).niceSelect('update');
            if (presentCityElem) $(presentCityElem).niceSelect('update');
        }
    }

    async function loadCities(stateId, citySelect, selectedCityId = null) {
        if (!stateId || !citySelect) {
            console.error("Missing parameters:", {stateId, citySelect});
            return;
        } 
        
        citySelect.innerHTML = '<option value="">Loading cities...</option>';
        
        try {
            const response = await fetch(siteUrlGetCities, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfTokenName]: csrfToken
                },
                body: JSON.stringify({ state_id: stateId })                
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }            
            
            const data = await response.json();
            
            // Reset options
            citySelect.innerHTML = '';            
            // Add default option
            const defaultOption = new Option('Select City', '');
            defaultOption.selected = true;
            citySelect.add(defaultOption);

            // Add cities if available
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(city => {
                    const option = new Option(city.name, city.id);
                    if (selectedCityId && city.id == selectedCityId) {
                        option.selected = true;
                    }
                    citySelect.add(option);
                });
            } else {
                citySelect.innerHTML = '<option value="">No cities found</option>';
            }

        } catch (error) {
            console.error('Error loading cities:', error);
            citySelect.innerHTML = '<option value="">Error loading cities</option>';
        } finally {
            if (typeof $.fn !== 'undefined' && typeof $.fn.niceSelect === 'function') {
                $(citySelect).niceSelect('update');
            }
        }
    }    
});