@if(\Illuminate\Support\Facades\Auth::check())
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide('/page')? 'active' : '' }}">
        <a href="{{ route('page.index') }}">{{ __('Page') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide(['/me', '/me/manager-share', '/me/share', '/me/page-use'])? 'active' : '' }}">
        <a href="{{ route('me.index') }}">{{ __('Cá nhân') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide(['/event', '/event/index'])? 'active' : '' }}">
        <a href="{{ route('event.index') }}">{{ __('Sự kiện') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide(['/setting', '/setting/index'])? 'active' : '' }}">
        <a href="{{ route('setting.index') }}">{{ __('Cài đặt') }}</a>
    </li>
@endif
