<!DOCTYPE html>
<html lang="{{ $report->language }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }} - Rapport TV n° {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            color: #000;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            background-color: #FFFF00; /* Yellow header background */
            color: #000;
            padding: 5px;
            text-align: center;
            border: 1px solid #000;
            margin-bottom: 0;
        }
        .report-number {
            text-align: right;
            background-color: #F0F0F0;
            padding: 3px;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            border: 1px solid #000;
            padding: 4px;
        }
        .observation-header {
            background-color: #D3D3D3; /* Light gray */
            font-weight: bold;
            padding: 5px;
        }
        .observation-data {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 10px;
        }
        .label {
            font-weight: bold;
        }
        .image-container {
            padding: 0;
            margin: 0;
            text-align: center;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        .info-section {
            margin-bottom: 8px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 2px;
        }
        .gravity-value {
            text-align: right;
            font-weight: bold;
        }
        .defect-list {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .defect-list th {
            background-color: #D3D3D3;
            padding: 4px;
            border: 1px solid #000;
            text-align: left;
        }
        .defect-list td {
            border: 1px solid #000;
            padding: 4px;
        }
        .severity-1 {
            color: #FF0000; /* Red */
        }
        .severity-2 {
            color: #FFA500; /* Orange */
        }
        .severity-4 {
            color: #000000; /* Black */
        }
        .comment-section {
            margin-top: 15px;
            border: 1px solid #000;
            padding: 5px;
        }
        .comment-item {
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #CCC;
        }
        .comment-header {
            font-weight: bold;
            color: #333;
            margin-bottom: 2px;
        }
        .comment-date {
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Report Title -->
    <div class="header">
        <h2 style="margin: 0;">{{ __('Tronçon') }} {{ $report->id }}</h2>
    </div>
    <div class="report-number">
        <span>Rapport TV n° {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <!-- Report Information -->
    <table>
        <tr>
            <td width="50%">
                <div class="info-section">
                    <span class="label">{{ __('Date inspection') }}:</span>
                    {{ $report->created_at->format('d.m.Y') }}
                </div>
                <div class="info-section">
                    <span class="label">N° de commande:</span>
                    {{ $report->id }}
                </div>
                <div class="info-section">
                    <span class="label">{{ __('Personne présente') }}:</span>
                    {{ $report->creator->name }}
                </div>
            </td>
            <td width="50%">
                <div class="info-section">
                    <span class="label">{{ __('Opérateur') }}:</span>
                    {{ substr($report->creator->name, 0, 3) }}
                </div>
                <div class="info-section">
                    <span class="label">{{ __('Véhicule') }}:</span>
                    {{ $report->organization->name }}
                </div>
                <div class="info-section">
                    <span class="label">{{ __('Météo') }}:</span>
                    -
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div class="info-section">
                    <span class="label">{{ __('Remarque') }}:</span>
                    {{ $report->description }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Defect List -->
    <table class="defect-list">
        <thead>
            <tr>
                <th>{{ __('Ouvrage') }}</th>
                <th>{{ __('Clip vidéo') }}</th>
                <th>{{ __('Distance (ml.)') }}</th>
                <th>{{ __('Anomalies / remarques') }}</th>
                <th>{{ __('Gravité') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->reportDefects as $index => $defect)
                <tr>
                    @if($index === 0)
                        <td rowspan="{{ count($report->reportDefects) }}">
                            <!-- Show reference number -->
                            @if($defect->coordinates && isset($defect->coordinates['reference']))
                                <div style="text-align: center; margin-bottom: 5px;">
                                    <div style="border: 2px solid #000; border-radius: 50%; width: 40px; height: 40px; display: inline-block; text-align: center; line-height: 40px;">
                                        {{ $defect->coordinates['reference'] }}
                                    </div>
                                </div>
                            @endif
                        </td>
                    @endif
                    <td>{{ $defect->defectType ? substr($defect->defectType->name, 0, 2) : '-' }}</td>
                    <td>{{ $defect->coordinates['distance'] ?? '0,00' }}</td>
                    <td class="{{ 'severity-' . ($defect->severity === 'critical' ? '1' : ($defect->severity === 'high' ? '2' : ($defect->severity === 'medium' ? '3' : '4'))) }}">
                        {{ $defect->description }}
                    </td>
                    <td style="text-align: center;">
                        {{ $defect->severity === 'critical' ? '1' : ($defect->severity === 'high' ? '2' : ($defect->severity === 'medium' ? '3' : '4')) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Page Break for Observations -->
    <div class="page-break"></div>

    <!-- Observations Section - One per page -->
    @foreach($report->reportDefects as $index => $defect)
        <div class="header">
            <h2 style="margin: 0;">{{ __('Tronçon') }} {{ $report->id }}</h2>
        </div>
        <div class="report-number">
            <span>Rapport TV n° {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>

        <table>
            <tr>
                <td colspan="3" class="observation-header">
                    {{ __('Observation') }} {{ $index + 1 }}
                </td>
            </tr>
            <tr>
                <td width="20%">
                    <span class="label">{{ __('Distance') }}:</span>
                    {{ $defect->coordinates['distance'] ?? '--' }} ml.
                </td>
                <td width="30%">
                    <span class="label">{{ __('Compteur') }}:</span>
                    {{ $defect->coordinates['counter'] ?? '--' }}
                </td>
                <td width="50%">
                    <span class="label">{{ __('Niveau d\'eau') }}:</span>
                    {{ $defect->coordinates['water_level'] ?? '--' }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="info-section">
                        <span class="label">{{ __('Constat') }}:</span>
                        {{ $defect->description }}
                    </div>
                    <div class="info-section">
                        <span class="label">{{ __('Remarque') }}:</span>
                        {{ $defect->coordinates['comment'] ?? '' }}
                    </div>
                </td>
                <td style="vertical-align: top;">
                    <div class="gravity-value">
                        <span class="label">{{ __('Gravité') }}:</span>
                        {{ $defect->severity === 'critical' ? '1' : ($defect->severity === 'high' ? '2' : ($defect->severity === 'medium' ? '3' : '4')) }}
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="image-container">
                    @if($defectImage = $report->reportImages->where('defect_id', $defect->id)->first())
                        <img src="{{ public_path('storage/' . $defectImage->file_path) }}" alt="Defect Image">
                    @else
                        <div style="height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5;">
                            <p>{{ __('No image available') }}</p>
                        </div>
                    @endif
                </td>
            </tr>
        </table>

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    <!-- Network Map if available -->
    @if($mapImage = $report->reportImages->where('caption', 'Map')->first())
        <div class="page-break"></div>
        <div class="header">
            <h2 style="margin: 0;">{{ __('Plan du réseau inspecté') }}</h2>
        </div>
        <div class="report-number">
            <span>Rapport TV n° {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="image-container" style="margin-top: 10px;">
            <img src="{{ public_path('storage/' . $mapImage->file_path) }}" alt="Network Map">
        </div>
    @endif

    <!-- Comments Section if included -->
    @if($includeComments && count($report->reportComments) > 0)
        <div class="page-break"></div>
        <div class="header">
            <h2 style="margin: 0;">{{ __('Commentaires') }}</h2>
        </div>
        <div class="report-number">
            <span>Rapport TV n° {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="comment-section">
            @foreach($report->reportComments->where('include_in_pdf', true) as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        {{ $comment->user->name }} <span class="comment-date">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="comment-content">
                        {{ $comment->content }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
