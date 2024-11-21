import "./css/tailwind.css";
import { renderMenu } from "./js/components/menu";
import { Register } from "./js/modules/register";
import { setupDashboard } from "./js/modules/dashboard";
import { setupBinnacle } from "./js/modules/binnacle";
import { setupHome } from "./js/modules/home";

if (module.hot) {
  module.hot.accept();
}

// Detectar la página actual y ejecutar el módulo correspondiente
document.addEventListener("DOMContentLoaded", () => {
  // Renderizar el menú en todas las páginas
  renderMenu();

  // Detectar la página actual por el path
  const path = window.location.pathname;

  // Normaliza las rutas para que sean consistentes
  const normalizedPath = path.endsWith("/")
    ? "/index.html"
    : path.endsWith(".html")
    ? path
    : `${path}.html`;

  switch (normalizedPath) {
    case "/public/index.html":
    case "/index.html":
      setupHome();
      break;
    case "/public/dashboard.html":
    case "/dashboard.html":
      setupDashboard();
      break;
    case "/public/register.html":
    case "/register.html":
      Register.displayForm();
      Register.setupRegisterForm();
      break;
    case "/public/binnacle.html":
    case "/binnacle.html":
      setupBinnacle();
      break;
    default:
      console.error("Ruta no encontrada:", path);
  }
});

