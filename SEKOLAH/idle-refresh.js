let idleTime = 0;

// Event listeners untuk reset timer
document.onmousemove = resetTimer;
document.onkeypress = resetTimer;
document.onclick = resetTimer;

function resetTimer() {
    idleTime = 0; // Reset waktu idle
}

// Fungsi untuk mengecek waktu idle
setInterval(function() {
    idleTime++;
    if (idleTime >= 1) { // Jika idle selama 5 menit
        location.reload(); // Refresh halaman
    }
}, 60000); // Cek setiap 1 menit
