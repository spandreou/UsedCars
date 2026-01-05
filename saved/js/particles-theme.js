(function () {
  "use strict";

  const CONTAINER_ID = "particles-js";

  // Light: μαύρα particles / Dark: άσπρα particles
  const CONFIG = {
    light: {
      particles: {
        number: { value: 65, density: { enable: true, value_area: 900 } },
        color: { value: "#111111" },
        shape: { type: "circle" },
        opacity: { value: 0.35 },
        size: { value: 2, random: true },
        line_linked: {
          enable: true,
          distance: 160,
          color: "#111111",
          opacity: 0.25,
          width: 1
        },
        move: { enable: true, speed: 1.1, direction: "none", out_mode: "out" }
      },
      interactivity: {
        detect_on: "canvas",
        events: {
          onhover: { enable: true, mode: "grab" },
          onclick: { enable: false },
          resize: true
        },
        modes: {
          grab: { distance: 180, line_linked: { opacity: 0.55 } }
        }
      },
      retina_detect: true
    },

    dark: {
      particles: {
        number: { value: 75, density: { enable: true, value_area: 900 } },
        color: { value: "#ffffff" },
        shape: { type: "circle" },
        opacity: { value: 0.35 },
        size: { value: 2, random: true },
        line_linked: {
          enable: true,
          distance: 160,
          color: "#ffffff",
          opacity: 0.28,
          width: 1.8
        },
        move: { enable: true, speed: 1.15, direction: "none", out_mode: "out" }
      },
      interactivity: {
        detect_on: "canvas",
        events: {
          onhover: { enable: true, mode: "grab" },
          onclick: { enable: false },
          resize: true
        },
        modes: {
          grab: { distance: 180, line_linked: { opacity: 0.7 } }
        }
      },
      retina_detect: true
    }
  };

  function getTheme() {
    return document.body.getAttribute("data-theme") === "dark" ? "dark" : "light";
  }

  function destroyParticles() {
    if (window.pJSDom && window.pJSDom.length) {
      window.pJSDom[0].pJS.fn.vendors.destroypJS();
      window.pJSDom = [];
    }
  }

  function loadParticles(theme) {
    const el = document.getElementById(CONTAINER_ID);
    if (!el) return;

    // αν το particles.js δεν φορτώθηκε, μην σκάει σιωπηλά
    if (typeof window.particlesJS !== "function") {
      console.error("particlesJS not found. Check particles.min.js include order.");
      return;
    }

    destroyParticles();
    window.particlesJS(CONTAINER_ID, CONFIG[theme]);
  }

  function init() {
    loadParticles(getTheme());

    // κάθε φορά που αλλάζει data-theme, ξαναφορτώνουμε particles
    const obs = new MutationObserver(() => {
      loadParticles(getTheme());
    });

    obs.observe(document.body, {
      attributes: true,
      attributeFilter: ["data-theme"]
    });
  }

  document.addEventListener("DOMContentLoaded", init);
})();
