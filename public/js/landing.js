// ========== HAMBURGER MENU ==========
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

if (hamburger) {
    hamburger.addEventListener('click', () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('active');
    });
}

// ========== SCROLL REVEAL (Fade In Up) ==========
const revealSections = document.querySelectorAll('.section');

const revealOnScroll = () => {
    const windowHeight = window.innerHeight;
    revealSections.forEach(section => {
        const sectionTop = section.getBoundingClientRect().top;
        if (sectionTop < windowHeight - 100) {
            section.classList.add('revealed');
        }
    });
};

window.addEventListener('scroll', revealOnScroll);
revealOnScroll();

// ========== BACK TO TOP BUTTON ==========
const backToTop = document.querySelector('.back-to-top');

window.addEventListener('scroll', () => {
    if (window.scrollY > 500) {
        backToTop.classList.add('active');
    } else {
        backToTop.classList.remove('active');
    }
});

backToTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

// ========== TILT EFFECT ON CARDS (lebih halus & fun) ==========
const cards = document.querySelectorAll('.service-card, .feature-card, .highlight-item');

cards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateX = (y - centerY) / 20;
        const rotateY = (centerX - x) / 20;
        card.style.transform = `perspective(500px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-8px)`;
    });
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'perspective(500px) rotateX(0deg) rotateY(0deg) translateY(0)';
    });
});

// ========== FLOATING EMOJI (lebih asik, dikurangi interval) ==========
const createFloatingEmoji = () => {
    const emojis = ['📚', '✨', '🚀', '📖', '🎓', '🌟', '💡', '🤓', '🎉', '⭐', '📘', '🧠'];
    const emoji = document.createElement('div');
    emoji.innerText = emojis[Math.floor(Math.random() * emojis.length)];
    emoji.style.position = 'fixed';
    emoji.style.bottom = '0px';
    emoji.style.left = Math.random() * window.innerWidth + 'px';
    emoji.style.fontSize = '28px';
    emoji.style.opacity = '0.8';
    emoji.style.pointerEvents = 'none';
    emoji.style.zIndex = '9999';
    emoji.style.transition = 'all 5s cubic-bezier(0.2, 0.9, 0.4, 1.1)';
    emoji.style.filter = 'drop-shadow(0 0 6px rgba(38,166,154,0.5))';
    document.body.appendChild(emoji);
    
    setTimeout(() => {
        emoji.style.transform = 'translateY(-100vh) rotate(720deg)';
        emoji.style.opacity = '0';
    }, 50);
    
    setTimeout(() => {
        emoji.remove();
    }, 5000);
};

// Munculkan emoji tiap 15 detik (tidak terlalu ramai)
setInterval(createFloatingEmoji, 15000);

// ========== SMOOTH SCROLL UNTUK ANCHOR ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            e.preventDefault();
            targetElement.scrollIntoView({ behavior: 'smooth' });
            if (navMenu.classList.contains('active')) {
                hamburger.click();
            }
        }
    });
});

// ========== PARTIKEL LATAR BELAKANG YANG BERGERAK ==========
function createParticles() {
    const particleContainer = document.createElement('div');
    particleContainer.className = 'particle-bg';
    document.body.appendChild(particleContainer);
    
    for (let i = 0; i < 60; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        const size = Math.random() * 8 + 2;
        particle.style.width = size + 'px';
        particle.style.height = size + 'px';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 10 + 's';
        particle.style.animationDuration = Math.random() * 15 + 8 + 's';
        particle.style.opacity = Math.random() * 0.4;
        particleContainer.appendChild(particle);
    }
}
createParticles();

// ========== BUBBLE DI HERO SECTION ==========
function createBubbles() {
    const hero = document.querySelector('.hero');
    if (!hero) return;
    for (let i = 0; i < 20; i++) {
        const bubble = document.createElement('div');
        bubble.className = 'bubble';
        const size = Math.random() * 60 + 10;
        bubble.style.width = size + 'px';
        bubble.style.height = size + 'px';
        bubble.style.left = Math.random() * 100 + '%';
        bubble.style.bottom = '-50px';
        bubble.style.animationDelay = Math.random() * 5 + 's';
        bubble.style.animationDuration = Math.random() * 6 + 4 + 's';
        hero.appendChild(bubble);
    }
}
createBubbles();

// ========== CURSOR GLOW EFFECT ==========
const cursorGlow = document.createElement('div');
cursorGlow.className = 'cursor-glow';
document.body.appendChild(cursorGlow);

document.addEventListener('mousemove', (e) => {
    cursorGlow.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
});

// Sembunyikan glow saat mouse keluar window
document.addEventListener('mouseleave', () => {
    cursorGlow.style.opacity = '0';
});
document.addEventListener('mouseenter', () => {
    cursorGlow.style.opacity = '1';
});

// ========== SPARKLE EFFECT SAAT KLIK ==========
function createSparkle(x, y) {
    const sparkle = document.createElement('div');
    sparkle.innerHTML = '✨';
    sparkle.style.position = 'fixed';
    sparkle.style.left = x + 'px';
    sparkle.style.top = y + 'px';
    sparkle.style.fontSize = '20px';
    sparkle.style.pointerEvents = 'none';
    sparkle.style.zIndex = '10000';
    sparkle.style.transition = 'all 0.6s ease-out';
    sparkle.style.opacity = '1';
    document.body.appendChild(sparkle);
    
    setTimeout(() => {
        sparkle.style.transform = 'translateY(-40px) scale(0.5)';
        sparkle.style.opacity = '0';
    }, 10);
    
    setTimeout(() => {
        sparkle.remove();
    }, 600);
}

document.addEventListener('click', (e) => {
    createSparkle(e.clientX, e.clientY);
});

// ========== PARALLAX HALUS UNTUK HERO IMAGE ==========
const heroImage = document.querySelector('.hero-image-wrapper');
if (heroImage) {
    window.addEventListener('mousemove', (e) => {
        const mouseX = e.clientX / window.innerWidth;
        const mouseY = e.clientY / window.innerHeight;
        const moveX = (mouseX - 0.5) * 20;
        const moveY = (mouseY - 0.5) * 20;
        heroImage.style.transform = `translate(${moveX}px, ${moveY}px)`;
    });
    heroImage.addEventListener('mouseleave', () => {
        heroImage.style.transform = 'translate(0, 0)';
    });
}

// ========== ANIMASI TEKS PADA JUDUL ==========
const sectionTitles = document.querySelectorAll('.section-title');
sectionTitles.forEach(title => {
    title.addEventListener('mouseenter', () => {
        title.style.animation = 'wiggle 0.3s ease';
        setTimeout(() => {
            title.style.animation = '';
        }, 300);
    });
});