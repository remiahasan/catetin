 <nav class="fixed top-0 w-full z-50 h-16 bg-gray-50">
     <div class="flex flex-wrap items-center justify-between mx-auto p-4">


         <a href="{{ route('pegawai.transaksi.index') }}">
             <div class="flex items-center justify-center gap-2">
                 <!-- Logo Catetin -->
                 <svg class="w-6 h-6 text-gray-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 211.01 237.38"
                     fill="currentColor">
                     <g>
                         <path
                             d="m211.01,131.88c0,9.11-1.16,17.95-3.33,26.38-11.71,45.51-53.02,79.13-102.17,79.13S15.03,203.76,3.33,158.26c-2.17-8.43-3.33-17.27-3.33-26.38h26.38c0,9.25,1.59,18.13,4.5,26.38,9.45,26.72,32.84,46.86,61.44,51.66,4.29.72,8.69,1.09,13.19,1.09s8.9-.37,13.19-1.09c9.47-1.59,18.37-4.86,26.38-9.5,16.18-9.36,28.74-24.29,35.06-42.16h-87.82v-52.75H0c0-9.11,1.16-17.95,3.33-26.38,2.41-9.36,6.06-18.22,10.79-26.38C32.36,21.22,66.45,0,105.5,0s73.15,21.22,91.39,52.75c4.73,8.16,8.38,17.02,10.79,26.38,2.17,8.43,3.33,17.27,3.33,26.38h-26.38c0-9.25-1.59-18.13-4.5-26.38-3.48-9.83-8.84-18.77-15.66-26.38-5.58-6.24-12.12-11.57-19.41-15.78-8.01-4.64-16.91-7.91-26.38-9.5-4.29-.72-8.69-1.09-13.19-1.09s-8.9.37-13.19,1.09c-18.08,3.03-34.08,12.2-45.78,25.28-6.82,7.61-12.18,16.55-15.66,26.38h61.44v-26.38h26.38v26.38h26.38v26.38h-26.38v26.38h92.32Z" />
                     </g>
                 </svg>
                 <h1 class="font-semibold text-2xl text-gray-800">Catetin</h1>
             </div>
         </a>

         <!-- Hamburger Menu -->
         <div class="sm:flex sm:items-center sm:ms-6">
             <x-dropdown align="right" width="48">
                 <x-slot name="trigger">
                     <button class="text-gray-800 focus:outline-none">
                         <!-- Ikon Hamburger -->
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                         </svg>
                     </button>
                 </x-slot>

                 <x-slot name="content">
                     <x-dropdown-link :href="route('profile.view')">
                         {{ __('Profile') }}
                     </x-dropdown-link>
                     <x-dropdown-link :href="route('pegawai.update_stoke')">
                         {{ __('Update Stok') }}
                     </x-dropdown-link>
                     <x-dropdown-link :href="route('pegawai.riwayat-transaksi.index')">
                         {{ __('Riwayat Transaksi') }}
                     </x-dropdown-link>
                     <!-- Authentication -->

                 </x-slot>
             </x-dropdown>
         </div>
     </div>
 </nav>
