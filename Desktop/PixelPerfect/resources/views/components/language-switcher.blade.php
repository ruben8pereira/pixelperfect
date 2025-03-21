<!-- resources/views/components/language-switcher.blade.php -->
<div class="language-switcher">
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            @if(App::getLocale() == 'en')
                <span class="flag-icon flag-icon-us me-1"></span> English
            @elseif(App::getLocale() == 'fr')
                <span class="flag-icon flag-icon-fr me-1"></span> Français
            @elseif(App::getLocale() == 'de')
                <span class="flag-icon flag-icon-de me-1"></span> Deutsch
            @endif
        </button>
        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
            <li>
                <a class="dropdown-item {{ App::getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">
                    <span class="flag-icon flag-icon-us me-1"></span> English
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ App::getLocale() == 'fr' ? 'active' : '' }}" href="{{ route('language.switch', 'fr') }}">
                    <span class="flag-icon flag-icon-fr me-1"></span> Français
                </a>
            </li>
            <li>
                <a class="dropdown-item {{ App::getLocale() == 'de' ? 'active' : '' }}" href="{{ route('language.switch', 'de') }}">
                    <span class="flag-icon flag-icon-de me-1"></span> Deutsch
                </a>
            </li>
        </ul>
    </div>
</div>
