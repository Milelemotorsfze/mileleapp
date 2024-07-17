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
        const { text = '', parent_id = null, id = null, created_at = new Date().toISOString(), files = [], wo_histories = [], new_vehicles = [], removed_vehicles = [], updated_vehicles = [] } = commentData;
        // Check for invalid comment data
        if (!id || (text === '' && files.length === 0)) {
            console.error('Invalid comment data:', commentData);
            return;
        }

        if (text.length > 1000) { // Set an appropriate limit for your application
            alert('The text field must not be greater than 1000 characters.');
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

        // Process wo_histories for additional divs
        let historiesHtml = '';

        const baseUrl = 'http://127.0.0.1:8000';

        if (wo_histories.length >= 1) {
            const orderedItems = wo_histories.sort((a, b) => a.field.localeCompare(b.field));

            historiesHtml = `
                <table style="margin-top:10px;margin-bottom:10px;border:1px solid #e9e9ef;">
                    <thead>
                        <tr style="border-width: 1;">
                            <th style="padding-top:5px;padding-bottom:5px;padding-left:5px; font-size:12px!important;">Field</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Type</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Old Value</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">New Value</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            orderedItems.forEach(item => {
                let oldValueHtml = item.old_value ? `<td>${item.old_value}</td>` : '<td></td>';
                let newValueHtml = item.new_value ? `<td>${item.new_value}</td>` : '<td></td>';

                const fileFields = ['brn_file', 'signed_pfi', 'signed_contract', 'payment_receipts', 'noc', 'enduser_trade_license', 'enduser_passport', 'enduser_contract', 'vehicle_handover_person_id'];

                if (fileFields.includes(item.field_name)) {
                    const oldFileUrl = item.old_value && item.old_value.startsWith('http') ? item.old_value : `${baseUrl}/${item.old_value}`;
                    const newFileUrl = item.new_value && item.new_value.startsWith('http') ? item.new_value : `${baseUrl}/${item.new_value}`;

                    oldValueHtml = item.old_value
                        ? `<td>
                                <a href="${oldFileUrl}" target="_blank">
                                    <button class="btn btn-primary btn-style">View</button>
                                </a>
                                <a href="${oldFileUrl}" download>
                                    <button class="btn btn-info btn-style">Download</button>
                                </a>
                        </td>`
                        : '<td></td>';

                    newValueHtml = item.new_value
                        ? `<td>
                                <a href="${newFileUrl}" target="_blank">
                                    <button class="btn btn-primary btn-style">View</button>
                                </a>
                                <a href="${newFileUrl}" download>
                                    <button class="btn btn-info btn-style">Download</button>
                                </a>
                        </td>`
                        : '<td></td>';
                }
                // Check for specific values and update the display text accordingly
                if (item.old_value === 'total_deposit') {
                    oldValueHtml = '<td>Total Deposit</td>';
                } else if (item.old_value === 'custom_deposit') {
                    oldValueHtml = '<td>Custom Deposit</td>';
                }

                if (item.new_value === 'total_deposit') {
                    newValueHtml = '<td>Total Deposit</td>';
                } else if (item.new_value === 'custom_deposit') {
                    newValueHtml = '<td>Custom Deposit</td>';
                }
                historiesHtml += `
                    <tr style="border:1px solid #e9e9ef;">
                        <td style="padding-left:5px;">${item.field}</td>
                        <td>${item.type}</td>
                        ${oldValueHtml}
                        ${newValueHtml}
                    </tr>
                `;
            });

            historiesHtml += `
                    </tbody>
                </table>
            `;
        }
        let newVehiclesHtml = '';

        if (new_vehicles.length >= 1) {
            const orderedNewVehicles = new_vehicles.sort((a, b) => a.vehicle.vin.localeCompare(b.vehicle.vin));

            newVehiclesHtml = `
                <table style="margin-top:10px;margin-bottom:10px;border:1px solid #e9e9ef;">
                    <thead>
                        <tr><th colSpan="15" style="padding-left:5px!important;font-size:12px!important;padding-top:5px;padding-bottom:5px;">${new_vehicles.length} vehicles added as new</th></tr>
                        <tr style="border-width: 1;">
                            <th style="padding-top:5px;padding-bottom:5px;padding-left:5px; font-size:12px!important;">View More</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">VIN</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Brand</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Variant</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Engine</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Model Description</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Model Year</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Steering</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Exterior Colour</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Interior Colour</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Warehouse</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Territory</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Preferred Destination</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Import Type</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Ownership Name</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            orderedNewVehicles.forEach(item => {
                // Helper function to find new_value for a specific field_name
                const getFieldValue = (field_name, default_value) => {
                    const detail = item.record_histories.find(detail => detail.field_name === field_name);
                    return detail ? detail.new_value : (item.vehicle[field_name] || default_value);
                };

                const brandValue = getFieldValue('brand', '');
                const variantValue = getFieldValue('variant', '');
                const engineValue = getFieldValue('engine', '');
                const modelDescriptionValue = getFieldValue('model_description', '');
                const modelYearValue = getFieldValue('model_year', '');
                const steeringValue = getFieldValue('steering', '');
                const exteriorColourValue = getFieldValue('exterior_colour', '');
                const interiorColourValue = getFieldValue('interior_colour', '');
                const warehouseValue = getFieldValue('warehouse', '');
                const territoryValue = getFieldValue('territory', '');
                const preferredDestinationValue = getFieldValue('preferred_destination', '');
                const importDocumentTypeNameValue = getFieldValue('import_document_type_name', '');
                const ownershipNameValue = getFieldValue('ownership_name', '');

                const viewMoreUrl = `javascript:void(0);`; // Prevent default link behavior

                newVehiclesHtml += `
                    <tr style="border:1px solid #e9e9ef;">
                        <td style="padding-left:5px;">
                            <a href="${viewMoreUrl}" class="view-more-btn-removed" data-vin="${item.vehicle.vin}" data-id="${item.vehicle_id}" title="View More">ViewMore</a>
                            ${item.vehicle.deleted_at == null ? `<a href="${viewMoreUrl}" class="view-more-btn" data-vin="${item.vehicle.vin}" data-id="${item.vehicle_id}" title="View Current Record">CurrentRecord</a>` : ''}
                        </td>
                        <td>${item.vehicle.vin || ''}</td>
                        <td>${brandValue}</td>
                        <td>${variantValue}</td>
                        <td>${engineValue}</td>
                        <td>${modelDescriptionValue}</td>
                        <td>${modelYearValue}</td>
                        <td>${steeringValue}</td>
                        <td>${exteriorColourValue}</td>
                        <td>${interiorColourValue}</td>
                        <td>${warehouseValue}</td>
                        <td>${territoryValue}</td>
                        <td>${preferredDestinationValue}</td>
                        <td>${importDocumentTypeNameValue}</td>
                        <td>${ownershipNameValue}</td>
                    </tr>
                `;
            });

            newVehiclesHtml += `
                    </tbody>
                </table>
            `;        
        }

        // Process removed_vehicles for additional divs
        let removedVehiclesHtml = '';
        if (removed_vehicles.length >= 1) {
            const orderedRemovedVehicles = removed_vehicles.sort((a, b) => a.vin.localeCompare(b.vin));

            removedVehiclesHtml = `
                <table style="margin-top:10px;margin-bottom:10px;border:1px solid #e9e9ef;">
                    <thead>
                        <tr><th colSpan="15" style="padding-left:5px!important;font-size:12px!important;padding-top:5px;padding-bottom:5px;">${removed_vehicles.length} vehicles removed</th></tr>
                        <tr style="border-width: 1;">
                            <th style="padding-top:5px;padding-bottom:5px;padding-left:5px; font-size:12px!important;">View More</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">VIN</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Brand</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Variant</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Engine</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Model Description</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Model Year</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Steering</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Exterior Colour</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Interior Colour</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Warehouse</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Territory</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Preferred Destination</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Import Type</th>
                            <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Ownership Name</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            orderedRemovedVehicles.forEach(item => {
                const viewMoreUrl = `javascript:void(0);`; // Prevent default link behavior

                removedVehiclesHtml += `
                    <tr style="border:1px solid #e9e9ef;">
                        <td style="padding-left:5px;">
                            <a href="${viewMoreUrl}" class="view-more-btn-removed" data-vin="${item.vin}" data-id="${item.id}" title="View More">View More</a>
                        </td>
                        <td>${item.vin || ''}</td>
                        <td>${item.brand || ''}</td>
                        <td>${item.variant || ''}</td>
                        <td>${item.engine || ''}</td>
                        <td>${item.model_description || ''}</td>
                        <td>${item.model_year || ''}</td>
                        <td>${item.steering || ''}</td>
                        <td>${item.exterior_colour || ''}</td>
                        <td>${item.interior_colour || ''}</td>
                        <td>${item.warehouse || ''}</td>
                        <td>${item.territory || ''}</td>
                        <td>${item.preferred_destination || ''}</td>
                        <td>${item.import_document_type_name || ''}</td>
                        <td>${item.ownership_name || ''}</td>
                    </tr>
                `;
            });

            removedVehiclesHtml += `
                    </tbody>
                </table>
            `;        
        }

        // Process updated_vehicles for additional divs
        let updatedVehiclesHtml = '';
        if (updated_vehicles.length >= 1) {
            const orderedUpdatedVehicles = updated_vehicles.sort((a, b) => a.vehicle.vin.localeCompare(b.vehicle.vin));

            updatedVehiclesHtml = `
                <table style="margin-top:10px;margin-bottom:10px;border:1px solid #e9e9ef;">
                    <thead>
                        <tr>
                            <th colSpan="4" style="padding-left:5px!important;font-size:12px!important;padding-top:5px;padding-bottom:5px;border-bottom:1px solid #e9e9ef;">
                                ${updated_vehicles.length} vehicles data updated
                            </th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            orderedUpdatedVehicles.forEach(item => {
                const viewMoreUrl = `javascript:void(0);`; // Prevent default link behavior
                // Add a row for the vehicle's VIN
                updatedVehiclesHtml += `
                    <tr>
                        <th colSpan="4" style="padding-left:5px!important;font-size:12px!important;padding-top:5px;padding-bottom:5px; border-top:2px solid #e1e1ea;">
                        <a href="${viewMoreUrl}" class="view-more-btn-removed" data-vin="${item.vehicle.vin}" data-id="${item.vehicle_id}" title="View More">ViewMore</a>
                            ${item.vehicle.deleted_at == null ? `<a href="${viewMoreUrl}" class="view-more-btn" data-vin="${item.vehicle.vin}" data-id="${item.vehicle_id}" title="View Current Record">CurrentRecord</a>` : ''} ${item.vehicle.vin} details updated as follows
                        </th>
                    </tr>
                    <tr style="border-width: 1;">
                        <th style="padding-top:5px;padding-bottom:5px;padding-left:5px; font-size:12px!important;">Field</th>
                        <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Type</th>
                        <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">Old Value</th>
                        <th style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">New Value</th>
                    </tr>
                `;

                // Sort the record_histories by field name in ascending order
                const sortedDetails = item.record_histories.sort((a, b) => a.field.localeCompare(b.field));

                // Loop through each sorted record_history and create table rows
                sortedDetails.forEach(detail => {
                    updatedVehiclesHtml += `
                        <tr style="border:1px solid #e9e9ef;">
                            <td style="padding-top:5px;padding-bottom:5px;padding-left:5px; font-size:12px!important;">${detail.field || ''}</td>
                            <td style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">${detail.type || ''}</td>
                            <td style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">${detail.old_value || ''}</td>
                            <td style="padding-top:5px;padding-bottom:5px; font-size:12px!important;">${detail.new_value || ''}</td>
                        </tr>
                    `;
                });
            });

            updatedVehiclesHtml += `
                    </tbody>
                </table>
            `;
        }

        const commentHtml = `
            <div class="comment mt-2" id="comment-${id}" data-comment-id="${id}" data-parent-id="${parent_id}">
                <div class="row">
                    <div class="col-xxl-1 col-lg-1 col-md-1" style="width:3.33333%;">
                        <img class="rounded-circle header-profile-user" src="http://127.0.0.1:8000/images/users/avatar-1.jpg" alt="Header Avatar" style="float: left;">
                    </div>
                    <div class="col-xxl-11 col-lg-11 col-md-11">
                        <div class="comment-text" style="font-size:12px;">
                            <span class="comment-short">${text.split('\n')[0]}</span>
                            <span class="comment-full" style="display: none;">${text}</span>
                            <a href="#" class="read-more" onclick="toggleReadMore(${id}); return false;">Read more</a>
                        </div>
                        <div class="comment-details" style="display: none;">
                            ${historiesHtml}
                            ${newVehiclesHtml}
                            ${removedVehiclesHtml}
                            ${updatedVehiclesHtml}
                            <div class="d-flex flex-wrap">${filePreviewsHtml}</div>
                        </div>
                        <button class="btn btn-secondary btn-sm reply-button" onclick="showReplyForm(${id})" title="Reply">
                            <i class="fa fa-reply" aria-hidden="true"></i>
                        </button>
                        <span style="color:gray; font-size:12px;">
                            ${commentData.user ? commentData.user.name : 'System Generated'}
                        </span>
                        <span style="color:gray; font-size:12px;"> - ${formattedDateTime}</span>
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

        // Add event listeners to the "View More" buttons vehicles
        document.querySelectorAll('.view-more-btn').forEach(button => {
            button.addEventListener('click', function() {
                const vin = this.getAttribute('data-vin');
                const targetRow = document.querySelector(`.first-row td[data-vin="${vin}"]`);

                if (targetRow) {
                    targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
       // Add event listeners to the "View More" buttons for removed vehicles
        document.querySelectorAll('.view-more-btn-removed').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from WO Data History tab
                const woDataHistoryTabLink = document.querySelector('a[href="#wo_data_history"]');
                if (woDataHistoryTabLink) {
                    woDataHistoryTabLink.classList.remove('active');
                }

                // Add active class to WO Vehicle Data History tab
                const woVehicleDataHistoryTabLink = document.querySelector('a[href="#wo_vehicle_data_history"]');
                if (woVehicleDataHistoryTabLink) {
                    woVehicleDataHistoryTabLink.classList.add('active');
                }

                // Add show active classes for the id="wo_vehicle_data_history" tab content
                const woVehicleDataHistoryTab = document.getElementById('wo_vehicle_data_history');
                if (woVehicleDataHistoryTab) {
                    woVehicleDataHistoryTab.classList.add('show', 'active');
                }
                const woDataHistoryTab = document.getElementById('wo_data_history');
                if (woDataHistoryTab) {
                    woDataHistoryTab.classList.remove('show', 'active');
                }            
                // Scroll to the target row in the table
                const id = this.getAttribute('data-id');
                const targetRow = document.querySelector(`#myVehAddonTable .vehicle-row[data-id="${id}"]`);

                if (targetRow) {
                    targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        });
    }

    function toggleReadMore(id) {
        const comment = $(`#comment-${id}`);
        const readMoreLink = comment.find('.read-more');

        comment.find('.comment-short').toggle();
        comment.find('.comment-full').toggle();
        comment.find('.comment-details').toggle();

        if (readMoreLink.text() === 'Read more') {
            readMoreLink.text('Read less');
        } else {
            readMoreLink.text('Read more');
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