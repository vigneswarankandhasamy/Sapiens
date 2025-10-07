// Global variables
let currentSlide = 0
let currentTab = "bestselling"
const cart = JSON.parse(localStorage.getItem("cart")) || []
const wishlist = JSON.parse(localStorage.getItem("wishlist")) || []
let isLoggedIn = localStorage.getItem("isLoggedIn") === "true"

// Search placeholders
const searchPlaceholders = [
  "Search for shirts...",
  "Find your perfect jacket...",
  "Looking for pants?",
  "Discover new arrivals...",
  "Search accessories...",
  "Find your style...",
]

// Initialize the website
document.addEventListener("DOMContentLoaded", () => {
  initializeNavigation()
  initializeHeroSlider()
  initializeSearch()
  initializeProductTabs()
  initializeAuth()

  // Initialize testimonials animation
  initializeTestimonials()
})

// Navigation functionality
function initializeNavigation() {
  const mobileToggle = document.querySelector(".mobile-menu-toggle")
  const navMenu = document.querySelector(".nav-menu")
  const backdrop = document.querySelector(".nav-backdrop")

  if (mobileToggle) {
    mobileToggle.addEventListener("click", function () {
      navMenu.classList.toggle("active")
      this.classList.toggle("active")
      backdrop.classList.toggle("active")
    })
  }

  // Close menu when clicking backdrop
  if (backdrop) {
    backdrop.addEventListener("click", () => {
      navMenu.classList.remove("active")
      mobileToggle.classList.remove("active")
      backdrop.classList.remove("active")
    })
  }

  // Dropdown functionality
  const dropdowns = document.querySelectorAll(".dropdown")
  dropdowns.forEach((dropdown) => {
    const toggle = dropdown.querySelector(".dropdown-toggle")
    const menu = dropdown.querySelector(".dropdown-menu")

    if (toggle && menu) {
      toggle.addEventListener("click", (e) => {
        e.preventDefault()
        // Close other open first-level menus in mobile slide
        if (dropdown.closest('.mobile-nav-slide')) {
          dropdowns.forEach((d) => {
            if (d !== dropdown) d.querySelector('.dropdown-menu')?.classList.remove('show')
          })
        }
        menu.classList.toggle("show")
      })
    }
  })

  // Close dropdowns when clicking outside
  document.addEventListener("click", (e) => {
    dropdowns.forEach((dropdown) => {
      if (!dropdown.contains(e.target)) {
        const menu = dropdown.querySelector(".dropdown-menu")
        if (menu) menu.classList.remove("show")
      }
    })
  })
}

// Mobile submenu toggles for multi-level menu
document.addEventListener('DOMContentLoaded', () => {
  const mobileNav = document.getElementById('mobileNav')
  if (!mobileNav) return

  // Tap to expand/collapse submenu
  mobileNav.querySelectorAll('.dropdown-submenu > .submenu-toggle').forEach((toggleEl) => {
    toggleEl.addEventListener('click', (e) => {
      // Allow navigating if link has real destination and not just category header? Prevent default for toggling.
      e.preventDefault()
      const parent = toggleEl.parentElement
      const submenu = parent.querySelector(':scope > .dropdown-menu')

      // Close siblings
      parent.parentElement.querySelectorAll(':scope > .dropdown-submenu').forEach((sib) => {
        if (sib !== parent) {
          sib.classList.remove('open')
          sib.querySelector(':scope > .dropdown-menu')?.classList.remove('show')
        }
      })

      parent.classList.toggle('open')
      submenu?.classList.toggle('show')
    })
  })
})


// Hero slider functionality for images
function initializeHeroSlider() {
  const slides = document.querySelectorAll(".image-slide")
  const dots = document.querySelectorAll(".dot")
  const prevBtn = document.querySelector(".prev-btn")
  const nextBtn = document.querySelector(".next-btn")

  if (slides.length === 0) return

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle("active", i === index)
    })

    dots.forEach((dot, i) => {
      dot.classList.toggle("active", i === index)
    })

    currentSlide = index
  }

  function nextSlide() {
    const next = (currentSlide + 1) % slides.length
    showSlide(next)
  }

  function prevSlide() {
    const prev = (currentSlide - 1 + slides.length) % slides.length
    showSlide(prev)
  }

  // Event listeners
  if (nextBtn) nextBtn.addEventListener("click", nextSlide)
  if (prevBtn) prevBtn.addEventListener("click", prevSlide)

  dots.forEach((dot, index) => {
    dot.addEventListener("click", () => showSlide(index))
  })

  // Auto-play slider every 4 seconds
  setInterval(nextSlide, 4000)
}

// Search functionality
function initializeSearch() {
  const searchInput = document.getElementById("searchInput")
  if (!searchInput) return

  let placeholderIndex = 0

  function changePlaceholder() {
    searchInput.placeholder = searchPlaceholders[placeholderIndex]
    placeholderIndex = (placeholderIndex + 1) % searchPlaceholders.length
  }

  // Change placeholder every 3 seconds
  setInterval(changePlaceholder, 3000)

  // Search functionality
  searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase()
    // Implement search logic here
    console.log("Searching for:", query)
  })

  const searchBtn = document.querySelector(".search-btn")
  if (searchBtn) {
    searchBtn.addEventListener("click", () => {
      const query = searchInput.value.toLowerCase()
      if (query.trim()) {
        // Redirect to shop page with search query
        window.location.href = `shop.html?search=${encodeURIComponent(query)}`
      }
    })
  }
}

// Product tabs functionality
function initializeProductTabs() {
  const tabBtns = document.querySelectorAll(".tab-btn")

  tabBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const tab = this.dataset.tab

      // Update active tab
      tabBtns.forEach((b) => b.classList.remove("active"))
      this.classList.add("active")

      // Load products for selected tab
      currentTab = tab
    })
  })
}

// Generate star rating
function generateStars(rating) {
  const fullStars = Math.floor(rating)
  const hasHalfStar = rating % 1 !== 0
  let stars = ""

  for (let i = 0; i < fullStars; i++) {
    stars += '<i class="fas fa-star"></i>'
  }

  if (hasHalfStar) {
    stars += '<i class="fas fa-star-half-alt"></i>'
  }

  const emptyStars = 5 - Math.ceil(rating)
  for (let i = 0; i < emptyStars; i++) {
    stars += '<i class="far fa-star"></i>'
  }

  return stars
}

// Testimonials animation
function initializeTestimonials() {
  const track = document.querySelector(".testimonial-track")
  if (!track) return

  // Clone testimonials for infinite scroll
  const testimonials = track.innerHTML
  track.innerHTML = testimonials + testimonials
}

// Authentication functionality
function initializeAuth() {
  updateAuthUI()

  const logoutLink = document.querySelector(".logout-link")
  if (logoutLink) {
    logoutLink.addEventListener("click", (e) => {
      e.preventDefault()
      logout()
    })
  }
}

function updateAuthUI() {
  const loginLink = document.querySelector(".login-link")
  const accountLink = document.querySelector(".account-link")
  const logoutLink = document.querySelector(".logout-link")

  if (isLoggedIn) {
    if (loginLink) loginLink.style.display = "none"
    if (accountLink) accountLink.style.display = "block"
    if (logoutLink) logoutLink.style.display = "block"
  } else {
    if (loginLink) loginLink.style.display = "block"
    if (accountLink) accountLink.style.display = "none"
    if (logoutLink) logoutLink.style.display = "none"
  }
}

function logout() {
  localStorage.removeItem("isLoggedIn")
  localStorage.removeItem("userEmail")
  isLoggedIn = false
  updateAuthUI()
  alert("You have been logged out successfully!")
}


// Utility functions
function findProductById(id) {
  for (const category in products) {
    const product = products[category].find((p) => p.id === id)
    if (product) return product
  }
  return null
}

function quickView(productId) {
  const product = findProductById(productId)
  if (product) {
    window.location.href = `product-detail.html?id=${productId}`
  }
}

function showNotification(message, type = "info") {
  // Create notification element
  const notification = document.createElement("div")
  notification.className = `notification notification-${type}`
  notification.innerHTML = `
        <div class="notification-content">
            <span>${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `

  // Add styles
  notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        background: ${type === "success" ? "#28a745" : type === "error" ? "#dc3545" : "#17a2b8"};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        z-index: 10000;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `

  document.body.appendChild(notification)

  // Animate in
  setTimeout(() => {
    notification.style.transform = "translateX(0)"
  }, 100)

  // Close functionality
  const closeBtn = notification.querySelector(".notification-close")
  closeBtn.addEventListener("click", () => {
    notification.style.transform = "translateX(100%)"
    setTimeout(() => {
      document.body.removeChild(notification)
    }, 300)
  })

  // Auto close after 3 seconds
  setTimeout(() => {
    if (document.body.contains(notification)) {
      notification.style.transform = "translateX(100%)"
      setTimeout(() => {
        if (document.body.contains(notification)) {
          document.body.removeChild(notification)
        }
      }, 300)
    }
  }, 3000)
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault()
    const target = document.querySelector(this.getAttribute("href"))
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      })
    }
  })
})

// Navbar scroll effect
window.addEventListener("scroll", () => {
  const navbar = document.querySelector(".navbar")
  if (window.scrollY > 100) {
    navbar.style.background = "rgba(255, 255, 255, 0.95)"
    navbar.style.backdropFilter = "blur(10px)"
  } else {
    navbar.style.background = "#fff"
    navbar.style.backdropFilter = "none"
  }
})

// Add fade-in animation class
const style = document.createElement("style")
style.textContent = `
    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }
`
document.head.appendChild(style)

// Intersection Observer for animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -50px 0px",
}

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("fade-in")
    }
  })
}, observerOptions)

// Observe elements for animation
document.querySelectorAll(".category-card, .video-item, .testimonial-card").forEach((el) => {
  observer.observe(el)
})


const mobileToggle = document.getElementById('mobileToggle');
const mobileNav = document.getElementById('mobileNav');
const closeBtn = document.getElementById('closeMobileNav');

mobileToggle.addEventListener('click', () => {
mobileNav.classList.add('active');
});

closeBtn.addEventListener('click', () => {
mobileNav.classList.remove('active');
document.querySelector(".nav-backdrop").classList.remove("active");
});

// Optional: Close menu when clicking outside (desktop only)
window.addEventListener('click', (e) => {
if (!mobileNav.contains(e.target) && !mobileToggle.contains(e.target)) {
    mobileNav.classList.remove('active');
}
});

   document.addEventListener("DOMContentLoaded", () => {
  const searchBtn = document.querySelector(".nav-icons .fa-search").parentElement;
  const mobileSearch = document.getElementById("mobileSearch");
  const closeSearch = document.getElementById("closeSearch");
  const backdrop = document.querySelector(".nav-backdrop");

  function closeSearchBox() {
    mobileSearch.classList.remove("active");
    backdrop.classList.remove("active");
  }

  // Open search (mobile only)
  searchBtn.addEventListener("click", () => {
    if (window.innerWidth <= 768) {
      mobileSearch.classList.add("active");
      backdrop.classList.add("active");
    }
  });

  // Close search on button click
  closeSearch.addEventListener("click", closeSearchBox);

  // Close search if backdrop clicked
  backdrop.addEventListener("click", closeSearchBox);

  // ✅ Close search if user scrolls
  window.addEventListener("scroll", () => {
    if (mobileSearch.classList.contains("active")) {
      closeSearchBox();
    }
  });
});

function addToCart(productId) {
  alert("Product " + productId + " added to cart!");
}

document.addEventListener("DOMContentLoaded", function () {
  const reviewPopup = document.getElementById("reviewPopup");
  const openBtn = document.getElementById("openReviewPopup");
  const closeBtn = document.getElementById("closeReviewPopup");
  const reviewForm = document.getElementById("reviewForm");
  const reviewsList = document.getElementById("reviewsList");

  // Open Popup
  openBtn.addEventListener("click", () => {
    reviewPopup.style.display = "block";
  });

  // Close Popup
  closeBtn.addEventListener("click", () => {
    reviewPopup.style.display = "none";
  });

  // Close when clicking outside
  window.addEventListener("click", (e) => {
    if (e.target === reviewPopup) {
      reviewPopup.style.display = "none";
    }
  });

  // Submit Review
  reviewForm.addEventListener("submit", function (e) {
    e.preventDefault();

    const name = document.getElementById("name").value;
    const rating = document.getElementById("rating").value;
    const comment = document.getElementById("comment").value;

    // Create review card
    const reviewCard = document.createElement("div");
    reviewCard.classList.add("review-card");
    reviewCard.innerHTML = `
      <h4>${name} - ${"⭐".repeat(rating)}</h4>
      <p>${comment}</p>
    `;

    reviewsList.prepend(reviewCard); // newest review on top

    // Reset form & close popup
    reviewForm.reset();
    reviewPopup.style.display = "none";
  });
});


