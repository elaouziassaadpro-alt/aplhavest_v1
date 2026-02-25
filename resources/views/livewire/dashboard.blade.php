<div>
    {{-- =============================================
         CUSTOM DASHBOARD STYLES
    ============================================= --}}
    <style>
        .kpi-card {
            border-radius: 16px;
            border: none;
            color: #fff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            overflow: hidden;
            position: relative;
        }
        .kpi-card::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
        }
        .kpi-card::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.2) !important;
        }
        .kpi-card .kpi-icon {
            font-size: 2.5rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }
        .kpi-card .kpi-value {
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
        }
        .kpi-card .kpi-label {
            font-size: 0.82rem;
            opacity: 0.85;
            font-weight: 500;
            letter-spacing: 0.4px;
            text-transform: uppercase;
        }
        .kpi-blue   { background: linear-gradient(135deg, #5D87FF 0%, #3854c7 100%); }
        .kpi-teal   { background: linear-gradient(135deg, #13DEB9 0%, #0ba88c 100%); }
        .kpi-orange { background: linear-gradient(135deg, #FFAE1F 0%, #e08a00 100%); }
        .kpi-red    { background: linear-gradient(135deg, #FA896B 0%, #d85a3a 100%); }
        .kpi-purple { background: linear-gradient(135deg, #ae86f5 0%, #7c3ded 100%); }
        .kpi-slate  { background: linear-gradient(135deg, #5e7597 0%, #3b4f6e 100%); }
        .kpi-pink   { background: linear-gradient(135deg, #f76ca1 0%, #c43b73 100%); }
        .kpi-indigo { background: linear-gradient(135deg, #6574cd 0%, #3d4baa 100%); }

        .chart-card {
            border-radius: 16px;
            border: none;
            box-shadow: 0 2px 18px rgba(0,0,0,0.07);
        }
        .chart-card .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.06);
            padding: 1.2rem 1.5rem;
        }
        .chart-card .card-header h5 {
            font-size: 1rem;
            font-weight: 700;
            margin: 0;
            color: #2d3a4a;
        }
        .chart-card .card-body {
            padding: 1.2rem 1.5rem 1.5rem;
        }
        .risk-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 600;
        }
        .risk-lr { background: rgba(19,222,185,0.15); color: #0ba88c; }
        .risk-mr { background: rgba(255,174,31,0.15); color: #e08a00; }
        .risk-hr { background: rgba(250,137,107,0.15); color: #d85a3a; }

        .table-card { border-radius: 16px; border: none; box-shadow: 0 2px 18px rgba(0,0,0,0.07); }
        .table-card .table thead th {
            background: #f6f9fc;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c84a3;
            border: none;
            padding: 0.85rem 1rem;
        }
        .table-card .table tbody td { padding: 0.85rem 1rem; vertical-align: middle; border-color: #f1f4f8; }
        .op-badge { font-size: 0.75rem; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
        .section-title { font-size: 1.35rem; font-weight: 700; color: #2d3a4a; margin-bottom: 0.2rem; }
        .section-sub   { font-size: 0.85rem; color: #8aa0b8; margin-bottom: 1.25rem; }
    </style>

    <div class="container-fluid px-4 py-4">

        {{-- =============================================
             ROW 1 — KPI CARDS (8 tiles)
        ============================================= --}}
        <h6 class="section-title">Vue d'ensemble</h6>
        <p class="section-sub">Indicateurs clés du système</p>
        <div class="row g-3 mb-4">

            {{-- Établissements --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-blue p-3">
                    <div class="kpi-icon"><i class="ti ti-building"></i></div>
                    <div class="kpi-value">{{ $etablissments->count() }}</div>
                    <div class="kpi-label">Établissements</div>
                </div>
            </div>

            {{-- Actionnaires --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-teal p-3">
                    <div class="kpi-icon"><i class="ti ti-wallet"></i></div>
                    <div class="kpi-value">{{ $actionnaires->count() }}</div>
                    <div class="kpi-label">Actionnaires</div>
                </div>
            </div>

            {{-- Bénéficiaires effectifs --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-purple p-3">
                    <div class="kpi-icon"><i class="ti ti-users"></i></div>
                    <div class="kpi-value">{{ $benificiaireseffectifs->count() }}</div>
                    <div class="kpi-label">Bénéficiaires</div>
                </div>
            </div>

            {{-- Administrateurs --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-orange p-3">
                    <div class="kpi-icon"><i class="ti ti-briefcase"></i></div>
                    <div class="kpi-value">{{ $administrateurs->count() }}</div>
                    <div class="kpi-label">Administrateurs</div>
                </div>
            </div>

            {{-- Contacts --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-indigo p-3">
                    <div class="kpi-icon"><i class="ti ti-phone"></i></div>
                    <div class="kpi-value">{{ $contacts->count() }}</div>
                    <div class="kpi-label">Contacts</div>
                </div>
            </div>

            {{-- Opérations --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-slate p-3">
                    <div class="kpi-icon"><i class="ti ti-arrows-exchange"></i></div>
                    <div class="kpi-value">{{ $operationCount }}</div>
                    <div class="kpi-label">Opérations</div>
                </div>
            </div>

            {{-- PPE --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-red p-3">
                    <div class="kpi-icon"><i class="ti ti-shield-exclamation"></i></div>
                    <div class="kpi-value">{{ $totalPpeCount }}</div>
                    <div class="kpi-label">Total PPE</div>
                </div>
            </div>

            {{-- Dossiers incomplets --}}
            <div class="col-6 col-sm-4 col-md-3 col-xl-3">
                <div class="card kpi-card kpi-pink p-3">
                    <div class="kpi-icon"><i class="ti ti-file-off"></i></div>
                    <div class="kpi-value">{{ $incompleteEtablissementsCount }}</div>
                    <div class="kpi-label">Dossiers incomplets</div>
                </div>
            </div>
        </div>

        {{-- =============================================
             ROW 2 — Main Chart + Risk Distribution
        ============================================= --}}
        <div class="row g-3 mb-4">

            {{-- Monthly Trend Bar Chart --}}
            <div class="col-lg-8">
                <div class="card chart-card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5><i class="ti ti-chart-bar me-2 text-primary"></i>Évolution mensuelle des Établissements</h5>
                        <span class="badge bg-light-primary text-primary fs-2 fw-semibold">6 derniers mois</span>
                    </div>
                    <div class="card-body">
                        <div id="chart-monthly" style="min-height: 290px;"></div>
                    </div>
                </div>
            </div>

            {{-- Risk Donut + breakdown --}}
            <div class="col-lg-4">
                <div class="card chart-card h-100">
                    <div class="card-header">
                        <h5><i class="ti ti-chart-donut me-2 text-warning"></i>Répartition des Risques</h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <div id="chart-risk" style="min-height: 200px; width: 100%;"></div>
                        <div class="w-100 mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                                <span class="risk-badge risk-lr"><span class="rounded-circle bg-success d-inline-block" style="width:8px;height:8px;"></span> Faible (LR)</span>
                                <span class="fw-bold fs-5">{{ $riskCounts['LR'] }}</span>
                            </div>
                            <div class="progress mb-2" style="height:6px;">
                                @php $total = max(1, array_sum($riskCounts)); @endphp
                                <div class="progress-bar bg-success" style="width: {{ round($riskCounts['LR']/$total*100) }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                                <span class="risk-badge risk-mr"><span class="rounded-circle bg-warning d-inline-block" style="width:8px;height:8px;"></span> Moyen (MR)</span>
                                <span class="fw-bold fs-5">{{ $riskCounts['MR'] }}</span>
                            </div>
                            <div class="progress mb-2" style="height:6px;">
                                <div class="progress-bar bg-warning" style="width: {{ round($riskCounts['MR']/$total*100) }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                                <span class="risk-badge risk-hr"><span class="rounded-circle bg-danger d-inline-block" style="width:8px;height:8px;"></span> Élevé (HR)</span>
                                <span class="fw-bold fs-5">{{ $riskCounts['HR'] }}</span>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar bg-danger" style="width: {{ round($riskCounts['HR']/$total*100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================
             ROW 3 — Sector + Country Charts
        ============================================= --}}
        <div class="row g-3 mb-4">
            {{-- Sector Chart --}}
            <div class="col-lg-6">
                <div class="card chart-card h-100">
                    <div class="card-header">
                        <h5><i class="ti ti-category me-2 text-info"></i>Top 5 Secteurs d'Activité</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-sector" style="min-height: 270px;"></div>
                    </div>
                </div>
            </div>

            {{-- Country Chart --}}
            <div class="col-lg-6">
                <div class="card chart-card h-100">
                    <div class="card-header">
                        <h5><i class="ti ti-map-pin me-2 text-success"></i>Top 5 Pays de Résidence</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-country" style="min-height: 270px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================
             ROW 4 — Montant total + Recent Operations
        ============================================= --}}
        <div class="row g-3 mb-4">

            {{-- Montant Total Card --}}
            <div class="col-lg-4">
                <div class="card chart-card h-100">
                    <div class="card-header">
                        <h5><i class="ti ti-currency-dollar me-2 text-primary"></i>Montant Total des Opérations</h5>
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-center gap-3">
                        <div style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,#5D87FF,#3854c7);display:flex;align-items:center;justify-content:center;">
                            <i class="ti ti-cash text-white" style="font-size:2.5rem;"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-primary" style="font-size:1.7rem; line-height:1.1;">
                                {{ number_format($totalOperationsAmount, 0, '.', ' ') }}
                            </div>
                            <div class="text-muted fs-3 fw-semibold mt-1">MAD</div>
                        </div>
                        <div class="d-flex gap-3 w-100 justify-content-center mt-2">
                            <div class="text-center">
                                <div class="fw-bold fs-5 text-dark">{{ $operationCount }}</div>
                                <div class="fs-2 text-muted">Opérations</div>
                            </div>
                            <div class="vr"></div>
                            <div class="text-center">
                                <div class="fw-bold fs-5 text-dark">{{ $etablissments->count() }}</div>
                                <div class="fs-2 text-muted">Établissements</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Operations Table --}}
            <div class="col-lg-8">
                <div class="card table-card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between" style="background:transparent; border-bottom:1px solid rgba(0,0,0,0.06); padding: 1.2rem 1.5rem;">
                        <h5 style="font-size:1rem;font-weight:700;margin:0;color:#2d3a4a;">
                            <i class="ti ti-clock me-2 text-primary"></i>Dernières Opérations
                        </h5>
                        <span class="badge text-bg-light text-muted fs-2">5 récentes</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead>
                                    <tr>
                                        <th>N° Opération</th>
                                        <th>Titre / ISIN</th>
                                        <th>Qté</th>
                                        <th>Montant Net</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOperations as $operation)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-primary">{{ $operation->operation_number }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ Str::limit($operation->titre, 20) }}</div>
                                            <div class="fs-2 text-muted">{{ $operation->code_isin }}</div>
                                        </td>
                                        <td class="text-nowrap">{{ number_format($operation->quantite) }}</td>
                                        <td class="fw-semibold text-nowrap">{{ number_format($operation->montant_net, 2) }} MAD</td>
                                        <td class="text-muted text-nowrap">{{ $operation->date_operation->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">Aucune opération récente</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- =============================================
             ROW 5 — Recent Files List
        ============================================= --}}
        <div class="row g-3">
            <div class="col-12">
                <div class="card table-card">
                    <div class="card-header d-flex align-items-center justify-content-between" style="background:transparent; border-bottom:1px solid rgba(0,0,0,0.06); padding: 1.2rem 1.5rem;">
                        <h5 style="font-size:1rem;font-weight:700;margin:0;color:#2d3a4a;">
                            <i class="ti ti-files me-2 text-info"></i>Fichiers OPC Récents
                        </h5>
                        <span class="badge text-bg-light text-muted fs-2">{{ $recentFiles->count() }} fichier(s)</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead>
                                    <tr>
                                        <th>Fichier</th>
                                        <th>Établissement</th>
                                        <th>Date d'ajout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentFiles as $file)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div style="width:34px;height:34px;border-radius:8px;background:#eef2ff;display:flex;align-items:center;justify-content:center;">
                                                    <i class="ti ti-file-text text-primary fs-5"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark">OPC #{{ $file->id }}</div>
                                                    <div class="d-flex gap-1 mt-1 flex-wrap">
                                                        @if($file->opc) <span class="badge text-bg-primary" style="font-size:0.68rem;">OPC</span> @endif
                                                        @if($file->ni)  <span class="badge text-bg-info"    style="font-size:0.68rem;">NI</span>  @endif
                                                        @if($file->fs)  <span class="badge text-bg-success" style="font-size:0.68rem;">FS</span>  @endif
                                                        @if($file->rg)  <span class="badge text-bg-warning text-dark" style="font-size:0.68rem;">RG</span>  @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ optional($file->etablissement)->name ?? '—' }}</td>
                                        <td class="text-muted text-nowrap">{{ $file->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Aucun fichier récent</td>
                                    </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- end container-fluid --}}

    {{-- =============================================
         APEXCHARTS INITIALIZATION
    ============================================= --}}
    @push('scripts')
    <script>
        // Re-initialize after Livewire navigation
        document.addEventListener('livewire:navigated', initializeDashboard);

        function initializeDashboard() {
            if (typeof ApexCharts === 'undefined') return;
            initCarousel();
            renderMonthlyChart();
            renderRiskChart();
            renderSectorChart();
            renderCountryChart();
        }

        function initCarousel() {
            if ($(".counter-carousel").length && typeof $.fn.owlCarousel === 'function') {
                $(".counter-carousel").trigger('destroy.owl.carousel');
                $(".counter-carousel").owlCarousel({
                    loop: true, margin: 30, mouseDrag: true, autoplay: true,
                    autoplayTimeout: 4000, autoplaySpeed: 2000, nav: false, rtl: true,
                    responsive: { 0:{items:2}, 576:{items:2}, 768:{items:3}, 1200:{items:5}, 1400:{items:6} }
                });
            }
        }

        function destroyChart(id) {
            var el = document.querySelector(id);
            if (el && el._chart) { el._chart.destroy(); }
        }

        function renderMonthlyChart() {
            destroyChart('#chart-monthly');
            var labels = {!! json_encode(array_column($monthlyTrend, 'month')) !!};
            var data   = {!! json_encode(array_column($monthlyTrend, 'count')) !!};
            var chart = new ApexCharts(document.querySelector('#chart-monthly'), {
                chart: { type: 'area', height: 290, toolbar: { show: false }, sparkline: { enabled: false } },
                series: [{ name: 'Nouveaux Établissements', data: data }],
                xaxis: { categories: labels, axisBorder: { show: false }, axisTicks: { show: false } },
                yaxis: { min: 0, labels: { formatter: (v) => Math.round(v) } },
                fill: {
                    type: 'gradient',
                    gradient: { shadeIntensity: 1, opacityFrom: 0.55, opacityTo: 0.05, stops: [0, 100] }
                },
                colors: ['#5D87FF'],
                stroke: { curve: 'smooth', width: 3 },
                dataLabels: { enabled: false },
                grid: { borderColor: '#f1f4f8', strokeDashArray: 4 },
                tooltip: { theme: 'light' },
            });
            chart.render();
            document.querySelector('#chart-monthly')._chart = chart;
        }

        function renderRiskChart() {
            destroyChart('#chart-risk');
            var lr = {{ $riskCounts['LR'] }};
            var mr = {{ $riskCounts['MR'] }};
            var hr = {{ $riskCounts['HR'] }};
            if (lr + mr + hr === 0) return;
            var chart = new ApexCharts(document.querySelector('#chart-risk'), {
                chart: { type: 'donut', height: 200, toolbar: { show: false } },
                series: [lr, mr, hr],
                labels: ['Faible (LR)', 'Moyen (MR)', 'Élevé (HR)'],
                colors: ['#13DEB9', '#FFAE1F', '#FA896B'],
                legend: { show: false },
                dataLabels: { enabled: true, style: { fontSize: '12px' } },
                plotOptions: { pie: { donut: { size: '68%', labels: {
                    show: true,
                    total: { show: true, label: 'Total', formatter: () => (lr + mr + hr) }
                }}}},
                stroke: { width: 0 },
                tooltip: { theme: 'light' },
            });
            chart.render();
            document.querySelector('#chart-risk')._chart = chart;
        }

        function renderSectorChart() {
            destroyChart('#chart-sector');
            var labels = {!! json_encode($sectorDistribution->pluck('libelle')) !!};
            var data   = {!! json_encode($sectorDistribution->pluck('count')) !!};
            if (!labels.length) return;
            var chart = new ApexCharts(document.querySelector('#chart-sector'), {
                chart: { type: 'bar', height: 270, toolbar: { show: false } },
                series: [{ name: 'Établissements', data: data }],
                colors: ['#5D87FF', '#13DEB9', '#FFAE1F', '#FA896B', '#ae86f5'],
                plotOptions: { bar: { borderRadius: 6, horizontal: false, columnWidth: '50%',
                    distributed: true } },
                dataLabels: { enabled: false },
                xaxis: { categories: labels, axisBorder: { show: false }, axisTicks: { show: false },
                    labels: { style: { fontSize: '11px' } } },
                yaxis: { labels: { formatter: (v) => Math.round(v) } },
                legend: { show: false },
                grid: { borderColor: '#f1f4f8', strokeDashArray: 4 },
                tooltip: { theme: 'light' },
            });
            chart.render();
            document.querySelector('#chart-sector')._chart = chart;
        }

        function renderCountryChart() {
    destroyChart('#chart-country');

    const labels = @json($top5Pays->pluck('libelle'));
    const data   = @json($top5Pays->pluck('total'));

    if (!labels || labels.length === 0) return;

    const chart = new ApexCharts(
        document.querySelector('#chart-country'),
        {
            chart: {
                type: 'bar',
                height: 270,
                toolbar: { show: false }
            },
            series: [{
                name: 'Établissements',
                data: data
            }],
            colors: ['#13DEB9'],
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: true,
                    barHeight: '50%'
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                labels: {
                    formatter: (val) => Math.round(val)
                },
                axisBorder: { show: false }
            },
            yaxis: {
                categories: labels,
                labels: {
                    style: { fontSize: '12px' }
                }
            },
            grid: {
                borderColor: '#f1f4f8',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'light'
            }
        }
    );

    chart.render();
    document.querySelector('#chart-country')._chart = chart;
}

        // First-load init
        $(function() { initializeDashboard(); });
    </script>
    @endpush
</div>
