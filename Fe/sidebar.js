document.addEventListener("DOMContentLoaded", function () {

    fetch('sidebar.html')
        .then(res => res.text())
        .then(data => {
            document.getElementById('sidebar-container').innerHTML = data;

            const path = window.location.pathname;

            if (path.includes("dashboard")) {
                document.getElementById("menu-dashboard")?.classList.add("active");
            }
            if (path.includes("transaksi")) {
                document.getElementById("menu-transaksi")?.classList.add("active");
            }
            if (path.includes("prediksi")) {
                document.getElementById("menu-prediksi")?.classList.add("active");
            }
            if (path.includes("profil")) {
                document.getElementById("menu-profil")?.classList.add("active");
            }
        });

});