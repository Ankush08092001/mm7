// Optimized JavaScript for MarineMonks Website

// Utility functions
const debounce = (func, delay) => {
  let timeout;
  return function() {
    const context = this;
    const args = arguments;
    clearTimeout(timeout);
    timeout = setTimeout(() => func.apply(context, args), delay);
  };
};

// DOM ready function
document.addEventListener("DOMContentLoaded", function() {
  // Initialize all components
  initNavigation();
  initAnimations();
  initForms();
  initFAQ();
  initLazyLoading();
  
  // Add active class to current page in navigation
  highlightCurrentPage();
});

// Navigation functionality
function initNavigation() {
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  const navLinks = document.querySelector(".nav-links");
  const authButtons = document.querySelector(".auth-buttons");
  
  if (mobileMenuToggle) {
    mobileMenuToggle.setAttribute("aria-expanded", "false");
    mobileMenuToggle.setAttribute("aria-label", "Toggle navigation menu");
    
    mobileMenuToggle.addEventListener("click", function() {
      const isExpanded = navLinks.classList.contains("active");
      navLinks.classList.toggle("active");
      
      // Also toggle auth buttons in mobile view
      if (authButtons) {
        authButtons.classList.toggle("active");
      }
      
      // Update aria attributes for accessibility
      mobileMenuToggle.setAttribute("aria-expanded", isExpanded ? "false" : "true");
    });
    
    // Close menu when clicking outside
    document.addEventListener("click", function(event) {
      if (!event.target.closest(".navbar") && navLinks.classList.contains("active")) {
        navLinks.classList.remove("active");
        if (authButtons) {
          authButtons.classList.remove("active");
        }
        mobileMenuToggle.setAttribute("aria-expanded", "false");
      }
    });
  }
  
  // Smooth scrolling for anchor links
  document.querySelectorAll("a[href^=\"#\"]").forEach(anchor => {
    anchor.addEventListener("click", function(e) {
      const targetId = this.getAttribute("href");
      if (targetId === "#") return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        
        // Calculate header height dynamically
        const headerHeight = document.querySelector("header") ? 
          document.querySelector("header").offsetHeight : 100;
        
        window.scrollTo({
          top: targetElement.offsetTop - headerHeight,
          behavior: "smooth"
        });
        
        // Set focus to the target element for accessibility
        targetElement.setAttribute("tabindex", "-1");
        targetElement.focus({preventScroll: true});
        
        // Close mobile menu if open
        if (navLinks && navLinks.classList.contains("active")) {
          navLinks.classList.remove("active");
          if (authButtons) {
            authButtons.classList.remove("active");
          }
          if (mobileMenuToggle) {
            mobileMenuToggle.setAttribute("aria-expanded", "false");
          }
        }
      }
    });
  });
}

// Animation functionality
function initAnimations() {
  const animateOnScroll = () => {
    const elements = document.querySelectorAll(".animate-on-scroll");
    
    elements.forEach(element => {
      if (element.classList.contains("animated")) return;
      
      const elementPosition = element.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;
      
      if (elementPosition < windowHeight - 50) {
        element.classList.add("animated");
        
        // Add staggered animation for children if they exist
        const children = element.querySelectorAll(".stagger-item");
        if (children.length > 0) {
          children.forEach((child, index) => {
            setTimeout(() => {
              child.classList.add("animated");
            }, 100 * index);
          });
        }
      }
    });
  };
  
  // Use requestAnimationFrame for smoother animations
  let ticking = false;
  window.addEventListener("scroll", function() {
    if (!ticking) {
      window.requestAnimationFrame(function() {
        animateOnScroll();
        ticking = false;
      });
      ticking = true;
    }
  });
  
  // Initial check for animations
  animateOnScroll();
}

// Form validation functionality
function initForms() {
  const loginForm = document.getElementById("login-form");
  const signupForm = document.getElementById("signup-form");

  const isValidEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  const showError = (field, message) => {
    // Remove existing error message first
    const existingError = field.parentNode.querySelector(".error-message");
    if (existingError) {
      existingError.remove();
    }
    
    const errorDiv = document.createElement("div");
    errorDiv.className = "error-message text-red-500 text-sm mt-1";
    errorDiv.innerText = message;
    errorDiv.id = field.id + "-error"; // Assign ID for aria-describedby
    field.parentNode.appendChild(errorDiv);
    field.classList.add("border-red-500");
    
    // Add aria attributes for accessibility
    field.setAttribute("aria-invalid", "true");
    field.setAttribute("aria-describedby", errorDiv.id);
  };

  const clearError = (field) => {
    const errorDiv = field.parentNode.querySelector(".error-message");
    if (errorDiv) {
      errorDiv.remove();
    }
    field.classList.remove("border-red-500");
    field.removeAttribute("aria-invalid");
    field.removeAttribute("aria-describedby");
  };

  const validateLoginForm = (form) => {
    let isValid = true;
    const emailField = form.querySelector("#email");
    const passwordField = form.querySelector("#password");

    // Clear previous errors
    clearError(emailField);
    clearError(passwordField);

    // Validate email
    if (!emailField.value || !isValidEmail(emailField.value)) {
      showError(emailField, "Please enter a valid email address");
      isValid = false;
    }

    // Validate password
    if (!passwordField.value) {
      showError(passwordField, "Please enter your password");
      isValid = false;
    }

    return isValid;
  };

  const validateSignupForm = (form) => {
    let isValid = true;
    const nameField = form.querySelector("#name");
    const emailField = form.querySelector("#email");
    const passwordField = form.querySelector("#password");
    const confirmPasswordField = form.querySelector("#password_confirmation");
    const termsCheckbox = form.querySelector("#terms");

    // Clear previous errors
    clearError(nameField);
    clearError(emailField);
    clearError(passwordField);
    clearError(confirmPasswordField);
    clearError(termsCheckbox); // Clear potential error near checkbox

    // Validate name
    if (!nameField.value) {
      showError(nameField, "Please enter your full name");
      isValid = false;
    }

    // Validate email
    if (!emailField.value || !isValidEmail(emailField.value)) {
      showError(emailField, "Please enter a valid email address");
      isValid = false;
    }

    // Validate password
    if (!passwordField.value || passwordField.value.length < 8) {
      showError(passwordField, "Password must be at least 8 characters");
      isValid = false;
    }

    // Validate password confirmation
    if (!confirmPasswordField.value) {
        showError(confirmPasswordField, "Please confirm your password");
        isValid = false;
    } else if (passwordField.value && confirmPasswordField.value !== passwordField.value) {
      showError(confirmPasswordField, "Passwords do not match");
      isValid = false;
    }

    // Validate terms checkbox
    if (!termsCheckbox.checked) {
      showError(termsCheckbox, "You must agree to the Terms of Service and Privacy Policy");
      isValid = false;
    }

    return isValid;
  };

  // Add validation to login form
  if (loginForm) {
    loginForm.addEventListener("submit", function(e) {
      if (!validateLoginForm(this)) {
        e.preventDefault(); // Prevent submission if validation fails
      } else {
        // Placeholder for successful submission
        e.preventDefault(); // Prevent actual submission for demo
        console.log("Login attempt:", { email: this.querySelector("#email").value });
        alert("Login successful! Redirecting to dashboard...");
        window.location.href = "dashboard/user-dashboard.html";
      }
    });
  }

  // Add validation to signup form
  if (signupForm) {
    signupForm.addEventListener("submit", function(e) {
      if (!validateSignupForm(this)) {
        e.preventDefault(); // Prevent submission if validation fails
      } else {
        // Placeholder for successful submission
        e.preventDefault(); // Prevent actual submission for demo
        console.log("Signup attempt:", { name: this.querySelector("#name").value, email: this.querySelector("#email").value });
        alert("Account created successfully! Redirecting to login...");
        window.location.href = "login.html";
      }
    });
  }
}

// FAQ functionality
function initFAQ() {
  const faqItems = document.querySelectorAll(".faq-item");

  faqItems.forEach((item, index) => {
    const question = item.querySelector(".faq-question");
    const answer = item.querySelector(".faq-answer");
    const icon = question ? question.querySelector("i") : null; // Get the icon element

    if (question && answer && icon) { // Ensure icon exists
      // Add proper ARIA attributes
      question.setAttribute("id", `faq-question-${index}`);
      question.setAttribute("aria-expanded", item.classList.contains("active") ? "true" : "false");
      question.setAttribute("aria-controls", `faq-answer-${index}`);

      answer.setAttribute("id", `faq-answer-${index}`);
      answer.setAttribute("aria-labelledby", `faq-question-${index}`);
      answer.setAttribute("role", "region");

      // Set initial icon state
      if (item.classList.contains("active")) {
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-up");
      } else {
        icon.classList.remove("fa-chevron-up");
        icon.classList.add("fa-chevron-down");
      }

      question.addEventListener("click", () => {
        const isActive = item.classList.contains("active"); // Check state *before* toggling

        // Close all other items
        faqItems.forEach(otherItem => {
          if (otherItem !== item) {
            otherItem.classList.remove("active");
            const otherQuestion = otherItem.querySelector(".faq-question");
            const otherIcon = otherQuestion ? otherQuestion.querySelector("i") : null;
            if (otherQuestion) {
              otherQuestion.setAttribute("aria-expanded", "false");
            }
            if (otherIcon) { // Reset other icons
              otherIcon.classList.remove("fa-chevron-up");
              otherIcon.classList.add("fa-chevron-down");
            }
          }
        });

        // Toggle current item
        item.classList.toggle("active");
        const isNowActive = item.classList.contains("active"); // Check state *after* toggling
        question.setAttribute("aria-expanded", isNowActive ? "true" : "false");

        // Toggle icon class
        if (isNowActive) {
          icon.classList.remove("fa-chevron-down");
          icon.classList.add("fa-chevron-up");
        } else {
          icon.classList.remove("fa-chevron-up");
          icon.classList.add("fa-chevron-down");
        }
      });

      // Add keyboard support
      question.addEventListener("keydown", function(e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          question.click();
        }
      });

      // Make focusable
      question.setAttribute("tabindex", "0");
    }
  });
}

// Lazy loading functionality
function initLazyLoading() {
  const lazyLoadImages = () => {
    const images = document.querySelectorAll("img[data-src]");
    
    images.forEach(img => {
      if (img.getBoundingClientRect().top < window.innerHeight + 200) {
        img.src = img.getAttribute("data-src");
        img.removeAttribute("data-src");
      }
    });
  };
  
  // Initialize lazy loading
  if (document.querySelectorAll("img[data-src]").length > 0) {
    lazyLoadImages();
    window.addEventListener("scroll", debounce(lazyLoadImages, 200));
  }
}

// Highlight current page in navigation
function highlightCurrentPage() {
  const currentPage = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll(".nav-links a");
  
  navLinks.forEach(link => {
    const linkHref = link.getAttribute("href");
    if (linkHref === currentPage || 
        (currentPage === "" && linkHref === "index.html") ||
        (currentPage === "/" && linkHref === "index.html")) {
      link.classList.add("active");
    }
  });
}

// Fix for mock test buttons (already applied in HTML, but good to keep consistent)
document.addEventListener("DOMContentLoaded", function() {
  const startTestButtons = document.querySelectorAll(".start-test-btn");
  if (startTestButtons.length > 0) {
    startTestButtons.forEach(button => {
      if (button.getAttribute("href") === "#") {
        button.setAttribute("href", "mock-test.html");
      }
    });
  }
});

// Fix for test tabs
document.addEventListener("DOMContentLoaded", function() {
  const testTabs = document.querySelectorAll(".test-tab");
  if (testTabs.length > 0) {
    testTabs.forEach(tab => {
      tab.addEventListener("click", function() {
        // Remove active class from all tabs
        testTabs.forEach(t => t.classList.remove("active"));
        // Add active class to clicked tab
        this.classList.add("active");
        
        // In a real application, this would show the corresponding content
        const tabType = this.textContent.trim().includes("Written") ? "Written Tests" : "Oral Tests";
        const sectionTitle = document.querySelector(".section-title h2");
        if (sectionTitle) {
          sectionTitle.innerHTML = tabType === "Written Tests" ? 
            "Written Mock <span class=\"highlight\">Tests</span>" : 
            "Oral Mock <span class=\"highlight\">Tests</span>";
        }
      });
    });
  }
});

