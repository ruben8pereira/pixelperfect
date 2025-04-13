@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="alert alert-info border-left-info">
        <div class="d-flex align-items-center">
            <div class="mr-3">
                <i class="fas fa-clock fa-2x text-info"></i>
            </div>
            <div>
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Shared Report') }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ __('This access expires on') }} {{ $invitation->expires_at->format('M d, Y h:i A') }}</div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $report->title }}</h1>
            <p class="text-muted">{{ __('Report details and defect analysis') }}</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Only show PDF export option -->
            <a href="{{ route('reports.export-pdf', ['report' => $report, 'language' => $report->language]) }}" class="btn btn-success">
                <i class="fas fa-file-pdf me-1"></i> {{ __('Export PDF') }}
            </a>
        </div>
    </div>

    <!-- Report Info Section -->
    <div class="row">
        <!-- Report Info Section -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>{{ __('Report Details') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3">{{ __('Description') }}</h6>
                            <p>{{ $report->description }}</p>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2">{{ __('Created By') }}</h6>
                                    <p class="mb-3">{{ $report->creator->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2">{{ __('Organization') }}</h6>
                                    <p class="mb-3">{{ $report->organization->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2">{{ __('Created On') }}</h6>
                                    <p class="mb-3">{{ $report->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="fw-bold mb-2">{{ __('Language') }}</h6>
                                    <p class="mb-3">
                                        @if($report->language == 'en')
                                            <i class="flag-icon flag-icon-us me-1"></i> {{ __('English') }}
                                        @elseif($report->language == 'fr')
                                            <i class="flag-icon flag-icon-fr me-1"></i> {{ __('French') }}
                                        @elseif($report->language == 'de')
                                            <i class="flag-icon flag-icon-de me-1"></i> {{ __('German') }}
                                        @else
                                            {{ $report->language }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 border-start">
                            <h6 class="fw-bold mb-3">{{ __('Report Statistics') }}</h6>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon bg-primary-light text-primary rounded-circle p-2 me-3">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">{{ __('Defects') }}</div>
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
                                            <div class="small text-muted">{{ __('Images') }}</div>
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
                                            <div class="small text-muted">{{ __('PDF Exports') }}</div>
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
                                            <div class="small text-muted">{{ __('Comments') }}</div>
                                            <div class="fw-bold">{{ $report->reportComments->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Defects Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-primary"></i>{{ __('Defects') }}</h5>
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
                            </div>

                            <div class="mt-3">
                                <p>{{ $defect->description }}</p>
                            </div>

                            @if($defect->coordinates)
                                <div class="mt-3 bg-light p-3 rounded">
                                    <h6 class="fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>{{ __('Location') }}</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="text-muted">{{ __('Latitude') }}:</span>
                                            <span class="fw-bold">{{ $defect->coordinates['latitude'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="text-muted">{{ __('Longitude') }}:</span>
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
                            <h5>{{ __('No defects recorded') }}</h5>
                            <p class="text-muted">{{ __('No defects have been added to this report yet.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Images Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-images me-2 text-primary"></i>{{ __('Images') }}</h5>
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
                            <h5>{{ __('No images uploaded') }}</h5>
                            <p class="text-muted">{{ __('No images have been added to this report yet.') }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-comments me-2 text-primary"></i>{{ __('Comments') }}</h5>
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
                                            <span class="badge bg-success">{{ __('PDF') }}</span>
                                        @endif
                                    </div>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <p class="text-muted">{{ __('No comments yet.') }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Shared By Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-share-alt me-2 text-primary"></i>{{ __('Shared By') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar rounded-circle bg-primary text-white flex-shrink-0 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            {{ substr($invitation->inviter->name, 0, 1) }}
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $invitation->inviter->name }}</h6>
                            <p class="text-muted mb-0">{{ $invitation->inviter->email }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="text-center mt-3">
                        <p class="mb-2">{{ __('Need a PixelPerfect account?') }}</p>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i> {{ __('Create Account') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
