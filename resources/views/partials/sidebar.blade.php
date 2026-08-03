<div class="sidebar">

    <div class="logo">

        <div class="logo-icon">

            IT

        </div>

        <div>

            <h4>IT Solution</h4>

            <small>Guest Management</small>

        </div>

    </div>

    <ul>

        <li class="active">
            Dashboard
        </li>

        <li>
            Check-in Tamu
        </li>

        <li>
            Daftar Kunjungan
        </li>

        <li>
            Database Tamu
        </li>

        <li>
            Lead & Follow Up
        </li>

        <li>
            Laporan
        </li>

        <li>
            Master Data
        </li>

        <li>
            Pengguna
        </li>

        <form action="{{ route('logout') }}" method="get" class="block">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 hover:bg-white/10 rounded transition text-xs text-white">
                <i class="fas fa-sign-out-alt w-4 text-center transform rotate-180"></i>
                <span>Keluar</span>
            </button>
        </form>

    </ul>

</div>