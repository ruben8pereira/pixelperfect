@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Report</h1>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Reports
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-bold">{{ __('Whoops! Something went wrong.') }}</div>
        <ul class="mt-3 mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="card bg-light shadow-none mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2 text-primary"></i>Report Information</h5>
                            </div>
                            <div class="card-body">
                                <!-- Title Field -->
                                <div class="mb-3">
                                    <label for="title" class="form-label fw-bold">{{ __('Report Title') }}</label>
                                    <input id="title" class="form-control" type="text" name="title" value="{{ old('title') }}" required autofocus placeholder="Enter a descriptive title for this report">
                                </div>

                                <!-- Description Field -->
                                <div class="mb-3">
                                    <label for="description" class="form-label fw-bold">{{ __('Description') }}</label>
                                    <textarea id="description" name="description" rows="4" class="form-control" placeholder="Describe the inspection details, location, and other relevant information">{{ old('description') }}</textarea>
                                </div>

                                <!-- Organization Field - Only for Admins -->
                                @if(auth()->user()->role->name == 'Administrator')
                                <div class="mb-3">
                                    <label for="organization_id" class="form-label fw-bold">{{ __('Organization') }}</label>
                                    <select id="organization_id" name="organization_id" class="form-select" required>
                                        <option value="">Select Organization</option>
                                        @foreach(\App\Models\Organization::all() as $organization)
                                            <option value="{{ $organization->id }}" {{ old('organization_id') == $organization->id ? 'selected' : '' }}>
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
                                        <option value="fr" {{ old('language') == 'fr' ? 'selected' : '' }}>French</option>
                                        <option value="de" {{ old('language') == 'de' ? 'selected' : '' }}>German</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Image Upload -->
                        <div class="card bg-light shadow-none mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0"><i class="fas fa-images me-2 text-primary"></i>Report Images</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="mt-1 d-flex justify-content-center p-4 border border-2 border-dashed rounded">
                                        <div class="text-center">
                                            <div class="mb-3">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-secondary"></i>
                                            </div>
                                            <div class="text-sm">
                                                <label for="images" class="btn btn-sm btn-primary mb-2">
                                                    <i class="fas fa-upload me-1"></i> Upload images
                                                    <input id="images" name="images[]" type="file" class="d-none" multiple accept="image/*">
                                                </label>
                                                <p class="mb-0 text-muted">or drag and drop</p>
                                                <p class="small text-muted mt-1">PNG, JPG, GIF up to 10MB</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="image-preview" class="mt-3 row g-2"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Defects Section -->
                        <div class="card bg-light shadow-none mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2 text-primary"></i>Defects</h5>
                            </div>
                            <div class="card-body">
                                <div id="defects-container">
                                    <div class="defect-item mb-4 pb-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="fw-bold mb-0">
                                                <span class="badge bg-secondary me-2">1</span>Defect #1
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDefect(this)" disabled>
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="defects[0][defect_type_id]" class="form-label">{{ __('Defect Type') }}</label>
                                                <select name="defects[0][defect_type_id]" class="form-select" required>
                                                    <option value="">Select Type</option>
                                                    @foreach(\App\Models\DefectType::all() as $defectType)
                                                        <option value="{{ $defectType->id }}">
                                                            {{ $defectType->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="defects[0][severity]" class="form-label">{{ __('Severity') }}</label>
                                                <select name="defects[0][severity]" class="form-select">
                                                    <option value="low">Low</option>
                                                    <option value="medium">Medium</option>
                                                    <option value="high">High</option>
                                                    <option value="critical">Critical</option>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label for="defects[0][description]" class="form-label">{{ __('Description') }}</label>
                                                <textarea name="defects[0][description]" rows="2" class="form-control" placeholder="Describe the defect, its appearance and location"></textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="defects[0][coordinates][latitude]" class="form-label">{{ __('Latitude') }}</label>
                                                <input name="defects[0][coordinates][latitude]" type="text" class="form-control" placeholder="e.g. 45.123456">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="defects[0][coordinates][longitude]" class="form-label">{{ __('Longitude') }}</label>
                                                <input name="defects[0][coordinates][longitude]" type="text" class="form-control" placeholder="e.g. -73.123456">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="button" onclick="addDefect()" class="btn btn-outline-primary">
                                        <i class="fas fa-plus me-1"></i> Add Another Defect
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Create Report
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript for Image Preview and Defect Management -->
<script>
    // Image preview functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('images');
        const imagePreview = document.getElementById('image-preview');

        imageInput.addEventListener('change', function() {
            imagePreview.innerHTML = '';

            if (this.files) {
                Array.from(this.files).forEach((file, index) => {
                    if (!file.type.match('image.*')) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const col = document.createElement('div');
                        col.className = 'col-4';

                        const card = document.createElement('div');
                        card.className = 'card h-100';

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'card-img-top';
                        img.style.height = '120px';
                        img.style.objectFit = 'cover';

                        const cardBody = document.createElement('div');
                        cardBody.className = 'card-body p-2';

                        const fileName = document.createElement('p');
                        fileName.className = 'small text-muted text-truncate mb-0';
                        fileName.textContent = file.name;

                        cardBody.appendChild(fileName);
                        card.appendChild(img);
                        card.appendChild(cardBody);
                        col.appendChild(card);
                        imagePreview.appendChild(col);
                    };

                    reader.readAsDataURL(file);
                });
            }
        });
    });

    // Defect management
    let defectCount = 1;

    function addDefect() {
        defectCount++;
        const defectsContainer = document.getElementById('defects-container');

        const defectItem = document.createElement('div');
        defectItem.className = 'defect-item mb-4 pb-3 border-bottom';
        defectItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">
                    <span class="badge bg-secondary me-2">${defectCount}</span>Defect #${defectCount}
                </h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeDefect(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="defects[${defectCount-1}][defect_type_id]" class="form-label">{{ __('Defect Type') }}</label>
                    <select name="defects[${defectCount-1}][defect_type_id]" class="form-select" required>
                        <option value="">Select Type</option>
                        @foreach(\App\Models\DefectType::all() as $defectType)
                            <option value="{{ $defectType->id }}">
                                {{ $defectType->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="defects[${defectCount-1}][severity]" class="form-label">{{ __('Severity') }}</label>
                    <select name="defects[${defectCount-1}][severity]" class="form-select">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="defects[${defectCount-1}][description]" class="form-label">{{ __('Description') }}</label>
                    <textarea name="defects[${defectCount-1}][description]" rows="2" class="form-control" placeholder="Describe the defect, its appearance and location"></textarea>
                </div>

                <div class="col-md-6">
                    <label for="defects[${defectCount-1}][coordinates][latitude]" class="form-label">{{ __('Latitude') }}</label>
                    <input name="defects[${defectCount-1}][coordinates][latitude]" type="text" class="form-control" placeholder="e.g. 45.123456">
                </div>

                <div class="col-md-6">
                    <label for="defects[${defectCount-1}][coordinates][longitude]" class="form-label">{{ __('Longitude') }}</label>
                    <input name="defects[${defectCount-1}][coordinates][longitude]" type="text" class="form-control" placeholder="e.g. -73.123456">
                </div>
            </div>
        `;

        defectsContainer.appendChild(defectItem);

        // Enable all delete buttons when we have more than one defect
        const deleteButtons = document.querySelectorAll('.defect-item button');
        deleteButtons.forEach(button => {
            button.removeAttribute('disabled');
        });
    }

    function removeDefect(button) {
        const defectItem = button.closest('.defect-item');
        defectItem.remove();

        // Update numbering
        const defectItems = document.querySelectorAll('.defect-item');
        defectItems.forEach((item, index) => {
            const badge = item.querySelector('.badge');
            const heading = item.querySelector('h6');

            badge.textContent = index + 1;
            heading.childNodes[1].textContent = 'Defect #' + (index + 1);
        });

        // Reset counter
        defectCount = defectItems.length;

        // If only one defect remains, disable its delete button
        if (defectItems.length === 1) {
            const lastDeleteButton = defectItems[0].querySelector('button');
            lastDeleteButton.setAttribute('disabled', 'disabled');
        }
    }
</script>
@endsection
