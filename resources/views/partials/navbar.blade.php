<header class="navbar" style="height: 70px; background: #ffffff; border-bottom: 1px solid #e8edf5; display: flex; align-items: center; justify-content: space-between; padding: 0 32px; position: sticky; top: 0; z-index: 1000 !important; box-sizing: border-box;">
    
    <div style="display: flex; align-items: center; gap: 16px;">
        <button type="button" id="sidebarToggle" style="background: #f8fafc; border: 1px solid #e8edf5; width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; color: #172033; font-size: 18px; cursor: pointer; position: relative; z-index: 99999 !important; pointer-events: auto !important;">
            <i class="bi bi-list"></i>
        </button>

        <div>
            <h1 style="font-size: 16px; font-weight: 800; color: #172033; margin: 0 0 2px 0; letter-spacing: -0.2px;">Dashboard Owner</h1>
            <p style="font-size: 11px; font-weight: 600; color: #778195; margin: 0;">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </p>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 12px;">

        {{-- DROPDOWN NOTIFIKASI --}}
        @auth
        @php
        $myNotifications = auth()->user()->notifications()
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();

        $unreadCount = auth()->user()->notifications()
            ->whereNull('read_at')
            ->count();
        @endphp

        <div class="notification-dropdown">
            <button type="button" class="btn-bell">
                🔔
                @if($unreadCount > 0)
                <span class="badge">{{ $unreadCount }}</span>
                @endif
            </button>

            <div class="dropdown-box">
                <div style="padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                    <span style="font-size: 12px; font-weight: 700; color: #172033;">Notifikasi Baru</span>
                    @if($unreadCount > 0)
                    <form action="{{ route('frontoffice.notifications.readAll') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #006B3F; font-size: 10px; font-weight: 700; cursor: pointer; padding: 0;">
                            Tandai semua dibaca
                        </button>
                    </form>
                    @endif
                </div>

                <div style="max-height: 280px; overflow-y: auto; display: flex; flex-direction: column;">
                    @forelse($myNotifications as $notif)
                    <div class="notif-item">
                        <div class="notif-item-content">
                            <strong>
                                <span style="width: 6px; height: 6px; background: #22c55e; border-radius: 50%; display: inline-block; flex-shrink: 0;"></span>
                                {{ $notif->title }}
                            </strong>
                            <p>{{ $notif->body }}</p>
                            <small>{{ $notif->created_at->diffForHumans() }}</small>
                        </div>
                        <form action="{{ route('frontoffice.notifications.read', $notif->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="notif-item-mark-btn" title="Tandai dibaca">
                                ✓
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="notif-item-empty">
                        Tidak ada notifikasi baru.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endauth

        <div style="width: 1px; height: 24px; background: #e8edf5; margin: 0 4px;"></div>

        <div style="display: flex; align-items: center;">
            <img src="{{ asset('images/foto-perusahaan.jpg') }}" alt="Logo Perusahaan" style="width: 130px; height: 38px; object-fit: contain; display: block;">
        </div>

    </div>

</header>