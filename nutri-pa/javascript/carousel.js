

const carousel = document.querySelector(".carousel");
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");

let direction;
//fjeofiejfioeji

prevBtn.addEventListener("click", () => {
  direction = -1;
  carousel.style.transform = `translateX(${direction * 100}%)`;
});

nextBtn.addEventListener("click", () => {
  direction = 1;
  carousel.style.transform = `translateX(${direction * 100}%)`;
});

carousel.addEventListener("transitionend", () => {
  if (direction === 1) {
    const firstImg = carousel.querySelector("img:first-child");
    carousel.appendChild(firstImg);
  } else if (direction === -1) {
    const lastImg = carousel.querySelector("img:last-child");
    carousel.prepend(lastImg);
  }
  carousel.style.transition = "none";
  carousel.style.transform = "translateX(0)";
  setTimeout(() => {
    carousel.style.transition = "transform 0.5s";
  });
});

// const carouselImages = document.querySelector('.carousel-images');
// const prevButton = document.querySelector('.prev-button');
// const nextButton = document.querySelector('.next-button');

// let counter = 0;
// const size = carouselImages.children[0].clientWidth;

// prevButton.addEventListener('click', () => {
//   if (counter > 0) {
//     counter--;
//     carouselImages.style.transform = `translateX(${-size * counter}px)`;
//   }
// });

// nextButton.addEventListener('click', () => {
//   if (counter < carouselImages.children.length - 1) {
//     counter++;
//     carouselImages.style.transform = `translateX(${-size * counter}px)`;
    
//   }
// });
