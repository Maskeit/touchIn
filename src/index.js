import "./css/tailwind.css";
import { renderMenu } from "./js/components/menu";
import { Register } from "./js/modules/register";
import { DashboardModule } from "./js/modules/dashboard";
import { Binnacle } from "./js/modules/binnacle";

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
    case "/public/dashboard.html":
    case "/dashboard.html":
      DashboardModule.setupDashboard();
      break;
    case "/public/register.html":
    case "/register.html":
      Register.setupRegisterForm();
      break;
    case "/public/binnacle.html":
    case "/binnacle.html":
      Binnacle.setupBinnacle();
      break;
    default:
      console.error("Ruta no encontrada:", path);
  }
});

