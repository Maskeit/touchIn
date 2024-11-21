export default class Auth {
    constructor(baseUrl) {
        this.baseUrl = baseUrl; // URL base del backend
    }

    /**
     * Método para registrar un usuario.
     * @param {Object} userData - Los datos del usuario (name, email, pin).
     * @returns {Promise<Object>} - Respuesta del servidor.
     */
    async register(userData) {
        console.log('register en progress');
        try {
            const response = await fetch(`${this.baseUrl}/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData),
            });

            // Procesar la respuesta del servidor
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.error || 'Error en el registro');
            }

            return data; // Retorna el mensaje o información del servidor
        } catch (error) {
            // Manejo de errores
            console.error('Error en Auth.register:', error.message);
            throw error;
        }
    }
}
