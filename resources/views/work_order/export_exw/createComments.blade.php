<div class="row" id="comments-section">
    <!-- Comments will be dynamically inserted here -->
</div>
<div class="form-group">
    <label for="new-comment">Add a comment:</label>
    <textarea class="form-control" id="new-comment" rows="3"></textarea>
    <button class="btn btn-sm btn-primary mt-2" onclick="addComment()">Add Comment</button>
</div>
<script type="text/javascript">
function addComment(parentId = null) {
        const commentText = parentId ? $(`#reply-input-${parentId}`).val() : $('#new-comment').val();
        if (commentText.trim() === '') return;

        const commentHtml = `
            <div class="comment mt-2" data-comment-id="${commentIdCounter}" data-parent-id="${parentId || ''}">
                <div class="col-xxl-1 col-lg-1 col-md-1" style="width:3.33333%;">
                    <img class="rounded-circle header-profile-user" src="http://127.0.0.1:8000/images/users/avatar-1.jpg" alt="Header Avatar" style="float: left;">
                </div>
                <div class="col-xxl-11 col-lg-11 col-md-11">${commentText}</br>
                    <span style="color:gray;">Rejitha R Prasad</span>
                    <span style="color:gray;"> - 30 May 2024, 18:00:00</span></br>
                    <button class="btn btn-secondary btn-sm reply-button" onclick="showReplyForm(${commentIdCounter})">Reply</button></br>
                    <div class="reply-form" id="reply-form-${commentIdCounter}" style="display: none;">
                        <textarea class="form-control reply" id="reply-input-${commentIdCounter}" rows="2" placeholder="Write a reply..."></textarea>
                        <button class="btn btn-sm btn-info mt-2" onclick="addComment(${commentIdCounter})">Send Reply</button>
                    </div>
                    <div class="replies" id="replies-${commentIdCounter}"></div>
                </div>
            </div>
        `;

        if (parentId === null) {
            $('#comments-section').append(commentHtml);
            $('#new-comment').val('');
        } else {
            $(`#replies-${parentId}`).append(commentHtml);
            $(`#reply-input-${parentId}`).val('');
            $(`#reply-form-${parentId}`).hide();
        }

        commentIdCounter++;
    }
		
    function showReplyForm(commentId) {
        $(`#reply-form-${commentId}`).toggle();
    }
</script>