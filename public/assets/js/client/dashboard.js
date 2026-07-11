/**
 * ============================================================
 * PAGE: Dashboard Client
 * Sumber data : ClientData.dashboard, ClientData.klaim
 * ============================================================
 */

$(function () {
    const dash = ClientData.dashboard;

    /* ── Kartu ringkasan ── */
    $('#stat-pertanggungan').text(ClientHelper.formatIDR(dash.totalPertanggungan));
    $('#stat-premi').text(ClientHelper.formatIDR(dash.totalPremi));
    $('#stat-debitur').text(ClientHelper.formatNumber(dash.totalDebitur));
    $('#stat-klaim').text(ClientHelper.formatNumber(dash.totalKlaim));
    $('#stat-klaim-detail').text(dash.klaimDiproses + ' diproses, ' + dash.klaimSelesai + ' selesai');

    /* ── Grafik penutupan per bulan ── */
    const chart = new ApexCharts(document.querySelector('#chart-penutupan'), {
        chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [{
            name: 'Jumlah Penutupan',
            data: dash.penutupanPerBulan.map(d => d.jumlah)
        }],
        xaxis: {
            categories: dash.penutupanPerBulan.map(d => d.bulan),
            labels: { style: { fontSize: '14px', fontWeight: 700 } }
        },
        yaxis: { labels: { style: { fontSize: '14px' } } },
        colors: ['#00a651'],
        plotOptions: { bar: { borderRadius: 6, columnWidth: '55%' } },
        dataLabels: {
            enabled: true,
            style: { fontSize: '13px', fontWeight: 800 }
        },
        grid: { borderColor: '#e8eef4' }
    });
    chart.render();

    /* ── Tabel klaim terbaru (5 teratas) ── */
    const rows = ClientData.klaim.slice(0, 5).map(k => `
        <tr>
            <td class="fw-bold">${k.klaimId}</td>
            <td>${k.debitur}</td>
            <td>${k.tanggalLapor}</td>
            <td class="fw-bold">${ClientHelper.formatNumber(k.nilaiKlaim)}</td>
            <td>${ClientHelper.statusLink(k.status, k.statusType, '/client/klaim/detail/' + k.id)}</td>
        </tr>
    `).join('');

    $('#table-klaim-terbaru tbody').html(rows);
});
