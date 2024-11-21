import Auth from "../classes/Auth";
import { V_Global } from "../components/system";
// Inicializa la clase Auth con la URL base del backend
const auth = new Auth(V_Global); // Cambia esta URL según tu backend

/**
 * Lógica para manejar el registro del usuario.
 */
export const Register = {
  setupRegisterForm() {
    const form = document.getElementById("registro-form");
    const responseElement = document.getElementById("response");

    if (!form) {
      console.error("Formulario de registro no encontrado");
      return;
    }

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      responseElement.textContent = "Procesando...";
      responseElement.classList.add("text-gray-500");

      const userData = {
        name: form.name.value,
        email: form.email.value,
        pin: form.pin.value,
      };

      try {
        const response = await auth.register(userData);
        responseElement.textContent = `Usuario registrado con éxito: ${response.message}`;
        responseElement.classList.replace("text-gray-500", "text-green-500");
      } catch (error) {
        responseElement.textContent = `Error: ${error.message}`;
        responseElement.classList.replace("text-gray-500", "text-red-500");
      }
    });

    this.displayForm();
  },

  displayForm() {
    const appElement = document.getElementById("app"); // Asegúrate de tener un contenedor con este ID
    if (!appElement) {
      console.error(
        "No se encontró el contenedor principal para el formulario."
      );
      return;
    }

    appElement.innerHTML = `
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md mx-auto">
          <h1 class="text-2xl font-bold mb-6 text-center text-blue-600">Registro de Usuario</h1>
          <form id="registro-form" class="space-y-4">
            <div>
              <label for="name" class="block text-gray-700 font-medium mb-2">Nombre:</label>
              <input
                type="text"
                id="name"
                name="name"
                required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Ingresa tu nombre"
              />
            </div>
            <div>
              <label for="email" class="block text-gray-700 font-medium mb-2">Correo Electrónico:</label>
              <input
                type="email"
                id="email"
                name="email"
                required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Ingresa tu correo electrónico"
              />
            </div>
            <div>
              <label for="pin" class="block text-gray-700 font-medium mb-2">PIN:</label>
              <input
                type="password"
                id="pin"
                name="pin"
                required
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                placeholder="Crea un PIN"
              />
            </div>
            <button
                type="submit"
                class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg font-bold hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300"
            >
                Registrar
            </button>
          </form>
          <p id="response" class="mt-4 text-center text-gray-700"></p>
        </div>
    `;
  },
};
