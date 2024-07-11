<style>
    .comment {
        margin-bottom: 20px;
    }
    .reply {
        margin-left: 30px; /* Indent replies by 40px */
        margin-top: 10px;
    }
    .reply-button {
        margin-top: 0px;
    }
    .replies {
        margin-left: 30px; /* Indent nested replies by 40px */
    }
    .file-preview {
        position: relative;
        display: flex;
    }

    .file-preview .hover-options {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
    }

    .file-preview:hover .hover-options {
        display: flex;
    }

    .file-preview img {
        transition: opacity 0.3s;
    }

    .file-preview:hover img {
        opacity: 0.6;
    }

    .hover-options button {
        margin: 0 1px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
    }

    .hover-options button i {
        pointer-events: none;
    }
    .reply-button {
        margin-left:10px;
    }
</style>
<!-- <head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head> -->
<div class="row">
    <div class="row" id="comments-section">
        
    </div>
    <textarea class="form-control" id="new-comment" rows="2" placeholder="Add a comment..."></textarea>
    <div class="row mt-2">
        <div class="col-xxl-11 col-lg-11 col-md-11">
            <div id="file-previews" class="ml-2 d-flex flex-wrap"></div>
        </div>
        <div class="col-xxl-1 col-lg-1 col-md-1">
            <button class="btn btn-sm btn-primary mt-2" style="float:right;" id="addCommentStyle" onclick="addCommentFromInput()" title="Add Comment">
                <i class="fa fa-paper-plane" aria-hidden="true"></i></button>
            <input type="file" id="comment-files" multiple class="form-control" style="display:none;">
            <label for="comment-files" title="Attachments" class="btn btn-sm btn-info mt-2" style="float:right; margin-right:5px;">
                <i class="fa fa-paperclip" aria-hidden="true"></i>
            </label>
        </div>
    </div>
</div>
<script>
    var workOrder = {!! json_encode($workOrder) !!};
    document.getElementById('comment-files').addEventListener('change', function() {
        previewFiles(this.files, 'file-previews');
    });
    function previewFiles(files, previewContainerId, commentId) {
        const previewContainer = document.getElementById(previewContainerId);
        previewContainer.innerHTML = ''; // Clear previous previews

        for (const file of files) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.createElement('div');
                preview.classList.add('file-preview', 'm-1');
                preview.dataset.commentId = commentId; // Add commentId as a data attribute

                if (file.type.startsWith('image/')) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        <div class="hover-options">
                            <button onclick="viewImage('${e.target.result}')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>
                            <button onclick="downloadImage('${e.target.result}', '${file.name}')" title="Download"><i class="fa fa-download" aria-hidden="true"></i></button>
                        </div>
                    `;
                } else {
                    preview.innerHTML = `<a href="${e.target.result}" target="_blank">${file.name}</a>`;
                }

                previewContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    }
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
        const { text = '', parent_id = null, id = null, created_at = new Date().toISOString(), files = [] } = commentData;

        // Check for invalid comment data
        if (!id || (text === '' && files.length === 0)) {
            console.error('Invalid comment data:', commentData);
            return;
        }

        const formattedDate = new Date(created_at).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        });

        const formattedTime = new Date(created_at).toLocaleTimeString('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        });

        const formattedDateTime = `${formattedDate}, ${formattedTime}`;

        const filePreviewsHtml = files.map(file => {
            if (file.file_data.startsWith('data:image/')) {
                return `
                    <div class="file-preview m-1" data-comment-id="${id}">
                        <img src="${file.file_data}" alt="${file.file_name}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        <div class="hover-options">
                            <button onclick="viewImage('${file.file_data}')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>
                            <button onclick="downloadImage('${file.file_data}', '${file.file_name}')" title="Download"><i class="fa fa-download" aria-hidden="true"></i></button>
                        </div>
                    </div>
                `;
            } else {
                return `<a href="${file.file_data}" class="m-1" target="_blank">${file.file_name}</a>`;
            }
        }).join('');

        const replyButtonHtml = commentData.user ? `
            <button class="btn btn-secondary btn-sm reply-button" onclick="showReplyForm(${id})" title="Reply">
                <i class="fa fa-reply" aria-hidden="true"></i>
            </button>` : '';

        const additionalDivHtml = !commentData.user ? `<div id="additional-div-${id}">Some data will display here</div>` : '';

        const commentHtml = `
            <div class="comment mt-2" id="comment-${id}" data-comment-id="${id}" data-parent-id="${parent_id}">
                <div class="row">
                    <div class="col-xxl-1 col-lg-1 col-md-1" style="width:3.33333%;">
                        <img class="rounded-circle header-profile-user" src="http://127.0.0.1:8000/images/users/avatar-1.jpg" alt="Header Avatar" style="float: left;">
                    </div>
                    <div class="col-xxl-11 col-lg-11 col-md-11">
                        <div class="comment-text">${text}</div>
                        ${additionalDivHtml}
                        <div class="d-flex flex-wrap">${filePreviewsHtml}</div>
                        ${replyButtonHtml}
                        <span style="color:gray;">
                            ${commentData.user ? commentData.user.name : 'System Generated'}
                        </span>
                        <span style="color:gray;"> - ${formattedDateTime}</span>
                        <div class="reply-form" id="reply-form-${id}" style="display: none;">
                            <textarea class="form-control reply" id="reply-input-${id}" rows="2" placeholder="Write a reply..."></textarea>
                            <div class="row">
                                <div class="col-xxl-11 col-lg-11 col-md-11">
                                    <div id="reply-previews-${id}" class="reply ml-2 d-flex flex-wrap"></div>
                                </div>
                                <div class="col-xxl-1 col-lg-1 col-md-1">
                                    <input type="file" id="reply-files-${id}" multiple class="form-control validate-excluded" style="display:none;">
                                    <button class="btn btn-sm btn-primary" onclick="addCommentFromInput(${id})" title="Send Reply" style="float:right; margin-top:0.6rem;">
                                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                    </button>
                                    <label for="reply-files-${id}" title="Attachments" class="btn btn-sm btn-info reply" style="float:right; margin-right:5px;">
                                        <i class="fa fa-paperclip" aria-hidden="true"></i>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="replies" id="replies-${id}"></div>
                    </div>
                </div>
            </div>
        `;

        if (parent_id === null) {
            $('#comments-section').append(commentHtml);
            $('#new-comment').val('');
            $('#comment-files').val('');
            $('#file-previews').empty();
        } else {
            $(`#replies-${parent_id}`).append(commentHtml);
            $(`#reply-input-${parent_id}`).val('');
            $(`#reply-files-${parent_id}`).val('');
            $(`#reply-previews-${parent_id}`).empty();
            $(`#reply-form-${parent_id}`).hide();
        }
    }

    function addCommentFromInput(parentId = null) {
        // Get the comment text and file inputs
        const commentText = parentId ? $(`#reply-input-${parentId}`).val() : $('#new-comment').val();
        const filesInput = parentId ? $(`#reply-files-${parentId}`)[0].files : $('#comment-files')[0].files;

        // Check if comment text is empty and no files are attached
        if (commentText.trim() === '' && filesInput.length === 0) {
            console.error('Cannot add an empty comment without files.');
            return;
        }

        // Create a FormData object to hold the comment data
        const formData = new FormData();
        formData.append('text', commentText.trim() === '' ? '' : commentText); // Store text as null if empty
        formData.append('parent_id', parentId ? parentId : '');
        formData.append('work_order_id', workOrder.id);

        // Append files to the FormData object
        Array.from(filesInput).forEach(file => {
            formData.append('files[]', file);
        });
        $.ajax({
            url: '/comments', // Laravel route to handle comment storage
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
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
         // Add event listener for reply file input
         $(`#reply-files-${commentId}`).off('change').on('change', function() {
            previewFiles(this.files, `reply-previews-${commentId}`);
        });
    }

    function renderComments(comments) {
        comments.forEach(comment => addComment(comment));
    }
    function viewImage(src) {
        const newWindow = window.open();
        newWindow.document.write(`<img src="${src}" style="max-width: 100%; height: auto;">`);
    }
    function downloadImage(src, filename) {
        const link = document.createElement('a');
        link.href = src;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>