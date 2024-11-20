<div class="modal fade" id="editParentModal" tabindex="-1" role="dialog" aria-labelledby="editParentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('update.parentAccount', $parentAccount->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editParentModalLabel">Edit Parent Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="email">Email address </label>
                        <input class="form-control" id="email" name="email" type="email" value="{{$parentAccount->email}}" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="username">Username </label>
                        <input class="form-control" name="username" id="username" type="text" value="{{$parentAccount->username}}" placeholder="" required>
                     </div>
                     <div class="form-group mb-3">
                        <label for="password">Password (Leave this field empty if you don't want to change password)</label>
                        <input class="form-control" id="password" name="password" type="password" placeholder="Password" >
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
