window.particlesLight = {
  particles: {
    number: {
      value: 70,
      density: {
        enable: true,
        value_area: 900
      }
    },
    color: {
      value: "#6fa8ff"
    },
    shape: {
      type: "circle"
    },
    opacity: {
      value: 0.4
    },
    size: {
      value: 2,
      random: true
    },
    line_linked: {
      enable: true,
      distance: 150,
      color: "#6fa8ff",
      opacity: 0.35,
      width: 3
    },
    move: {
      enable: true,
      speed: 1.2,
      direction: "none",
      out_mode: "out"
    }
  },
  interactivity: {
    detect_on: "window",
    events: {
      onhover: {
        enable: true,
        mode: "grab"   // 👈 ΤΟ “ΑΡΠΑΓΜΑ”
      },
      resize: true
    },
    modes: {
      grab: {
        distance: 220,
        line_linked: {
          opacity: 0.85
        }
      }
    }
  },
  retina_detect: true
};
