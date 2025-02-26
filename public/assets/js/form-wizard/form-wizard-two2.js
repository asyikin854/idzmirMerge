(function ($) {
    "use strict";

    var formWizard = {
        init: function () {
            var navListItems = $('div.setup-panel div a'),
                allWells = $('.setup-content'),
                allNextBtn = $('.nextBtn');

            allWells.hide(); // Hide all steps initially

            // Handle navigation between steps
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

            // Handle "Next" button click
            allNextBtn.click(function () {
                var curStep = $(this).closest(".setup-content"),
                    curStepBtn = curStep.attr("id"),
                    nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                    curInputs = curStep.find("input[type='text'],input[type='url'],input[type='number'],input[type='date'],input[type='email'],select"),
                    isValid = true;

                // Remove previous error messages
                $(".form-group").removeClass("has-error");
                $(".error-message").remove();

                // Validate current step
                for (var i = 0; i < curInputs.length; i++) {
                    if (!curInputs[i].validity.valid) {
                        isValid = false;
                        $(curInputs[i]).closest(".form-group").addClass("has-error");
                        $(curInputs[i]).after('<span class="error-message" style="color:red;">This field is required.</span>');
                    }
                }

                // If current step is valid, proceed to the next step
                if (isValid) {
                    nextStepWizard.removeAttr('disabled').trigger('click');
                }
            });

            // Initialize the first step
            $('div.setup-panel div a.btn-primary').trigger('click');
        }
    };

    // Initialize the form wizard
    formWizard.init();

    // Toggle fields based on nationality selection
    function toggleFieldsBasedOnNationality() {
        const nationalitySelect = document.getElementById('child_nationality');
        const childICInput = document.getElementById('child_ic');
        const childPassportInput = document.getElementById('child_passport');
        const childICLabel = document.getElementById('child_ic_label');
        const childPassportLabel = document.getElementById('child_passport_label');
        const malaysianDiv = document.getElementById('malaysian');
        const nonMalaysianDiv = document.getElementById('non-malaysian');
        const malaysianId = document.getElementById('malaysian-id');
        const nonMalaysianId = document.getElementById('non-malaysian-id');

        // Initial state: Hide both fields and labels
        childICInput.style.display = 'none';
        childPassportInput.style.display = 'none';
        childICLabel.style.display = 'none';
        childPassportLabel.style.display = 'none';
        malaysianDiv.style.display = 'none';
        nonMalaysianDiv.style.display = 'none';
        malaysianId.disabled = true;
        nonMalaysianId.disabled = true;

        // Handle nationality change
        nationalitySelect.addEventListener('change', function () {
            const selectedNationality = nationalitySelect.value;

            if (selectedNationality === 'Malaysian') {
                childICInput.style.display = 'block';
                childPassportInput.style.display = 'none';
                childICInput.required = true;
                childPassportInput.required = false;
                childPassportInput.value = '';
                childICLabel.style.display = 'block';
                childPassportLabel.style.display = 'none';
                malaysianDiv.style.display = 'block';
                nonMalaysianDiv.style.display = 'none';
                malaysianId.disabled = false;
                nonMalaysianId.disabled = true;
            } else if (selectedNationality) {
                childICInput.style.display = 'none';
                childPassportInput.style.display = 'block';
                childICInput.required = false;
                childPassportInput.required = true;
                childICInput.value = '';
                childICLabel.style.display = 'none';
                childPassportLabel.style.display = 'block';
                malaysianDiv.style.display = 'none';
                nonMalaysianDiv.style.display = 'block';
                malaysianId.disabled = true;
                nonMalaysianId.disabled = false;
            } else {
                childICInput.style.display = 'none';
                childPassportInput.style.display = 'none';
                childICInput.required = false;
                childPassportInput.required = false;
                childICInput.value = '';
                childPassportInput.value = '';
                childICLabel.style.display = 'none';
                childPassportLabel.style.display = 'none';
                malaysianDiv.style.display = 'none';
                nonMalaysianDiv.style.display = 'none';
                malaysianId.disabled = true;
                nonMalaysianId.disabled = true;
            }
        });
    }

    // Run on document ready
    document.addEventListener('DOMContentLoaded', toggleFieldsBasedOnNationality);

    // Toggle fields for father and mother information
    document.addEventListener('DOMContentLoaded', function () {
        function toggleFields(checkboxId, inputDivId, specificFields) {
            const checkbox = document.getElementById(checkboxId);
            const inputDiv = document.getElementById(inputDivId);

            checkbox.addEventListener('change', function () {
                if (checkbox.checked) {
                    inputDiv.style.display = 'none';
                    specificFields.forEach(function (field) {
                        field.required = false;
                        field.value = '';
                    });
                } else {
                    inputDiv.style.display = 'block';
                    specificFields.forEach(function (field) {
                        field.required = true;
                    });
                }
            });
        }

        // Initialize toggle for mother fields
        const specificMotherFields = [
            document.getElementById('mother_name'),
            document.getElementById('mother_ic'),
            document.getElementById('mother_phone'),
            document.getElementById('mother_email'),
        ];
        toggleFields('mother_checkbox', 'mother_input', specificMotherFields);

        // Initialize toggle for father fields
        const specificFatherFields = [
            document.getElementById('father_name'),
            document.getElementById('father_ic'),
            document.getElementById('father_phone'),
            document.getElementById('father_email'),
        ];
        toggleFields('father_checkbox', 'father_input', specificFatherFields);
    });

    // Dynamic list of required fields
    document.addEventListener('DOMContentLoaded', function () {
        const requiredFieldsList = document.getElementById('requiredFieldsList');
        const requiredFields = document.querySelectorAll('#registrationForm [required]');

        // Function to update the list of required fields
        function updateRequiredFieldsList() {
            requiredFieldsList.innerHTML = ''; // Clear the list

            requiredFields.forEach(function (field) {
                const label = field.labels ? field.labels[0].innerText : field.name;
                const listItem = document.createElement('li');

                // Check if the field is empty
                if (!field.value.trim()) {
                    listItem.textContent = label;
                    listItem.classList.add('missing'); // Highlight missing fields
                } else {
                    listItem.textContent = label;
                    listItem.classList.remove('missing'); // Remove highlight if filled
                }

                requiredFieldsList.appendChild(listItem);
            });
        }

        // Update the list initially
        updateRequiredFieldsList();

        // Add event listeners to all required fields
        requiredFields.forEach(function (field) {
            field.addEventListener('input', updateRequiredFieldsList); // Update on input
            field.addEventListener('change', updateRequiredFieldsList); // Update on change
        });

        // Update the list when navigating between steps
        const stepButtons = document.querySelectorAll('.setup-panel a');
        stepButtons.forEach(function (button) {
            button.addEventListener('click', updateRequiredFieldsList);
        });
    });

})(jQuery);