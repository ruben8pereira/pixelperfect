<!DOCTYPE html>
<html lang="{{ $report->language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }}</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .logo {
            max-height: 60px;
            max-width: 200px;
        }
        h1 {
            font-size: 24px;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        h2 {
            font-size: 18px;
            margin: 20px 0 10px 0;
            color: #2c3e50;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        h3 {
            font-size: 16px;
            margin: 15px 0 5px 0;
            color: #2c3e50;
        }
        p {
            margin: 0 0 10px 0;
            line-height: 1.4;
        }
        .meta-info {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        .meta-label {
            font-weight: bold;
        }
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 10px;
            margin-bottom: 15px;
        }
        .defect-item {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #eee;
        }
        .defect-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }
        .defect-type {
            font-weight: bold;
        }
        .severity {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .severity-low {
            background-color: #d4edda;
            color: #155724;
        }
        .severity-medium {
            background-color: #fff3cd;
            color: #856404;
        }
        .severity-high {
            background-color: #ffe5d0;
            color: #b52b27;
        }
        .severity-critical {
            background-color: #f8d7da;
            color: #721c24;
        }
        .image-gallery {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 15px;
            margin: 20px 0;
        }
        .image-item {
            page-break-inside: avoid;
        }
        .image-item img {
            max-width: 100%;
            border: 1px solid #ddd;
        }
        .image-caption {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }
        .comment-item {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 11px;
        }
        .comment-header {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            margin-bottom: 5px;
        }
        .comment-author {
            font-weight: bold;
            color: #2c3e50;
        }
        .comment-date {
            color: #6c757d;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
        .footer .page:after {
            content: counter(page);
        }
    </style>
</head>
<body>
    <div class="footer">
        <div>{{ $report->organization->name }} - {{ $report->title }}</div>
        <div class="page">Page </div>
    </div>

    <div class="header">
        @if($report->organization->logo_path)
            <img src="{{ public_path('storage/' . $report->organization->logo_path) }}" alt="Organization Logo" class="logo">
        @endif
        <h1>{{ $report->title }}</h1>
        <div class="meta-info">
            <span class="meta-label">{{ __('Organization') }}:</span> {{ $report->organization->name }}
        </div>
        <div class="meta-info">
            <span class="meta-label">{{ __('Report ID') }}:</span> {{ $report->id }}
            <span style="margin-left: 20px;" class="meta-label">{{ __('Created') }}:</span> {{ $report->created_at->format('M d, Y') }}
            <span style="margin-left: 20px;" class="meta-label">{{ __('By') }}:</span> {{ $report->creator->name }}
        </div>
    </div>

    @if($report->description)
        <div>
            <h2>{{ __('Description') }}</h2>
            <p>{{ $report->description }}</p>
        </div>
    @endif

    @if(count($report->reportDefects) > 0)
        <div>
            <h2>{{ __('Defects') }}</h2>

            @foreach($report->reportDefects as $index => $defect)
                <div class="defect-item">
                    <div class="defect-header">
                        <span class="defect-type">{{ $index + 1 }}. {{ $defect->defectType->name }}</span>
                        <span class="severity severity-{{ $defect->severity }}">{{ ucfirst($defect->severity) }}</span>
                    </div>

                    @if($defect->description)
                        <p>{{ $defect->description }}</p>
                    @endif

                    @if($defect->coordinates)
                        <div class="meta-info">
                            <span class="meta-label">{{ __('Location') }}:</span>
                            {{ __('Lat') }}: {{ $defect->coordinates['latitude'] ?? 'N/A' }},
                            {{ __('Lng') }}: {{ $defect->coordinates['longitude'] ?? 'N/A' }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    @if(count($report->reportImages) > 0)
        <div>
            <h2>{{ __('Images') }}</h2>

            <div class="image-gallery">
                @foreach($report->reportImages as $image)
                    <div class="image-item">
                        <img src="{{ public_path('storage/' . $image->file_path) }}" alt="{{ __('Report Image') }}">
                        @if($image->caption)
                            <div class="image-caption">{{ $image->caption }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($includeComments && count($report->reportComments) > 0)
        <div>
            <h2>{{ __('Comments') }}</h2>

            @foreach($report->reportComments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        <span class="comment-author">{{ $comment->user->name }}</span>
                        <span class="comment-date">{{ $comment->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    <p>{{ $comment->content }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <div style="text-align: center; margin-top: 30px; font-size: 10px; color: #777;">
        {{ __('Report generated on') }} {{ now()->format('M d, Y H:i') }}
    </div>
</body>
</html>
