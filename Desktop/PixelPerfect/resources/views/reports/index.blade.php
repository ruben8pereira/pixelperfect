@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ __('Reports') }}</h1>
            <p class="text-muted">{{ __('Manage your pipe inspection reports') }}</p>
        </div>
        @if(auth()->user()->role && auth()->user()->role->name != 'BasicUser')
        <a href="{{ route('reports.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> {{ __('New Report') }}
        </a>
        @endif
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <!-- Search and Filter Section -->
            <form action="{{ route('reports.index') }}" method="GET" class="mb-4">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="search" class="form-label fw-bold">{{ __('Search') }}</label>
                        <input id="search" type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('Search by title or description...') }}">
                    </div>

                    @if(auth()->user()->role && auth()->user()->role->name == 'Administrator')
                    <div class="col-md-3">
                        <label for="organization" class="form-label fw-bold">{{ __('Organization') }}</label>
                        <select id="organization" name="organization" class="form-select">
                            <option value="">{{ __('All Organizations') }}</option>
                            @foreach(\App\Models\Organization::all() as $organization)
                                <option value="{{ $organization->id }}" {{ request('organization') == $organization->id ? 'selected' : '' }}>
                                    {{ $organization->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-2">
                        <label for="severity" class="form-label fw-bold">{{ __('Severity') }}</label>
                        <select id="severity" name="severity" class="form-select">
                            <option value="">{{ __('All Severities') }}</option>
                            <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>{{ __('Critical') }}</option>
                            <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                            <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                            <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="date_range" class="form-label fw-bold">{{ __('Date Range') }}</label>
                        <select id="date_range" name="date_range" class="form-select">
                            <option value="">{{ __('All Time') }}</option>
                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>{{ __('Today') }}</option>
                            <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>{{ __('This Week') }}</option>
                            <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>{{ __('This Month') }}</option>
                            <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>{{ __('This Year') }}</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Reports Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">{{ __('Report') }}</th>
                            <th scope="col">{{ __('Created') }}</th>
                            @if(auth()->user()->role && auth()->user()->role->name == 'Administrator')
                                <th scope="col">{{ __('Organization') }}</th>
                            @endif
                            <th scope="col" class="text-center">{{ __('Defects') }}</th>
                            <th scope="col" class="text-center">{{ __('Exports') }}</th>
                            <th scope="col" class="text-center">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="report-icon bg-light rounded p-2 me-3">
                                            <i class="fas fa-file-alt fa-lg text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $report->title }}</h6>
                                            <p class="text-muted small mb-0">{{ Str::limit($report->description, 50) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div class="fw-bold">{{ $report->created_at->format('M d, Y') }}</div>
                                        <div class="text-muted">{{ $report->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                @if(auth()->user()->role && auth()->user()->role->name == 'Administrator')
                                    <td>{{ $report->organization->name }}</td>
                                @endif
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $report->reportDefects->count() }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $report->pdf_export_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="{{ __('View Report') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('update', $report)
                                        <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="{{ __('Edit Report') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        <a href="{{ route('reports.export-pdf', $report) }}" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="{{ __('Export PDF') }}">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @can('delete', $report)
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $report->id }}" title="{{ __('Delete Report') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $report->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $report->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $report->id }}">Confirm Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete the report "<strong>{{ $report->title }}</strong>"? This action cannot be undone.
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role->name == 'Administrator' ? '6' : '5' }}" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h5>No reports found</h5>
                                        <p class="text-muted">No reports match your search criteria or you haven't created any reports yet.</p>
                                        <a href="{{ route('reports.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-1"></i> Create Your First Report
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reports->hasPages())
                <div class="d-flex justify-content-end mt-4">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
