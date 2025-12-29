@extends('admin.layout')

@section('page-title', 'Dashboard Admin')

@section('content')

{{-- ================= 1. KARTU STATISTIK ================= --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
    {{-- Card: Total Users --}}
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.1), rgba(0, 212, 212, 0.1)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #b0b0b0; margin-bottom: 10px;">Total Users</p>
                <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 32px;">{{ $totalUsers }}</h2>
            </div>
            <i class="bi bi-people-fill" style="font-size: 24px; color: #00d4d4;"></i>
        </div>
    </div>

    {{-- Card: Total Films --}}
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.1), rgba(0, 212, 212, 0.1)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #b0b0b0; margin-bottom: 10px;">Total Film</p>
                <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 32px;">{{ $totalFilms }}</h2>
            </div>
            <i class="bi bi-film" style="font-size: 24px; color: #e94b3c;"></i>
        </div>
    </div>

    {{-- Card: Active Subscriptions --}}
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.1), rgba(0, 212, 212, 0.1)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #b0b0b0; margin-bottom: 10px;">Active Subscriptions</p>
                <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 32px;">{{ $activeSubscriptions }}</h2>
            </div>
            <i class="bi bi-credit-card" style="font-size: 24px; color: #00d4d4;"></i>
        </div>
    </div>

    {{-- Card: Total Watches --}}
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.1), rgba(0, 212, 212, 0.1)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #b0b0b0; margin-bottom: 10px;">Total Watches</p>
                <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 32px;">{{ $totalWatches }}</h2>
            </div>
            <i class="bi bi-eye" style="font-size: 24px; color: #e94b3c;"></i>
        </div>
    </div>
</div>

{{-- ================= 2. AREA GRAFIK ================= --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-bottom: 40px;">

    {{-- Grafik Pendaftar --}}
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.05), rgba(0, 212, 212, 0.05)); border: 1px solid rgba(233, 75, 60, 0.2); padding: 25px; border-radius: 8px;">
        <h3 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px; margin-top: 0;">
            📈 Pendaftar Baru (All Time)
        </h3>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="userChart"></canvas>
        </div>
    </div>

    {{-- Grafik Trafik (Baru) --}}
    <div style="background: linear-gradient(135deg, rgba(0, 212, 212, 0.05), rgba(233, 75, 60, 0.05)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <h3 style="background: linear-gradient(135deg, #00d4d4, #e94b3c); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px; margin-top: 0;">
            🌏 Trafik Pengunjung (Page Views)
        </h3>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="trafficChart"></canvas>
        </div>
    </div>

</div>

{{-- ================= 3. TOP 5 FILM ================= --}}
<div style="margin-bottom: 40px;">
    <h3 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px;">
        🔥 Top 5 Film Paling Sering Ditonton
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
        @foreach($popularFilms as $film)
            <div style="background: rgba(31, 41, 55, 0.6); padding: 15px; border-radius: 8px; border-left: 4px solid #e94b3c; display: flex; align-items: center; justify-content: space-between; border: 1px solid rgba(255,255,255,0.05);">
                <div style="overflow: hidden;">
                    <h5 style="margin: 0; color: #e5e5e5; font-size: 1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                        {{ $film->title }}
                    </h5>

                    <small style="color: #9ca3af;">
                        {{ $film->genre->name ?? 'General' }}
                    </small>
                </div>

                <div style="text-align: right; min-width: 60px;">
                    <span style="font-size: 1.2rem; font-weight: bold; color: #00d4d4;">
                        {{ $film->watch_histories_count }}
                    </span>
                    <br>
                    <small style="font-size: 0.7rem; color: #b0b0b0;">Views</small>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- ================= 4. TABEL SUBSCRIPTION ================= --}}
<div>
    <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px;">📊 Recent Subscriptions</h2>
    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; color: #e5e5e5;">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <th style="padding: 12px; text-align: left;">User</th>
                    <th style="padding: 12px; text-align: left;">Plan</th>
                    <th style="padding: 12px; text-align: left;">Amount</th>
                    <th style="padding: 12px; text-align: left;">Started</th>
                    <th style="padding: 12px; text-align: left;">Expires</th>
                    <th style="padding: 12px; text-align: left;">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($recentSubscriptions as $sub)
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                        <td style="padding: 12px;">{{ $sub->user->name }}</td>
                        <td style="padding: 12px;">{{ $sub->plan->name }}</td>
                        <td style="padding: 12px;">Rp {{ number_format($sub->amount, 0, ',', '.') }}</td>
                        <td style="padding: 12px;">{{ $sub->started_at?->format('d M Y') }}</td>
                        <td style="padding: 12px;">{{ $sub->expires_at?->format('d M Y') }}</td>
                        <td style="padding: 12px;">
                            <span style="background: linear-gradient(135deg, rgba(0, 212, 212, 0.2), rgba(0, 212, 212, 0.1)); color: #00d4d4; padding: 5px 10px; border-radius: 4px; font-size: 12px;">
                                {{ ucfirst($sub->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ================= 5. SCRIPT CHART.JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // --- CHART 1: USER REGISTRATIONS (LINE) ---
        const ctxUser = document.getElementById('userChart').getContext('2d');
        const userLabels = {!! json_encode($dates) !!};
        const userData = {!! json_encode($counts) !!};

        new Chart(ctxUser, {
            type: 'line',
            data: {
                labels: userLabels,
                datasets: [{
                    label: 'User Baru',
                    data: userData,
                    borderColor: '#00d4d4', // Warna Cyan
                    backgroundColor: 'rgba(0, 212, 212, 0.15)',
                    borderWidth: 2,
                    pointBackgroundColor: '#e94b3c', // Warna Merah
                    pointBorderColor: '#fff',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#e5e5e5' } }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { display: false }
                    }
                }
            }
        });

        // --- CHART 2: VISITOR TRAFFIC (BAR) ---
        const ctxTraffic = document.getElementById('trafficChart').getContext('2d');
        // Menggunakan operator null coalescing (??) untuk mencegah error jika data kosong
        const trafficLabels = {!! json_encode($trafficDates ?? []) !!};
        const trafficData = {!! json_encode($trafficCounts ?? []) !!};

        new Chart(ctxTraffic, {
            type: 'bar',
            data: {
                labels: trafficLabels,
                datasets: [{
                    label: 'Page Views',
                    data: trafficData,
                    backgroundColor: 'rgba(233, 75, 60, 0.7)', // Warna Merah Transparan
                    borderColor: '#e94b3c',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: '#00d4d4' // Hover jadi Cyan
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#e5e5e5' } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#9ca3af' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' }
                    },
                    x: {
                        ticks: { color: '#9ca3af' },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection
