@extends($layout)

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
            @if($notifications->count() > 0)
                <form method="POST" action="{{ route('notifications.read-all') }}" onsubmit="return false;" class="hidden" id="mark-all-form"></form>
                <button type="button" id="mark-all-read" class="text-sm text-teal-600 hover:text-teal-700 font-semibold">
                    Mark all as read
                </button>
            @endif
        </div>

        <div class="space-y-3" id="notification-page-list">
            @forelse($notifications as $notification)
                <div class="p-4 border rounded-lg {{ $notification->read_at ? 'border-gray-200' : 'border-teal-200 bg-teal-50' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-gray-800">{{ $notification->message ?? 'Notification' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if(!empty($notification->url))
                            <a href="{{ $notification->url }}" class="text-xs text-teal-600 hover:text-teal-700 font-semibold">View</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-10">
                    No notifications yet.
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    const markAllButton = document.getElementById('mark-all-read');
    if (markAllButton) {
        markAllButton.addEventListener('click', async () => {
            await fetch("{{ route('notifications.read-all') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            });
            window.location.reload();
        });
    }
</script>
@endpush
@endsection
