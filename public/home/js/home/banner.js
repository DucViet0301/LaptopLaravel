let currentIndex = 0;
const images = document.querySelectorAll('.carousel-image');

function showImage(index) {
  images.forEach((img, i) => {
    img.style.display = (i === index) ? "block" : "none";
  });
}

function previousImage() {
  currentIndex = (currentIndex - 1 + images.length) % images.length;
  showImage(currentIndex);
}

function nextImage() {
  currentIndex = (currentIndex + 1) % images.length;
  showImage(currentIndex);
}

// Hiển thị ảnh đầu tiên khi load
showImage(currentIndex);