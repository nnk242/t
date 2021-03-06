@if(\Illuminate\Support\Facades\Auth::check())
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide('/page')? 'active' : '' }}">
        <a href="{{ route('page.index') }}">{{ __('Page') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide('/message')? 'active' : '' }}">
        <a href="{{ route('message.index') }}">{{ __('Tin nhắn') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide(['/me', '/me/manager-share', '/me/share', '/me/page-use'])? 'active' : '' }}">
        <a href="{{ route('me.index') }}">{{ __('Cá nhân') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide(['/role', '/role/index'])? 'active' : '' }}">
        <a href="{{ route('role.index') }}">{{ __('Quyền') }}</a>
    </li>
    <li
        class="{{ \App\Components\ActiveComponent::isActiveSide(['/gift', '/event/gift'])? 'active' : '' }}">
        <a href="{{ route('gift.index') }}">{{ __('Gift') }}</a>
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
