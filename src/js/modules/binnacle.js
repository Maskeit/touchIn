import Dashboard from "../classes/Dashboard";
import { V_Global } from "../components/system";

const dash = new Dashboard( V_Global ); // instance

export const Binnacle = {
    setupBinnacle(){
        // Llama al método para obtener el binnacle
        dash.binnacle()
           .then((binnacle) => {
                this.displayBinnacle(binnacle); // Renderiza el binnacle en la tabla
            })
           .catch((error) => {
                const messageElement = document.getElementById("binnacle-message");
                messageElement.textContent = `Error al cargar el binnacle: ${error.message}`;
                messageElement.classList.add("text-red-500");
            });
    },

    displayBinnacle(binnacleData){
        console.log(binnacleData);
        const tableBody = document.getElementById("binnacle-table-body");
        if (!binnacleData || binnacleData.length === 0) {
            const messageElement = document.getElementById("dashboard-message");
            messageElement.textContent = "No hay usuarios registrados.";
            messageElement.classList.add("text-gray-500");
            return;
        }

        // Limpia cualquier contenido existente en la tabla
        tableBody.innerHTML = "";

        // Itera sobre los usuarios y crea las filas
        binnacleData.forEach((user) => {
            console.log(user)
            const row = document.createElement("tr");
            row.classList.add("hover:bg-gray-100");

            row.innerHTML = `
                <td class="border border-gray-300 px-4 py-2">${user.id}</td>
                <td class="border border-gray-300 px-4 py-2">${user.user_id}</td>
                <td class="border border-gray-300 px-4 py-2">${user.authorized}</td>
                <td class="border border-gray-300 px-4 py-2">${user.attempted_at}</td>
            `;

            tableBody.appendChild(row);
        });
    }
}