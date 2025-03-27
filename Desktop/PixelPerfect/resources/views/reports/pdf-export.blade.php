<!-- resources/views/reports/pdf-export.blade.php -->
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }} - {{ __('Rapport TV n°') }}
        {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            color: #000;
        }

        .cover-page {
            position: relative;
            height: 100%;
            padding: 0;
            margin: 0;
        }

        .cover-header {
            background: linear-gradient(135deg, #0056b3 0%, #2a7fff 100%);
            padding: 30px;
            color: white;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            text-align: center;
        }

        .company-logo {
            font-size: 32pt;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }

        .company-tagline {
            font-style: italic;
            margin-top: 5px;
            opacity: 0.9;
        }

        .report-title-box {
            border-left: 5px solid #0056b3;
            padding: 20px 30px;
            margin: 40px auto;
            width: 80%;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .report-title {
            font-size: 24pt;
            color: #0056b3;
            margin: 0 0 15px 0;
        }

        .report-subtitle {
            font-size: 18pt;
            color: #333;
            margin: 0 0 10px 0;
        }

        .report-date {
            font-size: 14pt;
            color: #555;
        }

        .client-info-container {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 30px;
        }

        .client-info-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .client-info-container td {
            padding: 8px 10px;
            border: none;
            vertical-align: top;
        }

        .client-label {
            font-weight: bold;
            color: #0056b3;
            width: 30%;
        }

        .decorative-line {
            height: 3px;
            background: linear-gradient(to right, #0056b3, transparent);
            margin: 20px 30px;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            background-color: #FFFF00;
            /* Yellow header background */
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
            background-color: #D3D3D3;
            /* Light gray */
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
            color: #FF0000;
            /* Red */
        }

        .severity-2 {
            color: #FFA500;
            /* Orange */
        }

        .severity-3 {
            color: #777777;
            /* Gray */
        }

        .severity-4 {
            color: #000000;
            /* Black */
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

        .company-info {
            text-align: center;
            font-size: 8pt;
            margin-bottom: 20px;
        }

        .gravity-legend {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }

        .gravity-legend td {
            padding: 8px;
            border: 1px solid #000;
            vertical-align: top;
        }

        .gravity-level-4 {
            color: #000000;
        }

        .gravity-level-3 {
            color: #008000;
        }

        .gravity-level-2 {
            color: #FF8C00;
        }

        .gravity-level-1 {
            color: #FF1493;
        }

        .gravity-level-0 {
            color: #FF0000;
        }

        .action-text {
            font-weight: bold;
            margin-top: 8px;
            display: block;
        }

        .page-footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            padding: 5px;
            border-top: 1px solid #ccc;
        }

        .page-footer::after {
            content: " " attr(data-page);
        }

        .page-number:after {
            content: counter(page);
        }

        .page-count:after {
            content: counter(pages);
        }
    </style>
</head>

<body>
    <!-- Cover Page -->
    <div class="cover-page">
        <div class="cover-header">
            <h1 class="company-logo">Pixel Perfect</h1>
            <p class="company-tagline">{{ __('Solutions d\'inspection professionnelles') }}</p>
        </div>

        <div class="report-title-box">
            <h1 class="report-title">{{ __('Inspection télévisée de réseaux de canalisations') }}</h1>
            <h2 class="report-title">{{ __($report->title) }}</h2>
            <h3 class="report-subtitle">{{ __('Rapport TV n°') }}
                {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</h3>
            <p class="report-date">{{ __('Date') }}: {{ $report->created_at->format('d.m.Y') }}</p>
        </div>

        <div class="decorative-line"></div>

        <div class="client-info-container">
            <table>
                <tr>
                    <td class="client-label">{{ __('Client') }}:</td>
                    <td>{{ $report->client ?? $report->organization->name }}</td>
                </tr>
                <tr>
                    <td class="client-label">{{ __('Chantier / lieu des travaux') }}:</td>
                    <td>{{ $report->location ?? $report->title }}</td>
                </tr>
                <tr>
                    <td class="client-label">{{ __('Motif de l\'intervention') }}:</td>
                    <td>{{ $report->description }}</td>
                </tr>
            </table>
        </div>

        <div class="company-info" style="position: absolute; bottom: 40px; left: 0; right: 0;">
            <p>{{ __('Avenue de La Gare 1, CH-1880 Bex') }}</p>
            <p>{{ __('Tél.: +41 (0)24 444 44 44 | E-mail: info@pixelperfect.com | Web: www.pixelperfect.com') }}</p>
            <p>{{ __('Bureau: Rue Caroline 4, 1003 Lausanne') }}</p>
        </div>
    </div>

    <!-- Gravity Legend -->
    <div class="page-break"></div>
    <div class="header">
        <h2 style="margin: 0;">{{ __('Légende de classification des observations et défauts des tronçons') }}</h2>
    </div>
    <div class="report-number">
        <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <table class="gravity-legend" style="background-color: #f5f5f5;">
        <tr>
            <td>
                <div class="gravity-level-4">
                    <strong>4 :</strong> {{ __('Constats sans dommage / La canalisation est en bon état.') }}

                    <p>{{ __('Par exemple coude, raccordement (embranchement), changement de matériaux, regard de visite intermédiaire, etc., et toutes informations utiles.') }}
                    </p>

                    <span class="action-text">{{ __('Pas de dégât constaté.') }}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-3">
                    <strong>3 :</strong>
                    {{ __('Défauts de construction ou structurels ayant une influence insignifiante sur l\'étanchéité, l\'hydraulique ou la statique de la canalisation.') }}

                    <p>{{ __('Par exemple légères déformations des tuyaux en matières synthétiques, paroi du tuyau légèrement attaquée, manchons avec joints larges, embranchement mal rhabillé, petits dépôts de matériaux sur le radier, etc.') }}
                    </p>

                    <span
                        class="action-text">{{ __('Les mesures de réfection ou d\'entretien peuvent être planifiées à long terme.') }}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-2">
                    <strong>2 :</strong>
                    {{ __('Défauts de constructions ou structurels affectant l\'étanchéité, l\'hydraulique, ou la statique.') }}

                    <p>{{ __('Par exemple déformations marquées des tuyaux en matières synthétiques, paroi du tuyau attaquée, décalages aux joints, embranchements saillants ou non rhabillés, fissures de faible ampleur, entartrage, dépôts de matériaux, etc. Ouvrages tels que regards cachés ou recouverts.') }}
                    </p>

                    <span
                        class="action-text">{{ __('Les mesures de réfection / assainissement ou d\'entretien sont nécessaires à moyen terme (en règle générale dans les 3 à 5 ans).') }}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-1">
                    <strong>1 :</strong>
                    {{ __('Dommages structurels ne garantissant plus l\'étanchéité, l\'hydraulique ou la sécurité statique.') }}

                    <p>{{ __('Par exemple graves déformations et écrasements des tuyaux, paroi du tuyau perforée ou poreuse, déboîtements aux joints laissant entrevoir le terrain ainsi que des exfiltrations ou infiltrations d\'eau et / ou de racines, embranchements fortement saillants, ruptures et fissures importantes et ouvertes, entartrage, dépôts de matériaux, etc.') }}
                    </p>

                    <span
                        class="action-text">{{ __('Des mesures de réfection / assainissement ou d\'entretien sont urgentes et à exécuter à court terme (en règle générale dans les 1 à 2 ans). Des investigations sont à envisager.') }}</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-0">
                    <strong>0 :</strong>
                    {{ __('La canalisation est déjà ou sera prochainement obstruée ; la canalisation est effondrée, totalement obstruée de racines ou autres obstacles empêchant l\'écoulement et l\'inspection et risque de conduire à un refoulement ou inondation dans les bâtiments. La canalisation fuit, avec risque de pollution des eaux souterraines pour les réseaux d\'eaux usées et industriels.') }}

                    <span
                        class="action-text">{{ __('Des mesures de réfection / assainissement ou d\'entretien doivent être réalisées d\'urgence. Des réparations provisoires sont à envisager afin d\'éviter des dégâts plus importants.') }}</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    @if ($mapImage = $report->reportImages->where('caption', 'Map')->first())
        <div class="header">
            <h2 style="margin: 0;">{{ __('Plan du réseau inspecté') }}</h2>
        </div>
        <div class="report-number">
            <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="image-container" style="margin-top: 10px;">
            <img src="{{ public_path('storage/' . $mapImage->file_path) }}" alt="Network Map">
        </div>
        <div class="page-break"></div>
    @endif

    <!-- Pipe Sections Information with corresponding defects -->
    @if (isset($report->reportSections) && $report->reportSections->count() > 0)
        @foreach ($report->reportSections as $section)
            <div class="header">
                <h2 style="margin: 0;">{{ __('Tronçon') }} {{ $section->name }}</h2>
            </div>
            <div class="report-number">
                <span>{{ __('Rapport TV n°') }}
                    {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <!-- Section Information -->
            <table>
                <tr>
                    <td width="50%">
                        <div class="info-section">
                            <span class="label">{{ __('Diamètre') }}:</span>
                            {{ $section->diameter }} mm
                        </div>
                        <div class="info-section">
                            <span class="label">{{ __('Matériel') }}:</span>
                            {{ ucfirst($section->material) }}
                        </div>
                        <div class="info-section">
                            <span class="label">{{ __('Longueur') }}:</span>
                            {{ $section->length }} m
                        </div>
                    </td>
                    <td width="50%">
                        <div class="info-section">
                            <span class="label">{{ __('Chambre de départ') }}:</span>
                            {{ $section->start_manhole }}
                        </div>
                        <div class="info-section">
                            <span class="label">{{ __('Chambre d\'arrivée') }}:</span>
                            {{ $section->end_manhole }}
                        </div>
                        <div class="info-section">
                            <span class="label">{{ __('Localisation') }}:</span>
                            {{ $section->location }}
                        </div>
                    </td>
                </tr>
                @if ($section->comments)
                    <tr>
                        <td colspan="2">
                            <div class="info-section">
                                <span class="label">{{ __('Commentaires') }}:</span>
                                {{ $section->comments }}
                            </div>
                        </td>
                    </tr>
                @endif
            </table>

            <!-- Section Image -->
            @php
                $sectionImage = $report->reportImages->where('section_id', $section->id)->first();
            @endphp

            @if ($sectionImage)
                <div style="margin-top: 15px;">
                    <table class="image-table" style="width: 100%;">
                        <tr>
                            <td class="image-container" style="padding: 0; text-align: center; vertical-align: top; border: 1px solid #000;">
                                <img src="{{ public_path('storage/' . $sectionImage->file_path) }}" alt="Section Image"
                                    style="width: 100%; height: auto; max-height: 400px; object-fit: contain;">
                            </td>
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Get defects for this section -->
            @php
                $sectionDefects = $report->reportDefects->filter(function($defect) use ($section) {
                    return $defect->section_id == $section->id;
                });
            @endphp

            @if($sectionDefects->count() > 0)
                <!-- Defect List for this section -->
                <div style="margin-top: 20px;">
                    <table class="defect-list">
                        <thead>
                            <tr>
                                <th>{{ __('Observation') }}</th>
                                <th>{{ __('Distance (ml.)') }}</th>
                                <th>{{ __('Compteur') }}</th>
                                <th>{{ __('Anomalies / remarques') }}</th>
                                <th>{{ __('Gravité') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sectionDefects as $index => $defect)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td>{{ $defect->coordinates['distance'] ?? '0,00' }}</td>
                                    <td>{{ $defect->coordinates['counter'] ?? '-' }}</td>
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
                </div>

                <!-- Individual Observations for this section -->
                @foreach($sectionDefects as $index => $defect)
                    <div class="page-break"></div>
                    <div class="header">
                        <h2 style="margin: 0;">{{ __('Tronçon') }} {{ $section->name }}</h2>
                    </div>
                    <div class="report-number">
                        <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                                @if ($defectImage = $report->reportImages->where('defect_id', $defect->id)->first())
                                    <img src="{{ public_path('storage/' . $defectImage->file_path) }}" alt="Defect Image">
                                @else
                                    <div
                                        style="height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5;">
                                        <p>{{ __('No image available') }}</p>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                @endforeach
            @else
                <div style="margin-top: 20px; text-align: center; padding: 20px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                    <p>{{ __('No defects recorded for this section') }}</p>
                </div>
            @endif

            <div class="page-break"></div>
        @endforeach
    @endif

    <!-- Handle defects that don't have section_id -->
    @php
        $unassignedDefects = $report->reportDefects->filter(function($defect) {
            return empty($defect->section_id);
        });
    @endphp

    @if ($unassignedDefects->count() > 0)
        <div class="header">
            <h2 style="margin: 0;">{{ __('Défauts non assignés à une section') }}</h2>
        </div>
        <div class="report-number">
            <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>

        <!-- Defect List for unassigned defects -->
        <table class="defect-list">
            <thead>
                <tr>
                    <th>{{ __('Observation') }}</th>
                    <th>{{ __('Distance (ml.)') }}</th>
                    <th>{{ __('Compteur') }}</th>
                    <th>{{ __('Anomalies / remarques') }}</th>
                    <th>{{ __('Gravité') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($unassignedDefects as $index => $defect)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $defect->coordinates['distance'] ?? '0,00' }}</td>
                        <td>{{ $defect->coordinates['counter'] ?? '-' }}</td>
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

        <!-- Individual Observations for unassigned defects -->
        @foreach($unassignedDefects as $index => $defect)
            <div class="page-break"></div>
            <div class="header">
                <h2 style="margin: 0;">{{ __('Défaut non assigné') }} #{{ $index + 1 }}</h2>
            </div>
            <div class="report-number">
                <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                        @if ($defectImage = $report->reportImages->where('defect_id', $defect->id)->first())
                            <img src="{{ public_path('storage/' . $defectImage->file_path) }}" alt="Defect Image">
                        @else
                            <div
                                style="height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5;">
                                <p>{{ __('No image available') }}</p>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>
        @endforeach
    @endif

    <!-- Comments Section if included -->
    @if ($includeComments && count($report->reportComments) > 0)
        <div class="page-break"></div>
        <div class="header">
            <h2 style="margin: 0;">{{ __('Commentaires') }}</h2>
        </div>
        <div class="report-number">
            <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="comment-section">
            @foreach ($report->reportComments->where('include_in_pdf', true) as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        {{ $comment->user->name }} <span
                            class="comment-date">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    <div class="comment-content">
                        {{ $comment->content }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="page-break"></div>

    <!-- Avertissements -->
    <div class="header">
        <h2 style="margin: 0;">{{ __('Avertissements pour l\'analyse du rapport TV') }}</h2>
    </div>
    <div class="report-number">
        <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <ol>
        <li>
            {{ __('Les degrés de gravités de chaque observation sont une appréciation personnelle de l\'opérateur, indiqués à titre d\'aide pour l\'analyse de l\'état de la canalisation. Il convient de considérer l\'ensemble du tronçon et, selon les cas, l\'ensemble du réseau d\'assainissement alentour avant toute prise de mesure, ainsi que tous facteurs externes (contexte, situation géographique, utilisation et débit dans le réseau, météo, etc.).') }}
        </li>
        <li>
            {{ __('Les éventuels croquis inclus dans ce dossier sont des plans schématiques établis sur la base des constatations faites lors du passage de la caméra. Bien que ces informations aient été relevées avec soin, des divergences peuvent apparaître avec la réalité.') }}
        </li>
        <li>
            {{ __('Seul un nettoyage préalable des canalisations à inspecter par curage à eau sous pression permet de vérifier l\'état des canalisations de façon optimale. Notre responsabilité ne saurait être engagée pour tous défauts ou éléments qui n\'auraient été decelés faute d\'un tel nettoyage. Les éventuelles micro-fissures ne peuvent pas toujours être décelées.') }}
        </li>
        <li>
            {{ __('L\'inspection télévisée ne permet pas d\'identifier tous les types de défauts, notamment ceux situés hors du champ visuel de la caméra. Une inspection complémentaire peut s\'avérer nécessaire dans certains cas pour obtenir un diagnostic complet du réseau.') }}
        </li>
        <li>
            {{ __('Les mesures et distances indiquées dans ce rapport sont données à titre indicatif et peuvent présenter une marge d\'erreur inhérente aux équipements utilisés. Une vérification sur site est recommandée avant d\'entreprendre des travaux.') }}
        </li>
        <li>
            {{ __('L\'interprétation des défauts observés doit être effectuée par un professionnel qualifié, capable d\'évaluer l\'impact de chaque anomalie sur le fonctionnement global du réseau et sa durabilité à long terme.') }}
        </li>
        <li>
            {{ __('Les conditions d\'accès et l\'état général du réseau au moment de l\'inspection peuvent limiter la qualité des observations. Les segments inaccessibles ou partiellement observables sont clairement identifiés dans le rapport.') }}
        </li>
        <li>
            {{ __('Ce rapport est valable à la date de l\'inspection. L\'évolution naturelle des réseaux, les interventions ultérieures ou les conditions climatiques exceptionnelles peuvent modifier l\'état des canalisations après la réalisation de ce diagnostic.') }}
        </li>
        <li>
            {{ __('Les recommandations formulées dans ce rapport sont basées sur l\'état constaté lors de l\'inspection et les normes en vigueur. Elles ne prennent pas en compte d\'éventuelles modifications réglementaires postérieures à la date d\'émission du rapport.') }}
        </li>
        <li>
            {{ __('La classification des défauts suit une méthodologie standardisée, mais peut varier selon les pratiques locales et les exigences spécifiques du client. Une concertation avec le gestionnaire du réseau est recommandée pour définir les priorités d\'intervention.') }}
        </li>
        <li>
            {{ __('Les images et vidéos fournies dans ce rapport sont destinées à illustrer les observations mentionnées. Leur qualité peut être affectée par les conditions d\'éclairage, de visibilité et les contraintes techniques rencontrées lors de l\'inspection.') }}
        </li>
        <li>
            {{ __('Le présent rapport ne constitue pas une étude de dimensionnement ou de capacité hydraulique du réseau. Des études complémentaires peuvent être nécessaires pour évaluer ces aspects spécifiques.') }}
        </li>
        <li>
            {{ __('La durée de vie résiduelle des canalisations ne peut être déterminée avec précision sur la seule base d\'une inspection visuelle. Des analyses complémentaires de matériaux peuvent être recommandées dans certains cas.') }}
        </li>
        <li>
            {{ __('Certains défauts peuvent évoluer rapidement après l\'inspection, notamment en présence d\'infiltrations, de mouvements de terrain ou de charges importantes en surface. Une surveillance régulière est recommandée pour les défauts critiques identifiés.') }}
        </li>
        <li>
            {{ __('Ce rapport doit être conservé par le propriétaire ou gestionnaire du réseau et transmis à tout intervenant futur pour assurer la traçabilité des inspections et interventions sur le réseau concerné.') }}
        </li>
    </ol>

    <div class="signature">
        <p>{{ __('Signature du Technicien') }}: {{ $report->inspector_name }}</p>
    </div>

    <div class="page-footer">
        {{ __('Pixel Perfect - Avenue de la Gare 1, 1880 Bex - Tél.: +41 (0)24 444 44 44') }} |
        {{ __('Page') }} <span class="page-number">
    </div>
</body>

</html>
