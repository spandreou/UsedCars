particlesJS("particles-js", {
  particles: {
    number: {
      value: 85,
      density: {
        enable: true,
        value_area: 900
      }
    },

    color: {
      value: "#6fa0ff"
    },

    shape: {
      type: "circle"
    },

    opacity: {
      value: 0.7,
      random: false
    },

    size: {
      value: 3,
      random: true
    },

    line_linked: {
      enable: true,
      distance: 160,
      color: "#4f86ff",
      opacity: 0.85,
      width: 3
    },

    move: {
      enable: true,
      speed: 1.1,
      direction: "none",
      random: false,
      straight: false,
      out_mode: "out"
    }
  },

  interactivity: {
    events: {
      onhover: {
        enable: true,
        mode: "grab"
      },
      onclick: {
        enable: false
      }
    },

    modes: {
      grab: {
        distance: 200,
        line_linked: {
          opacity: 1
        }
      }
    }
  },

  retina_detect: true
});
