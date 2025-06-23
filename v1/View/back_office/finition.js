

// Function to enable dark mode
function enableDarkMode() {
  // Set the theme attribute to dark
  document.documentElement.setAttribute("data-bs-theme", "dark");
  // Save dark mode preference to local storage
  localStorage.setItem("theme", "dark");
  // Update dropdown list styles
  updateDropdownListStyles("dark");
}

// Function to enable light mode
function enableLightMode() {
  // Set the theme attribute to light
  document.documentElement.setAttribute("data-bs-theme", "light");
  // Save light mode preference to local storage
  localStorage.setItem("theme", "light");
  // Update dropdown list styles
  updateDropdownListStyles("light");
}

// Function to toggle between light and dark modes
function toggleMode() {
  const currentTheme = document.documentElement.getAttribute("data-bs-theme");
  if (currentTheme === "dark") {
    enableLightMode(); // If dark mode is active, switch to light mode
  } else {
    enableDarkMode(); // If light mode is active, switch to dark mode
  }
}

// Add click event listener to the toggle mode button
darkModeToggle.addEventListener("click", toggleMode);

// Check the user's preference in local storage
const theme = localStorage.getItem("theme");
if (theme === "dark") {
  // Enable dark mode if preference is saved
  enableDarkMode();
} else {
  // Enable light mode by default
  enableLightMode();
}
