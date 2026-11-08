{{-- Read-only details of a demo request (plan step 3.4). --}}
<div class="admin-card p-6 mb-6 space-y-4">
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4">
        <div><dt class="text-white/50 text-sm">{{ __('admin.name') }}</dt><dd class="text-white">{{ $item->name }}</dd></div>
        <div><dt class="text-white/50 text-sm">{{ __('admin.cms.company') }}</dt><dd class="text-white">{{ $item->company ?: '-' }}</dd></div>
        <div><dt class="text-white/50 text-sm">{{ __('admin.phone') }}</dt><dd class="text-white" dir="ltr">{{ \App\Support\PhoneNumber::display($item->phone) ?: $item->phone }}</dd></div>
        <div><dt class="text-white/50 text-sm">{{ __('admin.email') }}</dt><dd class="text-white">{{ $item->email ?: '-' }}</dd></div>
        <div><dt class="text-white/50 text-sm">{{ __('admin.cms.product') }}</dt><dd class="text-white">{{ $item->product ?: '-' }}</dd></div>
        <div><dt class="text-white/50 text-sm">{{ __('admin.cms.preferred_contact') }}</dt><dd class="text-white">{{ __('admin.cms.contact_' . $item->preferred_contact) }}</dd></div>
        <div><dt class="text-white/50 text-sm">{{ __('admin.cms.received') }}</dt><dd class="text-white">{{ $item->created_at->format('Y-m-d H:i') }}</dd></div>
    </dl>
    @if($item->message)
        <div class="border-t border-white/10 pt-4">
            <p class="text-white/80 whitespace-pre-line leading-relaxed">{{ $item->message }}</p>
        </div>
    @endif
    <div class="flex flex-wrap gap-3 pt-2">
        <a href="{{ \App\Support\PhoneNumber::whatsappUrl($item->phone) }}" target="_blank" rel="noopener noreferrer"
            class="px-5 py-2.5 rounded-lg btn-primary text-white font-medium">{{ __('admin.reply_whatsapp') }}</a>
        <a href="{{ \App\Support\PhoneNumber::telUrl($item->phone) }}" class="px-5 py-2.5 rounded-lg glass text-white font-medium">{{ __('admin.call_phone') }}</a>
        @if($item->email)
            <a href="mailto:{{ $item->email }}" class="px-5 py-2.5 rounded-lg glass text-white font-medium">{{ __('admin.send_email') }}</a>
        @endif
    </div>
</div>
