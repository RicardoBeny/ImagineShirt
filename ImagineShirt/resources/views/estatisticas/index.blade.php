@extends('template.layout', ['dados' => ['active1' => '',
                                        'active2' => '',
                                        'active3' => '',
                                        'active4' => '']]) 

@section('titulo',' | Estatisticas')

@section('main')

<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__text">
                    <h4>Perfil - {{$user->name}}</h4>
                    <div class="breadcrumb__links">
                        <a href="{{ route('root') }}">Página Inicial</a>
                        <a href="{{ route('user', $user) }}">Perfil</a>
                        <span style = "font-weight: bold;">Estatisticas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="container-fluid mt-5">
    <div class="row mr-5 ml-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Encomendas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$orderNum}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ganhos (Anual)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalSumAno}} €</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ganhos (Mensal)</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$totalSumMes}} €</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Clientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$clientCount}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-info shadow h-100 py-2">
                <div class="card-body">
                <h4 class="card-title d-flex justify-content-center mb-4 text-info">Ganhos Último Ano</h5>
                    <div style="max-height: 400px; overflow-y: auto;">
                        <canvas id="earningsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-info shadow h-100 py-2">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-center mb-4 text-info">Distribuição de Usuários</h5>
                    <div class="chart-container" style="max-height: 1000px; overflow-y: auto;">
                        <canvas id="userDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-md-12 mb-4">
            <div class="card border-warning shadow h-30 py-2">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-center mb-4 text-warning">Encomendas Realizadas no último Ano</h5>
                    <canvas id="encomendasAno"></canvas>
                </div>
            </div>
        </div>
    
        <div class="col-xl-3 col-md-12 mb-4">
            <div class="card border-info shadow h-100 py-2">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-center mb-4 text-info">Últimas Contas Registadas</h5>
                    <div class="chart-container d-flex justify-content-center" style="overflow-y: auto;">
                        <table class="smaller-table">
                            <tbody>
                                @foreach ($usersNovos as $user)
                                    @php    
                                        switch($user->user_type){
                                            case 'A':
                                                $tipoUser = 'Administrador';
                                                break;
                                            case 'E':
                                                $tipoUser = 'Funcionário';
                                                break;
                                            case 'C':
                                                $tipoUser = 'Cliente';
                                                break;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <img src="{{ $user->fullPhotoUrl }}" class="rounded-circle img-responsive" alt="{{ $user->name }}" style="max-width: 100px; max-height: 100px;">
                                        </td>
                                        <td class="text-center align-middle">
                                            <span class="font-weight-bold text-uppercase">{{$tipoUser}}</span><br>{{$user->name}}<br>{{$user->created_at}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-12 mb-4">
            <div class="card border-info shadow h-100 py-2">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-center mb-4 text-info">Ultimas Encomendas</h5>
                <div class="chart-container d-flex justify-content-center" style="max-height: 1000px; overflow-y: auto;">
                    <table class="smaller-table">
                        <tbody>
                        @foreach ($ultimasEncomenda as $ultimaencomenda)
                            <tr class="d-flex mb-3" style="justify-content: center">
                                <td class="d-flex text-center mr-2" style="align-items: center">
                                    <span class="font-weight-bold text-uppercase">ID: {{ $ultimaencomenda->id }}<br></span>
                                </td>
                                <td style="text-align: center !important; "class="text-center align-middle">
                                    <span class="font-weight-bold text-uppercase">{{ $ultimaencomenda->status }}<br></span>{{ $ultimaencomenda->clientes->user->name }}<br>{{$ultimaencomenda->date}}
                                </td>
                                <td class="d-flex text-center mr-2" style="align-items: center">
                                    <span class="font-weight-bold text-uppercase">{{ $ultimaencomenda->total_price }}€<br></span>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    // necessario ficar na vista porque usa variaveis dela
    document.addEventListener('DOMContentLoaded', function() {
    var earningsData = {!! json_encode($earningsData) !!};
    var months = earningsData.map(data => data.month);
    var earnings = earningsData.map(data => data.earnings);

    var ctx2 = document.getElementById('earningsChart').getContext('2d');

    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Ganhos',
                data: earnings,
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return value + ' €';
                        }
                    },
                    gridLines: {
                        display: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            },
            legend: {
                display: false
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var value = tooltipItem.yLabel;
                        return value + ' €';
                    }
                }
            }
        }
    });
});


document.addEventListener('DOMContentLoaded', function() {
    var clientData = {!! json_encode($clientData) !!};
    var labels = clientData.map(data => data.label);
    var percentages = clientData.map(data => data.percentage);

    var ctx = document.getElementById('userDistributionChart').getContext('2d');

    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: percentages,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 205, 86, 0.6)',
                    'rgba(255, 99, 132, 0.6)',
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            legend: {
                position: 'right'
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];;
                        return data.labels[tooltipItem.index] + ': ' + currentValue + '%';
                    }
                }
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var encomendasData = {!! json_encode($encomendasData) !!};
    var months = encomendasData.map(data => data.month);
    var numencomendas = encomendasData.map(data => data.numencomendas);

    var ctx2 = document.getElementById('encomendasAno').getContext('2d');

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Numero de encomendas no último ano',
                data: numencomendas,
                backgroundColor: 'rgba(252,220,4,0.3)',
                borderColor: 'rgba(256,196,4)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                yAxes: [{
                    ticks: {
                        callback: function(value, index, values) {
                            return value ;
                        }
                    },
                    gridLines: {
                        display: false
                    }
                }],
                xAxes: [{
                    gridLines: {
                        display: false
                    }
                }]
            },
            legend: {
                display: false
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        var value = tooltipItem.yLabel;
                        return value;
                    }
                }
            }
        }
    });
});

         
</script>
@endsection
