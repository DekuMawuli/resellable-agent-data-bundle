@php
    $isTestMode = !(bool) \App\Models\Setting::query()->value('use_live_payment');
    $noticeContext = $noticeContext ?? 'general';

    $messages = [
        'agent_purchase' => [
            'heading' => 'System is running in Test Mode',
            'body'    => 'Your purchase will be recorded but <strong>no real data bundle will be sent</strong> to the recipient. The admin will approve and fulfil orders locally. This is for testing purposes only.',
        ],
        'agent_topup' => [
            'heading' => 'Paystack Test Mode Active',
            'body'    => 'Payments are processed with <strong>test keys — no real money will be charged</strong>. Use Paystack\'s test card details to complete the top-up. Your wallet will be credited as normal.',
        ],
        'admin_order' => [
            'heading' => 'Test Mode — Orders approved locally',
            'body'    => 'While in test mode, approving a purchase <strong>will not call the Realest API</strong>. Orders are marked as completed locally with a test reference. Switch to Live in Settings when ready for real fulfilment.',
        ],
        'general' => [
            'heading' => 'System is running in Test Mode',
            'body'    => 'Payments and order fulfilment are simulated. <strong>No real charges or API calls</strong> will be made until you switch to Live mode in Settings.',
        ],
    ];

    $msg = $messages[$noticeContext] ?? $messages['general'];
@endphp

@if ($isTestMode)
<div class="d-flex align-items-start gap-3 p-3 mb-3"
     style="background:#fff7ed;border:1px solid #fed7aa;border-left:4px solid #f97316;border-radius:.5rem;">
    <div class="flex-shrink-0 mt-1" style="color:#ea580c;font-size:1rem;">
        <i class="fas fa-flask"></i>
    </div>
    <div class="flex-grow-1 min-w-0">
        <p class="mb-1 fw-semibold" style="color:#9a3412;font-size:.85rem;">
            {{ $msg['heading'] }}
        </p>
        <p class="mb-0" style="color:#c2410c;font-size:.78rem;line-height:1.5;">
            {!! $msg['body'] !!}
        </p>
    </div>
    @if ($noticeContext !== 'agent_topup')
    <div class="flex-shrink-0 d-none d-md-block">
        <a href="{{ route('root.settings') }}" class="btn btn-sm"
           style="background:#fff7ed;border:1px solid #fdba74;color:#ea580c;font-size:.72rem;white-space:nowrap;">
            <i class="fas fa-sliders-h me-1"></i>Go Live
        </a>
    </div>
    @endif
</div>
@endif
