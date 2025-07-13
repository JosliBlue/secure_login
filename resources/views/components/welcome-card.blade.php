<div class="bg-gradient-to-r from-gray-200 to-gray-300 border border-gray-200 rounded-xl p-6 my-3">
    <div class="flex items-center ">
        <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center mr-4">
            <span class="iconify w-5 h-5 text-white" data-icon="uiw:user"></span>
        </div>
        <div>
            <h4 class="text-2xl font-bold text-gray-800">¡Bienvenido de vuelta!</h4>
            <p class="text-lg font-semibold text-gray-700">{{ Auth::user()->getEmail() }}</p>
        </div>
    </div>
</div>
