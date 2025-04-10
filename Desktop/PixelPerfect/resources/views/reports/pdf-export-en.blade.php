<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }} - TV Report No.
        {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            color: #000;
        }

        Copiar .cover-page {
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
            max-width: 95%;
            max-height: 95vh;
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
            <p class="company-tagline">Professional inspection solutions</p>
        </div>

        <div class="report-title-box">
            <h1 class="report-title">CCTV Inspection of Pipeline Networks</h1>
            <h2 class="report-title">{{ $report->title }}</h2>
            <h3 class="report-subtitle">TV Report No.
                {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</h3>
            <p class="report-date">Date: {{ $report->created_at->format('d.m.Y') }}</p>
        </div>

        <div class="decorative-line"></div>

        <div class="client-info-container">
            <table>
                <tr>
                    <td class="client-label">Client:</td>
                    <td>{{ $report->client ?? $report->organization->name }}</td>
                </tr>
                <tr>
                    <td class="client-label">Work site / Location:</td>
                    <td>{{ $report->location ?? $report->title }}</td>
                </tr>
                <tr>
                    <td class="client-label">Reason for intervention:</td>
                    <td>{{ $report->description }}</td>
                </tr>
            </table>
        </div>

        <div class="company-info" style="position: absolute; bottom: 40px; left: 0; right: 0;">
            <p>Avenue de La Gare 1, CH-1880 Bex</p>
            <p>Tel.: +41 (0)24 444 44 44 | E-mail: info@pixelperfect.com | Web: www.pixelperfect.com</p>
            <p>Office: Rue Caroline 4, 1003 Lausanne</p>
        </div>
    </div>

    <!-- Gravity Legend -->
    <div class="page-break"></div>
    <div class="header">
        <h2 style="margin: 0;">Classification legend for observations and defects of sections</h2>
    </div>
    <div class="report-number">
        <span>TV Report No. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <table class="gravity-legend" style="background-color: #f5f5f5;">
        <tr>
            <td>
                <div class="gravity-level-4">
                    <strong>4:</strong> No damage observed / The pipeline is in good condition.

                    <p>For example, bend, connection (branch), change of materials, intermediate inspection manhole,
                        etc., and any useful information.
                    </p>

                    <span class="action-text">No damage observed.</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-3">
                    <strong>3:</strong>
                    Construction or structural defects having insignificant influence on the watertightness, hydraulics,
                    or statics of the pipeline.

                    <p>For example, slight deformations of pipes in synthetic materials, slightly damaged pipe wall,
                        sleeves with wide joints, poorly finished branch connection, small deposits of materials on the
                        invert, etc.
                    </p>

                    <span class="action-text">Repair or maintenance measures can be planned for the long term.</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-2">
                    <strong>2:</strong>
                    Construction or structural defects affecting watertightness, hydraulics, or statics.

                    <p>For example, marked deformations of pipes in synthetic materials, damaged pipe wall, joint
                        displacements, protruding or unfinished branch connections, minor cracks, scaling, material
                        deposits, etc. Structures such as hidden or covered manholes.
                    </p>

                    <span class="action-text">Repair/renovation or maintenance measures are necessary in the medium term
                        (generally within 3 to 5 years).</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-1">
                    <strong>1:</strong>
                    Structural damage no longer guaranteeing watertightness, hydraulics, or static safety.

                    <p>For example, serious deformations and crushing of pipes, perforated or porous pipe wall, joint
                        disconnections exposing the ground as well as water and/or root exfiltrations or infiltrations,
                        strongly protruding branch connections, major and open breaks and cracks, scaling, material
                        deposits, etc.
                    </p>

                    <span class="action-text">Repair/renovation or maintenance measures are urgent and should be
                        executed in the short term (generally within 1 to 2 years). Investigations should be
                        considered.</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-0">
                    <strong>0:</strong>
                    The pipeline is already or will soon be obstructed; the pipeline is collapsed, completely obstructed
                    by roots or other obstacles preventing flow and inspection and risks leading to backflow or flooding
                    in buildings. The pipeline leaks, with risk of groundwater pollution for wastewater and industrial
                    networks.

                    <span class="action-text">Repair/renovation or maintenance measures must be carried out urgently.
                        Temporary repairs should be considered to avoid more significant damage.</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    @if ($mapImage = $report->reportImages->where('caption', 'Map')->first())
        <div class="header">
            <h2 style="margin: 0;">Map of inspected network</h2>
        </div>
        <div class="report-number">
            <span>TV Report No. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                <h2 style="margin: 0;">Section {{ $section->name }}</h2>
            </div>
            <div class="report-number">
                <span>TV Report No.
                    {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <!-- Add pipe section details -->
            <table class="section-details-table">
                <tr>
                    <td width="50%">
                        <div class="info-section">
                            <span class="label">Diameter:</span>
                            {{ $section->diameter }} mm
                        </div>
                        <div class="info-section">
                            <span class="label">Material:</span>
                            {{ ucfirst($section->material) }}
                        </div>
                        <div class="info-section">
                            <span class="label">Length:</span>
                            {{ $section->length }} m
                        </div>
                    </td>
                    <td width="50%">
                        <div class="info-section">
                            <span class="label">Starting chamber:</span>
                            {{ $section->start_manhole }}
                        </div>
                        <div class="info-section">
                            <span class="label">Ending chamber:</span>
                            {{ $section->end_manhole }}
                        </div>
                        <div class="info-section">
                            <span class="label">Location:</span>
                            {{ $section->location }}
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Section Image -->
            @php
                $sectionImage = $report->reportImages->where('section_id', $section->id)->first();
            @endphp

            @if ($sectionImage)
                <div class="image-container" style="margin-top: 10px;">
                    <img src="{{ public_path('storage/' . $sectionImage->file_path) }}" alt="Section Image">
                </div>
            @endif

            <!-- Get defects for this section -->
            @php
                $sectionDefects = $report->reportDefects->filter(function ($defect) use ($section) {
                    return $defect->section_id == $section->id;
                });
            @endphp

            <div class="page-break"></div>

            <div class="header">
                <h2 style="margin: 0;">Section {{ $section->name }}</h2>
            </div>
            <div class="report-number">
                <span>TV Report No.
                    {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <!-- Section Information -  -->
            <table class="section-details-table">
                <tr>
                    <td class="section-details-left">
                        <div>
                            <span class="label">Inspection date:</span>
                            {{ $report->inspection_date ? date('d.m.Y', strtotime($report->inspection_date)) : $report->created_at->format('d.m.Y') }}
                        </div>
                        <div>
                            <span class="label">Order No.:</span>
                            {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <span class="label">Person present:</span>
                            {{ $report->creator->name }}
                        </div>
                    </td>
                    <td class="section-details-right">
                        <div>
                            <span class="label">Operator:</span>
                            {{ $report->operator ?? $report->creator->name }}
                        </div>
                        <div>
                            <span class="label">Vehicle:</span>
                            Vehicle {{ $report->organization->name }}
                        </div>
                        <div>
                            <span class="label">Weather:</span>
                            {{ $report->weather ?? '-' }}
                        </div>
                    </td>
                </tr>
                <tr class="section-full-row">
                    <td colspan="2">
                        <span class="label">Remarks:</span>
                        {{ $section->comments ?? '-' }}
                    </td>
                </tr>
            </table>
            @if ($sectionDefects->count() > 0)
                <!-- Defect List for this section - similar to Image 1 -->
                <div>
                    <table class="defect-list">
                        <thead>
                            <tr>
                                <th>Observation</th>
                                <th>Distance (lm.)</th>
                                <th>Counter</th>
                                <th>Anomalies / remarks</th>
                                <th>Severity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sectionDefects as $index => $defect)
                                <tr>
                                    <td style="text-align: center;">{{ $index + 1 }}</td>
                                    <td>{{ $defect->coordinates['distance'] ?? '0,00' }}</td>
                                    <td>{{ $defect->coordinates['counter'] ?? '-' }}</td>
                                    <td
                                        class="{{ 'severity-' . ($defect->severity === 'critical' ? '1' : ($defect->severity === 'high' ? '2' : ($defect->severity === 'medium' ? '3' : '4'))) }}">
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
                @foreach ($sectionDefects as $index => $defect)
                    <div class="page-break"></div>
                    <div class="header">
                        <h2 style="margin: 0;">Section {{ $section->name }}</h2>
                    </div>
                    <div class="report-number">
                        <span>TV Report No.
                            {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <table>
                        <tr>
                            <td colspan="3" class="observation-header">
                                Observation {{ $index + 1 }}
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span class="label">Distance:</span>
                                {{ $defect->coordinates['distance'] ?? '--' }} lm.
                            </td>
                            <td width="30%">
                                <span class="label">Counter:</span>
                                {{ $defect->coordinates['counter'] ?? '--' }}
                            </td>
                            <td width="50%">
                                <span class="label">Water level:</span>
                                {{ $defect->coordinates['water_level'] ?? '--' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="info-section">
                                    <span class="label">Finding:</span>
                                    {{ $defect->description }}
                                </div>
                                <div class="info-section">
                                    <span class="label">Remarks:</span>
                                    {{ $defect->coordinates['comment'] ?? '' }}
                                </div>
                            </td>
                            <td style="vertical-align: top;">
                                <div class="gravity-value">
                                    <span class="label">Severity:</span>
                                    {{ $defect->severity === 'critical' ? '1' : ($defect->severity === 'high' ? '2' : ($defect->severity === 'medium' ? '3' : '4')) }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="image-container">
                                @if ($defectImage = $report->reportImages->where('defect_id', $defect->id)->first())
                                    <img src="{{ public_path('storage/' . $defectImage->file_path) }}"
                                        alt="Defect Image">
                                @else
                                    <div
                                        style="height: 200px; display: flex; align-items: center; justify-content: center; background-color: #f5f5f5;">
                                        <p>No image available</p>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    </table>
                @endforeach
                <div class="page-break"></div>
            @else
                <div
                    style="margin-top: 20px; text-align: center; padding: 20px; background-color: #f8f9fa; border: 1px solid #dee2e6;">
                    <p>No defects recorded for this section</p>
                </div>
            @endif
        @endforeach
    @endif

    <!-- Comments Section if included -->
    @if ($includeComments && count($report->reportComments) > 0)
        <div class="header">
            <h2 style="margin: 0;">Comments</h2>
        </div>
        <div class="report-number">
            <span>TV Report No. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
        <div class="page-break"></div>
    @endif

    <!-- Warnings -->
    <div class="header">
        <h2 style="margin: 0;">Warnings for TV report analysis</h2>
    </div>
    <div class="report-number">
        <span>TV Report No. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <ol>
        <li>
            The severity levels of each observation are a personal assessment by the operator, indicated as an aid for
            analyzing the condition of the pipeline. It is advisable to consider the entire section and, depending on
            the case, the entire surrounding sewerage network before taking any measures, as well as all external
            factors (context, geographical situation, use and flow in the network, weather, etc.).
        </li>
        <li>
            Any sketches included in this file are schematic plans established based on observations made during the
            camera inspection. Although this information has been carefully recorded, discrepancies may appear with
            reality.
        </li>
        <li>
            Only prior cleaning of the pipelines to be inspected by high-pressure water jetting allows for optimal
            verification of the condition of the pipelines. We cannot be held responsible for any defects or elements
            that would not have been detected due to a lack of such cleaning. Any micro-cracks cannot always be
            detected.
        </li>
        <li>
            The CCTV inspection does not allow the identification of all types of defects, particularly those located
            outside the camera's field of view. Complementary inspection may be necessary in some cases to obtain a
            complete diagnosis of the network.
        </li>
        <li>
            The measurements and distances indicated in this report are given for information only and may present a
            margin of error inherent to the equipment used. On-site verification is recommended before undertaking any
            work.
        </li>
        <li>
            The interpretation of observed defects must be carried out by a qualified professional, capable of
            evaluating the impact of each anomaly on the overall functioning of the network and its long-term
            durability.
        </li>
        <li>
            Access conditions and the general state of the network at the time of inspection may limit the quality of
            observations. Inaccessible or partially observable segments are clearly identified in the report.
        </li>
        <li>
            This report is valid on the date of inspection. The natural evolution of networks, subsequent interventions,
            or exceptional weather conditions may change the state of pipelines after this diagnosis is made.
        </li>
        <li>
            The recommendations in this report are based on the condition observed during inspection and current
            standards. They do not take into account any regulatory changes after the date of issue of the report.
        </li>
        <li>
            The classification of defects follows a standardized methodology but may vary according to local practices
            and specific client requirements. Consultation with the network manager is recommended to define
            intervention priorities.
        </li>
        <li>
            The images and videos provided in this report are intended to illustrate the mentioned observations. Their
            quality may be affected by lighting conditions, visibility, and technical constraints encountered during
            inspection.
        </li>
        <li>
            This report does not constitute a study of the sizing or hydraulic capacity of the network. Additional
            studies may be necessary to evaluate these specific aspects.
        </li>
        <li>
            The remaining service life of pipelines cannot be determined precisely based solely on visual inspection.
            Additional material analyses may be recommended in some cases.
        </li>
        <li>
            Some defects may evolve rapidly after inspection, particularly in the presence of infiltrations, ground
            movements, or significant surface loads. Regular monitoring is recommended for identified critical defects.
        </li>
        <li>
            This report must be kept by the owner or manager of the network and transmitted to any future intervener to
            ensure traceability of inspections and interventions on the concerned network.
        </li>
    </ol>

    <div class="signature">
        <p>Technician's Signature:</p>
    </div>

    <div class="page-footer">
        Pixel Perfect - Avenue de la Gare 1, 1880 Bex - Tel.: +41 (0)24 444 44 44 |
        Page<span class="page-number">
    </div>
</body>

</html>
