
$.getScript('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.js', function() {
    var trophyContext = document.getElementById("trophyData");
    var donationsContext = document.getElementById("donationsData");

    var trophyData = new Chart(trophyContext, {
        type: 'line',
        data: {
            labels: DATES,
            datasets: [{
                label: 'Trophy',
                backgroundColor: "rgba(155, 89, 182, 0.5)",
                borderColor: "rgb(155, 89, 182)",
                data: DATATrophy,
                fill: false,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                
                xAxes: [{
                    display: false
                }]
            }
        }
    });

    var donationsData = new Chart(donationsContext, {
        type: 'line',
        data: {
            labels: DATES,
            datasets: [{
                label: 'Troops Donated',
                backgroundColor: "rgba(201, 63, 63, 0.5)",
                borderColor: "rgb(201, 63, 63)",
                data: DATADonations,
                fill: false,
                borderWidth: 1
            }, {
                label: 'Troops Received',
                backgroundColor: "rgba(34, 139, 34, 0.5)",
                borderColor: "rgb(34, 139, 34)",
                fill: false,
                data: DATAReceived,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    display: false
                }]
            }
        }
    });
});