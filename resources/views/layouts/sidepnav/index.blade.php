@if(\Illuminate\Support\Facades\Auth::check())
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide('/page')? 'selected-page' : '' }}">
        <a href="{{ route('page.index') }}">{{ __('Page') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide(['/me', '/me/manager-share', '/me/share'])? 'selected-page' : '' }}">
        <a href="{{ route('me.index') }}">{{ __('Cá nhân') }}</a>
    </li>
@endif
