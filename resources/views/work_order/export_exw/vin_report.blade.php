@extends('layouts.table')
@section('content')
<style>
	#vin-report-table th, #vin-report-table td {
		white-space: nowrap;
		font-size: 12px;
		vertical-align: middle;
	}
	/* Bootstrap paints row stripes/hover as an inset box-shadow over the cell, so override that too. */
	#vin-report-table tbody tr.not-matching {
		--bs-table-accent-bg: transparent;
	}
	#vin-report-table tbody tr.not-matching > td {
		background-color: #fdeaea !important;
		box-shadow: none !important;
	}
	#vin-report-table tbody tr.not-matching:hover > td {
		background-color: #fadcdc !important;
	}
	.vin-filters label {
		font-size: 12px;
		font-weight: 600;
		margin-bottom: 2px;
	}
	.vin-filters .form-control {
		height: 32px;
		font-size: 12px;
	}
	.vin-filters .select2-container {
		width: 100% !important;
	}
	#vin-report-loader {
		position: fixed;
		inset: 0;
		background: rgba(0, 0, 0, 0.45);
		z-index: 9999;
		display: none;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		gap: 12px;
		color: #fff;
		font-size: 14px;
	}
	#vin-report-loader.active {
		display: flex;
	}
	#vin-report-loader .loader {
		width: 50px;
		height: 50px;
		border: 5px solid #f3f3f3;
		border-top: 5px solid #3498db;
		border-radius: 50%;
		animation: vin-report-spin 1s linear infinite;
	}
	@keyframes vin-report-spin {
		0% { transform: rotate(0deg); }
		100% { transform: rotate(360deg); }
	}
</style>
<div id="vin-report-loader" class="active" role="status" aria-live="polite">
	<div class="loader"></div>
	<div id="vin-report-loader-text">Loading...</div>
</div>
<div class="card-header">
	<h4 class="card-title d-inline-block">Vin Report - Work Order Info</h4>
	<a id="vin-report-export" href="{{ route('work-order.vin-report.export') }}" class="btn btn-sm btn-success float-end">
		<i class="fa fa-file-excel" aria-hidden="true"></i> Export Excel
	</a>
	<button type="button" id="vin-report-refresh" class="btn btn-sm btn-info float-end me-1" title="Reload the latest data from Matrix">
		<i class="fa fa-sync" aria-hidden="true"></i> Refresh Data
	</button>
	<small id="vin-report-built-at" class="text-muted float-end me-2 mt-1"></small>
</div>
<div class="card-body">
	<form id="vin-report-filters" class="vin-filters" onsubmit="return false;">
		<div class="row g-2 mb-2">
			<div class="col-xxl-3 col-lg-4 col-md-6">
				<label for="vin_search">Search VIN / WO / SO</label>
				<input type="text" class="form-control" id="vin_search" name="vin_search" placeholder="Full or partial VIN, WO or SO number" autocomplete="off">
			</div>
			<div class="col-xxl-2 col-lg-2 col-md-3 col-6">
				<label for="wo_date_from">WO Date From</label>
				<input type="date" class="form-control" id="wo_date_from" name="wo_date_from">
			</div>
			<div class="col-xxl-2 col-lg-2 col-md-3 col-6">
				<label for="wo_date_to">WO Date To</label>
				<input type="date" class="form-control" id="wo_date_to" name="wo_date_to">
			</div>
			<div class="col-xxl-2 col-lg-2 col-md-3 col-6">
				<label for="grn_date_from">GRN Date From</label>
				<input type="date" class="form-control" id="grn_date_from" name="grn_date_from">
			</div>
			<div class="col-xxl-2 col-lg-2 col-md-3 col-6">
				<label for="grn_date_to">GRN Date To</label>
				<input type="date" class="form-control" id="grn_date_to" name="grn_date_to">
			</div>
		</div>
		<div class="row g-2 mb-3">
			@php
				$listFilters = [
					'destination_match' => 'Destination Match Status',
					'wo_status' => 'WO Status',
					'delivery_status' => 'Delivery Status',
					'incoterm' => 'Incoterms',
					'pol' => 'POL',
					'pod' => 'POD',
					'actual_destination' => 'Actual Destination',
					'sales_person' => 'Salesperson',
					'brand' => 'Brand',
					'model_line' => 'Model Line',
					'variant' => 'Variant',
					'model_year' => 'Model Year',
				];
			@endphp
			@foreach($listFilters as $key => $label)
				<div class="col-xxl-2 col-lg-3 col-md-4 col-6">
					<label for="filter_{{ $key }}">{{ $label }}</label>
					<select class="form-control vin-report-select" id="filter_{{ $key }}" name="{{ $key }}[]" multiple data-placeholder="All">
						@foreach($filterOptions[$key] ?? [] as $option)
							<option value="{{ $option }}">{{ $option }}</option>
						@endforeach
					</select>
				</div>
			@endforeach
			<div class="col-xxl-2 col-lg-3 col-md-4 col-6 d-flex align-items-end gap-1">
				<button type="button" id="vin-report-apply" class="btn btn-sm btn-primary w-50">Apply</button>
				<button type="button" id="vin-report-reset" class="btn btn-sm btn-secondary w-50">Reset</button>
			</div>
		</div>
	</form>

	<div class="table-responsive">
		<table id="vin-report-table" class="table table-striped table-bordered w-100">
			<thead class="bg-soft-secondary">
				<tr>
					@foreach($columns as $key => $heading)
						<th>{{ $heading }}</th>
					@endforeach
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
	const woShowUrl = @json(route('work-order.show', '__ID__'));
	const vinHistoryUrl = @json(route('wo-vehicles.data-history', '__ID__'));
	const exportUrl = @json(route('work-order.vin-report.export'));
	const columnKeys = @json(array_keys($columns));
	const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

	// Full-screen loader; counts overlapping requests so it only hides when all have finished.
	let pendingLoads = 1; // initial table load
	const showLoader = (text = 'Loading...') => {
		pendingLoads++;
		$('#vin-report-loader-text').text(text);
		$('#vin-report-loader').addClass('active');
	};
	const hideLoader = () => {
		pendingLoads = Math.max(0, pendingLoads - 1);
		if (pendingLoads === 0) {
			$('#vin-report-loader').removeClass('active');
		}
	};

	$('.vin-report-select').each(function () {
		$(this).select2({ allowClear: true, placeholder: $(this).data('placeholder'), width: '100%' });
	});

	// Values arrive HTML-escaped from the server (datatables escape config).
	const formatDate = (value) => {
		if (!value) return '';
		const parts = String(value).substring(0, 10).split('-');
		return parts.length === 3 ? parts[2] + ' ' + months[parseInt(parts[1], 10) - 1] + ' ' + parts[0] : value;
	};
	const statusBadge = (value, classes) => value ? '<span class="badge ' + (classes[value] || 'badge-soft-secondary') + '">' + value + '</span>' : '';

	const renderers = {
		vin: (data, type, row) => {
			if (!data) return '';
			let html = '<a href="' + vinHistoryUrl.replace('__ID__', row.wo_vehicle_id) + '" title="VIN change history">' + data + '</a>';
			if (parseInt(row.active_wo_count, 10) > 1) {
				html += ' <span class="badge badge-soft-danger" title="This VIN is linked to ' + row.active_wo_count + ' active work orders">'
					+ row.active_wo_count + ' active WOs</span>';
			}
			return html;
		},
		wo_number: (data, type, row) => data ? '<a href="' + woShowUrl.replace('__ID__', row.work_order_id) + '">' + data + '</a>' : '',
		wo_status: (data) => statusBadge(data, {
			'Active': 'badge-soft-success', 'On Hold': 'badge-soft-warning', 'Cancelled': 'badge-soft-danger',
			'Succeeded': 'badge-soft-primary', 'Partially Delivered': 'badge-soft-info'
		}),
		delivery_status: (data) => statusBadge(data, {
			'Ready': 'badge-soft-info', 'On Hold': 'badge-soft-warning', 'Delivered': 'badge-soft-success',
			'Delivered With Docs Hold': 'badge-soft-primary'
		}),
		destination_match: (data) => statusBadge(data, {
			'Matching': 'badge-soft-success', 'Not Matching': 'badge-soft-danger', 'Not Available': 'badge-soft-secondary'
		}),
		wo_date: (data, type) => type === 'display' ? formatDate(data) : data,
		grn_date: (data, type) => type === 'display' ? formatDate(data) : data,
	};

	const filterParams = () => {
		const params = {};
		// Multi-selects are sent as key[]=value (jQuery adds the brackets for arrays).
		$('#vin-report-filters').serializeArray().forEach(({ name, value }) => {
			if (value === '') return;
			if (name.endsWith('[]')) {
				const key = name.slice(0, -2);
				(params[key] = params[key] || []).push(value);
			} else {
				params[name] = value;
			}
		});
		return params;
	};

	let initialLoad = true;
	let forceRefresh = false;
	$('#vin-report-table')
		.on('preXhr.dt', function () {
			if (initialLoad) {
				initialLoad = false; // already counted in pendingLoads
				return;
			}
			showLoader();
		})
		.on('xhr.dt', function (e, settings, json) {
			if (json && json.built_at) {
				$('#vin-report-built-at').text('Data as of ' + json.built_at);
			}
			hideLoader(); // fires on success and on error
		});

	// Show a readable message instead of DataTables' default alert (e.g. when the session has expired).
	$.fn.dataTable.ext.errMode = 'none';
	$('#vin-report-table').on('error.dt', function (e, settings, techNote, message) {
		const status = settings.jqXHR ? settings.jqXHR.status : 0;
		const responseType = settings.jqXHR ? (settings.jqXHR.getResponseHeader('Content-Type') || '') : '';
		if (status === 401 || status === 419 || responseType.indexOf('text/html') !== -1) {
			alert('Your session has expired. Please refresh the page and log in again.');
		} else if (status === 403) {
			alert('You do not have permission to view this report.');
		} else if (status !== 0) {
			alert('Could not load the VIN report. Please try again.');
		}
		console.error('VIN report:', message);
	});

	const table = $('#vin-report-table').DataTable({
		processing: false,
		serverSide: true,
		searching: false,
		scrollX: true,
		pageLength: 25,
		lengthMenu: [[25, 50, 100, 250], [25, 50, 100, 250]],
		order: [[columnKeys.indexOf('wo_date'), 'desc']],
		ajax: {
			url: @json(route('work-order.vin-report')),
			data: (d) => {
				Object.assign(d, filterParams());
				if (forceRefresh) {
					d.refresh = 1;
					forceRefresh = false;
				}
			},
		},
		columns: columnKeys.map((key) => ({
			data: key,
			name: key,
			defaultContent: '',
			render: renderers[key] || null,
		})),
		createdRow: (row, data) => {
			if (data.destination_match === 'Not Matching') {
				$(row).addClass('not-matching');
			}
		},
	});

	const reload = () => table.ajax.reload();

	// Report data is cached for a few minutes on the server; this rebuilds it immediately.
	$('#vin-report-refresh').on('click', function () {
		forceRefresh = true;
		reload();
	});
	$('#vin-report-apply').on('click', reload);
	$('#vin-report-filters').on('change', 'input[type="date"], select', reload);

	let searchTimer = null;
	$('#vin_search').on('input', function () {
		clearTimeout(searchTimer);
		searchTimer = setTimeout(reload, 400);
	});

	$('#vin-report-reset').on('click', function () {
		$('#vin-report-filters')[0].reset();
		$('.vin-report-select').val(null).trigger('change.select2');
		reload();
	});

	// Download through fetch so the loader stays up until the file has arrived.
	$('#vin-report-export').on('click', function (e) {
		e.preventDefault();
		showLoader('Preparing Excel export...');
		fetch(exportUrl + '?' + $.param(filterParams()), { credentials: 'same-origin' })
			.then((response) => {
				if (response.status === 403) {
					throw new Error('You do not have permission to export this report.');
				}
				if (!response.ok) {
					throw new Error('Export failed (' + response.status + '). Please try again.');
				}
				// A redirect to the login page comes back as HTML instead of the Excel file.
				if (response.redirected || (response.headers.get('Content-Type') || '').indexOf('text/html') !== -1) {
					throw new Error('Your session has expired. Please refresh the page and log in again.');
				}
				const disposition = response.headers.get('Content-Disposition') || '';
				const match = disposition.match(/filename\*?=(?:UTF-8'')?"?([^";]+)"?/i);
				const fileName = match ? decodeURIComponent(match[1]) : 'vin-report.xlsx';
				return response.blob().then((blob) => ({ blob, fileName }));
			})
			.then(({ blob, fileName }) => {
				const url = URL.createObjectURL(blob);
				const link = document.createElement('a');
				link.href = url;
				link.download = fileName;
				document.body.appendChild(link);
				link.click();
				link.remove();
				setTimeout(() => URL.revokeObjectURL(url), 1000);
			})
			.catch((error) => alert(error.message || 'Export failed, please try again.'))
			.finally(hideLoader);
	});
});
</script>
@endpush
