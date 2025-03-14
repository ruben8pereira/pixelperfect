<!-- resources/views/reports/pdf-export.blade.php -->
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }} - {{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</title>
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
        .severity-3 {
            color: #777777; /* Gray */
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
        .logo {
            text-align: center;
            margin-bottom: 10px;
        }
        .company-info {
            text-align: center;
            font-size: 8pt;
            margin-bottom: 20px;
        }
        .gravity-legend {
            margin-top: 20px;
            border: 1px solid #000;
            padding: 5px;
        }
        .gravity-legend-title {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .gravity-legend-item {
            margin-bottom: 3px;
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
    </style>
</head>
<body>
    <!-- Cover Page -->
    <div class="logo">
        <h1>PixelPerfect</h1>
        <!-- Add your logo here -->
        <!-- <img src="{{ public_path('images/logo.png') }}" alt="Company Logo" style="max-width: 200px;"> -->
    </div>

    <div class="company-info">
        <p>{{ __('Avenue de la gare 1, CH-1880 Bex') }}</p>
        <p>{{ __('Tél.: +41 (0)24 444 44 44 | E-mail: info@pixelperfect.com | Web: www.pixelperfect.com') }}</p>
        <p>{{ __('Bureau: Rue Caroline 4, 1003 Lausanne') }}</p>
    </div>

    <div style="text-align: center; margin: 50px 0;">
        <h1>{{ __('Inspection télévisée de réseaux de canalisations') }}</h1>
        <h2>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</h2>
        <p>{{ __('Date') }} {{ $report->created_at->format('d.m.Y') }}</p>
    </div>

    <table style="margin-top: 50px;">
        <tr>
            <td style="width: 30%; border: none;">{{ __('Client') }}:</td>
            <td style="border: none;">{{ $report->organization->name }}</td>
        </tr>
        <tr>
            <td style="border: none;">{{ __('Chantier / lieu des travaux') }}:</td>
            <td style="border: none;">{{ $report->location ?? $report->title }}</td>
        </tr>
        <tr>
            <td style="border: none;">{{ __('Motif de l\'intervention') }}:</td>
            <td style="border: none;">{{ $report->description }}</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- Report Title -->
    <div class="header">
        <h2 style="margin: 0;">{{ __('Tronçon') }} {{ $report->id }}</h2>
    </div>
    <div class="report-number">
        <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                    <span class="label">{{ __('N° de commande') }}:</span>
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
                    {{ $report->weather ?? '-' }}
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
            @forelse($report->reportDefects as $index => $defect)
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
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">{{ __('No defects recorded') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Page Break for Observations -->
    <div class="page-break"></div>

    <!-- Observations Section - One per page -->
    @forelse($report->reportDefects as $index => $defect)
        <div class="header">
            <h2 style="margin: 0;">{{ __('Tronçon') }} {{ $report->id }}</h2>
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
    @empty
        <div style="text-align: center; padding: 50px 0;">
            <h3>{{ __('No defects recorded for this report') }}</h3>
        </div>
    @endforelse

    <!-- Network Map if available -->
    @if($mapImage = $report->reportImages->where('caption', 'Map')->first())
        <div class="page-break"></div>
        <div class="header">
            <h2 style="margin: 0;">{{ __('Plan du réseau inspecté') }}</h2>
        </div>
        <div class="report-number">
            <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
            <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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

    <!-- Gravity Legend -->
    <div class="page-break"></div>
    <div class="header">
        <h2 style="margin: 0;">{{ __('Légende de classification des observations et défauts des tronçons') }}</h2>
    </div>
    <div class="report-number">
        <span>{{ __('Rapport TV n°') }} {{ str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <div class="gravity-legend">
        <div class="gravity-legend-item">
            <span class="label">4 : </span>
            {{ __('Constats sans dommage / La canalisation est en bon état.') }}
            <p>{{ __('Par exemple coude, raccordement (embranchement), changement de matériaux, regard de visite intermédiaire, etc., et toutes informations utiles.') }}</p>
        </div>
        <div class="gravity-legend-item">
            <span class="label">3 : </span>
            {{ __('Défauts de construction ou structurels ayant une influence insignifiante sur l\'étanchéité, l\'hydraulique ou la statique de la canalisation.') }}
            <p>{{ __('Par exemple légères déformations des tuyaux en matières synthétiques, paroi du tuyau légèrement attaquée, manchons avec joints larges, embranchement mal rhabillé, petits dépôts de matériaux sur le radier, etc.') }}</p>
        </div>
        <div class="gravity-legend-item">
            <span class="label">2 : </span>
            {{ __('Défauts de constructions ou structurels affectant l\'étanchéité, l\'hydraulique, ou la statique.') }}
            <p>{{ __('Par exemple déformations marquées des tuyaux en matières synthétiques, paroi du tuyau attaquée, décalages aux joints, embranchements saillants ou non rhabillés, fissures de faible ampleur, entartrage, dépôts de matériaux, etc. Ouvrages tels que regards cachés ou recouverts.') }}</p>
        </div>
        <div class="gravity-legend-item">
            <span class="label">1 : </span>
            {{ __('Dommages structurels ne garantissant plus l\'étanchéité, l\'hydraulique ou la sécurité statique.') }}
            <p>{{ __('Par exemple graves déformations et écrasements des tuyaux, paroi du tuyau perforée ou poreuse, déboîtements aux joints laissant entrevoir le terrain ainsi que des exfiltrations ou infiltrations d\'eau et / ou de racines, embranchements fortement saillants, ruptures et fissures importantes et ouvertes, entartrage, dépôts de matériaux, etc.') }}</p>
        </div>
        <div class="gravity-legend-item">
            <span class="label">0 : </span>
            {{ __('La canalisation est déjà ou sera prochainement obstruée ; la canalisation est effondrée, totalement obstruée de racines ou autres obstacles empêchant l\'écoulement et l\'inspection et risque de conduire à un refoulement ou inondation dans les bâtiments. La canalisation fuit, avec risque de pollution des eaux souterraines pour les réseaux d\'eaux usées et industriels.') }}
        </div>
    </div>

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
    </ol>

    <div class="page-footer">
        {{ __('PixelPerfect - Avenue de la gare 1, 1880 Bex - Tél.: +41 (0)24 444 44 44') }} | {{ __('Page') }}
    </div>
</body>
</html>
