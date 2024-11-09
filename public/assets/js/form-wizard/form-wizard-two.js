(function ($) {
    "use strict";

    var formWizard = {
        init: function() {
            var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn');
                
            allWells.hide(); // Hide all steps initially

            navListItems.click(function (e) {
                e.preventDefault();
                var $target = $($(this).attr('href')),
                    $item = $(this);

                if (!$item.hasClass('disabled')) {
                    navListItems.removeClass('btn btn-light').addClass('btn btn-primary');
                    $item.addClass('btn btn-light');
                    allWells.hide();
                    $target.show();
                    $target.find('input:eq(0)').focus();
                }
            });

            allNextBtn.click(function() {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url']"),
                    isValid = true;

                $(".form-group").removeClass("has-error");
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                    }
                }
                if (isValid) nextStepWizard.removeAttr('disabled').trigger('click');
            });

            $('div.setup-panel div a.btn-primary').trigger('click');
        }
    };

    formWizard.init();

    // Toggle fields based on nationality selection
    function toggleFieldsBasedOnNationality() {
        const nationalitySelect = document.getElementById('child_nationality');
        const childICInput = document.getElementById('child_ic');
        const childPassportInput = document.getElementById('child_passport');
        const childICLabel = document.getElementById('child_ic_label'); 
        const childPassportLabel = document.getElementById('child_passport_label'); 
        const malaysianDiv = document.getElementById('malaysian'); // Div for Malaysian
        const nonMalaysianDiv = document.getElementById('non-malaysian'); // Div for non-Malaysian
        const malaysianId = document.getElementById('malaysian-id'); // Div for non-Malaysian
        const nonMalaysianId = document.getElementById('non-malaysian-id'); // Div for non-Malaysian
    
        // Initial state: Hide both fields and labels
        childICInput.style.display = 'none'; 
        childPassportInput.style.display = 'none'; 
        childICLabel.style.display = 'none';
        childPassportLabel.style.display = 'none';
        malaysianDiv.style.display = 'none'; // Hide Malaysian div
        nonMalaysianDiv.style.display = 'none'; // Hide non-Malaysian div
        malaysianId.disabled = true; // Hide non-Malaysian div
        nonMalaysianId.disabled = true; // Hide non-Malaysian div
    
        nationalitySelect.addEventListener('change', function () {
            const selectedNationality = nationalitySelect.value;
    
            if (selectedNationality === 'Malaysian') {
                childICInput.style.display = 'block'; // Show IC input
                childPassportInput.style.display = 'none'; // Hide Passport input
                childICInput.required = true;
                childPassportInput.required = false;
                childPassportInput.value = '';
                childICLabel.style.display = 'block'; // Show IC label
                childPassportLabel.style.display = 'none'; // Hide Passport label
                malaysianDiv.style.display = 'block'; // Show Malaysian div
                nonMalaysianDiv.style.display = 'none'; // Hide non-Malaysian div
                malaysianId.disabled = false; 
                nonMalaysianId.disabled = true;
            } else if (selectedNationality) {
                childICInput.style.display = 'none'; // Hide IC input
                childPassportInput.style.display = 'block'; // Show Passport input
                childICInput.required = false;
                childPassportInput.required = true;
                childICInput.value = '';
                childICLabel.style.display = 'none'; // Hide IC label
                childPassportLabel.style.display = 'block'; // Show Passport label
                malaysianDiv.style.display = 'none'; // Hide Malaysian div
                nonMalaysianDiv.style.display = 'block'; // Show non-Malaysian div
                malaysianId.disabled = true; // Hide non-Malaysian div
                nonMalaysianId.disabled = false;
            } else {
                childICInput.style.display = 'none'; // Hide IC input
                childPassportInput.style.display = 'none'; // Hide Passport input
                childICInput.required = false;
                childPassportInput.required = false;
                childICInput.value = '';
                childPassportInput.value = '';
                childICLabel.style.display = 'none'; // Hide IC label
                childPassportLabel.style.display = 'none'; // Hide Passport label
                malaysianDiv.style.display = 'none'; // Hide Malaysian div
                nonMalaysianDiv.style.display = 'none'; // Hide non-Malaysian div
                malaysianId.disabled = true; // Hide non-Malaysian div
                nonMalaysianId.disabled = true;
            }
        });
    }
    
    // Run on document ready
    document.addEventListener('DOMContentLoaded', toggleFieldsBasedOnNationality);
    

})(jQuery);
