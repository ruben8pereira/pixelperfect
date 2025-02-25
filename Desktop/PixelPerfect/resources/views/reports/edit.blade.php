<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Report') }}: {{ $report->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 p-4 rounded-md">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reports.update', $report) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div>
                                <!-- Title Field -->
                                <div class="mb-4">
                                    <x-label for="title" :value="__('Report Title')" />
                                    <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $report->title)" required autofocus />
                                </div>

                                <!-- Description Field -->
                                <div class="mb-4">
                                    <x-label for="description" :value="__('Description')" />
                                    <textarea id="description" name="description" rows="4" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ old('description', $report->description) }}</textarea>
                                </div>

                                <!-- Organization Field - Only for Admins -->
                                @if(auth()->user()->role->name == 'Administrator')
                                    <div class="mb-4">
                                        <x-label for="organization_id" :value="__('Organization')" />
                                        <select id="organization_id" name="organization_id" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full" required>
                                            <option value="">Select Organization</option>
                                            @foreach(\App\Models\Organization::all() as $organization)
                                                <option value="{{ $organization->id }}" {{ old('organization_id', $report->organization_id) == $organization->id ? 'selected' : '' }}>
                                                    {{ $organization->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <!-- Language Field -->
                                <div class="mb-4">
                                    <x-label for="language" :value="__('Report Language')" />
                                    <select id="language" name="language" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                        <option value="en" {{ old('language', $report->language) == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="fr" {{ old('language', $report->language) == 'fr' ? 'selected' : '' }}>French</option>
                                        <option value="de" {{ old('language', $report->language) == 'de' ? 'selected' : '' }}>German</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div>
                                <!-- Image Upload Section -->
                                <div class="mb-4">
                                    <x-label for="images" :value="__('Add More Images')" />
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                    <span>Upload images</span>
                                                    <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                PNG, JPG, GIF up to 10MB
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Existing Images Section -->
                                @if($report->reportImages->count() > 0)
                                    <div class="mb-4">
                                        <h4 class="font-medium mb-2">Current Images</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            @foreach($report->reportImages as $image)
                                                <div class="border rounded-lg overflow-hidden relative">
                                                    <img src="{{ asset('storage/' . $image->file_path) }}" alt="Report Image" class="w-full h-32 object-cover">
                                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 hover:opacity-100 bg-black bg-opacity-50 transition-opacity">
                                                        <button type="button" onclick="document.getElementById('delete-image-{{ $image->id }}').submit()" class="bg-red-500 text-white p-1 rounded-full">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <form id="delete-image-{{ $image->id }}" action="{{ route('reports.images.destroy', [$report, $image]) }}" method="POST" class="hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Existing Defects Section -->
                                <div class="mb-4">
                                    <x-label :value="__('Defects')" />
                                    <div class="mt-2 p-4 border border-gray-200 rounded-md">
                                        <div id="defects-container">
                                            @forelse($report->reportDefects as $index => $defect)
                                                <div class="defect-item mb-4 pb-4 border-b border-gray-200">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h4 class="font-medium">Defect #{{ $index + 1 }}</h4>
                                                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="removeDefect(this)" {{ $report->reportDefects->count() <= 1 ? 'disabled' : '' }}>
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <input type="hidden" name="defects[{{ $index }}][id]" value="{{ $defect->id }}">

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <x-label for="defects[{{ $index }}][defect_type_id]" :value="__('Defect Type')" />
                                                            <select name="defects[{{ $index }}][defect_type_id]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full" required>
                                                                <option value="">Select Type</option>
                                                                @foreach(\App\Models\DefectType::all() as $defectType)
                                                                    <option value="{{ $defectType->id }}" {{ $defect->defect_type_id == $defectType->id ? 'selected' : '' }}>
                                                                        {{ $defectType->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <x-label for="defects[{{ $index }}][severity]" :value="__('Severity')" />
                                                            <select name="defects[{{ $index }}][severity]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                                                <option value="low" {{ $defect->severity == 'low' ? 'selected' : '' }}>Low</option>
                                                                <option value="medium" {{ $defect->severity == 'medium' ? 'selected' : '' }}>Medium</option>
                                                                <option value="high" {{ $defect->severity == 'high' ? 'selected' : '' }}>High</option>
                                                                <option value="critical" {{ $defect->severity == 'critical' ? 'selected' : '' }}>Critical</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2">
                                                        <x-label for="defects[{{ $index }}][description]" :value="__('Description')" />
                                                        <textarea name="defects[{{ $index }}][description]" rows="2" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">{{ $defect->description }}</textarea>
                                                    </div>

                                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <x-label for="defects[{{ $index }}][coordinates][latitude]" :value="__('Latitude')" />
                                                            <x-input name="defects[{{ $index }}][coordinates][latitude]" type="text" class="block mt-1 w-full" placeholder="e.g. 45.123456" value="{{ $defect->coordinates['latitude'] ?? '' }}" />
                                                        </div>
                                                        <div>
                                                            <x-label for="defects[{{ $index }}][coordinates][longitude]" :value="__('Longitude')" />
                                                            <x-input name="defects[{{ $index }}][coordinates][longitude]" type="text" class="block mt-1 w-full" placeholder="e.g. -73.123456" value="{{ $defect->coordinates['longitude'] ?? '' }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="defect-item mb-4 pb-4 border-b border-gray-200">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h4 class="font-medium">Defect #1</h4>
                                                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="removeDefect(this)" disabled>
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <x-label for="defects[0][defect_type_id]" :value="__('Defect Type')" />
                                                            <select name="defects[0][defect_type_id]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full" required>
                                                                <option value="">Select Type</option>
                                                                @foreach(\App\Models\DefectType::all() as $defectType)
                                                                    <option value="{{ $defectType->id }}">
                                                                        {{ $defectType->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div>
                                                            <x-label for="defects[0][severity]" :value="__('Severity')" />
                                                            <select name="defects[0][severity]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                                                                <option value="low">Low</option>
                                                                <option value="medium">Medium</option>
                                                                <option value="high">High</option>
                                                                <option value="critical">Critical</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="mt-2">
                                                        <x-label for="defects[0][description]" :value="__('Description')" />
                                                        <textarea name="defects[0][description]" rows="2" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"></textarea>
                                                    </div>

                                                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                                        <div>
                                                            <x-label for="defects[0][coordinates][latitude]" :value="__('Latitude')" />
                                                            <x-input name="defects[0][coordinates][latitude]" type="text" class="block mt-1 w-full" placeholder="e.g. 45.123456" />
                                                        </div>
                                                        <div>
                                                            <x-label for="defects[0][coordinates][longitude]" :value="__('Longitude')" />
                                                            <x-input name="defects[0][coordinates][longitude]" type="text" class="block mt-1 w-full" placeholder="e.g. -73.123456" />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforelse
                                        </div>

                                        <div class="mt-2">
                                            <button type="button" onclick="addDefect()" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                Add Another Defect
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <a href="{{ route('reports.show', $report) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150 mr-2">
                                Cancel
                            </a>
                            <x-button>
                                {{ __('Update Report') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Defect Management -->
    <script>
        let defectCount = {{ $report->reportDefects->count() > 0 ? $report->reportDefects->count() : 1 }};

        function addDefect() {
            defectCount++;
            const defectsContainer = document.getElementById('defects-container');

            const defectItem = document.createElement('div');
            defectItem.className = 'defect-item mb-4 pb-4 border-b border-gray-200';
            defectItem.innerHTML = `
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-medium">Defect #${defectCount}</h4>
                    <button type="button" class="text-gray-400 hover:text-gray-500" onclick="removeDefect(this)">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="defects[${defectCount-1}][defect_type_id]" :value="__('Defect Type')" />
                        <select name="defects[${defectCount-1}][defect_type_id]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full" required>
                            <option value="">Select Type</option>
                            @foreach(\App\Models\DefectType::all() as $defectType)
                                <option value="{{ $defectType->id }}">
                                    {{ $defectType->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="defects[${defectCount-1}][severity]" :value="__('Severity')" />
                        <select name="defects[${defectCount-1}][severity]" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                </div>

                <div class="mt-2">
                    <x-label for="defects[${defectCount-1}][description]" :value="__('Description')" />
                    <textarea name="defects[${defectCount-1}][description]" rows="2" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1 w-full"></textarea>
                </div>

                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="defects[${defectCount-1}][coordinates][latitude]" :value="__('Latitude')" />
                        <x-input name="defects[${defectCount-1}][coordinates][latitude]" type="text" class="block mt-1 w-full" placeholder="e.g. 45.123456" />
                    </div>
                    <div>
                        <x-label for="defects[${defectCount-1}][coordinates][longitude]" :value="__('Longitude')" />
                        <x-input name="defects[${defectCount-1}][coordinates][longitude]" type="text" class="block mt-1 w-full" placeholder="e.g. -73.123456" />
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

            // If only one defect remains, disable its delete button
            const remainingDefects = document.querySelectorAll('.defect-item');
            if (remainingDefects.length === 1) {
                const lastDeleteButton = remainingDefects[0].querySelector('button');
                lastDeleteButton.setAttribute('disabled', 'disabled');
            }
        }
    </script>
</x-app-layout>
