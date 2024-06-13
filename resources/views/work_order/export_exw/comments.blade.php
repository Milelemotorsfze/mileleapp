<style>
    .comment {
        margin-bottom: 20px;
    }
    .reply {
        margin-left: 30px; /* Indent replies by 40px */
        margin-top: 10px;
    }
    .reply-button {
        margin-top: 10px;
    }
    .replies {
        margin-left: 30px; /* Indent nested replies by 40px */
    }
</style>
<!-- <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head> -->
<div class="row">
    <div class="row" id="comments-section">
        
    </div>
    <div class="form-group">
        <label for="new-comment">Add a comment:</label>
        <textarea class="form-control" id="new-comment" rows="3"></textarea>
        <button class="btn btn-sm btn-primary mt-2" onclick="addCommentFromInput()">Add Comment</button>
    </div>
</div>
<script>
    var workOrder = {!! json_encode($workOrder) !!};
	$(document).ready(function() {
        // Set up AJAX to include the CSRF token in the request headers
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        const workOrderId = workOrder.id; // Ensure this value is correctly set
        $.ajax({
            url: `/comments/${workOrderId}`, // Endpoint to fetch all comments for the work order
            type: 'GET',
            success: function(response) {
                console.log('AJAX response:', response); // Log the entire response
                if (response && response.comments) {
                    renderComments(response.comments); // Assuming the response contains an array of comments
                } else {
                    console.error('Unexpected response structure:', response);
                }
            },
            error: function(error) {
                console.error('Error fetching comments:', error);
            }
        });
    });

function addComment(commentData = {}) {
    console.log('Adding comment:', commentData); // Log the comment data

    const { text = '', parent_id = null, id = null, created_at = new Date().toISOString() } = commentData;

    if (!text || !id) {
        console.error('Invalid comment data:', commentData);
        return;
    }

    const commentHtml = `
        <div class="comment mt-2" id="comment-${id}" data-comment-id="${id}" data-parent-id="${parent_id}">
            <div class="col-xxl-1 col-lg-1 col-md-1" style="width:3.33333%;">
                <img class="rounded-circle header-profile-user" src="http://127.0.0.1:8000/images/users/avatar-1.jpg" alt="Header Avatar" style="float: left;">
            </div>
            <div class="col-xxl-11 col-lg-11 col-md-11">${text}</br>
                <span style="color:gray;">Rejitha R Prasad</span>
                <span style="color:gray;"> - ${new Date(created_at).toLocaleString()}</span></br>
                <button class="btn btn-secondary btn-sm reply-button" onclick="showReplyForm(${id})">Reply</button></br>
                <div class="reply-form" id="reply-form-${id}" style="display: none;">
                    <textarea class="form-control reply" id="reply-input-${id}" rows="2" placeholder="Write a reply..."></textarea>
                    <button class="btn btn-sm btn-info mt-2" onclick="addCommentFromInput(${id})">Send Reply</button>
                </div>
                <div class="replies" id="replies-${id}"></div>
            </div>
        </div>
    `;

    if (parent_id === null) {
        $('#comments-section').append(commentHtml);
        $('#new-comment').val('');
    } else {
        $(`#replies-${parent_id}`).append(commentHtml);
        $(`#reply-input-${parent_id}`).val('');
        $(`#reply-form-${parent_id}`).hide();
    }
}

function addCommentFromInput(parentId = null) {
    const commentText = parentId ? $(`#reply-input-${parentId}`).val() : $('#new-comment').val();
    if (commentText.trim() === '') return;

    const commentData = {
        text: commentText,
        parent_id: parentId,
        work_order_id: workOrder.id
    };

    $.ajax({
        url: '/comments', // Laravel route to handle comment storage
        type: 'POST',
        data: commentData,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Comment added:', response); // Log the response
            addComment(response);
        },
        error: function(error) {
            console.error('Error adding comment:', error);
        }
    });
}

function showReplyForm(commentId) {
    $(`#reply-form-${commentId}`).toggle();
}

function renderComments(comments) {
    comments.forEach(comment => addComment(comment));
}


</script>