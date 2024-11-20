<div class="modal fade" id="editMotherModal" tabindex="-1" role="dialog" aria-labelledby="editMotherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('update.motherInfo', $motherInfo->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMotherModalLabel">Edit Mother Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="mother_name">Name</label>
                        <input type="text" class="form-control" name="mother_name" value="{{ $motherInfo->mother_name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mother_phone">Phone No</label>
                        <input type="text" class="form-control" name="mother_phone" value="{{ $motherInfo->mother_phone }}" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="mother_ic">I/C Number / Passport</label>
                        <input class="form-control" maxlength="12" name="mother_ic" id="mother_ic" value="{{ $motherInfo->mother_ic }}" type="text" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_race">Race </label>
                        <input class="form-control" name="mother_race" id="mother_race" type="text" value="{{ $motherInfo->mother_race }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_occ">Occupation </label>
                        <input class="form-control" name="mother_occ" id="mother_occ" type="text" value="{{ $motherInfo->mother_occ }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_email">Email </label>
                        <input class="form-control" name="mother_email" id="mother_email" type="email" value="{{ $motherInfo->mother_email }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_address">Address </label>
                        <input class="form-control" name="mother_address" id="mother_address" type="text" value="{{ $motherInfo->mother_address }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_posscode">Postcode </label>
                        <input class="form-control" name="mother_posscode" id="mother_posscode" type="number" value="{{ $motherInfo->mother_posscode }}" maxlength="5" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_city">City </label>
                        <input class="form-control" name="mother_city" id="mother_city" type="text" value="{{ $motherInfo->mother_city }}" >
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_work_address">Work Address </label>
                        <input class="form-control" name="mother_work_address" id="mother_work_address" value="{{ $motherInfo->mother_work_address }}" type="text">
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_work_posscode">Postcode </label>
                        <input class="form-control" name="mother_work_posscode" id="mother_work_posscode" type="number" value="{{ $motherInfo->mother_work_posscode }}" maxlength="5">
                     </div>
                     <div class="form-group mb-3">
                        <label for="mother_work_city">City </label>
                        <input class="form-control" name="mother_work_city" id="mother_work_city" value="{{ $motherInfo->mother_work_city }}" type="text">
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
