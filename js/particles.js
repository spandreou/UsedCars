document.addEventListener("DOMContentLoaded", () => {

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

  const CONFIG = {
    light: {
      particles: {
        number: { value: 70, density: { enable: true, value_area: 900 } },
        color: { value: "#000000" },
        shape: { type: "circle" },
        opacity: { value: 0.45 },
        size: { value: 2, random: true },
        line_linked: {
          enable: true,
          distance: 180,
          color: "#000000",
          opacity: 0.85,
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
        detect_on: "window", // ✅ ΤΟ ΚΛΕΙΔΙ
        events: {
          onhover: { enable: true, mode: "grab" },
          resize: true
        },
        modes: {
          grab: {
            distance: 240,
            line_linked: { opacity: 1 }
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
          opacity: 0.85,
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
        detect_on: "window", // ✅
        events: {
          onhover: { enable: true, mode: "grab" },
          resize: true
        },
        modes: {
          grab: {
            distance: 240,
            line_linked: { opacity: 1 }
          }
        }
      },
      retina_detect: true
    }
  };

  function load() {
    destroy();
    particlesJS("particles-js", CONFIG[getTheme()]);
  }

  load();

  new MutationObserver(load).observe(document.body, {
    attributes: true,
    attributeFilter: ["data-theme"]
  });

});
