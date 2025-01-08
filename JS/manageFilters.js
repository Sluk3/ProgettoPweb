function setFilter(action) {
    if (action === 'apply') {

        const bpm = $("#bpm-range").slider("option", "values");
        const price = $("#price-range").slider("option", "values");
        document.getElementById('filtered').value = 1;
        document.getElementById('bpm1').value = bpm[0];
        document.getElementById('bpm2').value = bpm[1];
        document.getElementById('price1').value = price[0];
        document.getElementById('price2').value = price[1];
        const form = document.getElementById('formfilter');
        form.submit();
    } else if (action === 'reset') {
        const form = document.getElementById('formfilter');
        document.getElementsByName('filtered').value = 0;
        form.submit();
        form.reset();
    }

}


$(document).ready(function () {
    // Function to handle enabling/disabling of BPM and key filters
    function handleFilterFields() {
        const $typeSelect = $('#typeFilter');
        const $bpmRange = $('#bpm-range');
        const $bpmLabel = $('#bpmlab');
        const $tonalitySelect = $('select[name="tonalityfilter"]');

        // Get the parent elements to disable the entire sections
        const $bpmSection = $bpmRange.closest('.mb-3');
        const $keySection = $tonalitySelect.closest('.mb-3');

        // Function to set disabled state
        function setDisabledState(isDisabled) {
            // Disable/Enable BPM range slider
            if ($bpmRange.hasClass('ui-slider')) {
                $bpmRange.slider(isDisabled ? 'disable' : 'enable');
            }
            $bpmLabel.prop('disabled', isDisabled);

            // Disable/Enable tonality select
            $tonalitySelect.prop('disabled', isDisabled);

            // Add/remove opacity to show disabled state visually
            $bpmSection.css('opacity', isDisabled ? '0.5' : '1');
            $keySection.css('opacity', isDisabled ? '0.5' : '1');

            if (isDisabled) {
                // Reset values when disabled
                if ($bpmRange.hasClass('ui-slider')) {
                    $bpmRange.slider('values', [60, 200]);
                    $('#bpmlab').val('60 BPM - 200 BPM');
                }
                $tonalitySelect.val('*');
            }
        }

        // Initial state and change handler
        function updateFieldsState() {
            const isTypeOne = $typeSelect.val() === '1';
            setDisabledState(!isTypeOne);
        }

        // Add change event listener to type select
        $typeSelect.on('change', updateFieldsState);

        // Set initial state
        updateFieldsState();
    }

    // Initialize filter handling
    handleFilterFields();
});