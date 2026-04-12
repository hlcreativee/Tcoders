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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const data = @json($data);
const prediksi = {{ $prediksi }};

// =========================
// TOTAL REVENUE
// =========================
let totalRevenue = 0;
data.forEach(d => totalRevenue += Number(d.total));

document.getElementById("total-revenue").innerText =
    "Rp " + totalRevenue.toLocaleString();

// =========================
// TOTAL TRANSAKSI
// =========================
document.getElementById("total-trx").innerText = data.length;

// =========================
// PRODUK TERLARIS
// =========================
const produkCount = {};

data.forEach(d => {
    produkCount[d.product] = (produkCount[d.product] || 0) + Number(d.qty);
});

const topProduct = Object.keys(produkCount).reduce((a,b)=>
    produkCount[a] > produkCount[b] ? a : b
);

document.getElementById("top-product").innerText = topProduct;

// =========================
// CHART
// =========================
const labels = data.map(d => d.date);
const qty = data.map(d => Number(d.qty));

// tambah prediksi
labels.push("Prediksi");
qty.push(null);

const predLine = [...Array(qty.length - 1).fill(null), prediksi];

new Chart(document.getElementById("chart"), {
    data: {
        labels: labels,
        datasets: [
            {
                type: 'bar',
                label: 'Aktual',
                data: qty
            },
            {
                type: 'line',
                label: 'Prediksi',
                data: predLine,
                borderDash: [5,5],
                tension: 0.4
            }
        ]
    }
});

</script>

@endsection