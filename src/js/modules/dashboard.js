import Dashboard from "../classes/Dashboard";
import { V_Global } from "../components/system";

const dash = new Dashboard(V_Global); // Instancia de Dashboard

export const DashboardModule = {
    setupDashboard() {
        console.log("Configurando el Dashboard...");

        // Llama al método para obtener los usuarios
        dash.getUsers()
            .then((users) => {
                this.displayUsers(users); // Renderiza los usuarios en la tabla
            })
            .catch((error) => {
                const messageElement = document.getElementById("dashboard-message");
                messageElement.textContent = `Error al cargar los usuarios: ${error.message}`;
                messageElement.classList.add("text-red-500");
            });
    },

    displayUsers(users) {
        const tableBody = document.getElementById("user-table-body");
        if (!users || users.length === 0) {
            const messageElement = document.getElementById("dashboard-message");
            messageElement.textContent = "No hay usuarios registrados.";
            messageElement.classList.add("text-gray-500");
            return;
        }

        // Limpia cualquier contenido existente en la tabla
        tableBody.innerHTML = "";

        // Itera sobre los usuarios y crea las filas
        users.forEach((user) => {
            const row = document.createElement("tr");
            row.classList.add("hover:bg-gray-100");

            row.innerHTML = `
                <td class="border border-gray-300 px-4 py-2">${user.id}</td>
                <td class="border border-gray-300 px-4 py-2">${user.name}</td>
                <td class="border border-gray-300 px-4 py-2">${user.email}</td>
                <td class="border border-gray-300 px-4 py-2">${user.created_at}</td>
            `;

            tableBody.appendChild(row);
        });
    },
};
