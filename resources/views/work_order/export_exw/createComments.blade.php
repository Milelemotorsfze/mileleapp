<!-- <link href="https://ichord.github.io/At.js/dist/css/jquery.atwho.css" rel="stylesheet"> -->
<link href="{{ asset('css/custom/jquery.atwho.min.jquery.atwho.css') }}" rel="stylesheet">
<!-- <script src="https://ichord.github.io/Caret.js/src/jquery.caret.js"></script> -->
<script src="{{ asset('js/custom/jquery.caret.js') }}"></script>
<!-- Include At.js -->
<!-- <script src="https://ichord.github.io/At.js/dist/js/jquery.atwho.min.js"></script> -->
<script src="{{ asset('js/custom/jquery.atwho.min.js') }}"></script>
<style>
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

<div class="row" id="comments-section">
    <!-- Comments will be dynamically inserted here -->
</div>

<textarea class="form-control" id="new-comment" rows="2" placeholder="Add a comment..."></textarea>
<div class="row mt-2">
    <div class="col-xxl-11 col-lg-11 col-md-11">
        <div id="file-previews" class="ml-2 d-flex flex-wrap"></div>
    </div>
    <div class="col-xxl-1 col-lg-1 col-md-1">
        <button class="btn btn-sm btn-primary mt-2" style="float:right;" id="addCommentStyle" onclick="addComment()" title="Add Comment"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
        <input type="file" id="comment-files" multiple class="form-control" style="display:none;">
        <label for="comment-files" title="Attachments" class="btn btn-sm btn-info mt-2" style="float:right; margin-right:5px;">
            <i class="fa fa-paperclip" aria-hidden="true"></i>
        </label>
    </div>
</div>


<script type="text/javascript">
    document.getElementById('comment-files').addEventListener('change', function() {
        previewFiles(this.files, 'file-previews');
    });
    function previewFiles(files, previewContainerId, commentId) {
        const allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        const previewContainer = document.getElementById(previewContainerId);
        previewContainer.innerHTML = ''; // Clear previous previews

        for (const file of files) {
            // Check if the file type is allowed
            if (!allowedFileTypes.includes(file.type)) {
                alert('Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.');
                continue;
            }

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
                            <button onclick="downloadFile('${e.target.result}', '${file.name}')" title="Download"><i class="fa fa-download" aria-hidden="true"></i></button>
                        </div>
                    `;
                } else if (file.type === 'application/pdf') {
                    preview.innerHTML = `
                        <embed src="${e.target.result}" type="application/pdf" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                        <div class="hover-options">
                            <button onclick="viewPDF('${e.target.result}')" title="View PDF"><i class="fa fa-eye" aria-hidden="true"></i></button>
                            <button onclick="downloadFile('${e.target.result}', '${file.name}')" title="Download PDF"><i class="fa fa-download" aria-hidden="true"></i></button>
                        </div>
                    `;
                }

                previewContainer.appendChild(preview);
            };
            reader.readAsDataURL(file);
        }
    }

    function viewPDF(src) {
        const newWindow = window.open();
        newWindow.document.write(`<embed src="${src}" type="application/pdf" style="width: 100%; height: 100%;">`);
    }

    function downloadFile(src, filename) {
        const link = document.createElement('a');
        link.href = src;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
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
  
    function addComment(parentId = null) {
        const commentText = parentId ? $(`#reply-input-${parentId}`).val() : $('#new-comment').val();
        const commentFiles = parentId ? $(`#reply-files-${parentId}`).prop('files') : $('#comment-files').prop('files');

        if (commentText.trim() === '' && commentFiles.length === 0) return;

        // Extract mentioned user IDs using a regular expression
        const mentionedUserIds = [];
        const mentionPattern = /@(\w+)/g;
        let match;
        while ((match = mentionPattern.exec(commentText)) !== null) {
            mentionedUserIds.push(match[1]); // Push the user ID or username
        }
        // Disable the submit button to prevent multiple submissions
        const submitButton = parentId ? $(`#reply-form-${parentId} .btn-primary`) : $('#addCommentStyle');
        submitButton.prop('disabled', true);

         // Check file sizes before appending them to FormData
        const maxFileSize = 2048 * 1024; // 2048 KB in bytes
        for (const file of commentFiles) { // Changed from filesInput to commentFiles
            if (file.size > maxFileSize) {
                alert(`The file ${file.name} exceeds the 2MB size limit.`);
                submitButton.prop('disabled', false); // Re-enable the submit button if validation fails
                return;
            }
        }
        const currentDateTime = new Date();
        const formattedDateTime = formatDateTime(currentDateTime);
        // Assume base URL is available as a constant
        const baseUrl = '{{env('BASE_URL')}}';

        // Get the authenticated user's image path dynamically or fall back to 'OIP.jpg' if not available
        const userProfileImage = "{{ Auth::user()->empProfile && Auth::user()->empProfile->image_path ? Auth::user()->empProfile->image_path : 'images/users/OIP.jpg' }}";

        const filePreviewsHtml = Array.from(commentFiles).map(file => {
            const reader = new FileReader();
            return new Promise((resolve) => {
                reader.onload = function(e) {
                    if (file.type.startsWith('image/')) {
                        resolve(`
                            <div class="file-preview m-1" data-comment-id="${commentIdCounter}">
                                <img src="${e.target.result}" alt="${file.name}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                <div class="hover-options">
                                    <button onclick="viewImage('${e.target.result}')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    <button onclick="downloadFile('${e.target.result}', '${file.name}')" title="Download"><i class="fa fa-download" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        `);
                    } else if (file.type === 'application/pdf') {
                        resolve(`
                            <div class="file-preview m-1" data-comment-id="${commentIdCounter}">
                                <embed src="${e.target.result}" type="application/pdf" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                <div class="hover-options">
                                    <button onclick="viewPDF('${e.target.result}')" title="View PDF"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                    <button onclick="downloadFile('${e.target.result}', '${file.name}')" title="Download PDF"><i class="fa fa-download" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        `);
                    } else {
                        resolve(`<a href="${e.target.result}" class="m-1" target="_blank">${file.name}</a>`);
                    }
                };
                reader.readAsDataURL(file);
            });
        });

        Promise.all(filePreviewsHtml).then(filePreviews => {
            const commentHtml = `
                <div class="comment mt-2" data-comment-id="${commentIdCounter}" data-parent-id="${parentId || ''}" data-date-time="${formattedDateTime}">
                    <div class="row">
                        <div class="col-xxl-1 col-lg-1 col-md-1" style="width:3.33333%;">
                        <img class="rounded-circle header-profile-user" 
                        src="${baseUrl}/${userProfileImage}" 
                        alt="Header Avatar" style="float: left;">                        </div>
                        <div class="col-xxl-11 col-lg-11 col-md-11">
                            <div class="comment-text">${commentText}</div>
                            <div class="d-flex flex-wrap">${filePreviews.join('')}</div>
                            <button class="btn btn-secondary btn-sm reply-button" onclick="showReplyForm(${commentIdCounter})" title="Reply">
                                <i class="fa fa-reply" aria-hidden="true"></i>
                            </button>
                            <span style="color:gray;">{{ Auth::user()->name }}</span>
                            <span style="color:gray;"> - ${formattedDateTime}</span>
                            
                            <div class="reply-form" id="reply-form-${commentIdCounter}" style="display: none;">
                                <textarea class="form-control reply" id="reply-input-${commentIdCounter}" rows="2" placeholder="Write a reply..."></textarea>
                                <div class="row">
                                    <div class="col-xxl-11 col-lg-11 col-md-11">
                                        <div id="reply-previews-${commentIdCounter}" class="reply ml-2 d-flex flex-wrap"></div>
                                    </div>
                                    <div class="col-xxl-1 col-lg-1 col-md-1">
                                        <input type="file" id="reply-files-${commentIdCounter}" multiple class="form-control validate-excluded" style="display:none;">
                                        <button class="btn btn-sm btn-primary" onclick="addComment(${commentIdCounter})" title="Send Reply" style="float:right; margin-top:0.6rem;">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        </button>
                                        <label for="reply-files-${commentIdCounter}" title="Attachments" class="btn btn-sm btn-info reply" style="float:right; margin-right:5px;">
                                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="replies" id="replies-${commentIdCounter}"></div>
                        </div>
                    </div>
                </div>
            `;

            if (parentId === null) {
                $('#comments-section').append(commentHtml);
                $('#new-comment').val('');
                $('#comment-files').val('');
                $('#file-previews').empty();
            } else {
                $(`#replies-${parentId}`).append(commentHtml);
                $(`#reply-input-${parentId}`).val('');
                $(`#reply-files-${parentId}`).val('');
                $(`#reply-previews-${parentId}`).empty();
                $(`#reply-form-${parentId}`).hide();
            }

            commentIdCounter++;

            // Re-enable the submit button after operation is complete
            submitButton.prop('disabled', false);
        }).catch(error => {
            console.error('Error processing files:', error);
            // Re-enable the submit button in case of an error
            submitButton.prop('disabled', false);
        });
    }
    function showReplyForm(commentId) {
        $(`#reply-form-${commentId}`).toggle();

        // Add event listener for reply file input
        $(`#reply-files-${commentId}`).off('change').on('change', function() {
            previewFiles(this.files, `reply-previews-${commentId}`);
        });
    }
    function formatDateTime(date) {
        const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        return date.toLocaleString('en-GB', options).replace(/,/g, '');
    }
</script>
