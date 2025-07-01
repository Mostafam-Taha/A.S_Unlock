document.addEventListener('DOMContentLoaded', function() {
  const homeSection = document.querySelector('.home .container');
  
  setTimeout(() => {
    homeSection.classList.add('visible');
  }, 300);

  window.addEventListener('scroll', function() {
    const sectionPosition = homeSection.getBoundingClientRect().top;
    const screenPosition = window.innerHeight / 1.3;
    
    if(sectionPosition < screenPosition) {
      homeSection.classList.add('visible');
    }
  });
});