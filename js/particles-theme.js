(function () {
  "use strict";

  const CONTAINER_ID = "particles-js";

  const CONFIG = {
    light: {
      particles: {
        number: { value: 70, density: { enable: true, value_area: 900 } },
        color: { value: "#111111" },
        shape: { type: "circle" },
        opacity: { value: 0.45 },
        size: { value: 2, random: true },
        line_linked: {
          enable: true,
          distance: 180,
          color: "#111111",
          opacity: 0.7,
          width: 2
        },
        move: {
          enable: true,
          speed: 1.1,
          direction: "none",
          out_mode: "out"
        }
      },
      interactivity: {
        detect_on: "window", // 🔥 ΤΟ ΚΛΕΙΔΙ
        events: {
          onhover: { enable: true, mode: "grab" },
          resize: true
        },
        modes: {
          grab: {
            distance: 220,
            line_linked: { opacity: 0.9 }
          }
        }
      },
      retina_detect: true
    },

    dark: {
      particles: {
        number: { value: 80, density: { enable: true, value_area: 900 } },
        color: { value: "#ffffff" },
        shape: { type: "circle" },
        opacity: { value: 0.4 },
        size: { value: 2, random: true },
        line_linked: {
          enable: true,
          distance: 180,
          color: "#ffffff",
          opacity: 0.7,
          width: 1.6
        },
        move: {
          enable: true,
          speed: 1.1,
          direction: "none",
          out_mode: "out"
        }
      },
      interactivity: {
        detect_on: "window", // 🔥
        events: {
          onhover: { enable: true, mode: "grab" },
          resize: true
        },
        modes: {
          grab: {
            distance: 220,
            line_linked: { opacity: 0.9 }
          }
        }
      },
      retina_detect: true
    }
  };

  function getTheme() {
    return document.body.getAttribute("data-theme") === "dark"
      ? "dark"
      : "light";
  }

  function destroy() {
    if (window.pJSDom && window.pJSDom.length) {
      window.pJSDom[0].pJS.fn.vendors.destroypJS();
      window.pJSDom = [];
    }
  }

  function bindGlobalMouse() {
    if (!window.pJSDom || !window.pJSDom.length) return;
    const pJS = window.pJSDom[0].pJS;

    document.addEventListener("mousemove", (e) => {
      pJS.interactivity.mouse.pos_x = e.clientX;
      pJS.interactivity.mouse.pos_y = e.clientY;
      pJS.interactivity.status = "mousemove";
    });
  }

  function load() {
    destroy();
    particlesJS(CONTAINER_ID, CONFIG[getTheme()]);
    bindGlobalMouse();
  }

  document.addEventListener("DOMContentLoaded", () => {
    load();

    new MutationObserver(load).observe(document.body, {
      attributes: true,
      attributeFilter: ["data-theme"]
    });
  });
})();
