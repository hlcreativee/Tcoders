@extends('layouts.app')

@section('content')

<div class="header">
    <h2>Dashboard Penjualan</h2>
</div>

<div class="cards">

    <div class="card">
        <h4>Total Revenue</h4>
        <h2 id="total-revenue">-</h2>
    </div>

    <div class="card">
        <h4>Total Transaksi</h4>
        <h2 id="total-trx">-</h2>
    </div>

    <div class="card">
        <h4>Produk Terlaris</h4>
        <h2 id="top-product">-</h2>
    </div>

    <div class="card">
        <h4>Prediksi Berikutnya</h4>
        <h2>
            {{ number_format($prediksi, 0, ',', '.') }}
        </h2>
    </div>

</div>

<div class="chart-box">
    <h3>Trend Penjualan & Prediksi</h3>
    <canvas id="chart"></canvas>
</div>

<h3>Prediksi Penjualan per Produk (Bulan Depan)</h3>
<canvas id="chart-produk"></canvas>

<ul id="prediksi-produk"></ul>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const data = @json($data);
    const prediksi = {{ $prediksi }};

    const formatRupiah = val => "Rp " + Number(val).toLocaleString();
    const formatNumber = val => Number(val).toLocaleString();

    let totalRevenue = 0;
    data.forEach(d => totalRevenue += Number(d.total));

    document.getElementById("total-revenue").innerText = formatRupiah(totalRevenue);
    document.getElementById("total-trx").innerText = data.length;

    const produkCount = {};
    data.forEach(d => {
        produkCount[d.product] = (produkCount[d.product] || 0) + Number(d.qty);
    });

    const sortedProduk = Object.entries(produkCount)
        .sort((a,b)=>b[1]-a[1]);

    const topProduct = sortedProduk.length ? sortedProduk[0][0] : "-";
    document.getElementById("top-product").innerText = topProduct;

    const monthlyData = {};

    data.forEach(d => {
        const date = new Date(d.date + "-01");
        const bulan = date.getFullYear() + '-' +
            (date.getMonth()+1).toString().padStart(2,'0');

        monthlyData[bulan] = (monthlyData[bulan] || 0) + Number(d.qty);
    });

    const labels = Object.keys(monthlyData).sort();
    const qty = labels.map(l => monthlyData[l]);

    const lastDate = new Date(labels[labels.length - 1] + "-01");
    lastDate.setMonth(lastDate.getMonth() + 1);

    const nextMonth = lastDate.getFullYear() + '-' +
        (lastDate.getMonth()+1).toString().padStart(2,'0');

    labels.push(nextMonth);
    qty.push(null);

    const prediksiPerProduk = {};

    sortedProduk.slice(0,5).forEach(([produk, total]) => {
        const ratio = total / Object.values(produkCount).reduce((a,b)=>a+b,0);
        prediksiPerProduk[produk] = Math.round(ratio * prediksi);
    });

    const datasetsPrediksi = Object.keys(prediksiPerProduk).map((produk, i) => {

        const warna = `hsl(${i*60}, 70%, 55%)`;

        return {
            type: 'bar',
            label: produk,
            data: [...Array(qty.length - 1).fill(null), prediksiPerProduk[produk]],
            backgroundColor: warna
        };
    });

    new Chart(document.getElementById("chart"), {
        data: {
            labels: labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'Aktual',
                    data: qty,
                    backgroundColor: '#4e73df'
                },
                ...datasetsPrediksi
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ctx.dataset.label + ": " + formatNumber(ctx.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: val => formatNumber(val)
                    }
                }
            }
        }
    });

    new Chart(document.getElementById("chart-produk"), {
        type: 'bar',
        data: {
            labels: Object.keys(prediksiPerProduk),
            datasets: [{
                label: 'Prediksi per Produk',
                data: Object.values(prediksiPerProduk),
                backgroundColor: '#1cc88a'
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: {
                tooltip: {
                    callbacks: {
                        label: ctx => formatNumber(ctx.raw)
                    }
                }
            }
        }
    });

});
</script>

@endsection