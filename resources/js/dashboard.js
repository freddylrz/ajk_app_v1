import {
    showGlobalLoading,
    hideGlobalLoading,
} from './initialize.js';

$(document).ready(async function () {
    showGlobalLoading();
    try {
        await getData();
    } catch (e) {
        console.error('Unexpected error on page load:', e);
    } finally {
        hideGlobalLoading();
    }
});


function getData(){
    return $.ajax({
        "url": '/api/v1/dashboard',
        "method": "GET",
        "timeout": 0,
    }).done(async function (res) {
        if (res.status !== 200)
            return;

        const data = res.data;
        const bulanTahun = new Date().toLocaleString('id-ID', {
            month: 'long',
            year: 'numeric'
        });

        $('#bulanBerjalan').text(bulanTahun);
        loadSummary(data.summary);
        loadClaimSummary(data.claim_summary);
        loadClaimCategory(data.claim_category);
        loadYearlyChart(data.yearly);
        loadCategoryChart(data.category);
    });
}

function loadSummary(summary) {
    $('#total_plafond').text(summary.total_plafond);
    $('#total_premium').text(summary.total_premium);
    $('#total_debitur').text(summary.total_debitur);

    $('#total_plafond_month').text(summary.total_plafond_month);
    $('#total_premium_month').text(summary.total_premium_month);
    $('#total_debitur_month').text(summary.total_debitur_month);
}

function loadClaimSummary(claim) {
    $('#total_claim').text(claim.total_claim);
    $('#claim_process').text(claim.claim_process);
    $('#claim_reject').text(claim.claim_reject);
    $('#claim_approve').text(claim.claim_approve);
    $('#claim_paid').text(claim.claim_paid);

}

function loadClaimCategory(data) {

    let html = '';

    data.forEach(item => {

        html += `
            <tr>
                <td>${item.category_name}</td>
                <td class="text-center">${item.debitur}</td>
                <td class="text-center">${item.claim}</td>
            </tr>
        `;

    });

    $('#claimCategoryTable').html(html);

}   
let yearlyChart = null;
let categoryChart = null;

function loadCategoryChart(data = []) {

    if (categoryChart) {
        categoryChart.destroy();
        categoryChart = null;
    }

    $('#categoryChart').empty();

    categoryChart = new ApexCharts(
        document.querySelector("#categoryChart"),
        {
            chart: {
                type: 'donut',
                height: 220
            },

            series: [],

            labels: [],

            noData: {
                text: 'Belum ada data',
                align: 'center',
                verticalAlign: 'middle',
                style: {
                    color: '#6c757d',
                    fontSize: '16px'
                }
            }
        }
    );

    categoryChart.render();

}


function toNumber(value) {
    return parseFloat(String(value).replace(/\./g, '').replace(',', '.'));
}

function loadYearlyChart(data = []) {

    if (yearlyChart) {
        yearlyChart.destroy();
        yearlyChart = null;
    }

    $('#yearlyChart').empty();

    yearlyChart = new ApexCharts(
        document.querySelector("#yearlyChart"),
        {
            chart: {
                type: 'line',
                height: 220,
                toolbar: {
                    show: false
                }
            },

            series: [],

            xaxis: {
                categories: []
            },

            noData: {
                text: 'Belum ada data',
                align: 'center',
                verticalAlign: 'middle',
                style: {
                    color: '#6c757d',
                    fontSize: '16px'
                }
            }
        }
    );

    yearlyChart.render();

}