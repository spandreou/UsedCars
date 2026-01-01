document.addEventListener("DOMContentLoaded", () => {
  const switchEl = document.getElementById("themeSwitch");
  const body = document.body;

  const savedTheme = localStorage.getItem("theme") || "light";

  function applyTheme(theme) {
    body.setAttribute("data-theme", theme);
    localStorage.setItem("theme", theme);
  }

  // αρχικό theme
  applyTheme(savedTheme);

  if (switchEl) {
    switchEl.checked = savedTheme === "dark";

    switchEl.addEventListener("change", () => {
      applyTheme(switchEl.checked ? "dark" : "light");
    });
  }
});
