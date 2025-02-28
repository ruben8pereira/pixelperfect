@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $report->title }}</h1>
            <p class="text-muted">Report details and defect analysis</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit Report
            </a>
            <a href="{{ route('reports.export-pdf', $report) }}" class="btn btn-success">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="moreActions" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreActions">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#shareModal"><i class="fas fa-share-alt me-2"></i> Share Report</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash me-2"></i> Delete Report</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="shareModalLabel">Share Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Share this report with team members or clients:</p>

                    <div class="mb-3">
                        <label for="shareLink" class="form-label">Share Link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareLink" value="{{ route('reports.shared', $report->share_token) }}" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyShareLink()">Copy</button>
                        </div>
                        <small class="text-muted">Anyone with this link can view the report without logging in.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Report</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="shareEmail" placeholder="Enter email address">
                            <button class="btn btn-primary" type="button">Send</button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                                        @if($report->language == 'en')
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
                                <a href="{{ route('reports.export-pdf', $report) }}" class="btn btn-primary">
                                    <i class="fas fa-file-pdf me-1"></i> Generate PDF Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Defects Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-primary"></i>Defects</h5>
                    @if(auth()->user()->can('update', $report))
                        <a href="{{ route('reports.edit', $report) }}#defects" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus me-1"></i> Add Defect
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @forelse($report->reportDefects as $index => $defect)
                        <div class="defect-item {{ !$loop->last ? 'mb-4 pb-4 border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex">
                                    <div class="defect-number rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h5 class="mb-1">{{ $defect->defectType->name }}</h5>
                                        <span class="badge
                                            {{ $defect->severity == 'low' ? 'bg-success' :
                                            ($defect->severity == 'medium' ? 'bg-warning text-dark' :
                                            ($defect->severity == 'high' ? 'bg-orange text-white' : 'bg-danger')) }}">
                                            {{ ucfirst($defect->severity) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('reports.edit', $report) }}#defect-{{ $index }}"><i class="fas fa-edit me-2"></i> Edit</a></li>
                                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteDefectModal{{ $defect->id }}"><i class="fas fa-trash me-2"></i> Delete</a></li>
                                    </ul>
                                </div>

                                <!-- Delete Defect Modal -->
                                <div class="modal fade" id="deleteDefectModal{{ $defect->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Defect</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete this defect? This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('reports.defects.destroy', [$report, $defect]) }}" method="POST">
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

                            @if($defect->coordinates)
                                <div class="mt-3 bg-light p-3 rounded">
                                    <h6 class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Location</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="text-muted">Latitude:</span>
                                            <span class="fw-bold">{{ $defect->coordinates['latitude'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted">Longitude:</span>
                                            <span class="fw-bold">{{ $defect->coordinates['longitude'] ?? 'N/A' }}</span>
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

                            @if(auth()->user()->can('update', $report))
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
                    @if(auth()->user()->can('update', $report))
                        <a href="{{ route('reports.edit', $report) }}#images" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-upload me-1"></i> Add Images
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if($report->reportImages->count() > 0)
                        <div class="report-image-gallery">
                            <div class="row g-2">
                                @foreach($report->reportImages as $image)
                                    <div class="col-6">
                                        <div class="image-card position-relative">
                                            <a href="{{ asset('storage/' . $image->file_path) }}" data-lightbox="report-images" data-title="{{ $image->caption ?? $report->title }}">
                                                <img src="{{ asset('storage/' . $image->file_path) }}" alt="Report Image" class="img-fluid rounded">
                                            </a>
                                            @if(auth()->user()->can('update', $report))
                                                <div class="image-actions position-absolute top-0 end-0 p-2">
                                                    <form action="{{ route('reports.images.destroy', [$report, $image]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Are you sure you want to delete this image?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                            @if($image->caption)
                                                <div class="image-caption small bg-dark bg-opacity-50 text-white position-absolute bottom-0 start-0 end-0 p-1 text-center">
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

                            @if(auth()->user()->can('update', $report))
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
                                <div class="avatar rounded-circle bg-primary text-white flex-shrink-0 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    {{ substr($comment->user->name, 0, 1) }}
                                </div>
                                <div class="comment-content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $comment->user->name }}</h6>
                                            <span class="text-muted small">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($comment->include_in_pdf)
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
                            <textarea id="content" name="content" rows="3" class="form-control" placeholder="Type your comment here..." required></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="include_in_pdf" id="include_in_pdf" value="1" checked>
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
@endsection
