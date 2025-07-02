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

// تأثير قسم Services
document.addEventListener('DOMContentLoaded', function() {
    // إنشاء مراقب للعناصر
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // إذا ظهر العنصر على الشاشة
                if (entry.target.classList.contains('title-se')) {
                    // تأثير للعنوان والنص الفرعي
                    entry.target.querySelector('h1').classList.add('visible');
                    entry.target.querySelector('p').classList.add('visible');
                } else if (entry.target.classList.contains('se-svg')) {
                    // تأثير للأيقونات
                    entry.target.querySelector('svg').classList.add('visible');
                } else if (entry.target.classList.contains('card')) {
                    // تأثير للبطاقات
                    entry.target.classList.add('visible');
                }
            }
        });
    }, {
        threshold: 0.1 // عندما يكون 10% من العنصر مرئيًا
    });

    // مراقبة العناصر
    document.querySelectorAll('.title-se, .card, .se-svg').forEach(el => {
        observer.observe(el);
    });
});

// تاثير قسم Product
document.addEventListener('DOMContentLoaded', function() {
    const productSection = document.querySelector('.product');
    
    // إنشاء مراقب متقدم
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const titleProd = entry.target.querySelector('.title-prod');
                const cards = entry.target.querySelectorAll('.card-pr');
                
                // تفعيل تأثير العنوان
                if (titleProd) {
                    titleProd.querySelector('h1').classList.add('visible');
                    titleProd.querySelector('p').classList.add('visible');
                }
                
                // تفعيل تأثير البطاقات بشكل متدرج
                cards.forEach((card, index) => {
                    setTimeout(() => {
                        card.classList.add('visible');
                    }, index * 200); // تأخير 200ms بين كل بطاقة
                });
                
                // إيقاف المراقبة بعد التنفيذ لتحسين الأداء
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px' // تحميل التأثير قبل 100px من الوصول للقسم
    });

    if (productSection) {
        observer.observe(productSection);
    }
});

// تأثير قسم Team
document.addEventListener('DOMContentLoaded', function() {
    const teamObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const title = entry.target.querySelector('.title-te');
                const members = entry.target.querySelectorAll('.grid-team');
                
                // تأثير العنوان
                if (title) {
                    title.querySelector('h1').classList.add('visible');
                    setTimeout(() => {
                        title.querySelector('p').classList.add('visible');
                    }, 300);
                }
                
                // تأثير أعضاء الفريق
                members.forEach((member, index) => {
                    setTimeout(() => {
                        member.classList.add('visible');
                    }, index * 200 + 500); // تأخير إضافي بعد العنوان
                });
                
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.2,
        rootMargin: '0px 0px -50px 0px'
    });

    const teamSection = document.querySelector('.team');
    if (teamSection) {
        teamObserver.observe(teamSection);
    }
});

// انتهاء صفحة index.php بفضل الله