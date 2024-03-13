// Mendapatkan elemen teks
var text = document.getElementById('text');

// Atur kecepatan perpindahan teks (pixels per frame)
var speed = 1;

// Atur arah perpindahan teks (1 ke kanan, -1 ke kiri)
var direction = 1;

// Fungsi untuk menggerakkan teks
function moveText() {
  // Dapatkan posisi teks saat ini
  var currentPosition = parseInt(text.style.left) || 0;

  // Tentukan arah perpindahan berdasarkan batas layar
  if (currentPosition >= window.innerWidth - text.offsetWidth) {
    direction = -1;
  } else if (currentPosition <= 0) {
    direction = 1;
  }

  // Hitung posisi baru
  var newPosition = currentPosition + speed * direction;

  // Atur posisi teks baru
  text.style.left = newPosition + 'px';

  // Memanggil fungsi ini lagi untuk membuat animasi berlanjut
  requestAnimationFrame(moveText);
}

// Mulai animasi
moveText();