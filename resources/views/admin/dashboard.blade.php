@extends('admin.layout')

@section('page-title', 'Dashboard Admin')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
    <!-- Card: Total Users -->
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.1), rgba(0, 212, 212, 0.1)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #b0b0b0; margin-bottom: 10px;">Total Users</p>
                <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 32px;">{{ $totalUsers }}</h2>
            </div>
            <i class="bi bi-people-fill" style="font-size: 24px; color: #00d4d4;"></i>
        </div>
    </div>

    <!-- Card: Total Films -->
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.1), rgba(0, 212, 212, 0.1)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #b0b0b0; margin-bottom: 10px;">Total Film</p>
                <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 32px;">{{ $totalFilms }}</h2>
            </div>
            <i class="bi bi-film" style="font-size: 24px; color: #e94b3c;"></i>
        </div>
    </div>

    <!-- Card: Active Subscriptions -->
    <div style="background: linear-gradient(135deg, rgba(233, 75, 60, 0.1), rgba(0, 212, 212, 0.1)); border: 1px solid rgba(0, 212, 212, 0.2); padding: 25px; border-radius: 8px;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #b0b0b0; margin-bottom: 10px;">Active Subscriptions</p>
                <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 32px;">{{ $activeSubscriptions }}</h2>
            </div>
            <i class="bi bi-credit-card" style="font-size: 24px; color: #00d4d4;"></i>
        </div>
    </div>

    <!-- Card: Total Watches -->
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
<!-- User Registration Chart -->
<div style="margin-bottom: 40px; background: linear-gradient(135deg, rgba(233, 75, 60, 0.05), rgba(0, 212, 212, 0.05)); border: 1px solid rgba(233, 75, 60, 0.2); padding: 25px; border-radius: 8px;">
    <h3 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px;">
        📈 Statistik Pendaftar Baru (7 Hari Terakhir)
    </h3>
    <div style="position: relative; height: 350px; width: 100%;">
        <canvas id="userChart"></canvas>
    </div>
</div>
{{-- // Popular Films --}}
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
<!-- Recent Subscriptions -->
<div>
    <h2 style="background: linear-gradient(135deg, #e94b3c, #00d4d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 20px;">📊 Recent Subscriptions</h2>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Plan</th>
                <th>Amount</th>
                <th>Started</th>
                <th>Expires</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($recentSubscriptions as $sub)
                <tr>
                    <td>{{ $sub->user->name }}</td>
                    <td>{{ $sub->plan->name }}</td>
                    <td>Rp {{ number_format($sub->amount, 0, ',', '.') }}</td>
                    <td>{{ $sub->started_at?->format('d M Y') }}</td>
                    <td>{{ $sub->expires_at?->format('d M Y') }}</td>
                    <td>
                        <span style="background: linear-gradient(135deg, rgba(0, 212, 212, 0.2), rgba(0, 212, 212, 0.1)); color: #00d4d4; padding: 5px 10px; border-radius: 4px; font-size: 12px;">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('userChart').getContext('2d');

        // Data dari Controller
        const labels = {!! json_encode($dates) !!};
        const data = {!! json_encode($counts) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'User Baru',
                    data: data,
                    // Styling agar sesuai tema gelap Flixplay
                    borderColor: '#00d4d4', // Warna Cyan
                    backgroundColor: 'rgba(0, 212, 212, 0.15)',
                    borderWidth: 3,
                    pointBackgroundColor: '#e94b3c', // Titik merah
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#e94b3c',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#e5e5e5' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#9ca3af' },
                        grid: { color: 'rgba(255, 255, 255, 0.1)' } // Garis tipis
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
