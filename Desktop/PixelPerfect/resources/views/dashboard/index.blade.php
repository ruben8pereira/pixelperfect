<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <h2 class="fw-semibold text-dark">Dashboard</h2>

        <div class="row g-4 mt-4">
            <!-- Stats Cards -->
            @if(auth()->user()->role && auth()->user()->role->name == 'Administrator')
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-primary text-white p-3">
                        <h5>Organizations</h5>
                        <p class="fs-3 fw-bold">{{ $organizations ?? 0 }}</p>
                    </div>
                </div>
            @endif

            @if(auth()->user()->role && (auth()->user()->role->name == 'Administrator' || auth()->user()->role->name == 'Organization'))
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-success text-white p-3">
                        <h5>Users</h5>
                        <p class="fs-3 fw-bold">{{ $users ?? 0 }}</p>
                    </div>
                </div>
            @endif

            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark p-3">
                    <h5>Reports</h5>
                    <p class="fs-3 fw-bold">{{ $reports ?? 0 }}</p>
                </div>
            </div>

            @if(auth()->user()->role && auth()->user()->role->name == 'Organization')
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm bg-info text-white p-3">
                        <h5>Organization</h5>
                        <p class="fs-5 fw-semibold">
                            {{ auth()->user()->organization ? auth()->user()->organization->name : 'Not assigned' }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Recent Reports Section -->
        <div class="mt-5">
            <h4 class="mb-3">Recent Reports</h4>

            @if(isset($recent_reports) && count($recent_reports) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Title</th>
                                <th>Created</th>
                                @if(auth()->user()->role && auth()->user()->role->name == 'Administrator')
                                    <th>Organization</th>
                                @endif
                                <th>Defects</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_reports as $report)
                                <tr>
                                    <td>{{ $report->title }}</td>
                                    <td>{{ $report->created_at->format('M d, Y') }}</td>
                                    @if(auth()->user()->role && auth()->user()->role->name == 'Administrator')
                                        <td>
                                            {{ $report->organization ? $report->organization->name : 'None' }}
                                        </td>
                                    @endif
                                    <td>{{ $report->reportDefects->count() }}</td>
                                    <td>
                                        <a href="{{ route('reports.show', $report) }}" class="btn btn-sm btn-primary">View</a>
                                        <a href="{{ route('reports.edit', $report) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="{{ route('reports.export-pdf', $report) }}" class="btn btn-sm btn-success">PDF</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-secondary">
                    <p>No reports found.
                        @if(auth()->user()->role && auth()->user()->role->name != 'User')
                            <a href="{{ route('reports.create') }}" class="text-primary">Create your first report</a>.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        @if(auth()->user()->role && auth()->user()->role->name != 'Guest')
            <div class="mt-4">
                <a href="{{ route('reports.create') }}" class="btn btn-lg btn-primary">New Report</a>
            </div>
        @endif
    </div>

</body>
</html>
