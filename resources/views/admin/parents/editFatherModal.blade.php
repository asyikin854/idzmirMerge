<div class="modal fade" id="editFatherModal" tabindex="-1" role="dialog" aria-labelledby="editFatherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('update.fatherInfo', $fatherInfo->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFatherModalLabel">Edit Father Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="father_name">Name</label>
                        <input type="text" class="form-control" name="father_name" value="{{ $fatherInfo->father_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="father_phone">Phone No</label>
                        <input type="text" class="form-control" name="father_phone" value="{{ $fatherInfo->father_phone }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="father_ic">I/C Number / Passport</label>
                        <input class="form-control" maxlength="12" name="father_ic" id="father_ic" value="{{ $fatherInfo->father_ic }}" type="text" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_race">Race </label>
                        <input class="form-control" name="father_race" id="father_race" type="text" value="{{ $fatherInfo->father_race }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_occ">Occupation </label>
                        <input class="form-control" name="father_occ" id="father_occ" type="text" value="{{ $fatherInfo->father_occ }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_email">Email </label>
                        <input class="form-control" name="father_email" id="father_email" type="email" value="{{ $fatherInfo->father_email }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_address">Address </label>
                        <input class="form-control" name="father_address" id="father_address" type="text" value="{{ $fatherInfo->father_address }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_posscode">Postcode </label>
                        <input class="form-control" name="father_posscode" id="father_posscode" type="number" value="{{ $fatherInfo->father_posscode }}" maxlength="5" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_city">City </label>
                        <input class="form-control" name="father_city" id="father_city" type="text" value="{{ $fatherInfo->father_city }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_work_address">Work Address </label>
                        <input class="form-control" name="father_work_address" id="father_work_address" value="{{ $fatherInfo->father_work_address }}" type="text">
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_work_posscode">Postcode </label>
                        <input class="form-control" name="father_work_posscode" id="father_work_posscode" type="number" value="{{ $fatherInfo->father_work_posscode }}" maxlength="5">
                     </div>
                     <div class="form-group mb-3">
                        <label for="father_work_city">City </label>
                        <input class="form-control" name="father_work_city" id="father_work_city" value="{{ $fatherInfo->father_work_city }}" type="text">
                     </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
