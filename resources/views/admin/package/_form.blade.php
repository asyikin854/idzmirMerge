<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    <div class="form-group">
        <label for="package_name">Package Name</label>
        <input type="text" id="package_name" name="package_name" class="form-control" value="{{ old('package_name', $package->package_name ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="package_step">Package Step</label>
        <input type="text" id="package_step" name="package_step" class="form-control" value="{{ old('package_step', $package->package_step ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="session_quantity">Session Quantity</label>
        <input type="number" id="session_quantity" name="session_quantity" class="form-control" value="{{ old('session_quantity', $package->session_quantity ?? '') }}" required>
    </div>
    <div class="form-group">
        <label for="type">Package Type</label>
        <select id="type" name="type" class="form-control" required>
            <option value="individual" {{ old('type', $package->type ?? '') === 'individual' ? 'selected' : '' }}>Individual</option>
            <option value="grouping" {{ old('type', $package->type ?? '') === 'grouping' ? 'selected' : '' }}>Grouping</option>
            <option value="screening" {{ old('type', $package->type ?? '') === 'screening' ? 'selected' : '' }}>Screening</option>
        </select>
    </div>
    <div class="form-group">
        <label for="quota">Quota</label>
        <select id="quota" name="quota" class="form-control" required>
 
        </select>
    </div>

    <div class="form-group">
        <label for="package_normal_price">Normal Price</label>
        <input type="number" id="package_normal_price" name="package_normal_price" class="form-control" step="0.01" value="{{ old('package_normal_price', $package->package_normal_price ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="package_wkday_price">Weekday Price</label>
        <input type="number" id="package_wkday_price" name="package_wkday_price" class="form-control" step="0.01" value="{{ old('package_wkday_price', $package->package_wkday_price ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="package_wkend_price">Weekend Price</label>
        <input type="number" id="package_wkend_price" name="package_wkend_price" class="form-control" step="0.01" value="{{ old('package_wkend_price', $package->package_wkend_price ?? '') }}" required>
    </div>

    <div class="form-group">
        <label for="package_long_desc1">Long Description 1</label>
        <textarea name="package_long_desc1" id="package_long_desc1" cols="30" rows="3" class="form-control">{{ old('package_long_desc1', $package->package_long_desc1 ?? '') }}</textarea>
    </div>
    <div class="form-group">
        <label for="package_long_desc2">Long Description 2</label>
        <textarea name="package_long_desc2" id="package_long_desc2" cols="30" rows="3" class="form-control">{{ old('package_long_desc2', $package->package_long_desc2 ?? '') }}</textarea>
    </div>
    <div class="form-group">
        <label for="package_long_desc3">Long Description 3</label>
        <textarea name="package_long_desc3" id="package_long_desc3" cols="30" rows="3" class="form-control">{{ old('package_long_desc3', $package->package_long_desc3 ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="package_short_desc1">Short Description 1</label>
        <textarea name="package_short_desc1" id="package_short_desc1" cols="30" rows="2" class="form-control">{{ old('package_short_desc1', $package->package_short_desc1 ?? '') }}</textarea>
    </div>
    <div class="form-group">
        <label for="package_short_desc2">Short Description 2</label>
        <textarea name="package_short_desc2" id="package_short_desc2" cols="30" rows="2" class="form-control">{{ old('package_short_desc2', $package->package_short_desc2 ?? '') }}</textarea>
    </div>
    <div class="form-group">
        <label for="package_short_desc3">Short Description 3</label>
        <textarea name="package_short_desc3" id="package_short_desc3" cols="30" rows="2" class="form-control">{{ old('package_short_desc3', $package->package_short_desc3 ?? '') }}</textarea>
    </div>
    <div class="form-group">
        <label for="package_short_desc4">Short Description 4</label>
        <textarea name="package_short_desc4" id="package_short_desc4" cols="30" rows="2" class="form-control">{{ old('package_short_desc4', $package->package_short_desc4 ?? '') }}</textarea>
    </div>
    <div class="form-group">
        <label for="package_short_desc5">Short Description 5</label>
        <textarea name="package_short_desc5" id="package_short_desc5" cols="30" rows="2" class="form-control">{{ old('package_short_desc5', $package->package_short_desc5 ?? '') }}</textarea>
    </div>

    <div class="form-group">
        <label for="citizenship">Citizenship</label>
        <select id="citizenship" name="citizenship" class="form-control" required>
            <option value="yes" {{ old('citizenship', $package->citizenship ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ old('citizenship', $package->citizenship ?? '') === 'no' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <label for="weekly">Weekly</label>
        <select id="weekly" name="weekly" class="form-control" required>
            <option value="yes" {{ old('weekly', $package->weekly ?? '') === 'yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ old('weekly', $package->weekly ?? '') === 'no' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <label for="consultation">Consultation</label>
        <select id="consultation" name="consultation" class="form-control" required>
            <option value="yes" {{ old('consultation', $package->consultation ?? '') === 'Yes' ? 'selected' : '' }}>Yes</option>
            <option value="no" {{ old('consultation', $package->consultation ?? '') === 'No' ? 'selected' : '' }}>No</option>
        </select>
    </div>

    <div class="form-group">
        <label for="filename">Upload Image/Poster</label>
        <input type="file" id="file" name="file" class="form-control">
        @if (!empty($package->path))
        <p>Current File: <a href="{{ asset('storage/' . $package->path) }}" target="_blank">View File</a></p>
    @endif
    </div>

    <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const quotaSelect = document.getElementById('quota');

        // Define the quota options for each package type
        const quotaOptions = {
            individual: ['8'], // Quota for "Individual"
            grouping: ['6'],   // Quota for "Grouping"
            screening: ['2']   // Quota for "Screening"
        };

        // Function to update the quota options based on the selected type
        function updateQuotaOptions(selectedType) {
            // Clear existing options
            quotaSelect.innerHTML = '';

            // Get the quota values for the selected type
            const options = quotaOptions[selectedType] || [];

            // Populate the quota dropdown
            options.forEach(value => {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                // Select the option if it matches the old value or existing package value
                if (value === "{{ old('quota', $package->quota ?? '') }}") {
                    option.selected = true;
                }
                quotaSelect.appendChild(option);
            });
        }

        // Initialize quota options on page load
        updateQuotaOptions(typeSelect.value);

        // Update quota options when the type changes
        typeSelect.addEventListener('change', function () {
            updateQuotaOptions(this.value);
        });
    });
</script>