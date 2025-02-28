@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ $report->title }}</h1>
            <p class="text-muted">Rapport TV n° {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.edit', $report) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> {{ __('Edit Report') }}
            </a>
            <a href="{{ route('reports.export-pdf', $report) }}" class="btn btn-success">
                <i class="fas fa-file-pdf me-1"></i> {{ __('Export PDF') }}
            </a>
        </div>
    </div>

    <!-- Report Header Information -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0">{{ __('Tronçon') }} {{ $report->id }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>{{ __('Date inspection') }}:</strong> {{ $report->created_at->format('d.m.Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('Person present') }}:</strong> {{ $report->creator->name }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>{{ __('Organization') }}:</strong> {{ $report->organization->name }}
                    </div>
                    <div class="mb-3">
                        <strong>{{ __('Description') }}:</strong> {{ $report->description }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map of Inspected Network - If available -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-warning text-dark py-2">
            <h5 class="mb-0">{{ __('Plan du réseau inspecté') }}</h5>
        </div>
        <div class="card-body p-0">
            @if($mapImage = $report->reportImages->where('caption', 'Map')->first())
                <img src="{{ asset('storage/' . $mapImage->file_path) }}" class="img-fluid w-100" alt="Network Map">
            @else
                <div class="p-4 text-center">
                    <p class="text-muted">{{ __('No network map available for this report') }}</p>
                </div>
            @endif
        </div>
        <div class="card-footer bg-light">
            <small class="text-muted">Rapport TV n° {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</small>
        </div>
    </div>

    <!-- Observations Section -->
    <div class="mb-4">
        <h4>{{ __('Observations') }}</h4>

        @forelse($report->reportDefects as $index => $defect)
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-secondary text-white">
                    <div class="d-flex justify-content-between">
                        <h5 class="mb-0">{{ __('Observation') }} {{ $index + 1 }}</h5>
                        <span class="badge {{ $defect->severity == 'low' ? 'bg-success' : ($defect->severity == 'medium' ? 'bg-warning text-dark' : ($defect->severity == 'high' ? 'bg-danger' : 'bg-danger')) }}">
                            {{ __('Severity') }}: {{ ucfirst($defect->severity) }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="p-3">
                                <div class="d-flex mb-2">
                                    <div style="width: 120px"><strong>{{ __('Distance') }}:</strong></div>
                                    <div>{{ $defect->coordinates['distance'] ?? '--' }} ml.</div>
                                </div>
                                <div class="d-flex mb-2">
                                    <div style="width: 120px"><strong>{{ __('Compteur') }}:</strong></div>
                                    <div>{{ $defect->coordinates['counter'] ?? '--' }}</div>
                                </div>
                                <div class="d-flex mb-2">
                                    <div style="width: 120px"><strong>{{ __('Niveau d\'eau') }}:</strong></div>
                                    <div>{{ $defect->coordinates['water_level'] ?? '--' }}</div>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <div><strong>{{ __('Constat') }}:</strong></div>
                                    <div>{{ $defect->description }}</div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <div><strong>{{ __('Gravité') }}:</strong></div>
                                    <div>{{ $defect->severity === 'critical' ? '1' : ($defect->severity === 'high' ? '2' : ($defect->severity === 'medium' ? '3' : '4')) }}</div>
                                </div>

                                <hr>

                                <div>
                                    <div><strong>{{ __('Remarque') }}:</strong></div>
                                    <div>{{ $defect->coordinates['comment'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 border-start">
                            @if($defectImage = $report->reportImages->where('defect_id', $defect->id)->first())
                                <img src="{{ asset('storage/' . $defectImage->file_path) }}" class="img-fluid w-100" style="height: 280px; object-fit: contain;" alt="Defect Image">
                            @else
                                <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                                    <p class="text-muted">{{ __('No image available') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-info">
                {{ __('No defects have been recorded for this report.') }}
            </div>
        @endforelse
    </div>

    <!-- Comments Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-3">
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
                        <p class="text-muted">{{ __('No comments yet. Be the first to add one!') }}</p>
                    </div>
                @endforelse
            </div>

            <hr class="my-4">

            <!-- Add Comment Form -->
            <form action="{{ route('reports.comments.store', $report) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="content" class="form-label fw-bold">{{ __('Add Comment') }}</label>
                    <textarea id="content" name="content" rows="3" class="form-control" placeholder="{{ __('Type your comment here...') }}" required></textarea>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="include_in_pdf" id="include_in_pdf" value="1" checked>
                        <label class="form-check-label" for="include_in_pdf">
                            {{ __('Include in PDF export') }}
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> {{ __('Add Comment') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
