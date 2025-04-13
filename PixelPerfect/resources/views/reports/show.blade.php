@extends('layouts.master')

@section('content')
    <style>
        .bg-orange {
            background-color: #ff8500 !important;
        }
    </style>
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">{{ $report->title }}</h1>
                <p class="text-muted">Report details and defect analysis</p>
            </div>
            <div class="d-flex gap-2">
                @if (Auth::user()->role->name == 'Organization')
                    <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning text-white">
                        <i class="fas fa-edit me-1"></i> Edit Report
                    </a>
                @endif
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="moreActions" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="fas fa-file-pdf me-2"></i> PDF & Share
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreActions">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#pdfPreviewModal"><i class="fas fa-eye me-2"></i> Preview PDF</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                data-bs-target="#pdfExportModal"><i class="fas fa-file-pdf me-2"></i> Export PDF</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#shareModal"><i
                                    class="fas fa-share-alt me-2"></i> Share Report</a></li>
                    </ul>
                </div>
                @if (Auth::user()->role->name == 'Organization')
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                @endifF
            </div>
        </div>

        <!-- Share Modal -->
        <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shareModalLabel">{{ __('Share Report') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Active Invitations Tab (if any) -->
                        @php
                            $activeInvitations = $report
                                ->invitations()
                                ->where('expires_at', '>', now())
                                ->where('is_used', false)
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp

                        <ul class="nav nav-tabs mb-3" id="shareTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="new-share-tab" data-bs-toggle="tab"
                                    data-bs-target="#new-share" type="button" role="tab" aria-controls="new-share"
                                    aria-selected="true">{{ __('New Invitation') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="active-shares-tab" data-bs-toggle="tab"
                                    data-bs-target="#active-shares" type="button" role="tab"
                                    aria-controls="active-shares" aria-selected="false">
                                    {{ __('Active Links') }}
                                    <span class="badge bg-primary">{{ $activeInvitations->count() }}</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content" id="shareTabContent">
                            <!-- New Share Tab -->
                            <div class="tab-pane fade show active" id="new-share" role="tabpanel"
                                aria-labelledby="new-share-tab">
                                <form action="{{ route('reports.share', $report) }}" method="POST" id="shareForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            placeholder="{{ __('Enter recipient\'s email') }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="expires_days"
                                            class="form-label">{{ __('Access Expires After') }}</label>
                                        <select class="form-select" id="expires_days" name="expires_days">
                                            <option value="1">{{ __('1 day') }}</option>
                                            <option value="3">{{ __('3 days') }}</option>
                                            <option value="7" selected>{{ __('1 week') }}</option>
                                            <option value="14">{{ __('2 weeks') }}</option>
                                            <option value="30">{{ __('30 days') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        {{ __('An email with a secure link will be sent to this address.') }}
                                    </div>

                                    <div class="mt-3 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary me-2"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button type="submit" class="btn btn-primary" id="sendInvitationBtn">
                                            <i class="fas fa-paper-plane me-1"></i> {{ __('Send Invitation') }}
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Active Shares Tab -->
                            <div class="tab-pane fade" id="active-shares" role="tabpanel"
                                aria-labelledby="active-shares-tab">
                                @if ($activeInvitations->count() > 0)
                                    <div class="list-group">
                                        @foreach ($activeInvitations as $invitation)
                                            <div class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">{{ $invitation->email }}</h6>
                                                    <form action="{{ route('reports.shares.cancel', $invitation) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('{{ __('Are you sure you want to cancel this invitation?') }}')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <p class="mb-1 small text-muted">
                                                    <i class="fas fa-clock me-1"></i> {{ __('Expires') }}:
                                                    {{ $invitation->expires_at->diffForHumans() }}
                                                    <br>
                                                    <i class="fas fa-eye me-1"></i> {{ __('Views') }}:
                                                    {{ $invitation->view_count }}
                                                </p>
                                                <div class="mt-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary copy-link-btn"
                                                        data-link="{{ route('reports.shared', $invitation->token) }}">
                                                        <i class="fas fa-copy me-1"></i> {{ __('Copy Link') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('reports.invitations', $report) }}"
                                            class="btn btn-link">{{ __('View All Invitations') }}</a>
                                    </div>
                                @else
                                    <div class="text-center p-4">
                                        <i class="fas fa-share-alt fa-2x text-muted mb-3"></i>
                                        <p>{{ __('No active invitations for this report.') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this report? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('reports.destroy', $report) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete Report</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF Export Modal -->
        <div class="modal fade" id="pdfExportModal" tabindex="-1" aria-labelledby="pdfExportModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfExportModalLabel">{{ __('Export PDF Report') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('reports.export-pdf', $report) }}" method="GET">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="language" class="form-label">{{ __('Report Language') }}</label>
                                <select id="language" name="language" class="form-select">
                                    <option value="en" {{ $report->language == 'en' ? 'selected' : '' }}>
                                        {{ __('English') }}</option>
                                    <option value="fr" {{ $report->language == 'fr' ? 'selected' : '' }}>
                                        {{ __('French') }}</option>
                                    <option value="de" {{ $report->language == 'de' ? 'selected' : '' }}>
                                        {{ __('German') }}</option>
                                </select>
                                <div class="form-text">{{ __('Select the language for the PDF report.') }}</div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="include_comments"
                                    name="include_comments" value="1" checked>
                                <label class="form-check-label"
                                    for="include_comments">{{ __('Include Comments') }}</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Export PDF') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- PDF Preview Modal -->
        <div class="modal fade" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pdfPreviewModalLabel">{{ __('Preview PDF Report') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('reports.preview-pdf', $report) }}" method="GET" target="_blank">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="preview_language" class="form-label">{{ __('Report Language') }}</label>
                                <select id="preview_language" name="language" class="form-select">
                                    <option value="en" {{ $report->language == 'en' ? 'selected' : '' }}>
                                        {{ __('English') }}</option>
                                    <option value="fr" {{ $report->language == 'fr' ? 'selected' : '' }}>
                                        {{ __('French') }}</option>
                                    <option value="de" {{ $report->language == 'de' ? 'selected' : '' }}>
                                        {{ __('German') }}</option>
                                </select>
                                <div class="form-text">{{ __('Select the language for the PDF preview.') }}</div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="preview_include_comments"
                                    name="include_comments" value="1" checked>
                                <label class="form-check-label"
                                    for="preview_include_comments">{{ __('Include Comments') }}</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Preview PDF') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Report Info Section -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Report Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-7">
                                <h6 class="fw-bold mb-3">Description</h6>
                                <p>{{ $report->description }}</p>

                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2">Created By</h6>
                                        <p class="mb-3">{{ $report->creator->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2">Organization</h6>
                                        <p class="mb-3">{{ $report->organization->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2">Created On</h6>
                                        <p class="mb-3">{{ $report->created_at->format('M d, Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2">Language</h6>
                                        <p class="mb-3">
                                            @if ($report->language == 'en')
                                                <i class="flag-icon flag-icon-us me-1"></i> English
                                            @elseif($report->language == 'fr')
                                                <i class="flag-icon flag-icon-fr me-1"></i> French
                                            @elseif($report->language == 'de')
                                                <i class="flag-icon flag-icon-de me-1"></i> German
                                            @else
                                                {{ $report->language }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 border-start">
                                <h6 class="fw-bold mb-3">Report Statistics</h6>
                                <div class="row">
                                    <div class="col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-primary-light text-primary rounded-circle p-2 me-3">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted">Defects</div>
                                                <div class="fw-bold">{{ $report->reportDefects->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-info-light text-info rounded-circle p-2 me-3">
                                                <i class="fas fa-images"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted">Images</div>
                                                <div class="fw-bold">{{ $report->reportImages->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-success-light text-success rounded-circle p-2 me-3">
                                                <i class="fas fa-file-pdf"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted">PDF Exports</div>
                                                <div class="fw-bold">{{ $report->pdf_export_count }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-warning-light text-warning rounded-circle p-2 me-3">
                                                <i class="fas fa-comments"></i>
                                            </div>
                                            <div>
                                                <div class="small text-muted">Comments</div>
                                                <div class="fw-bold">{{ $report->reportComments->count() }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-3">
                                    <!-- Replace the direct PDF generation link with a modal trigger button -->
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#pdfExportModal">
                                        <i class="fas fa-file-pdf me-1"></i> Generate PDF Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Defects Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-primary"></i>Defects</h5>
                        @if (auth()->user()->can('update', $report))
                            <a href="{{ route('reports.edit', $report) }}#defects"
                                class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus me-1"></i> Add Defect
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @forelse($report->reportDefects as $index => $defect)
                            <div class="defect-item {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex">
                                        <div class="defect-number rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3"
                                            style="width: 32px; height: 32px;">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $defect->defectType->name }}</h5>
                                            <span
                                                class="badge
                                            {{ $defect->severity == 'low'
                                                ? 'bg-success'
                                                : ($defect->severity == 'medium'
                                                    ? 'bg-warning text-white'
                                                    : ($defect->severity == 'high'
                                                        ? 'bg-orange text-white'
                                                        : 'bg-danger')) }}">
                                                {{ ucfirst($defect->severity) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('reports.edit', $report) }}#defect-{{ $index }}"><i
                                                        class="fas fa-edit me-2"></i> Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteDefectModal{{ $defect->id }}"><i
                                                        class="fas fa-trash me-2"></i> Delete</a></li>
                                        </ul>
                                    </div>

                                    <!-- Delete Defect Modal -->
                                    <div class="modal fade" id="deleteDefectModal{{ $defect->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Defect</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete this defect? This action cannot be
                                                        undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form
                                                        action="{{ route('reports.defects.destroy', [$report, $defect]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <p>{{ $defect->description }}</p>
                                </div>

                                @if ($defect->coordinates)
                                    <div class="mt-3 bg-light p-3 rounded">
                                        <h6 class="fw-bold"><i
                                                class="fas fa-map-marker-alt me-2 text-primary"></i>Location</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <span class="text-muted">Latitude:</span>
                                                <span
                                                    class="fw-bold">{{ $defect->coordinates['latitude'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="text-muted">Longitude:</span>
                                                <span
                                                    class="fw-bold">{{ $defect->coordinates['longitude'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-exclamation-circle fa-3x text-muted"></i>
                                </div>
                                <h5>No defects recorded</h5>
                                <p class="text-muted">No defects have been added to this report yet.</p>

                                @if (auth()->user()->can('update', $report))
                                    <a href="{{ route('reports.edit', $report) }}#defects" class="btn btn-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Add First Defect
                                    </a>
                                @endif
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Images Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-images me-2 text-primary"></i>Images</h5>
                        @if (auth()->user()->can('update', $report))
                            <a href="{{ route('reports.edit', $report) }}#images" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-upload me-1"></i> Add Images
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($report->reportImages->count() > 0)
                            <div class="report-image-gallery">
                                <div class="row g-2">
                                    @foreach ($report->reportImages as $image)
                                        <div class="col-6">
                                            <div class="image-card position-relative">
                                                <a href="{{ asset('storage/' . $image->file_path) }}"
                                                    data-lightbox="report-images"
                                                    data-title="{{ $image->caption ?? $report->title }}">
                                                    <img src="{{ asset('storage/' . $image->file_path) }}"
                                                        alt="Report Image" class="img-fluid rounded">
                                                </a>
                                                @if (auth()->user()->can('update', $report))
                                                    <div class="image-actions position-absolute top-0 end-0 p-2">
                                                        <form
                                                            action="{{ route('reports.images.destroy', [$report, $image]) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-sm btn-danger rounded-circle"
                                                                onclick="return confirm('Are you sure you want to delete this image?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                                @if ($image->caption)
                                                    <div
                                                        class="image-caption small bg-dark bg-opacity-50 text-white position-absolute bottom-0 start-0 end-0 p-1 text-center">
                                                        {{ $image->caption }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="mb-3">
                                    <i class="fas fa-images fa-3x text-muted"></i>
                                </div>
                                <h5>No images uploaded</h5>
                                <p class="text-muted">No images have been added to this report yet.</p>

                                @if (auth()->user()->can('update', $report))
                                    <a href="{{ route('reports.edit', $report) }}#images" class="btn btn-primary mt-2">
                                        <i class="fas fa-upload me-1"></i> Upload Images
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-comments me-2 text-primary"></i>Comments</h5>
                    </div>
                    <div class="card-body">
                        <div class="comments-container">
                            @forelse($report->reportComments as $comment)
                                <div class="comment-item d-flex mb-3">
                                    <div class="avatar rounded-circle bg-primary text-white flex-shrink-0 me-3 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                    <div class="comment-content flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $comment->user->name }}</h6>
                                                <span
                                                    class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if ($comment->include_in_pdf)
                                                <span class="badge bg-success">PDF</span>
                                            @endif
                                        </div>
                                        <p class="mb-0">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-3">
                                    <p class="text-muted">No comments yet. Be the first to add one!</p>
                                </div>
                            @endforelse
                        </div>

                        <hr class="my-4">

                        <!-- Add Comment Form -->
                        <form action="{{ route('reports.comments.store', $report) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="content" class="form-label fw-bold">Add Comment</label>
                                <textarea id="content" name="content" rows="3" class="form-control" placeholder="Type your comment here..."
                                    required></textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="include_in_pdf"
                                        id="include_in_pdf" value="1" checked>
                                    <label class="form-check-label" for="include_in_pdf">
                                        Include in PDF export
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Add Comment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyShareLink() {
            const shareLink = document.getElementById('shareLink');
            shareLink.select();
            document.execCommand('copy');

            // Show tooltip or notification
            alert('Link copied to clipboard!');
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle share form submission
            const shareForm = document.getElementById('shareForm');
            const sendInvitationBtn = document.getElementById('sendInvitationBtn');

            if (shareForm) {
                shareForm.addEventListener('submit', function() {
                    sendInvitationBtn.disabled = true;
                    sendInvitationBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __('Sending...') }}';
                });
            }

            // Handle copy link buttons
            const copyBtns = document.querySelectorAll('.copy-link-btn');
            copyBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const link = this.getAttribute('data-link');
                    navigator.clipboard.writeText(link).then(() => {
                        // Show copied feedback
                        const originalText = this.innerHTML;
                        this.innerHTML =
                            '<i class="fas fa-check me-1"></i> {{ __('Copied!') }}';
                        setTimeout(() => {
                            this.innerHTML = originalText;
                        }, 2000);
                    });
                });
            });
        });
    </script>
@endsection
