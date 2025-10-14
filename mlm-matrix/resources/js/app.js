// Enhanced logout handling - ES5 Compatible
function handleLogout(formId) {
  var form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Simple XMLHttpRequest approach for maximum compatibility
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/logout", true);
    xhr.setRequestHeader(
      "X-CSRF-TOKEN",
      document.querySelector('meta[name="csrf-token"]').getAttribute("content")
    );
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("Accept", "application/json");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          // Show success message
          showNotification("Đăng xuất thành công!", "success");

          // Clear all cookies and storage - ES5 compatible
          var cookies = document.cookie.split(";");
          for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i];
            var eqPos = cookie.indexOf("=");
            var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
            document.cookie =
              name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/";
          }

          try {
            localStorage.clear();
            sessionStorage.clear();
          } catch (e) {
            // Ignore storage errors in older browsers
          }

          // Redirect after a short delay - ES5 compatible
          setTimeout(function () {
            window.location.href = "/login";
          }, 1000);
        } else {
          showNotification("Đăng xuất thất bại", "error");
        }
      }
    };

    xhr.send();
  });
}

// Notification system - ES5 Compatible
function showNotification(message, type) {
  // Default parameter for older browsers
  if (typeof type === "undefined") {
    type = "info";
  }``

  // Remove existing notifications - ES5 compatible
  var existing = document.querySelectorAll(".notification");
  for (var i = 0; i < existing.length; i++) {
    existing[i].remove();
  }

  var notification = document.createElement("div");
  notification.className =
    "notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 " +
    getNotificationClasses(type);
  notification.innerHTML =
    '<div class="flex items-center"><i class="fas ' +
    getNotificationIcon(type) +
    ' mr-2"></i><span>' +
    message +
    "</span></div>";

  document.body.appendChild(notification);

  // Auto remove after 3 seconds - ES5 compatible
  setTimeout(function () {
    notification.style.opacity = "0";
    notification.style.transform = "translateX(100%)";
    setTimeout(function () {
      if (notification.parentNode) {
        notification.remove();
      }
    }, 300);
  }, 3000);
}

// Notification classes - ES5 Compatible
function getNotificationClasses(type) {
  var classes = {
    success: "bg-green-500 text-white",
    error: "bg-red-500 text-white",
    warning: "bg-yellow-500 text-white",
    info: "bg-blue-500 text-white",
  };
  return classes[type] || classes.info;
}

// Notification icons - ES5 Compatible
function getNotificationIcon(type) {
  var icons = {
    success: "fa-check-circle",
    error: "fa-exclamation-circle",
    warning: "fa-exclamation-triangle",
    info: "fa-info-circle",
  };
  return icons[type] || icons.info;
}

// Sidebar functionality - ES5 Compatible
document.addEventListener("DOMContentLoaded", function () {
  // Handle sidebar state for mobile - ES5 compatible
  var sidebar = document.querySelector('[x-data*="sidebarOpen"]');
  if (sidebar) {
    // Listen for Alpine.js changes and update body class - ES5 compatible
    var observer = new MutationObserver(function (mutations) {
      for (var i = 0; i < mutations.length; i++) {
        var mutation = mutations[i];
        if (
          mutation.type === "attributes" &&
          mutation.attributeName === "class"
        ) {
          var isOpen = sidebar.classList.contains("translate-x-0");
          if (isOpen) {
            document.body.classList.add("sidebar-open");
          } else {
            document.body.classList.remove("sidebar-open");
          }

          // Auto-focus first link when sidebar opens on mobile - ES5 compatible
          if (isOpen) {
            setTimeout(function () {
              var firstLink = sidebar.querySelector(".sidebar-link");
              if (firstLink) {
                firstLink.focus();
              }
            }, 100);
          }
        }
      }
    });
    observer.observe(sidebar, { attributes: true });
  }

  // Enhanced keyboard navigation for sidebar - ES5 compatible
  document.addEventListener("keydown", function (e) {
    // Only handle keyboard navigation when sidebar is open on mobile
    var sidebar = document.querySelector('[x-data*="sidebarOpen"]');
    if (sidebar && sidebar.classList.contains("translate-x-0")) {
      var links = sidebar.querySelectorAll(".sidebar-link");

      if (e.key === "ArrowDown" || e.key === "Tab") {
        e.preventDefault();
        var linksArray = Array.prototype.slice.call(links);
        var currentIndex = linksArray.indexOf(document.activeElement);
        var nextIndex = currentIndex < links.length - 1 ? currentIndex + 1 : 0;
        links[nextIndex].focus();
      } else if (e.key === "ArrowUp") {
        e.preventDefault();
        var linksArray = Array.prototype.slice.call(links);
        var currentIndex = linksArray.indexOf(document.activeElement);
        var prevIndex = currentIndex > 0 ? currentIndex - 1 : links.length - 1;
        links[prevIndex].focus();
      }
    }
  });
});

// Handle logout forms - ES5 compatible
handleLogout("logout-form");

// Add smooth scrolling for anchor links - ES5 compatible
var anchorLinks = document.querySelectorAll('a[href^="#"]');
for (var i = 0; i < anchorLinks.length; i++) {
  anchorLinks[i].addEventListener("click", function (e) {
    e.preventDefault();
    var target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
}

// Keyboard shortcuts for sidebar - ES5 compatible
document.addEventListener("keydown", function (e) {
  // Escape key closes sidebar on mobile
  if (e.key === "Escape") {
    var sidebar = document.querySelector('[x-data*="sidebarOpen"]');
    if (sidebar && sidebar.classList.contains("translate-x-0")) {
      // Trigger Alpine.js to close sidebar
      var closeBtn = document.querySelector('[x-data*="sidebarOpen"] button');
      if (closeBtn) {
        closeBtn.click();
      }
    }
  }
});
