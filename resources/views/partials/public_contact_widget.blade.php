@php
    $appSetting = $setting ?? null;
    $hasWhatsappLink = !blank(optional($appSetting)->whatsapp_link);
    $hasWhatsappNumber = !blank(optional($appSetting)->whatsapp_number);
    $hasContactNumber = !blank(optional($appSetting)->contact_number);
    $showContactWidget = $hasWhatsappLink || $hasWhatsappNumber || $hasContactNumber;

    $whatsappHref = $hasWhatsappLink
        ? $appSetting->whatsapp_link
        : ($hasWhatsappNumber ? "https://wa.me/" . preg_replace('/\D+/', '', (string) $appSetting->whatsapp_number) : null);

    $telHref = $hasContactNumber
        ? "tel:" . preg_replace('/\D+/', '', (string) $appSetting->contact_number)
        : null;
@endphp

@if($showContactWidget)
    <style>
        .public-contact-widget {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .public-contact-widget .widget-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 10px 14px;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 12px 24px rgba(15, 25, 35, 0.2);
        }

        .public-contact-widget .widget-btn:hover {
            color: #fff;
            transform: translateY(-1px);
        }

        .public-contact-widget .widget-btn.is-whatsapp {
            background: #1fa855;
        }

        .public-contact-widget .widget-btn.is-phone {
            background: #0d6efd;
        }
    </style>

    <div class="public-contact-widget">
        @if($whatsappHref)
            <a class="widget-btn is-whatsapp" href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer">
                <i class="fa-brands fa-whatsapp"></i>
                <span>WhatsApp</span>
            </a>
        @endif

        @if($telHref)
            <a class="widget-btn is-phone" href="{{ $telHref }}">
                <i class="fa-solid fa-phone"></i>
                <span>Call Us</span>
            </a>
        @endif
    </div>
@endif
