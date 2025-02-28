@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            {{ isset($report) ? __('Edit Report') : __('Create New Report') }}
        </h1>
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

    <form action="{{ isset($report) ? route('reports.update', $report) : route('reports.store') }}" method="POST" enctype="multipart/form-data" id="reportForm">
        @csrf
        @if(isset($report))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>{{ __('Report Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Title Field -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-bold required">{{ __('Report Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $report->title ?? '') }}" required>
                            <small class="text-muted">{{ __('Example: "Inspection of Main Street Sewer Line"') }}</small>
                        </div>

                        <!-- Description Field -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">{{ __('Description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $report->description ?? '') }}</textarea>
                            <small class="text-muted">{{ __('General remarks about this inspection report') }}</small>
                        </div>

                        <!-- Organization Field - Only for Admins -->
                        @if(auth()->user()->role && auth()->user()->role->name == 'Administrator')
                        <div class="mb-3">
                            <label for="organization_id" class="form-label fw-bold required">{{ __('Organization') }}</label>
                            <select class="form-select" id="organization_id" name="organization_id" required>
                                <option value="">{{ __('Select Organization') }}</option>
                                @foreach(\App\Models\Organization::all() as $organization)
                                    <option value="{{ $organization->id }}" {{ (old('organization_id', $report->organization_id ?? '') == $organization->id) ? 'selected' : '' }}>
                                        {{ $organization->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Language Field -->
                        <div class="mb-3">
                            <label for="language" class="form-label fw-bold">{{ __('Report Language') }}</label>
                            <select class="form-select" id="language" name="language">
                                <option value="en" {{ (old('language', $report->language ?? 'en') == 'en') ? 'selected' : '' }}>{{ __('English') }}</option>
                                <option value="fr" {{ (old('language', $report->language ?? '') == 'fr') ? 'selected' : '' }}>{{ __('French') }}</option>
                                <option value="de" {{ (old('language', $report->language ?? '') == 'de') ? 'selected' : '' }}>{{ __('German') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Network Map Upload -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h5 class="mb-0"><i class="fas fa-map me-2 text-primary"></i>{{ __('Network Map') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="map_image" class="form-label fw-bold">{{ __('Upload Network Map') }}</label>
                            <input type="file" class="form-control" id="map_image" name="map_image" accept="image/*">
                            <small class="text-muted">{{ __('Upload a map or overview drawing of the inspected network') }}</small>
                        </div>

                        @if(isset($report) && $mapImage = $report->reportImages->where('caption', 'Map')->first())
                            <div class="mt-3">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center bg-light py-2">
                                        <span>{{ __('Current Map') }}</span>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="keep_map_image" id="keep_map_image" value="1" checked>
                                            <label class="form-check-label" for="keep_map_image">{{ __('Keep this image') }}</label>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <img src="{{ asset('storage/' . $mapImage->file_path) }}" class="img-fluid" alt="Network Map">
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-6">
                <!-- Defects Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-primary"></i>{{ __('Defects') }}</h5>
                        <button type="button" class="btn btn-sm btn-primary" id="addDefectBtn">
                            <i class="fas fa-plus me-1"></i> {{ __('Add Defect') }}
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="defects-container">
                            @if(isset($report) && $report->reportDefects->count() > 0)
                                @foreach($report->reportDefects as $index => $defect)
                                    <div class="defect-card card mb-4">
                                        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ __('Defect') }} #{{ $index + 1 }}</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-defect-btn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <input type="hidden" name="defects[{{ $index }}][id]" value="{{ $defect->id }}">

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">{{ __('Defect Type') }}</label>
                                                    <select class="form-select" name="defects[{{ $index }}][defect_type_id]" required>
                                                        <option value="">{{ __('Select Type') }}</option>
                                                        @foreach(\App\Models\DefectType::all() as $defectType)
                                                            <option value="{{ $defectType->id }}" {{ $defect->defect_type_id == $defectType->id ? 'selected' : '' }}>
                                                                {{ $defectType->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-bold required">{{ __('Severity') }}</label>
                                                    <select class="form-select" name="defects[{{ $index }}][severity]" required>
                                                        <option value="low" {{ $defect->severity == 'low' ? 'selected' : '' }}>{{ __('Low (4)') }}</option>
                                                        <option value="medium" {{ $defect->severity == 'medium' ? 'selected' : '' }}>{{ __('Medium (3)') }}</option>
                                                        <option value="high" {{ $defect->severity == 'high' ? 'selected' : '' }}>{{ __('High (2)') }}</option>
                                                        <option value="critical" {{ $defect->severity == 'critical' ? 'selected' : '' }}>{{ __('Critical (1)') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold required">{{ __('Description') }}</label>
                                                <textarea class="form-control" name="defects[{{ $index }}][description]" rows="2" required>{{ $defect->description }}</textarea>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ __('Distance (ml.)') }}</label>
                                                    <input type="text" class="form-control" name="defects[{{ $index }}][coordinates][distance]" value="{{ $defect->coordinates['distance'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ __('Counter') }}</label>
                                                    <input type="text" class="form-control" name="defects[{{ $index }}][coordinates][counter]" value="{{ $defect->coordinates['counter'] ?? '' }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">{{ __('Water Level') }}</label>
                                                    <input type="text" class="form-control" name="defects[{{ $index }}][coordinates][water_level]" value="{{ $defect->coordinates['water_level'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">{{ __('Reference') }}</label>
                                                    <input type="text" class="form-control" name="defects[{{ $index }}][coordinates][reference]" value="{{ $defect->coordinates['reference'] ?? '' }}" placeholder="e.g. GR3">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">{{ __('Comment') }}</label>
                                                    <input type="text" class="form-control" name="defects[{{ $index }}][coordinates][comment]" value="{{ $defect->coordinates['comment'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">{{ __('Latitude') }}</label>
                                                    <input type="text" class="form-control" name="defects[{{ $index }}][coordinates][latitude]" value="{{ $defect->coordinates['latitude'] ?? '' }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">{{ __('Longitude') }}</label>
                                                    <input type="text" class="form-control" name="defects[{{ $index }}][coordinates][longitude]" value="{{ $defect->coordinates['longitude'] ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">{{ __('Defect Image') }}</label>
                                                <input type="file" class="form-control" name="defect_images[{{ $index }}]" accept="image/*">
                                            </div>

                                            @if($defectImage = $report->reportImages->where('defect_id', $defect->id)->first())
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center bg-light py-2">
                                                        <span>{{ __('Current Image') }}</span>
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox" name="keep_defect_images[{{ $index }}]" value="{{ $defectImage->id }}" checked>
                                                            <label class="form-check-label">{{ __('Keep this image') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <img src="{{ asset('storage/' . $defectImage->file_path) }}" class="img-fluid" alt="Defect Image">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- This will be shown if there are no defects yet -->
                                <div class="text-center py-4" id="no-defects-message">
                                    <div class="mb-3">
                                        <i class="fas fa-exclamation-triangle fa-3x text-muted"></i>
                                    </div>
                                    <h5>{{ __('No Defects Added') }}</h5>
                                    <p class="text-muted">{{ __('Click "Add Defect" to begin documenting pipe issues.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end mb-5">
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-times me-1"></i> {{ __('Cancel') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i>
                {{ isset($report) ? __('Update Report') : __('Create Report') }}
            </button>
        </div>
    </form>
</div>

<!-- Defect Template (hidden, used by JavaScript) -->
<template id="defect-template">
    <div class="defect-card card mb-4">
        <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Defect') }} #<span class="defect-number"></span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-defect-btn">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold required">{{ __('Defect Type') }}</label>
                    <select class="form-select" name="defects[INDEX][defect_type_id]" required>
                        <option value="">{{ __('Select Type') }}</option>
                        @foreach(\App\Models\DefectType::all() as $defectType)
                            <option value="{{ $defectType->id }}">{{ $defectType->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold required">{{ __('Severity') }}</label>
                    <select class="form-select" name="defects[INDEX][severity]" required>
                        <option value="low">{{ __('Low (4)') }}</option>
                        <option value="medium">{{ __('Medium (3)') }}</option>
                        <option value="high">{{ __('High (2)') }}</option>
                        <option value="critical">{{ __('Critical (1)') }}</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold required">{{ __('Description') }}</label>
                <textarea class="form-control" name="defects[INDEX][description]" rows="2" required></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('Distance (ml.)') }}</label>
                    <input type="text" class="form-control" name="defects[INDEX][coordinates][distance]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Counter') }}</label>
                    <input type="text" class="form-control" name="defects[INDEX][coordinates][counter]">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('Water Level') }}</label>
                    <input type="text" class="form-control" name="defects[INDEX][coordinates][water_level]">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Reference') }}</label>
                    <input type="text" class="form-control" name="defects[INDEX][coordinates][reference]" placeholder="e.g. GR3">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Comment') }}</label>
                    <input type="text" class="form-control" name="defects[INDEX][coordinates][comment]">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">{{ __('Latitude') }}</label>
                    <input type="text" class="form-control" name="defects[INDEX][coordinates][latitude]">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('Longitude') }}</label>
                    <input type="text" class="form-control" name="defects[INDEX][coordinates][longitude]">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('Defect Image') }}</label>
                <input type="file" class="form-control" name="defect_images[INDEX]" accept="image/*">
            </div>
        </div>
    </div>
</template>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const defectsContainer = document.getElementById('defects-container');
        const addDefectBtn = document.getElementById('addDefectBtn');
        const defectTemplate = document.getElementById('defect-template');
        const noDefectsMessage = document.getElementById('no-defects-message');

        let defectCount = {{ isset($report) ? $report->reportDefects->count() : 0 }};

        // Function to add a new defect
        function addDefect() {
            // Hide the no defects message if it exists
            if (noDefectsMessage) {
                noDefectsMessage.style.display = 'none';
            }

            // Clone the template
            const template = defectTemplate.content.cloneNode(true);

            // Set the defect number
            template.querySelector('.defect-number').textContent = defectCount + 1;

            // Update all name attributes to use the correct index
            const elements = template.querySelectorAll('[name*="INDEX"]');
            elements.forEach(element => {
                element.name = element.name.replace('INDEX', defectCount);
            });

            // Add event listener to remove button
            const removeBtn = template.querySelector('.remove-defect-btn');
            removeBtn.addEventListener('click', function() {
                this.closest('.defect-card').remove();

                // Update defect numbers
                updateDefectNumbers();

                // Show the no defects message if no defects are left
                if (defectsContainer.querySelectorAll('.defect-card').length === 0 && noDefectsMessage) {
                    noDefectsMessage.style.display = 'block';
                }
            });

            // Append the new defect card
            defectsContainer.appendChild(template);

            // Increment the count
            defectCount++;
        }

        // Function to update defect numbers
        function updateDefectNumbers() {
            const defectCards = defectsContainer.querySelectorAll('.defect-card');
            defectCards.forEach((card, index) => {
                card.querySelector('.defect-number').textContent = index + 1;
            });
        }

        // Add event listeners to existing remove buttons
        document.querySelectorAll('.remove-defect-btn').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.defect-card').remove();
                updateDefectNumbers();

                if (defectsContainer.querySelectorAll('.defect-card').length === 0 && noDefectsMessage) {
                    noDefectsMessage.style.display = 'block';
                }
            });
        });

        // Add event listener to add defect button
        addDefectBtn.addEventListener('click', addDefect);

        // Form validation before submit
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            // Check if at least one defect is added
            if (defectsContainer.querySelectorAll('.defect-card').length === 0) {
                e.preventDefault();
                alert('{{ __("Please add at least one defect to the report.") }}');
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
                alert('{{ __("Please fill in all required fields.") }}');
                return false;
            }
        });
    });
</script>
@endpush

<style>
    .required:after {
        content: " *";
        color: red;
    }
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>
@endsection
