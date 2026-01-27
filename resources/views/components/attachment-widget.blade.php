@props(['attachable_type', 'attachable_id', 'attachments' => []])

<div class="zenith-card overflow-hidden">
    <div class="p-6 border-b border-zenith-100 bg-zenith-50/30 flex items-center justify-between">
        <div>
            <h3 class="text-sm font-black uppercase tracking-tight text-zenith-900">Document Vault</h3>
            <p class="text-[10px] text-zenith-400 font-bold uppercase mt-1">Attachments and quality certificates</p>
        </div>
        <button type="button" onclick="document.getElementById('attachment_input').click()" class="zenith-button !py-2 !px-4 text-[10px]">
            UPLOAD NEW
        </button>
        <input type="file" id="attachment_input" class="hidden" onchange="uploadAttachment(this)">
    </div>

    <div class="p-6">
        @if(count($attachments) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($attachments as $attachment)
                <div class="flex items-center gap-3 p-3 bg-zenith-50 rounded-2xl border border-zenith-100 group">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-zenith-500 shadow-zenith-sm shrink-0">
                        @if(Str::contains($attachment->file_type, 'image'))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @elseif(Str::contains($attachment->file_type, 'pdf'))
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 00-2 2z"></path></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        @endif
                    </div>
                    <div class="flex-grow overflow-hidden">
                        <p class="text-xs font-black text-zenith-900 truncate">{{ $attachment->name }}</p>
                        <p class="text-[9px] text-zenith-400 font-bold uppercase">{{ number_format($attachment->file_size / 1024 / 1024, 2) }} MB • {{ strtoupper($attachment->document_type ?? 'Other') }}</p>
                    </div>
                    <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="p-1.5 hover:bg-white rounded-lg text-zenith-400 hover:text-zenith-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        <form action="{{ route('attachments.destroy', $attachment->id) }}" method="POST" onsubmit="zenithConfirmAction(event, 'Dismantle Document', 'Are you sure you want to remove this certificate from the vault?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 hover:bg-white rounded-lg text-red-300 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 border-2 border-dashed border-zenith-100 rounded-3xl">
                <svg class="w-10 h-10 text-zenith-100 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <p class="text-xs font-bold text-zenith-300 uppercase italic">Vault is empty. No certificates detected.</p>
            </div>
        @endif
    </div>
</div>

<script>
function uploadAttachment(input) {
    if (!input.files.length) return;
    
    const formData = new FormData();
    formData.append('file', input.files[0]);
    formData.append('attachable_type', {!! json_encode($attachable_type) !!});
    formData.append('attachable_id', {!! json_encode($attachable_id) !!});
    
    const btn = input.parentElement.querySelector('button');
    const originalText = btn.innerHTML;
    
    // Show loading state...
    btn.innerHTML = '<svg class="w-4 h-4 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83" stroke-width="2" stroke-linecap="round"/></svg>';
    btn.disabled = true;

    fetch('{{ route("attachments.store") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            btn.innerHTML = 'SUCCESS';
            btn.classList.replace('zenith-button', 'bg-green-500');
            setTimeout(() => location.reload(), 800);
        } else {
            ZenithUI.notify('error', data.message || 'Transmission failure.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        ZenithUI.notify('error', 'Critical transmission error.');
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}
</script>
