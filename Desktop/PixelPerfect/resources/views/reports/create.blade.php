@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Create New Report') }}</h1>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Reports') }}
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold">{{ __('Errors occurred!') }}</div>
        <ul class="mt-3 mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" id="reportForm">
        @csrf

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- Report General Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>{{ __('Report Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold required">{{ __('Report Title') }}</label>
                            <input id="title" class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                        </div>

                        <!-- Report Number Field -->
                        <div class="mb-3">
                            <label for="report_number" class="form-label fw-bold">{{ __('Report Number') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">TV n°</span>
                                <input id="report_number" class="form-control" type="text" name="report_number" value="{{ old('report_number', sprintf('%04d', $nextReportNumber ?? 1)) }}">
                            </div>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('Description') }}</label>
                            <textarea id="description" name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <!-- Date Field -->
                        <div class="mb-3">
                            <label for="inspection_date" class="form-label fw-bold">{{ __('Inspection Date') }}</label>
                            <input type="date" class="form-control" id="inspection_date" name="inspection_date" value="{{ old('inspection_date', date('Y-m-d')) }}">
                        </div>

                        <!-- Operator Field -->
                        <div class="mb-3">
                            <label for="operator" class="form-label fw-bold">{{ __('Operator') }}</label>
                            <input type="text" class="form-control" id="operator" name="operator" value="{{ old('operator', Auth::user()->name) }}">
                        </div>

                        <!-- Organization Field -->
                        @if(auth()->user()->role->name == 'Administrator')
                        <div class="mb-3">
                            <label for="organization_id" class="form-label fw-bold">{{ __('Organization') }}</label>
                            <select id="organization_id" name="organization_id" class="form-select" required>
                                <option value="">{{ __('Select Organization') }}</option>
                                @foreach(\App\Models\Organization::all() as $organization)
                                    <option value="{{ $organization->id }}" {{ old('organization_id', Auth::user()->organization_id) == $organization->id ? 'selected' : '' }}>
                                        {{ $organization->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Language Field -->
                        <div class="mb-3">
                            <label for="language" class="form-label fw-bold">{{ __('Report Language') }}</label>
                            <select id="language" name="language" class="form-select">
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="fr" {{ old('language', 'fr') == 'fr' ? 'selected' : '' }}>French</option>
                                <option value="de" {{ old('language') == 'de' ? 'selected' : '' }}>German</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Project Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-project-diagram me-2 text-primary"></i>{{ __('Project Details') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Client Field -->
                        <div class="mb-3">
                            <label for="client" class="form-label fw-bold">{{ __('Client') }}</label>
                            <input type="text" class="form-control" id="client" name="client" value="{{ old('client') }}">
                        </div>

                        <!-- Project Location -->
                        <div class="mb-3">
                            <label for="location" class="form-label fw-bold">{{ __('Location') }}</label>
                            <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}">
                        </div>

                        <!-- Intervention Reason -->
                        <div class="mb-3">
                            <label for="intervention_reason" class="form-label fw-bold">{{ __('Reason for Intervention') }}</label>
                            <select id="intervention_reason" name="intervention_reason" class="form-select">
                                <option value="control" {{ old('intervention_reason') == 'control' ? 'selected' : '' }}>{{ __('Network state control') }}</option>
                                <option value="maintenance" {{ old('intervention_reason') == 'maintenance' ? 'selected' : '' }}>{{ __('Maintenance') }}</option>
                                <option value="blockage" {{ old('intervention_reason') == 'blockage' ? 'selected' : '' }}>{{ __('Blockage clearance') }}</option>
                                <option value="inspection" {{ old('intervention_reason') == 'inspection' ? 'selected' : '' }}>{{ __('Periodic inspection') }}</option>
                                <option value="other" {{ old('intervention_reason') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                        </div>

                        <!-- Weather Conditions -->
                        <div class="mb-3">
                            <label for="weather" class="form-label fw-bold">{{ __('Weather Conditions') }}</label>
                            <select id="weather" name="weather" class="form-select">
                                <option value="sunny" {{ old('weather') == 'sunny' ? 'selected' : '' }}>{{ __('Sunny') }}</option>
                                <option value="cloudy" {{ old('weather') == 'cloudy' ? 'selected' : '' }}>{{ __('Cloudy') }}</option>
                                <option value="rainy" {{ old('weather') == 'rainy' ? 'selected' : '' }}>{{ __('Rainy') }}</option>
                                <option value="snow" {{ old('weather') == 'snow' ? 'selected' : '' }}>{{ __('Snow') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Network Map Upload -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-map me-2 text-primary"></i>{{ __('Network Map') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="map_image" class="form-label fw-bold">{{ __('Upload Network Map') }}</label>
                            <input type="file" class="form-control" id="map_image" name="map_image" accept="image/*">
                            <small class="text-muted">{{ __('Upload a map or overview drawing of the inspected network') }}</small>
                        </div>
                        <div id="map-preview" class="mt-3 d-none">
                            <div class="card">
                                <div class="card-body p-0">
                                    <img id="map-preview-img" class="img-fluid" alt="Network Map Preview">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Middle Column -->
            <div class="col-lg-4">
                <!-- Defects Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-primary"></i>{{ __('Defects') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addDefectBtn">
                            <i class="fas fa-plus me-1"></i> {{ __('Add Defect') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="defects-container">
                            <div class="defect-item mb-4 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">
                                        <span class="badge bg-secondary me-2">1</span>{{ __('Defect') }} #1
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-defect-btn" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold required">{{ __('Defect Type') }}</label>
                                        <select name="defects[0][defect_type_id]" class="form-select" required>
                                            <option value="">{{ __('Select Type') }}</option>
                                            @foreach(\App\Models\DefectType::all() as $defectType)
                                                <option value="{{ $defectType->id }}">
                                                    {{ $defectType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold required">{{ __('Severity') }}</label>
                                        <select name="defects[0][severity]" class="form-select defect-severity" required>
                                            <option value="low" data-value="4">{{ __('Low (4)') }}</option>
                                            <option value="medium" data-value="3">{{ __('Medium (3)') }}</option>
                                            <option value="high" data-value="2">{{ __('High (2)') }}</option>
                                            <option value="critical" data-value="1">{{ __('Critical (1)') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-bold required">{{ __('Description') }}</label>
                                        <textarea name="defects[0][description]" rows="2" class="form-control" required></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Distance (m)') }}</label>
                                        <input name="defects[0][coordinates][distance]" type="text" class="form-control" placeholder="e.g. 12.5">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Counter') }}</label>
                                        <input name="defects[0][coordinates][counter]" type="text" class="form-control" placeholder="e.g. 00:01:30">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Water Level') }}</label>
                                        <select name="defects[0][coordinates][water_level]" class="form-select">
                                            <option value="dry">{{ __('Dry') }}</option>
                                            <option value="<5%">{{ __('<5%') }}</option>
                                            <option value="5%">{{ __('5%') }}</option>
                                            <option value="10%">{{ __('10%') }}</option>
                                            <option value="25%">{{ __('25%') }}</option>
                                            <option value="50%">{{ __('50%') }}</option>
                                            <option value="75%">{{ __('75%') }}</option>
                    <option value="100%">{{ __('100%') }}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Reference Code') }}</label>
                <input name="defects[INDEX][coordinates][reference]" type="text" class="form-control" placeholder="e.g. GR3">
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('Additional Comments') }}</label>
                <input name="defects[INDEX][coordinates][comment]" type="text" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Latitude') }}</label>
                <input name="defects[INDEX][coordinates][latitude]" type="text" class="form-control" placeholder="e.g. 45.123456">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Longitude') }}</label>
                <input name="defects[INDEX][coordinates][longitude]" type="text" class="form-control" placeholder="e.g. -73.123456">
            </div>

            <div class="col-12">
                <label class="form-label">{{ __('Defect Image') }}</label>
                <input type="file" class="form-control defect-image-input" name="defect_images[INDEX]" accept="image/*" data-preview="defect-preview-INDEX">
                <div class="mt-2 d-none defect-image-preview" id="defect-preview-INDEX">
                    <img src="" class="img-fluid rounded" style="max-height: 150px">
                </div>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="defects[INDEX][mark_on_map]" id="mark_on_map_INDEX" value="1">
                    <label class="form-check-label" for="mark_on_map_INDEX">
                        {{ __('Mark this defect on the network map') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
">{{ __('75%') }}</option>
                                            <option value="100%">{{ __('100%') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Reference Code') }}</label>
                                        <input name="defects[0][coordinates][reference]" type="text" class="form-control" placeholder="e.g. GR3">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('Additional Comments') }}</label>
                                        <input name="defects[0][coordinates][comment]" type="text" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Latitude') }}</label>
                                        <input name="defects[0][coordinates][latitude]" type="text" class="form-control" placeholder="e.g. 45.123456">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Longitude') }}</label>
                                        <input name="defects[0][coordinates][longitude]" type="text" class="form-control" placeholder="e.g. -73.123456">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">{{ __('Defect Image') }}</label>
                                        <input type="file" class="form-control defect-image-input" name="defect_images[0]" accept="image/*" data-preview="defect-preview-0">
                                        <div class="mt-2 d-none defect-image-preview" id="defect-preview-0">
                                            <img src="" class="img-fluid rounded" style="max-height: 150px">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="defects[0][mark_on_map]" id="mark_on_map_0" value="1">
                                            <label class="form-check-label" for="mark_on_map_0">
                                                {{ __('Mark this defect on the network map') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info" id="no-defects-message" style="display: none;">
                            <i class="fas fa-info-circle"></i> {{ __('No defects added yet. Click "Add Defect" to begin documenting pipe issues.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Image Upload Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-images me-2 text-primary"></i>{{ __('Report Images') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="images" class="form-label fw-bold">{{ __('Upload Additional Images') }}</label>
                            <div class="mt-1 d-flex justify-content-center p-4 border border-2 border-dashed rounded" id="image-drop-area">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-secondary"></i>
                                    </div>
                                    <div class="text-sm">
                                        <label for="report_images" class="btn btn-sm btn-primary mb-2">
                                            <i class="fas fa-upload me-1"></i> {{ __('Upload Images') }}
                                            <input id="report_images" name="report_images[]" type="file" class="d-none" multiple accept="image/*">
                                        </label>
                                        <p class="mb-0 text-muted">{{ __('or drag and drop') }}</p>
                                        <p class="small text-muted mt-1">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                </div>
                            </div>
                            <div id="image-preview" class="mt-3 row g-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Tronçon/Section Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-road me-2 text-primary"></i>{{ __('Pipe Section Details') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addSectionBtn">
                            <i class="fas fa-plus me-1"></i> {{ __('Add Section') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="sections-container">
                            <div class="section-item mb-4 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">
                                        <span class="badge bg-secondary me-2">1</span>{{ __('Tronçon') }} #1
                                    </h6>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-section-btn" disabled>
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Section Name/Number') }}</label>
                                        <input name="sections[0][name]" type="text" class="form-control" value="Tronçon 1">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Pipe Diameter (mm)') }}</label>
                                        <input name="sections[0][diameter]" type="number" class="form-control" value="250">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Material') }}</label>
                                        <select name="sections[0][material]" class="form-select">
                                            <option value="concrete">{{ __('Concrete') }}</option>
                                            <option value="pvc">{{ __('PVC') }}</option>
                                            <option value="hdpe">{{ __('HDPE') }}</option>
                                            <option value="cast_iron">{{ __('Cast Iron') }}</option>
                                            <option value="clay">{{ __('Clay') }}</option>
                                            <option value="steel">{{ __('Steel') }}</option>
                                            <option value="other">{{ __('Other') }}</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Length (m)') }}</label>
                                        <input name="sections[0][length]" type="text" class="form-control" placeholder="e.g. 45.8">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Starting Chamber/Manhole') }}</label>
                                        <input name="sections[0][start_manhole]" type="text" class="form-control" placeholder="e.g. Manhole 1">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">{{ __('Ending Chamber/Manhole') }}</label>
                                        <input name="sections[0][end_manhole]" type="text" class="form-control" placeholder="e.g. Manhole 2">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('Location/Street') }}</label>
                                        <input name="sections[0][location]" type="text" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">{{ __('Comments') }}</label>
                                        <textarea name="sections[0][comments]" rows="2" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PDF Generation Options -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-file-pdf me-2 text-primary"></i>{{ __('PDF Generation Options') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="include_cover_page" id="include_cover_page" value="1" checked>
                            <label class="form-check-label" for="include_cover_page">
                                {{ __('Include cover page') }}
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="include_summary" id="include_summary" value="1" checked>
                            <label class="form-check-label" for="include_summary">
                                {{ __('Include summary section') }}
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="include_map" id="include_map" value="1" checked>
                            <label class="form-check-label" for="include_map">
                                {{ __('Include network map') }}
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="include_images" id="include_images" value="1" checked>
                            <label class="form-check-label" for="include_images">
                                {{ __('Include defect images') }}
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="include_comments" id="include_comments" value="1" checked>
                            <label class="form-check-label" for="include_comments">
                                {{ __('Include comments in PDF') }}
                            </label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('Available Languages for PDF') }}</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="generate_languages[]" id="lang_fr" value="fr" checked>
                                    <label class="form-check-label" for="lang_fr">{{ __('French') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="generate_languages[]" id="lang_en" value="en">
                                    <label class="form-check-label" for="lang_en">{{ __('English') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="generate_languages[]" id="lang_de" value="de">
                                    <label class="form-check-label" for="lang_de">{{ __('German') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
            </a>
            <button type="submit" class="btn btn-primary" id="submit-report">
                <i class="fas fa-save me-1"></i> {{ __('Create Report') }}
            </button>
        </div>
    </form>
</div>

<!-- Templates for JavaScript -->
<template id="defect-template">
    <div class="defect-item mb-4 pb-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">
                <span class="badge bg-secondary me-2">INDEX</span>{{ __('Defect') }} #INDEX
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-defect-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold required">{{ __('Defect Type') }}</label>
                <select name="defects[INDEX][defect_type_id]" class="form-select" required>
                    <option value="">{{ __('Select Type') }}</option>
                    @foreach(\App\Models\DefectType::all() as $defectType)
                        <option value="{{ $defectType->id }}">
                            {{ $defectType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold required">{{ __('Severity') }}</label>
                <select name="defects[INDEX][severity]" class="form-select defect-severity" required>
                    <option value="low" data-value="4">{{ __('Low (4)') }}</option>
                    <option value="medium" data-value="3">{{ __('Medium (3)') }}</option>
                    <option value="high" data-value="2">{{ __('High (2)') }}</option>
                    <option value="critical" data-value="1">{{ __('Critical (1)') }}</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold required">{{ __('Description') }}</label>
                <textarea name="defects[INDEX][description]" rows="2" class="form-control" required></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Distance (m)') }}</label>
                <input name="defects[INDEX][coordinates][distance]" type="text" class="form-control" placeholder="e.g. 12.5">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Counter') }}</label>
                <input name="defects[INDEX][coordinates][counter]" type="text" class="form-control" placeholder="e.g. 00:01:30">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Water Level') }}</label>
                <select name="defects[INDEX][coordinates][water_level]" class="form-select">
                    <option value="dry">{{ __('Dry') }}</option>
                    <option value="<5%">{{ __('<5%') }}</option>
                    <option value="5%">{{ __('5%') }}</option>
                    <option value="10%">{{ __('10%') }}</option>
                    <option value="25%">{{ __('25%') }}</option>
                    <option value="50%">{{ __('50%') }}</option>
                    <option value="75%">{{ __('75%') }}</option>
                    <option value="100%">{{ __('100%') }}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Reference Code') }}</label>
                <input name="defects[INDEX][coordinates][reference]" type="text" class="form-control" placeholder="e.g. GR3">
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('Additional Comments') }}</label>
                <input name="defects[INDEX][coordinates][comment]" type="text" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Latitude') }}</label>
                <input name="defects[INDEX][coordinates][latitude]" type="text" class="form-control" placeholder="e.g. 45.123456">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Longitude') }}</label>
                <input name="defects[INDEX][coordinates][longitude]" type="text" class="form-control" placeholder="e.g. -73.123456">
            </div>

            <div class="col-12">
                <label class="form-label">{{ __('Defect Image') }}</label>
                <input type="file" class="form-control defect-image-input" name="defect_images[INDEX]" accept="image/*" data-preview="defect-preview-INDEX">
                <div class="mt-2 d-none defect-image-preview" id="defect-preview-INDEX">
                    <img src="" class="img-fluid rounded" style="max-height: 150px">
                </div>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="defects[INDEX][mark_on_map]" id="mark_on_map_INDEX" value="1">
                    <label class="form-check-label" for="mark_on_map_INDEX">
                        {{ __('Mark this defect on the network map') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="section-template">
    <div class="section-item mb-4 pb-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">
                <span class="badge bg-secondary me-2">INDEX</span>{{ __('Tronçon') }} #INDEX
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-section-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Section Name/Number') }}</label>
                <input name="sections[INDEX][name]" type="text" class="form-control" value="Tronçon INDEX">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Pipe Diameter (mm)') }}</label>
                <input name="sections[INDEX][diameter]" type="number" class="form-control" value="250">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Material') }}</label>
                <select name="sections[INDEX][material]" class="form-select">
                    <option value="concrete">{{ __('Concrete') }}</option>
                    <option value="pvc">{{ __('PVC') }}</option>
                    <option value="hdpe">{{ __('HDPE') }}</option>
                    <option value="cast_iron">{{ __('Cast Iron') }}</option>
                    <option value="clay">{{ __('Clay') }}</option>
                    <option value="steel">{{ __('Steel') }}</option>
                    <option value="other">{{ __('Other') }}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Length (m)') }}</label>
                <input name="sections[INDEX][length]" type="text" class="form-control" placeholder="e.g. 45.8">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Starting Chamber/Manhole') }}</label>
                <input name="sections[INDEX][start_manhole]" type="text" class="form-control" placeholder="e.g. Manhole 1">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Ending Chamber/Manhole') }}</label>
                <input name="sections[INDEX][end_manhole]" type="text" class="form-control" placeholder="e.g. Manhole 2">
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('Location/Street') }}</label>
                <input name="sections[INDEX][location]" type="text" class="form-control">
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('Comments') }}</label>
                <textarea name="sections[INDEX][comments]" rows="2" class="form-control"></textarea>
            </div>
        </div>
    </div>
</template>

<!-- JavaScript for Form Operations -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let defectCount = 1;
        let sectionCount = 1;

        // Setup defect management
        const defectsContainer = document.getElementById('defects-container');
        const addDefectBtn = document.getElementById('addDefectBtn');
        const defectTemplate = document.getElementById('defect-template');

        // Setup section management
        const sectionsContainer = document.getElementById('sections-container');
        const addSectionBtn = document.getElementById('addSectionBtn');
        const sectionTemplate = document.getElementById('section-template');

        // Setup image preview for map
        const mapImageInput = document.getElementById('map_image');
        const mapPreview = document.getElementById('map-preview');
        const mapPreviewImg = document.getElementById('map-preview-img');

        // Setup image preview for report images
        const reportImagesInput = document.getElementById('report_images');
        const imagePreview = document.getElementById('image-preview');

        // Image drag and drop area
        const dropArea = document.getElementById('image-drop-area');

        // Add a new defect
        function addDefect() {
            // Clone template
            const template = defectTemplate.content.cloneNode(true);

            // Replace INDEX with actual count
            const elements = template.querySelectorAll('[name*="INDEX"]');
            elements.forEach(element => {
                element.name = element.name.replace(/INDEX/g, defectCount);
            });

            // Update other INDEX references
            const badges = template.querySelectorAll('.badge');
            badges.forEach(badge => {
                badge.textContent = defectCount + 1;
            });

            const headers = template.querySelectorAll('h6');
            headers.forEach(header => {
                header.innerHTML = header.innerHTML.replace(/INDEX/g, defectCount + 1);
            });

            const previews = template.querySelectorAll('[data-preview]');
            previews.forEach(preview => {
                preview.setAttribute('data-preview', preview.getAttribute('data-preview').replace(/INDEX/g, defectCount));
            });

            const checkboxes = template.querySelectorAll('[id*="INDEX"]');
            checkboxes.forEach(checkbox => {
                checkbox.id = checkbox.id.replace(/INDEX/g, defectCount);
            });

            const labels = template.querySelectorAll('[for*="INDEX"]');
            labels.forEach(label => {
                label.setAttribute('for', label.getAttribute('for').replace(/INDEX/g, defectCount));
            });

            // Add event listener to the new remove button
            const removeBtn = template.querySelector('.remove-defect-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('.defect-item').remove();
                updateDefectNumbers();
            });

            // Add event listener to the new image input
            const imageInput = template.querySelector('.defect-image-input');
            imageInput.addEventListener('change', handleDefectImagePreview);

            // Add to container
            defectsContainer.appendChild(template);

            // Increment counter
            defectCount++;

            // Enable all remove buttons
            document.querySelectorAll('.remove-defect-btn').forEach(btn => {
                btn.disabled = defectsContainer.querySelectorAll('.defect-item').length <= 1;
            });

            // Hide no defects message
            document.getElementById('no-defects-message').style.display = 'none';
        }

        // Update defect numbers after removal
        function updateDefectNumbers() {
            const defectItems = defectsContainer.querySelectorAll('.defect-item');

            defectItems.forEach((item, index) => {
                const badge = item.querySelector('.badge');
                const header = item.querySelector('h6');

                badge.textContent = index + 1;
                header.innerHTML = header.innerHTML.replace(/#\d+/, '#' + (index + 1));

                // Also update the names to prevent gaps in indexes
                const inputs = item.querySelectorAll('[name*="defects["]');
                inputs.forEach(input => {
                    input.name = input.name.replace(/defects\[\d+\]/, 'defects[' + index + ']');
                });

                const imageInputs = item.querySelectorAll('[name*="defect_images["]');
                imageInputs.forEach(input => {
                    input.name = input.name.replace(/defect_images\[\d+\]/, 'defect_images[' + index + ']');
                });
            });

            // Show no defects message if empty
            if (defectItems.length === 0) {
                document.getElementById('no-defects-message').style.display = 'block';
            }

            // Update counter
            defectCount = defectItems.length;
        }

        // Add a new section
        function addSection() {
            // Clone template
            const template = sectionTemplate.content.cloneNode(true);

            // Replace INDEX with actual count
            const elements = template.querySelectorAll('[name*="INDEX"]');
            elements.forEach(element => {
                element.name = element.name.replace(/INDEX/g, sectionCount);
            });

            // Update other INDEX references
            const badges = template.querySelectorAll('.badge');
            badges.forEach(badge => {
                badge.textContent = sectionCount + 1;
            });

            const headers = template.querySelectorAll('h6');
            headers.forEach(header => {
                header.innerHTML = header.innerHTML.replace(/INDEX/g, sectionCount + 1);
            });

            // Update value of section name
            const nameInput = template.querySelector('[name*="name"]');
            if (nameInput) {
                nameInput.value = nameInput.value.replace(/INDEX/g, sectionCount + 1);
            }

            // Add event listener to the new remove button
            const removeBtn = template.querySelector('.remove-section-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('.section-item').remove();
                updateSectionNumbers();
            });

            // Add to container
            sectionsContainer.appendChild(template);

            // Increment counter
            sectionCount++;

            // Enable all remove buttons
            document.querySelectorAll('.remove-section-btn').forEach(btn => {
                btn.disabled = sectionsContainer.querySelectorAll('.section-item').length <= 1;
            });
        }

        // Update section numbers after removal
        function updateSectionNumbers() {
            const sectionItems = sectionsContainer.querySelectorAll('.section-item');

            sectionItems.forEach((item, index) => {
                const badge = item.querySelector('.badge');
                const header = item.querySelector('h6');

                badge.textContent = index + 1;
                header.innerHTML = header.innerHTML.replace(/#\d+/, '#' + (index + 1));

                // Also update the names to prevent gaps in indexes
                const inputs = item.querySelectorAll('[name*="sections["]');
                inputs.forEach(input => {
                    input.name = input.name.replace(/sections\[\d+\]/, 'sections[' + index + ']');
                });

                // Update tronçon name if it follows the standard format
                const nameInput = item.querySelector('[name*="name"]');
                if (nameInput && nameInput.value.match(/^Tronçon \d+$/)) {
                    nameInput.value = 'Tronçon ' + (index + 1);
                }
            });

            // Update counter
            sectionCount = sectionItems.length;
        }

        // Handle defect image preview
        function handleDefectImagePreview(e) {
            const previewId = this.getAttribute('data-preview');
            const previewContainer = document.getElementById(previewId);

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = previewContainer.querySelector('img');
                    img.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.classList.add('d-none');
            }
        }

        // Handle map image preview
        if (mapImageInput) {
            mapImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        mapPreviewImg.src = e.target.result;
                        mapPreview.classList.remove('d-none');
                    }

                    reader.readAsDataURL(this.files[0]);
                } else {
                    mapPreview.classList.add('d-none');
                }
            });
        }

        // Handle report images preview
        if (reportImagesInput) {
            reportImagesInput.addEventListener('change', function() {
                handleReportImagesPreview(this.files);
            });
        }

        function handleReportImagesPreview(files) {
            if (!files || files.length === 0) return;

            imagePreview.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                if (!file.type.match('image.*')) return;

                const reader = new FileReader();

                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-3';

                    const card = document.createElement('div');
                    card.className = 'card h-100';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'card-img-top';
                    img.style.height = '160px';
                    img.style.objectFit = 'cover';

                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body p-2';

                    const caption = document.createElement('div');
                    caption.className = 'mb-2';

                    const captionInput = document.createElement('input');
                    captionInput.type = 'text';
                    captionInput.className = 'form-control form-control-sm';
                    captionInput.name = `report_image_captions[${index}]`;
                    captionInput.placeholder = 'Caption for this image';

                    caption.appendChild(captionInput);

                    const fileName = document.createElement('p');
                    fileName.className = 'small text-muted mb-0 text-truncate';
                    fileName.textContent = file.name;

                    cardBody.appendChild(caption);
                    cardBody.appendChild(fileName);
                    card.appendChild(img);
                    card.appendChild(cardBody);
                    col.appendChild(card);
                    imagePreview.appendChild(col);
                };

                reader.readAsDataURL(file);
            });
        }

        // Setup drag and drop for images
        if (dropArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, function() {
                    this.classList.add('border-primary');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, function() {
                    this.classList.remove('border-primary');
                }, false);
            });

            dropArea.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (reportImagesInput) {
                    // Create a new DataTransfer object
                    const dataTransfer = new DataTransfer();

                    // Add dropped files
                    Array.from(files).forEach(file => {
                        dataTransfer.items.add(file);
                    });

                    // Set the files to the input
                    reportImagesInput.files = dataTransfer.files;

                    // Trigger change event
                    reportImagesInput.dispatchEvent(new Event('change'));
                }

                handleReportImagesPreview(files);
            }, false);
        }

        // Add event listeners to initial elements
        document.querySelectorAll('.defect-image-input').forEach(input => {
            input.addEventListener('change', handleDefectImagePreview);
        });

        document.querySelectorAll('.remove-defect-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.defect-item').remove();
                updateDefectNumbers();
            });
        });

        document.querySelectorAll('.remove-section-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.section-item').remove();
                updateSectionNumbers();
            });
        });

        // Add button events
        if (addDefectBtn) {
            addDefectBtn.addEventListener('click', addDefect);
        }

        if (addSectionBtn) {
            addSectionBtn.addEventListener('click', addSection);
        }

        // Form validation
        const reportForm = document.getElementById('reportForm');

        if (reportForm) {
            reportForm.addEventListener('submit', function(e) {
                // Check if at least one defect is added
                if (defectsContainer.querySelectorAll('.defect-item').length === 0) {
                    e.preventDefault();
                    alert("{{ __('Please add at least one defect to the report.') }}");
                    return false;
                }

                // Check required fields
                const requiredFields = this.querySelectorAll('[required]');
                let missingFields = false;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        missingFields = true;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (missingFields) {
                    e.preventDefault();
                    alert("{{ __('Please fill in all required fields.') }}");
                    return false;
                }

                // Success - just before submitting, show a loading spinner
                document.getElementById('submit-report').innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __("Processing...") }}';
                document.getElementById('submit-report').disabled = true;
            });
        }
    });
</script>

<style>
    .required:after {
        content: " *";
        color: red;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    #image-drop-area {
        transition: all 0.3s ease;
    }
    #image-drop-area.border-primary {
        background-color: rgba(0, 123, 255, 0.1);
    }
</style>
    <div class="defect-item mb-4 pb-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">
                <span class="badge bg-secondary me-2">INDEX</span>{{ __('Defect') }} #INDEX
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-defect-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold required">{{ __('Defect Type') }}</label>
                <select name="defects[INDEX][defect_type_id]" class="form-select" required>
                    <option value="">{{ __('Select Type') }}</option>
                    @foreach(\App\Models\DefectType::all() as $defectType)
                        <option value="{{ $defectType->id }}">
                            {{ $defectType->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold required">{{ __('Severity') }}</label>
                <select name="defects[INDEX][severity]" class="form-select defect-severity" required>
                    <option value="low" data-value="4">{{ __('Low (4)') }}</option>
                    <option value="medium" data-value="3">{{ __('Medium (3)') }}</option>
                    <option value="high" data-value="2">{{ __('High (2)') }}</option>
                    <option value="critical" data-value="1">{{ __('Critical (1)') }}</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold required">{{ __('Description') }}</label>
                <textarea name="defects[INDEX][description]" rows="2" class="form-control" required></textarea>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Distance (m)') }}</label>
                <input name="defects[INDEX][coordinates][distance]" type="text" class="form-control" placeholder="e.g. 12.5">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Counter') }}</label>
                <input name="defects[INDEX][coordinates][counter]" type="text" class="form-control" placeholder="e.g. 00:01:30">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Water Level') }}</label>
                <select name="defects[INDEX][coordinates][water_level]" class="form-select">
                    <option value="dry">{{ __('Dry') }}</option>
                    <option value="<5%">{{ __('<5%') }}</option>
                    <option value="5%">{{ __('5%') }}</option>
                    <option value="10%">{{ __('10%') }}</option>
                    <option value="25%">{{ __('25%') }}</option>
                    <option value="50%">{{ __('50%') }}</option>
                    <option value="75%">{{ __('75%') }}</option>
                    <option value="100%">{{ __('100%') }}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Reference Code') }}</label>
                <input name="defects[INDEX][coordinates][reference]" type="text" class="form-control" placeholder="e.g. GR3">
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('Additional Comments') }}</label>
                <input name="defects[INDEX][coordinates][comment]" type="text" class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Latitude') }}</label>
                <input name="defects[INDEX][coordinates][latitude]" type="text" class="form-control" placeholder="e.g. 45.123456">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Longitude') }}</label>
                <input name="defects[INDEX][coordinates][longitude]" type="text" class="form-control" placeholder="e.g. -73.123456">
            </div>

            <div class="col-12">
                <label class="form-label">{{ __('Defect Image') }}</label>
                <input type="file" class="form-control defect-image-input" name="defect_images[INDEX]" accept="image/*" data-preview="defect-preview-INDEX">
                <div class="mt-2 d-none defect-image-preview" id="defect-preview-INDEX">
                    <img src="" class="img-fluid rounded" style="max-height: 150px">
                </div>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="defects[INDEX][mark_on_map]" id="mark_on_map_INDEX" value="1">
                    <label class="form-check-label" for="mark_on_map_INDEX">
                        {{ __('Mark this defect on the network map') }}
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="section-template">
    <div class="section-item mb-4 pb-3 border-bottom">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-bold mb-0">
                <span class="badge bg-secondary me-2">INDEX</span>{{ __('Tronçon') }} #INDEX
            </h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-section-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Section Name/Number') }}</label>
                <input name="sections[INDEX][name]" type="text" class="form-control" value="Tronçon INDEX">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Pipe Diameter (mm)') }}</label>
                <input name="sections[INDEX][diameter]" type="number" class="form-control" value="250">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Material') }}</label>
                <select name="sections[INDEX][material]" class="form-select">
                    <option value="concrete">{{ __('Concrete') }}</option>
                    <option value="pvc">{{ __('PVC') }}</option>
                    <option value="hdpe">{{ __('HDPE') }}</option>
                    <option value="cast_iron">{{ __('Cast Iron') }}</option>
                    <option value="clay">{{ __('Clay') }}</option>
                    <option value="steel">{{ __('Steel') }}</option>
                    <option value="other">{{ __('Other') }}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">{{ __('Length (m)') }}</label>
                <input name="sections[INDEX][length]" type="text" class="form-control" placeholder="e.g. 45.8">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Starting Chamber/Manhole') }}</label>
                <input name="sections[INDEX][start_manhole]" type="text" class="form-control" placeholder="e.g. Manhole 1">
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Ending Chamber/Manhole') }}</label>
                <input name="sections[INDEX][end_manhole]" type="text" class="form-control" placeholder="e.g. Manhole 2">
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('Location/Street') }}</label>
                <input name="sections[INDEX][location]" type="text" class="form-control">
            </div>

            <div class="col-md-12">
                <label class="form-label">{{ __('Comments') }}</label>
                <textarea name="sections[INDEX][comments]" rows="2" class="form-control"></textarea>
            </div>
        </div>
    </div>
</template>

<!-- JavaScript for Form Operations -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let defectCount = 1;
        let sectionCount = 1;

        // Setup defect management
        const defectsContainer = document.getElementById('defects-container');
        const addDefectBtn = document.getElementById('addDefectBtn');
        const defectTemplate = document.getElementById('defect-template');
        const noDefectsMessage = document.getElementById('no-defects-message');

        // Setup section management
        const sectionsContainer = document.getElementById('sections-container');
        const addSectionBtn = document.getElementById('addSectionBtn');
        const sectionTemplate = document.getElementById('section-template');

        // Setup image preview for map
        const mapImageInput = document.getElementById('map_image');
        const mapPreview = document.getElementById('map-preview');
        const mapPreviewImg = document.getElementById('map-preview-img');

        // Setup image preview for report images
        const reportImagesInput = document.getElementById('report_images');
        const imagePreview = document.getElementById('image-preview');

        // Image drag and drop area
        const dropArea = document.getElementById('image-drop-area');

        // Add a new defect
        function addDefect() {
            // Clone template
            const template = defectTemplate.content.cloneNode(true);

            // Replace INDEX with actual count
            const elements = template.querySelectorAll('[name*="INDEX"]');
            elements.forEach(element => {
                element.name = element.name.replace(/INDEX/g, defectCount);
            });

            // Update other INDEX references
            const badges = template.querySelectorAll('.badge');
            badges.forEach(badge => {
                badge.textContent = defectCount + 1;
            });

            const headers = template.querySelectorAll('h6');
            headers.forEach(header => {
                header.innerHTML = header.innerHTML.replace(/INDEX/g, defectCount + 1);
            });

            const previews = template.querySelectorAll('[data-preview]');
            previews.forEach(preview => {
                preview.setAttribute('data-preview', preview.getAttribute('data-preview').replace(/INDEX/g, defectCount));
            });

            const previewDivs = template.querySelectorAll('[id*="defect-preview-INDEX"]');
            previewDivs.forEach(div => {
                div.id = div.id.replace(/INDEX/g, defectCount);
            });

            const checkboxes = template.querySelectorAll('[id*="INDEX"]');
            checkboxes.forEach(checkbox => {
                checkbox.id = checkbox.id.replace(/INDEX/g, defectCount);
            });

            const labels = template.querySelectorAll('[for*="INDEX"]');
            labels.forEach(label => {
                label.setAttribute('for', label.getAttribute('for').replace(/INDEX/g, defectCount));
            });

            // Add event listener to the new remove button
            const removeBtn = template.querySelector('.remove-defect-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('.defect-item').remove();
                updateDefectNumbers();
            });

            // Add event listener to the new image input
            const imageInput = template.querySelector('.defect-image-input');
            imageInput.addEventListener('change', handleDefectImagePreview);

            // Add to container
            defectsContainer.appendChild(template);

            // Increment counter
            defectCount++;

            // Enable all remove buttons
            document.querySelectorAll('.remove-defect-btn').forEach(btn => {
                btn.disabled = defectsContainer.querySelectorAll('.defect-item').length <= 1;
            });

            // Hide no defects message
            if (noDefectsMessage) {
                noDefectsMessage.style.display = 'none';
            }
        }

        // Update defect numbers after removal
        function updateDefectNumbers() {
            const defectItems = defectsContainer.querySelectorAll('.defect-item');

            defectItems.forEach((item, index) => {
                const badge = item.querySelector('.badge');
                const header = item.querySelector('h6');

                badge.textContent = index + 1;
                header.innerHTML = header.innerHTML.replace(/#\d+/, '#' + (index + 1));

                // Also update the names to prevent gaps in indexes
                const inputs = item.querySelectorAll('[name*="defects["]');
                inputs.forEach(input => {
                    input.name = input.name.replace(/defects\[\d+\]/, 'defects[' + index + ']');
                });

                const imageInputs = item.querySelectorAll('[name*="defect_images["]');
                imageInputs.forEach(input => {
                    input.name = input.name.replace(/defect_images\[\d+\]/, 'defect_images[' + index + ']');
                });

                const checkboxes = item.querySelectorAll('[id*="mark_on_map_"]');
                checkboxes.forEach(checkbox => {
                    checkbox.id = checkbox.id.replace(/mark_on_map_\d+/, 'mark_on_map_' + index);
                });

                const labels = item.querySelectorAll('[for*="mark_on_map_"]');
                labels.forEach(label => {
                    label.setAttribute('for', label.getAttribute('for').replace(/mark_on_map_\d+/, 'mark_on_map_' + index));
                });

                // Update the preview ID
                const previewDivs = item.querySelectorAll('[id*="defect-preview-"]');
                previewDivs.forEach(div => {
                    div.id = 'defect-preview-' + index;
                });

                const previewInputs = item.querySelectorAll('[data-preview*="defect-preview-"]');
                previewInputs.forEach(input => {
                    input.setAttribute('data-preview', 'defect-preview-' + index);
                });
            });

            // Show no defects message if empty
            if (defectItems.length === 0 && noDefectsMessage) {
                noDefectsMessage.style.display = 'block';
            }

            // Update counter
            defectCount = defectItems.length;

            // Disable remove button if only one defect left
            if (defectItems.length <= 1) {
                document.querySelectorAll('.remove-defect-btn').forEach(btn => {
                    btn.disabled = true;
                });
            }
        }

        // Add a new section
        function addSection() {
            // Clone template
            const template = sectionTemplate.content.cloneNode(true);

            // Replace INDEX with actual count
            const elements = template.querySelectorAll('[name*="INDEX"]');
            elements.forEach(element => {
                element.name = element.name.replace(/INDEX/g, sectionCount);
            });

            // Update other INDEX references
            const badges = template.querySelectorAll('.badge');
            badges.forEach(badge => {
                badge.textContent = sectionCount + 1;
            });

            const headers = template.querySelectorAll('h6');
            headers.forEach(header => {
                header.innerHTML = header.innerHTML.replace(/INDEX/g, sectionCount + 1);
            });

            // Update value of section name
            const nameInput = template.querySelector('[name*="name"]');
            if (nameInput) {
                nameInput.value = nameInput.value.replace(/INDEX/g, sectionCount + 1);
            }

            // Add event listener to the new remove button
            const removeBtn = template.querySelector('.remove-section-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('.section-item').remove();
                updateSectionNumbers();
            });

            // Add to container
            sectionsContainer.appendChild(template);

            // Increment counter
            sectionCount++;

            // Enable all remove buttons
            document.querySelectorAll('.remove-section-btn').forEach(btn => {
                btn.disabled = sectionsContainer.querySelectorAll('.section-item').length <= 1;
            });
        }

        // Update section numbers after removal
        function updateSectionNumbers() {
            const sectionItems = sectionsContainer.querySelectorAll('.section-item');

            sectionItems.forEach((item, index) => {
                const badge = item.querySelector('.badge');
                const header = item.querySelector('h6');

                badge.textContent = index + 1;
                header.innerHTML = header.innerHTML.replace(/#\d+/, '#' + (index + 1));

                // Also update the names to prevent gaps in indexes
                const inputs = item.querySelectorAll('[name*="sections["]');
                inputs.forEach(input => {
                    input.name = input.name.replace(/sections\[\d+\]/, 'sections[' + index + ']');
                });

                // Update tronçon name if it follows the standard format
                const nameInput = item.querySelector('[name*="name"]');
                if (nameInput && nameInput.value.match(/^Tronçon \d+$/)) {
                    nameInput.value = 'Tronçon ' + (index + 1);
                }
            });

            // Update counter
            sectionCount = sectionItems.length;

            // Disable remove button if only one section left
            if (sectionItems.length <= 1) {
                document.querySelectorAll('.remove-section-btn').forEach(btn => {
                    btn.disabled = true;
                });
            }
        }

        // Handle defect image preview
        function handleDefectImagePreview(e) {
            const previewId = this.getAttribute('data-preview');
            const previewContainer = document.getElementById(previewId);

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const img = previewContainer.querySelector('img');
                    img.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                }

                reader.readAsDataURL(this.files[0]);
            } else {
                previewContainer.classList.add('d-none');
            }
        }

        // Handle map image preview
        if (mapImageInput) {
            mapImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        mapPreviewImg.src = e.target.result;
                        mapPreview.classList.remove('d-none');
                    }

                    reader.readAsDataURL(this.files[0]);
                } else {
                    mapPreview.classList.add('d-none');
                }
            });
        }

        // Handle report images preview
        if (reportImagesInput) {
            reportImagesInput.addEventListener('change', function() {
                handleReportImagesPreview(this.files);
            });
        }

        function handleReportImagesPreview(files) {
            if (!files || files.length === 0) return;

            imagePreview.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                if (!file.type.match('image.*')) return;

                const reader = new FileReader();

                reader.onload = function(e) {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-3';

                    const card = document.createElement('div');
                    card.className = 'card h-100';

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'card-img-top';
                    img.style.height = '160px';
                    img.style.objectFit = 'cover';

                    const cardBody = document.createElement('div');
                    cardBody.className = 'card-body p-2';

                    const caption = document.createElement('div');
                    caption.className = 'mb-2';

                    const captionInput = document.createElement('input');
                    captionInput.type = 'text';
                    captionInput.className = 'form-control form-control-sm';
                    captionInput.name = `report_image_captions[${index}]`;
                    captionInput.placeholder = 'Caption for this image';

                    caption.appendChild(captionInput);

                    const fileName = document.createElement('p');
                    fileName.className = 'small text-muted mb-0 text-truncate';
                    fileName.textContent = file.name;

                    cardBody.appendChild(caption);
                    cardBody.appendChild(fileName);
                    card.appendChild(img);
                    card.appendChild(cardBody);
                    col.appendChild(card);
                    imagePreview.appendChild(col);
                };

                reader.readAsDataURL(file);
            });
        }

        // Setup drag and drop for images
        if (dropArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, function() {
                    this.classList.add('border-primary');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, function() {
                    this.classList.remove('border-primary');
                }, false);
            });

            dropArea.addEventListener('drop', function(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                if (reportImagesInput) {
                    // Create a new DataTransfer object
                    const dataTransfer = new DataTransfer();

                    // Add dropped files
                    Array.from(files).forEach(file => {
                        dataTransfer.items.add(file);
                    });

                    // Set the files to the input
                    reportImagesInput.files = dataTransfer.files;

                    // Trigger change event
                    const event = new Event('change', { bubbles: true });
                    reportImagesInput.dispatchEvent(event);
                }

                handleReportImagesPreview(files);
            }, false);
        }

        // Add event listeners to initial elements
        document.querySelectorAll('.defect-image-input').forEach(input => {
            input.addEventListener('change', handleDefectImagePreview);
        });

        document.querySelectorAll('.remove-defect-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.defect-item').remove();
                updateDefectNumbers();
            });
        });

        document.querySelectorAll('.remove-section-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.closest('.section-item').remove();
                updateSectionNumbers();
            });
        });

        // Add button events
        if (addDefectBtn) {
            addDefectBtn.addEventListener('click', addDefect);
        }

        if (addSectionBtn) {
            addSectionBtn.addEventListener('click', addSection);
        }

        // Form validation
        const reportForm = document.getElementById('reportForm');

        if (reportForm) {
            reportForm.addEventListener('submit', function(e) {
                // Check if title is provided
                const titleField = document.getElementById('title');
                if (!titleField.value.trim()) {
                    e.preventDefault();
                    titleField.classList.add('is-invalid');
                    alert("{{ __('Please provide a title for the report.') }}");
                    return false;
                }

                // Check if at least one defect is added with required fields
                const defectItems = defectsContainer.querySelectorAll('.defect-item');
                if (defectItems.length === 0) {
                    e.preventDefault();
                    alert("{{ __('Please add at least one defect to the report.') }}");
                    return false;
                }

                // Check required fields for each defect
                let missingDefectFields = false;
                defectItems.forEach((item, index) => {
                    const defectType = item.querySelector('[name*="defect_type_id"]');
                    const description = item.querySelector('[name*="description"]');

                    if (!defectType.value || !description.value.trim()) {
                        defectType.classList.add('is-invalid');
                        description.classList.add('is-invalid');
                        missingDefectFields = true;
                    } else {
                        defectType.classList.remove('is-invalid');
                        description.classList.remove('is-invalid');
                    }
                });

                if (missingDefectFields) {
                    e.preventDefault();
                    alert("{{ __('Please fill in all required fields for each defect.') }}");
                    return false;
                }

                // Check required fields
                const requiredFields = this.querySelectorAll('[required]');
                let missingFields = false;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        missingFields = true;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (missingFields) {
                    e.preventDefault();
                    alert("{{ __('Please fill in all required fields.') }}");
                    return false;
                }

                // Success - just before submitting, show a loading spinner
                document.getElementById('submit-report').innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ __("Processing...") }}';
                document.getElementById('submit-report').disabled = true;
            });
        }
    });
</script>

<style>
    .required:after {
        content: " *";
        color: red;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
    #image-drop-area {
        transition: all 0.3s ease;
    }
    #image-drop-area.border-primary {
        background-color: rgba(0, 123, 255, 0.1);
    }
    .card-img-top {
        object-fit: cover;
    }
    .badge {
        font-size: 0.8rem;
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .form-control:focus, .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endsection
