import Auth from "../classes/Auth";
import { V_Global } from "../components/system";
// Inicializa la clase Auth con la URL base del backend
const auth = new Auth(V_Global); // Cambia esta URL según tu backend

/**
 * Lógica para manejar el registro del usuario.
 */
export const Register = {
  // setupRegisterForm() {
  //   const form = document.getElementById("registro-form");
  //   const responseElement = document.getElementById("response");

  //   if (!form) {
  //     console.error("Formulario de registro no encontrado");
  //     return;
  //   }

  //   // Elimina el manejador previo si existe
  //   form.removeEventListener("submit", form._submitHandler);
  //   form._submitHandler = async (e) => {
  //     e.preventDefault(); // Evita el comportamiento predeterminado del formulario
  //     e.stopPropagation(); // Evita la propagación del evento

  //     responseElement.textContent = "Procesando...";
  //     responseElement.classList.add("text-gray-500");
      
  //     // Usa FormData para extraer los datos del formulario
  //     const formData = new FormData(form);

  //     try {
  //       // Convierte FormData en un objeto plano
  //       const userData = Object.fromEntries(formData.entries());

  //       // Realiza la petición al backend
  //       const response = await auth.register(userData);

  //       // Actualiza el mensaje de éxito
  //       responseElement.textContent = `Usuario registrado con éxito: ${response.message}`;
  //       responseElement.classList.replace("text-gray-500", "text-green-500");
  //     } catch (error) {
  //       // Muestra el mensaje de error
  //       responseElement.textContent = `Error: ${error.message}`;
  //       responseElement.classList.replace("text-gray-500", "text-red-500");
  //     }
  //   };

  //   // Añade nuevamente el evento al formulario
  //   form.addEventListener("submit", form._submitHandler);
  // },
  setupRegisterForm() {
    const form = document.getElementById("registro-form");
    const appElement = document.getElementById("app");

    if (!form) {
        console.error("Formulario de registro no encontrado");
        return;
    }

    // Elimina el manejador previo si existe
    form.removeEventListener("submit", form._submitHandler);
    form._submitHandler = async (e) => {
        e.preventDefault(); // Evita el comportamiento predeterminado del formulario
        e.stopPropagation(); // Evita la propagación del evento

        // Usa FormData para extraer los datos del formulario
        const formData = new FormData(form);
        const responseElement = document.getElementById("response");
        try {
            // Convierte FormData en un objeto plano
            const userData = Object.fromEntries(formData.entries());

            // Realiza la petición al backend
            const response = await auth.register(userData);
            if(!response.success){
              responseElement.textContent = "Hubo un error al registrarse";
              responseElement.classList.replace("text-gray-700", "text-red-500");
              return;
            }
            const pin = response.pin            
            this.displaySuccess(pin); // Llama a la función para mostrar el mensaje de éxito
            return;
        } catch (error) {
            // Muestra el mensaje de error
            responseElement.textContent = `Error: ${error.message}`;
            responseElement.classList.replace("text-gray-700", "text-red-500");
        }
    };

    // Añade nuevamente el evento al formulario
    form.addEventListener("submit", form._submitHandler);
},

displaySuccess(pin) {
  const appElement = document.getElementById("app");

  if (!appElement) {
      console.error("Contenedor principal no encontrado");
      return;
  }

  // Reemplaza el contenido del contenedor con el mensaje de éxito
  appElement.innerHTML = `
      <div class="flex flex-col items-center justify-center">
          <div class="w-16 h-16 rounded-full bg-green-500 flex items-center justify-center mb-4">
              <svg class="w-8 h-8 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M20 6L9 17l-5-5"></path>
              </svg>
          </div>
          <h2 class="text-xl font-semibold text-green-600 mb-2">¡Registrado correctamente!</h2>
          <p class="text-gray-700">Su pin de acceso es: <span class="font-bold text-blue-600">${pin}</span></p>
          <p class="text-gray-500 mt-2">Guárdelo en un lugar seguro.</p>
      </div>
  `;
}

};
