@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">{{ __('Report Invitations') }}</h1>
            <p class="text-muted">{{ __('Manage access to') }} "{{ $report->title }}"</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reports.show', $report) }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Report') }}
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newInvitationModal">
                <i class="fas fa-plus me-1"></i> {{ __('New Invitation') }}
            </button>
        </div>
    </div>

    <!-- New Invitation Modal -->
    <div class="modal fade" id="newInvitationModal" tabindex="-1" aria-labelledby="newInvitationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newInvitationModalLabel">{{ __('New Invitation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('reports.share', $report) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="{{ __('Enter recipient\'s email') }}">
                        </div>

                        <div class="mb-3">
                            <label for="expires_days" class="form-label">{{ __('Access Expires After') }}</label>
                            <select class="form-select" id="expires_days" name="expires_days">
                                <option value="1">{{ __('1 day') }}</option>
                                <option value="3">{{ __('3 days') }}</option>
                                <option value="7" selected>{{ __('1 week') }}</option>
                                <option value="14">{{ __('2 weeks') }}</option>
                                <option value="30">{{ __('30 days') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i> {{ __('Send Invitation') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Invitation Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('Total Invitations') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $invitations->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('Active Invitations') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $invitations->where('expires_at', '>', now())->where('is_used', false)->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-link fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Total Views') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $invitations->sum('view_count') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Expired Invitations') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $invitations->where('expires_at', '<', now())->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invitations Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('All Invitations') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="invitationsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Invited By') }}</th>
                            <th>{{ __('Sent Date') }}</th>
                            <th>{{ __('Expiry Date') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Views') }}</th>
                            <th>{{ __('Last Accessed') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invitations as $invitation)
                            <tr>
                                <td>{{ $invitation->email }}</td>
                                <td>{{ $invitation->inviter->name }}</td>
                                <td>{{ $invitation->created_at->format('M d, Y') }}</td>
                                <td>{{ $invitation->expires_at->format('M d, Y') }}</td>
                                <td>
                                    @if($invitation->is_used)
                                        <span class="badge bg-secondary">{{ __('Used') }}</span>
                                    @elseif($invitation->expires_at->isPast())
                                        <span class="badge bg-danger">{{ __('Expired') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @endif
                                </td>
                                <td>{{ $invitation->view_count }}</td>
                                <td>{{ $invitation->last_accessed_at ? $invitation->last_accessed_at->format('M d, Y H:i') : __('Never') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary copy-link-btn" data-link="{{ route('reports.shared', $invitation->token) }}">
                                            <i class="fas fa-copy"></i>
                                        </button>

                                        @if(!$invitation->expires_at->isPast() && !$invitation->is_used)
                                            <form action="{{ route('reports.invitations.cancel', $invitation) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('Are you sure you want to cancel this invitation?') }}')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted mb-0">{{ __('No invitations found for this report.') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Copying Links -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const copyBtns = document.querySelectorAll('.copy-link-btn');
        copyBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const link = this.getAttribute('data-link');
                navigator.clipboard.writeText(link).then(() => {
                    // Show copied feedback
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                    }, 2000);
                });
            });
        });
    });
</script>
@endsection
