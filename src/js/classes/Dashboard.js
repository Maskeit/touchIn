export default class Dashboard {
    constructor(baseUrl){
        this.baseUrl = baseUrl
    }

    async getUsers() {
        try {
            const response = await fetch(`${this.baseUrl}/users`);
            if (!response.ok) {
                throw new Error("Error al obtener los usuarios.");
            }
            return await response.json();
        } catch (error) {
            console.error("Error en getUsers:", error);
            throw error;
        }
    }
    async binnacle(){
        try {
            const response = await fetch(`${this.baseUrl}/binnacle`);
            if (!response.ok) {
                throw new Error("Error al obtener el binnacle.");
            }
            return await response.json();
        } catch (error) {
            console.error("Error en binnacle:", error);
            throw error;
        }
    }
}