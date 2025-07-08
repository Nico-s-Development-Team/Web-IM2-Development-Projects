document.addEventListener('DOMContentLoaded', () => {
  const slider = document.getElementById('slider');
  let currentSlide = 0;

  function autoSlide() {
    const slides = slider.children;
    const totalSlides = slides.length;

    currentSlide = (currentSlide + 1) % totalSlides;

    slider.scrollTo({
      left: slider.clientWidth * currentSlide,
      behavior: 'smooth'
    });
  }

  setInterval(autoSlide, 3000);
});


