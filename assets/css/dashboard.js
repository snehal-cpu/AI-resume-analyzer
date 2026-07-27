const ctx = document.getElementById("atsChart");

if (ctx) {

new Chart(ctx, {

type: "line",

data: {

labels: ["Resume 1","Resume 2","Resume 3","Resume 4","Resume 5","Resume 6"],

datasets: [{

label: "ATS Score",

data: [65,72,78,81,88,94],

fill: true,

borderWidth: 3,

borderColor: "#3b82f6",

backgroundColor: "rgba(59,130,246,.15)",

tension: .4,

pointRadius: 5,

pointHoverRadius: 8,

pointBackgroundColor: "#8b5cf6"

}]

},

options: {

responsive: true,

plugins: {

legend: {

labels: {

color: "#ffffff"

}

}

},

scales: {

x: {

ticks: {

color: "#94a3b8"

},

grid: {

color: "rgba(255,255,255,.05)"

}

},

y: {

beginAtZero: true,

max: 100,

ticks: {

color: "#94a3b8"

},

grid: {

color: "rgba(255,255,255,.05)"

}

}

}

}

});

}
// ==============================
// Quick Action Button Animation
// ==============================

document.querySelectorAll(".action-btn")
.forEach(function(btn){

    btn.addEventListener("mouseenter",function(){

        this.style.transform="translateX(8px)";

    });


    btn.addEventListener("mouseleave",function(){

        this.style.transform="translateX(0)";

    });

});