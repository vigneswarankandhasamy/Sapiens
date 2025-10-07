const posts = [
  {
    title: "Campus Sustainability Initiatives",
    excerpt:
      "How our campus is working towards a greener future with innovative sustainability programs",
    imageSrc: "https://picsum.photos/id/1011/1200/800",
    author: "Environmental Committee",
    date: "June 12, 2023",
    readTime: "5 min",
    url: "#"
  },
  {
    title: "The Impact of VR on Learning",
    excerpt:
      "New study reveals significant improvements in student engagement with VR technology",
    imageSrc: "https://picsum.photos/id/1025/1200/800",
    author: "Tech Research Team",
    date: "June 10, 2023",
    readTime: "7 min",
    url: "#"
  },
  {
    title: "Alumni Spotlight: AI in Healthcare",
    excerpt:
      "Meet the graduate who's revolutionizing patient care with AI-powered diagnostics",
    imageSrc: "https://picsum.photos/id/1024/1200/800",
    author: "Alumni Association",
    date: "June 08, 2023",
    readTime: "9 min",
    url: "#"
  },
  {
    title: "Student Mental Health Resources",
    excerpt:
      "Comprehensive guide to mental health services available to all students",
    imageSrc: "https://picsum.photos/id/1040/1200/800",
    author: "Wellness Center",
    date: "July 12, 2023",
    readTime: "5 min",
    url: "#"
  }
];

let currentIndex = 0;
let direction = 1;
const carousel = document.getElementById("customCarousel");

function createSlide(post, index) {
  const slide = document.createElement("div");
  slide.className = "custom-carousel-slide";
  if (index === currentIndex) slide.classList.add("active");
  slide.style.backgroundImage = `url(${post.imageSrc})`;

  slide.innerHTML = `
      <div class="custom-carousel-overlay"></div>
      <div class="custom-carousel-content">
        <h1><a href="${post.url}" style="color:white;text-decoration:none" target="_blank">${post.title}</a></h1>
        <p>${post.excerpt}</p>
        <div class="author">${post.author} • ${post.date} • ${post.readTime}</div>
        <button class="cssbuttons-io-button">
          Shop Now
          <div class="icon">
            <svg height="24" width="24" viewBox="0 0 24 24">
              <path d="M0 0h24v24H0z" fill="none"></path>
              <path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z" fill="currentColor"></path>
            </svg>
          </div>
        </button>
      </div>
    `;

  return slide;
}


function renderSlides() {
  carousel.innerHTML = "";
  posts.forEach((post, i) => {
    const slide = createSlide(post, i);
    carousel.appendChild(slide);
  });

  const controls = document.createElement("div");
  controls.className = "custom-carousel-controls";

  const dots = document.createElement("div");
  dots.className = "custom-carousel-dots";
  posts.forEach((_, i) => {
    const dot = document.createElement("div");
    dot.className = `custom-carousel-dot ${i === currentIndex ? "active" : ""}`;
    dot.addEventListener("click", () => {
      direction = i > currentIndex ? 1 : -1;
      currentIndex = i;
      updateSlides();
    });
    dots.appendChild(dot);
  });

  const arrows = document.createElement("div");
  arrows.className = "custom-carousel-arrows";
  const prevBtn = document.createElement("button");
  prevBtn.className = "custom-carousel-arrow-btn";
  prevBtn.textContent = "<";
  prevBtn.onclick = () => {
    direction = -1;
    currentIndex = (currentIndex - 1 + posts.length) % posts.length;
    updateSlides();
  };

  const nextBtn = document.createElement("button");
  nextBtn.className = "custom-carousel-arrow-btn";
  nextBtn.textContent = ">";
  nextBtn.onclick = () => {
    direction = 1;
    currentIndex = (currentIndex + 1) % posts.length;
    updateSlides();
  };

  arrows.appendChild(prevBtn);
  arrows.appendChild(nextBtn);
  controls.appendChild(dots);
  controls.appendChild(arrows);
  carousel.appendChild(controls);
}

function updateSlides() {
  const slides = document.querySelectorAll(".custom-carousel-slide");
  slides.forEach((slide, i) => {
    slide.classList.remove("active", "exit-left", "exit-right");
    if (i === currentIndex) {
      slide.classList.add("active");
    } else if (direction === 1) {
      slide.classList.add("exit-left");
    } else {
      slide.classList.add("exit-right");
    }
  });

  const dots = document.querySelectorAll(".custom-carousel-dot");
  dots.forEach((dot, i) => {
    dot.classList.toggle("active", i === currentIndex);
  });
}

setInterval(() => {
  direction = 1;
  currentIndex = (currentIndex + 1) % posts.length;
  updateSlides();
}, 6000);

renderSlides();

        (() => {
    const track = document.getElementById("track");
    const wrap = track.parentElement;
    const cards = Array.from(track.children);
    const prev = document.getElementById("prev");
    const next = document.getElementById("next");
    const dotsBox = document.getElementById("dots");

    const isMobile = () => matchMedia("(max-width:767px)").matches;

    cards.forEach((_, i) => {
        const dot = document.createElement("span");
        dot.className = "dot";
        dot.onclick = () => activate(i, true);
        dotsBox.appendChild(dot);
    });

    const dots = Array.from(dotsBox.children);
    let current = 0;

    function center(i) {
        const card = cards[i];
        const axis = isMobile() ? "top" : "left";
        const size = isMobile() ? "clientHeight" : "clientWidth";
        const start = isMobile() ? card.offsetTop : card.offsetLeft;

        wrap.scrollTo({
        [axis]: start - (wrap[size] / 2 - card[size] / 2),
        behavior: "smooth"
        });
    }

    function toggleUI(i) {
        cards.forEach((c, k) => c.toggleAttribute("active", k === i));
        dots.forEach((d, k) => d.classList.toggle("active", k === i));
        prev.disabled = i === 0;
        next.disabled = i === cards.length - 1;
    }

    function activate(i, scroll) {
        if (i === current) return;
        current = i;
        toggleUI(i);
        if (scroll) center(i);
    }

    function go(step) {
        activate(Math.min(Math.max(current + step, 0), cards.length - 1), true);
    }

    prev.onclick = () => go(-1);
    next.onclick = () => go(1);

    addEventListener(
        "keydown",
        (e) => {
        if (["ArrowRight", "ArrowDown"].includes(e.key)) go(1);
        if (["ArrowLeft", "ArrowUp"].includes(e.key)) go(-1);
        },
        { passive: true }
    );

    cards.forEach((card, i) => {
        card.addEventListener(
        "mouseenter",
        () => matchMedia("(hover:hover)").matches && activate(i, true)
        );
        card.addEventListener("click", () => activate(i, true));
    });

    let sx = 0,
        sy = 0;

    track.addEventListener(
        "touchstart",
        (e) => {
        sx = e.touches[0].clientX;
        sy = e.touches[0].clientY;
        },
        { passive: true }
    );

    track.addEventListener(
        "touchend",
        (e) => {
        const dx = e.changedTouches[0].clientX - sx;
        const dy = e.changedTouches[0].clientY - sy;
        if (isMobile() ? Math.abs(dy) > 60 : Math.abs(dx) > 60) {
            go((isMobile() ? dy : dx) > 0 ? -1 : 1);
        }
        },
        { passive: true }
    );

    if (window.matchMedia("(max-width:767px)").matches) {
        dotsBox.hidden = true;
    }

    addEventListener("resize", () => center(current));

    // ===============================
    // ✅ AUTO SLIDER FUNCTIONALITY
    // ===============================
    let autoSlide = setInterval(() => {
        if (current < cards.length - 1) {
        go(1);
        } else {
        activate(0, true); // restart from beginning
        }
    }, 5000); // 5 seconds

    // Optional: Pause on mouse hover, resume on leave
    wrap.addEventListener("mouseenter", () => clearInterval(autoSlide));
    wrap.addEventListener("mouseleave", () => {
        autoSlide = setInterval(() => {
        if (current < cards.length - 1) {
            go(1);
        } else {
            activate(0, true);
        }
        }, 1300);
    });

    // ===============================
    // ✅ INITIALIZE
    // ===============================
    toggleUI(0);
    center(0);
    })();

    document.addEventListener("DOMContentLoaded", () => {
        const marquee = document.querySelector(".marquee-inner");
        const speed = 1; // Scrolling Speed
        let scrollAmount = 0;
        let isHovered = false;

        // Duplicates the content
        const marqueeContent = marquee.innerHTML;
        marquee.innerHTML += marqueeContent;

        const startScrolling = () => {
            if (!isHovered) {
                scrollAmount -= speed;
                if (Math.abs(scrollAmount) >= marquee.scrollWidth / 2) {
                    scrollAmount = 0;
                }
                marquee.style.transform = `translateX(${scrollAmount}px)`;
            }
            requestAnimationFrame(startScrolling);
        };

        marquee.addEventListener("mouseover", () => {
            isHovered = true;
        });

        marquee.addEventListener("mouseout", () => {
            isHovered = false;
        });

        startScrolling();
    });

    const endTime = new Date();
    endTime.setHours(endTime.getHours() + 48); // Ends in 48 hours

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = endTime - now;

        if (distance < 0) {
        document.getElementById("countdown").innerHTML = "<p>Sale Ended!</p>";
        return;
        }

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").textContent = String(days).padStart(2, "0");
        document.getElementById("hours").textContent = String(hours).padStart(2, "0");
        document.getElementById("minutes").textContent = String(minutes).padStart(2, "0");
        document.getElementById("seconds").textContent = String(seconds).padStart(2, "0");
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();
 
    gsap.registerPlugin(Draggable);

    const container = document.querySelector(".demo-001");
    let cards = [
      document.querySelector(".card-001"),
      document.querySelector(".card-002"),
      document.querySelector(".card-003"),
      document.querySelector(".card-004"),
      document.querySelector(".card-005"),
      document.querySelector(".card-006"),
      document.querySelector(".card-007"),
    ];

    const EASE = "back.out(1.7)";
    const SHADOW = "none";
    const MAX_DRAG_DISTANCE = 300;
    let dragDirection;

    gsap.set(container, { opacity: 1 });

    // INITIAL CONFIGURATION
    const initialCardSettings = [
      { rot: -24, scale: 0.7, origin: "bottom left", opacity: 0, z: 1 },
      { rot: -16, scale: 0.8, origin: "bottom left", z: 2 },
      { rot: -8, scale: 0.9, origin: "bottom left", z: 3 },
      { rot: 0, scale: 1.0, origin: "bottom center", z: 4 },
      { rot: 8, scale: 0.9, origin: "bottom right", z: 3 },
      { rot: 16, scale: 0.8, origin: "bottom right", z: 2 },
      { rot: 24, scale: 0.7, origin: "bottom right", opacity: 0, z: 1 }
    ];

    cards.forEach((card, i) => {
      gsap.set(card, {
        rotation: initialCardSettings[i].rot,
        scale: initialCardSettings[i].scale,
        transformOrigin: initialCardSettings[i].origin,
        opacity: initialCardSettings[i].opacity ?? 1,
        boxShadow: SHADOW,
        zIndex: initialCardSettings[i].z
      });
    });

    // DRAGGABLE SETUP
    let proxy = document.createElement("div");

    Draggable.create(proxy, {
      trigger: ".demo-001",
      type: "x",
      bounds: { minX: -MAX_DRAG_DISTANCE, maxX: MAX_DRAG_DISTANCE },

      onDrag() {
        dragDirection = Math.sign(this.x) === 1 ? "bottom right" : "bottom left";
        const distance = this.x / MAX_DRAG_DISTANCE;
        animateCardsOnDrag(distance);
      },

      onDragEnd() {
        if (Math.abs(this.x) > 50) {
          flipCards();
        }
        resetDraggablePosition();
        gsap.set(this.target, { x: 0 });
      }
    });

    // ANIMATE CARDS ON DRAG
    function animateCardsOnDrag(distance) {
      const d = gsap.utils.clamp(-1, 1, distance);
      const absD = Math.abs(d);

      const dragTweens = [
        { index: 0, rot: -26 + d, scale: (7 + d) / 10, opacity: d / 2 + 0.2 },
        { index: 1, rot: -16 + d * 2, scale: (8 + d) / 10 },
        { index: 2, rot: -8 + d * 4, scale: (9 + d) / 10 },
        {
          index: 3,
          rot: d * 8,
          origin: dragDirection,
          ease: "power4.out",
          boxShadow: `0px 22px ${70 - absD * 20}px 4px rgba(1, 14, 39, ${1 - absD / 4})`
        },
        { index: 4, rot: 8 + d * 4, scale: (-d + 9) / 10 },
        { index: 5, rot: 16 + d * 2, scale: (-d + 8) / 10 },
        { index: 6, rot: 26 + d, scale: (-d + 7) / 10, opacity: -d / 2 + 0.2 }
      ];

      dragTweens.forEach(
        ({ index, rot, scale, opacity, origin, boxShadow, ease }) => {
          gsap.to(cards[index], {
            rotation: gsap.utils.clamp(-30, 30, rot),
            ...(scale !== undefined && { scale: gsap.utils.clamp(0.6, 1, scale) }),
            ...(opacity !== undefined && { opacity }),
            ...(origin && { transformOrigin: origin }),
            ...(boxShadow && { boxShadow }),
            ...(ease !== undefined && { ease })
          });
        }
      );
    }

    // RESET CARDS POSITION
    function resetDraggablePosition() {
      cards.forEach((card, i) => {
        gsap.to(card, {
          rotation: initialCardSettings[i].rot,
          scale: initialCardSettings[i].scale,
          transformOrigin: initialCardSettings[i].origin,
          opacity: initialCardSettings[i].opacity ?? 1,
          boxShadow: SHADOW,
          ease: EASE
        });
      });
    }

    // FLIP CARDS
    function flipCards() {
      if (dragDirection === "bottom right") {
        cards.unshift(cards.pop());
      } else {
        cards.push(cards.shift());
      }

      let zIndex = gsap.utils.distribute({ base: 1, amount: 3, from: "edges" });
      gsap.set(cards, { zIndex });
      resetDraggablePosition();
    }

    // ✅ AUTO SLIDER
    setInterval(() => {
      dragDirection = "bottom right"; // auto move right
      flipCards();
    }, 1500); // every 3 seconds

    const indicatorsUnique = document.querySelectorAll('.indicator-unique');
const slidesUnique = document.getElementById('slides-unique');
let currentUnique = 0;
let intervalUnique = null;

function goToSlideUnique(index) {
  clearTimeout(intervalUnique);
  indicatorsUnique.forEach((ind, i) => {
    ind.classList.toggle('active', i === index);
    const progress = ind.querySelector('.progress-unique');
    progress.style.transition = 'none';
    progress.style.width = '0%';
    void progress.offsetWidth; // Reset transition
    if (i === index) {
      progress.style.transition = 'width 3s linear';
      setTimeout(() => progress.style.width = '100%', 50);
    }
  });

  slidesUnique.style.transform = `translateX(-${index * 100}%)`;
  currentUnique = index;

  intervalUnique = setTimeout(() => {
    goToSlideUnique((currentUnique + 1) % indicatorsUnique.length);
  }, 3000);
}

indicatorsUnique.forEach((indicator, index) => {
  indicator.addEventListener('click', () => goToSlideUnique(index));
});

goToSlideUnique(0);