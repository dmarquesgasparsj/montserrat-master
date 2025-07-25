@extends('template')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.js" integrity="sha512-d6nObkPJgV791iTGuBoVC9Aa2iecqzJRE0Jiqvk85BhLHAPhWqkuBiQb1xz2jvuHNqHLYoN3ymPfpiB1o+Zgpw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@section('content')

    <section class="section-padding">
        <div class="jumbotron text-left">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>
                        <span class="grey">{{ $page_title }}</span>
                    </h1>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <form method="GET" action="{{ route('dashboard.statistics') }}">
                            <input type="date" name="start" value="{{ $start->format('Y-m-d') }}" class="form-control mb-2">
                            <input type="date" name="end" value="{{ $end->format('Y-m-d') }}" class="form-control mb-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>
                </div>
                <div class="mb-3">
                    <strong>Average Nights:</strong> {{ $average_nights }}
                </div>
                <div class="container" style="width:50%">
                    <canvas id="mealChart"></canvas>
                </div>
            </div>
        </div>
    </section>

    <script>
        const mealData = {
            labels: @json(array_keys($meal_totals)),
            datasets: [{
                label: 'Meals',
                data: @json(array_values($meal_totals)),
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('mealChart').getContext('2d'), {
            type: 'bar',
            data: mealData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
