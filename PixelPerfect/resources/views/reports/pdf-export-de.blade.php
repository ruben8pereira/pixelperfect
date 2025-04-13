<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $report->title }} - TV-Bericht Nr.
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
            <p class="company-tagline">Professionelle Inspektionslösungen</p>
        </div>

        <div class="report-title-box">
            <h1 class="report-title">TV-Inspektion von Kanalisationsnetzen</h1>
            <h2 class="report-title">{{ $report->title }}</h2>
            <h3 class="report-subtitle">TV-Bericht Nr.
                {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</h3>
            <p class="report-date">Datum: {{ $report->created_at->format('d.m.Y') }}</p>
        </div>

        <div class="decorative-line"></div>

        <div class="client-info-container">
            <table>
                <tr>
                    <td class="client-label">Kunde:</td>
                    <td>{{ $report->client ?? $report->organization->name }}</td>
                </tr>
                <tr>
                    <td class="client-label">Baustelle / Arbeitsort:</td>
                    <td>{{ $report->location ?? $report->title }}</td>
                </tr>
                <tr>
                    <td class="client-label">Grund des Einsatzes:</td>
                    <td>{{ $report->description }}</td>
                </tr>
            </table>
        </div>

        <div class="company-info" style="position: absolute; bottom: 40px; left: 0; right: 0;">
            <p>Avenue de La Gare 1, CH-1880 Bex</p>
            <p>Tel.: +41 (0)24 444 44 44 | E-mail: info@pixelperfect.com | Web: www.pixelperfect.com</p>
            <p>Büro: Rue Caroline 4, 1003 Lausanne</p>
        </div>
    </div>

    <!-- Gravity Legend -->
    <div class="page-break"></div>
    <div class="header">
        <h2 style="margin: 0;">Klassifizierungslegende für Beobachtungen und Mängel von Abschnitten</h2>
    </div>
    <div class="report-number">
        <span>TV-Bericht Nr. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <table class="gravity-legend" style="background-color: #f5f5f5;">
        <tr>
            <td>
                <div class="gravity-level-4">
                    <strong>4:</strong> Keine Schäden beobachtet / Die Leitung ist in gutem Zustand.

                    <p>Zum Beispiel Krümmung, Anschluss (Abzweigung), Materialwechsel, Zwischenkontrollschacht usw. und
                        alle nützlichen Informationen.
                    </p>

                    <span class="action-text">Keine Schäden festgestellt.</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-3">
                    <strong>3:</strong>
                    Bau- oder Strukturmängel mit unwesentlichem Einfluss auf die Dichtheit, Hydraulik oder Statik der
                    Leitung.

                    <p>Zum Beispiel leichte Verformungen von Rohren aus synthetischen Materialien, leicht beschädigte
                        Rohrwand, Muffen mit breiten Fugen, schlecht verarbeiteter Anschluss, kleine
                        Materialablagerungen auf der Sohle usw.
                    </p>

                    <span class="action-text">Sanierungsmaßnahmen oder Wartungsarbeiten können langfristig geplant
                        werden.</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-2">
                    <strong>2:</strong>
                    Bau- oder Strukturmängel, die die Dichtheit, Hydraulik oder Statik beeinträchtigen.

                    <p>Zum Beispiel deutliche Verformungen von Rohren aus synthetischen Materialien, beschädigte
                        Rohrwand, Versatz an den Fugen, vorstehende oder nicht verarbeitete Anschlüsse, geringfügige
                        Risse, Verkalkungen, Materialablagerungen usw. Bauwerke wie versteckte oder abgedeckte Schächte.
                    </p>

                    <span class="action-text">Reparatur-/Sanierungsmaßnahmen oder Wartungsarbeiten sind mittelfristig
                        erforderlich (in der Regel innerhalb von 3 bis 5 Jahren).</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-1">
                    <strong>1:</strong>
                    Strukturschäden, die die Dichtheit, Hydraulik oder statische Sicherheit nicht mehr gewährleisten.

                    <p>Zum Beispiel schwere Verformungen und Quetschungen von Rohren, perforierte oder poröse Rohrwand,
                        Verbindungsstücke an Fugen, die den Boden freilegen sowie Wasseraustritt oder -infiltration
                        und/oder Wurzeln, stark vorstehende Abzweigungen, große und offene Brüche und Risse, Verkalkung,
                        Materialablagerungen usw.
                    </p>

                    <span class="action-text">Reparatur-/Sanierungsmaßnahmen oder Wartungsarbeiten sind dringend und
                        kurzfristig durchzuführen (in der Regel innerhalb von 1 bis 2 Jahren). Untersuchungen sollten in
                        Betracht gezogen werden.</span>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="gravity-level-0">
                    <strong>0:</strong>
                    Die Leitung ist bereits oder wird bald verstopft sein; die Leitung ist eingestürzt, vollständig
                    durch Wurzeln oder andere Hindernisse verstopft, die den Durchfluss und die Inspektion verhindern
                    und zu einem Rückstau oder einer Überschwemmung in Gebäuden führen können. Die Leitung undicht ist,
                    mit dem Risiko einer Grundwasserverschmutzung bei Abwasser- und Industrienetzen.

                    <span class="action-text">Reparatur-/Sanierungsmaßnahmen oder Wartungsarbeiten müssen dringend
                        durchgeführt werden. Vorläufige Reparaturen sollten in Betracht gezogen werden, um größere
                        Schäden zu vermeiden.</span>
                </div>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    @if ($mapImage = $report->reportImages->where('caption', 'Map')->first())
        <div class="header">
            <h2 style="margin: 0;">Karte des inspizierten Netzes</h2>
        </div>
        <div class="report-number">
            <span>TV-Bericht Nr. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                <h2 style="margin: 0;">Abschnitt {{ $section->name }}</h2>
            </div>
            <div class="report-number">
                <span>TV-Bericht Nr.
                    {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <!-- Add pipe section details -->
            <table class="section-details-table">
                <tr>
                    <td width="50%">
                        <div class="info-section">
                            <span class="label">Durchmesser:</span>
                            {{ $section->diameter }} mm
                        </div>
                        <div class="info-section">
                            <span class="label">Material:</span>
                            {{ ucfirst($section->material) }}
                        </div>
                        <div class="info-section">
                            <span class="label">Länge:</span>
                            {{ $section->length }} m
                        </div>
                    </td>
                    <td width="50%">
                        <div class="info-section">
                            <span class="label">Ausgangskammer:</span>
                            {{ $section->start_manhole }}
                        </div>
                        <div class="info-section">
                            <span class="label">Ankunftskammer:</span>
                            {{ $section->end_manhole }}
                        </div>
                        <div class="info-section">
                            <span class="label">Lokalisierung:</span>
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
                <h2 style="margin: 0;">Abschnitt {{ $section->name }}</h2>
            </div>
            <div class="report-number">
                <span>TV-Bericht Nr.
                    {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <!-- Section Information -  -->
            <table class="section-details-table">
                <tr>
                    <td class="section-details-left">
                        <div>
                            <span class="label">Inspektionsdatum:</span>
                            {{ $report->inspection_date ? date('d.m.Y', strtotime($report->inspection_date)) : $report->created_at->format('d.m.Y') }}
                        </div>
                        <div>
                            <span class="label">Auftragsnummer:</span>
                            {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <span class="label">Anwesende Person:</span>
                            {{ $report->creator->name }}
                        </div>
                    </td>
                    <td class="section-details-right">
                        <div>
                            <span class="label">Bediener:</span>
                            {{ $report->operator ?? $report->creator->name }}
                        </div>
                        <div>
                            <span class="label">Fahrzeug:</span>
                            Fahrzeug {{ $report->organization->name }}
                        </div>
                        <div>
                            <span class="label">Wetter:</span>
                            {{ $report->weather ?? '-' }}
                        </div>
                    </td>
                </tr>
                <tr class="section-full-row">
                    <td colspan="2">
                        <span class="label">Bemerkung:</span>
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
                                <th>Beobachtung</th>
                                <th>Entfernung (lm.)</th>
                                <th>Zähler</th>
                                <th>Anomalien / Bemerkungen</th>
                                <th>Schweregrad</th>
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
                        <h2 style="margin: 0;">Abschnitt {{ $section->name }}</h2>
                    </div>
                    <div class="report-number">
                        <span>TV-Bericht Nr.
                            {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <table>
                        <tr>
                            <td colspan="3" class="observation-header">
                                Beobachtung {{ $index + 1 }}
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span class="label">Entfernung:</span>
                                {{ $defect->coordinates['distance'] ?? '--' }} lm.
                            </td>
                            <td width="30%">
                                <span class="label">Zähler:</span>
                                {{ $defect->coordinates['counter'] ?? '--' }}
                            </td>
                            <td width="50%">
                                <span class="label">Wasserstand:</span>
                                {{ $defect->coordinates['water_level'] ?? '--' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="info-section">
                                    <span class="label">Befund:</span>
                                    {{ $defect->description }}
                                </div>
                                <div class="info-section">
                                    <span class="label">Bemerkung:</span>
                                    {{ $defect->coordinates['comment'] ?? '' }}
                                </div>
                            </td>
                            <td style="vertical-align: top;">
                                <div class="gravity-value">
                                    <span class="label">Schweregrad:</span>
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
                                        <p>Kein Bild verfügbar</p>
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
                    <p>Keine Mängel für diesen Abschnitt aufgezeichnet</p>
                </div>
            @endif
        @endforeach
    @endif

    <!-- Comments Section if included -->
    @if ($includeComments && count($report->reportComments) > 0)
        <div class="header">
            <h2 style="margin: 0;">Kommentare</h2>
        </div>
        <div class="report-number">
            <span>TV-Bericht Nr. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
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
        <h2 style="margin: 0;">Warnhinweise zur Analyse des TV-Berichts</h2>
    </div>
    <div class="report-number">
        <span>TV-Bericht Nr. {{ $report->report_number ?? str_pad($report->id, 4, '0', STR_PAD_LEFT) }}</span>
    </div>

    <ol>
        <li>
            Die Schweregrade jeder Beobachtung sind eine persönliche Einschätzung des Bedieners, die als Hilfe bei der
            Analyse des Zustands der Kanalisation angegeben werden. Es ist ratsam, den gesamten Abschnitt und
            gegebenenfalls das gesamte umliegende Abwassernetz zu berücksichtigen, bevor Maßnahmen ergriffen werden,
            sowie alle externen Faktoren (Kontext, geografische Lage, Verwendung und Durchfluss im Netz, Wetter usw.).
        </li>
        <li>
            Alle in dieser Datei enthaltenen Skizzen sind schematische Pläne, die auf der Grundlage von Beobachtungen
            während der Kamerainspektion erstellt wurden. Obwohl diese Informationen sorgfältig aufgezeichnet wurden,
            können Abweichungen von der Realität auftreten.
        </li>
        <li>
            Nur eine vorherige Reinigung der zu inspizierenden Rohrleitungen durch Hochdruckwasserstrahlen ermöglicht
            eine optimale Überprüfung des Zustands der Rohrleitungen. Wir können nicht für Mängel oder Elemente
            verantwortlich gemacht werden, die aufgrund einer fehlenden Reinigung nicht erkannt wurden. Eventuelle
            Mikrorisse können nicht immer erkannt werden.
        </li>
        <li>
            Die Fernsehinspektion ermöglicht nicht die Identifizierung aller Arten von Mängeln, insbesondere solcher,
            die sich außerhalb des Sichtfelds der Kamera befinden. In einigen Fällen kann eine zusätzliche Inspektion
            erforderlich sein, um eine vollständige Diagnose des Netzwerks zu erhalten.
        </li>
        <li>
            Die in diesem Bericht angegebenen Maße und Entfernungen dienen nur zu Informationszwecken und können
            aufgrund der verwendeten Geräte einen Fehlerspielraum aufweisen. Vor Beginn der Arbeiten wird eine
            Überprüfung vor Ort empfohlen.
        </li>
        <li>
            Die Interpretation der beobachteten Mängel sollte von einem qualifizierten Fachmann durchgeführt werden, der
            in der Lage ist, die Auswirkungen jeder Anomalie auf die Gesamtfunktion des Netzes und seine langfristige
            Haltbarkeit zu beurteilen.
        </li>
        <li>
            Die Zugangsbedingungen und der allgemeine Zustand des Netzes zum Zeitpunkt der Inspektion können die
            Qualität der Beobachtungen einschränken. Unzugängliche oder teilweise beobachtbare Segmente sind im Bericht
            klar gekennzeichnet.
        </li>
        <li>
            Dieser Bericht ist zum Zeitpunkt der Inspektion gültig. Die natürliche Entwicklung der Netze, spätere
            Eingriffe oder außergewöhnliche Witterungsbedingungen können den Zustand der Leitungen nach der Durchführung
            dieser Diagnose verändern.
        </li>
        <li>
            Die in diesem Bericht enthaltenen Empfehlungen basieren auf dem bei der Inspektion festgestellten Zustand
            und den geltenden Normen. Sie berücksichtigen keine möglichen regulatorischen Änderungen nach dem
            Ausstellungsdatum des Berichts.
        </li>
        <li>
            Die Klassifizierung der Mängel folgt einer standardisierten Methodik, kann aber je nach lokalen Praktiken
            und spezifischen Kundenanforderungen variieren. Eine Absprache mit dem Netzwerkmanager wird empfohlen, um
            die Interventionsprioritäten festzulegen.
        </li>
        <li>
            Die in diesem Bericht bereitgestellten Bilder und Videos sollen die erwähnten Beobachtungen
            veranschaulichen. Ihre Qualität kann durch die Beleuchtungsbedingungen, die Sichtbarkeit und die technischen
            Einschränkungen während der Inspektion beeinflusst werden.
        </li>
        <li>
            Dieser Bericht stellt keine Dimensionierungs- oder hydraulische Kapazitätsstudie des Netzwerks dar. Für die
            Bewertung dieser spezifischen Aspekte können zusätzliche Studien erforderlich sein.
        </li>
        <li>
            Die Restlebensdauer der Rohrleitungen kann nicht allein auf der Grundlage einer visuellen Inspektion genau
            bestimmt werden. In einigen Fällen können zusätzliche Materialanalysen empfohlen werden.
        </li>
        <li>
            Einige Mängel können sich nach der Inspektion schnell entwickeln, insbesondere bei Infiltrationen,
            Bodenbewegungen oder großen Oberflächenlasten. Für identifizierte kritische Mängel wird eine regelmäßige
            Überwachung empfohlen.
        </li>
        <li>
            Dieser Bericht sollte vom Eigentümer oder Manager des Netzwerks aufbewahrt und an jeden zukünftigen
            Teilnehmer weitergegeben werden, um die Rückverfolgbarkeit von Inspektionen und Eingriffen im betroffenen
            Netzwerk sicherzustellen.
        </li>
    </ol>

    <div class="signature">
        <p>Unterschrift des Technikers:</p>
    </div>

    <div class="page-footer">
        Pixel Perfect - Avenue de la Gare 1, 1880 Bex - Tel.: +41 (0)24 444 44 44 |
        Seite<span class="page-number">
    </div>
</body>

</html>
