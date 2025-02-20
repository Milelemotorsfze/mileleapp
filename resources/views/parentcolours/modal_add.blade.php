<style>
    .close-button {
        border: 0px solid #aaa;
        box-shadow: none;
        border-radius: 3px;
        background-color: #75788b;
    }

    .close-button:hover {
        background-color: rgb(89, 93, 115);
    }
</style>

<div class="modal fade" id="addParentColorModal" tabindex="-1" role="dialog" aria-labelledby="addParentColorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="form-add-parent-color" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Parent Color</h5>
                    <button type="button" class="close close-button" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="parent_color_name"><strong>Color Name:</strong></label>
                        <input type="text" class="form-control" id="parent_color_name" name="name" required>
                        <div id="parentColorError" class="error mt-1" style="display: none;"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

