export function renderMenu() {
    const menu = `
        <nav class="bg-blue-500 text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <!-- Logo -->
                <div class="text-xl font-bold">
                    <a href="/" class="hover:text-gray-200">TouchIn</a>
                </div>

                <!-- Menu Items -->
                <ul class="hidden md:flex space-x-6">
                    <li><a href="/" class="hover:text-gray-200">Inicio</a></li>
                    <li><a href="./dashboard" class="hover:text-gray-200">Dashboard</a></li>
                    <li><a href="./register" class="hover:text-gray-200">Registro</a></li>
                    <li><a href="./binnacle" class="hover:text-gray-200">Bitácora</a></li>
                </ul>

                <!-- Mobile Menu Button -->
                <button id="menu-toggle" class="block md:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile Menu -->
            <ul id="mobile-menu" class="md:hidden hidden flex-col space-y-4 mt-4 text-center bg-blue-600 p-4 rounded-lg">
                <li><a href="/" class="block hover:text-gray-200">Inicio</a></li>
                <li><a href="./dashboard" class="block hover:text-gray-200">Dashboard</a></li>
                <li><a href="./register" class="block hover:text-gray-200">Registro</a></li>
                <li><a href="./binnacle" class="block hover:text-gray-200">Bitácora</a></li>
            </ul>
        </nav>
    `;

    // Inserta el menú al inicio del body
    document.body.insertAdjacentHTML('afterbegin', menu);

    // Lógica para el toggle del menú móvil
    const menuToggle = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
}
