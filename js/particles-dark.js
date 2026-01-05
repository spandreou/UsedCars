window.particlesDark = {
  particles: {
    number: { value: 80, density: { enable: true, value_area: 900 } },
    color: { value: "#ffffff" },
    shape: { type: "circle" },
    opacity: { value: 0.35 },
    size: { value: 2, random: true },
    line_linked: {
      enable: true,
      distance: 160,
      color: "#ffffff",
      opacity: 0.4,
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
    detect_on: "window",
    events: {
      onhover: { enable: true, mode: "grab" },
      resize: true
    },
    modes: {
      grab: {
        distance: 200,
        line_linked: { opacity: 0.9 }
      }
    }
  },
  retina_detect: true
};
