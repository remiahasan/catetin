// simpan semua menu global
let menus = [];
let viewMode = localStorage.getItem("viewMode") || "card";

// Fungsi ubah mode tampilan
function setViewMode(mode) {
    viewMode = mode;

    localStorage.setItem("viewMode", mode);

    renderMenus(menus);
}

// Fungsi render menu
function renderMenus(dataMenus) {
    const menuList = document.getElementById("menu-list");
    menuList.innerHTML = "";

    if (dataMenus.length === 0) {
        menuList.innerHTML = `
            <p class="col-span-full text-center text-gray-500">
                Tidak ada menu ditemukan.
            </p>`;
        return;
    }

    if (viewMode === "card") {
        menuList.className = "max-w-5xl mx-auto p-4 grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-4";
        dataMenus.forEach(menu => {
            const fotoUrl = menu.foto ? `/${menu.foto}` : "/img/illustrations/no-image.png";
            menuList.innerHTML += `
                <div class="h-80 bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition relative flex flex-col">
                    <div class="relative">
                        ${menu.is_best_seller ? `
                                <div class="absolute top-2 ml-2 left-2 p-1 bg-white rounded-full">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                        <path d="M10.3072 7.21991C10.9493 5.61922 11.2704 4.81888 11.7919 4.70796C11.9291 4.67879 12.0708 4.67879 12.208 4.70796C12.7295 4.81888 13.0506 5.61922 13.6927 7.21991C14.0578 8.13019 14.2404 8.58533 14.582 8.8949C14.6778 8.98173 14.7818 9.05906 14.8926 9.12581C15.2874 9.36378 15.7803 9.40793 16.7661 9.49621C18.4348 9.64566 19.2692 9.72039 19.524 10.1961C19.5768 10.2947 19.6127 10.4013 19.6302 10.5117C19.7146 11.0448 19.1012 11.6028 17.8744 12.7189L17.5338 13.0289C16.9602 13.5507 16.6735 13.8116 16.5076 14.1372C16.4081 14.3325 16.3414 14.5429 16.3101 14.7598C16.258 15.1215 16.342 15.5 16.5099 16.257L16.5699 16.5275C16.8711 17.885 17.0217 18.5637 16.8337 18.8974C16.6649 19.1971 16.3538 19.3889 16.0102 19.4053C15.6277 19.4236 15.0887 18.9844 14.0107 18.106C13.3005 17.5273 12.9454 17.2379 12.5512 17.1249C12.191 17.0216 11.8089 17.0216 11.4487 17.1249C11.0545 17.2379 10.6994 17.5273 9.98917 18.106C8.91119 18.9844 8.37221 19.4236 7.98968 19.4053C7.64608 19.3889 7.33504 19.1971 7.16617 18.8974C6.97818 18.5637 7.12878 17.885 7.42997 16.5275L7.48998 16.257C7.65794 15.5 7.74191 15.1215 7.6898 14.7598C7.65854 14.5429 7.59182 14.3325 7.49232 14.1372C7.32645 13.8116 7.03968 13.5507 6.46613 13.0289L6.12546 12.7189C4.89867 11.6028 4.28527 11.0448 4.36975 10.5117C4.38724 10.4013 4.42312 10.2947 4.47589 10.1961C4.73069 9.72039 5.56507 9.64566 7.23384 9.49621C8.21962 9.40793 8.71251 9.36378 9.10735 9.12581C9.2181 9.05906 9.32211 8.98173 9.41793 8.8949C9.75954 8.58533 9.94211 8.13019 10.3072 7.21991Z" fill="#FBCF33" stroke="#FBCF33" stroke-width="2"/>
                                    </svg>
                                </div>
                         ` : ""}
                    </div>
                    <img class="rounded-t-lg h-[11rem] w-full object-cover" src="${fotoUrl}" alt="" />
                    <div class="p-4 flex flex-col justify-between flex-1">
                        <h3 class="font-semibold text-base sm:text-lg line-clamp-2 h-12">${menu.nama}</h3>
                        <p class="text-purple-700 font-bold">Rp ${parseInt(menu.harga).toLocaleString("id-ID")}</p>
                        <button onclick="tambahKeKeranjang(event, ${menu.id}, ${menu.business_id}, ${menu.harga})"
                                class="mt-3 w-full bg-gradient-to-tl from-purple-700 to-pink-500 text-white py-2 rounded-lg text-sm">
                            Tambah
                        </button>
                    </div>
                </div>`;
        });
    } else {
        menuList.className = "max-w-5xl mx-auto p-4";

        let wrapper = `
    <div class="bg-white shadow-md rounded-xl divide-y">
`;

        dataMenus.forEach(menu => {
            const fotoUrl = menu.foto ? `/${menu.foto}` : "/img/illustrations/no-image.png";

            wrapper += `
        <div class="flex items-center p-4">
            <div class="flex-shrink-0 relative">
                ${menu.is_best_seller ? `
                    <div class="absolute -top-1 -right-1 bg-white rounded-full w-5 h-5 flex items-center justify-center ring-1 ring-yellow-400">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.3072 7.21991C10.9493 5.61922 11.2704 4.81888 11.7919 4.70796C11.9291 4.67879 12.0708 4.67879 12.208 4.70796C12.7295 4.81888 13.0506 5.61922 13.6927 7.21991C14.0578 8.13019 14.2404 8.58533 14.582 8.8949C14.6778 8.98173 14.7818 9.05906 14.8926 9.12581C15.2874 9.36378 15.7803 9.40793 16.7661 9.49621C18.4348 9.64566 19.2692 9.72039 19.524 10.1961C19.5768 10.2947 19.6127 10.4013 19.6302 10.5117C19.7146 11.0448 19.1012 11.6028 17.8744 12.7189L17.5338 13.0289C16.9602 13.5507 16.6735 13.8116 16.5076 14.1372C16.4081 14.3325 16.3414 14.5429 16.3101 14.7598C16.258 15.1215 16.342 15.5 16.5099 16.257L16.5699 16.5275C16.8711 17.885 17.0217 18.5637 16.8337 18.8974C16.6649 19.1971 16.3538 19.3889 16.0102 19.4053C15.6277 19.4236 15.0887 18.9844 14.0107 18.106C13.3005 17.5273 12.9454 17.2379 12.5512 17.1249C12.191 17.0216 11.8089 17.0216 11.4487 17.1249C11.0545 17.2379 10.6994 17.5273 9.98917 18.106C8.91119 18.9844 8.37221 19.4236 7.98968 19.4053C7.64608 19.3889 7.33504 19.1971 7.16617 18.8974C6.97818 18.5637 7.12878 17.885 7.42997 16.5275L7.48998 16.257C7.65794 15.5 7.74191 15.1215 7.6898 14.7598C7.65854 14.5429 7.59182 14.3325 7.49232 14.1372C7.32645 13.8116 7.03968 13.5507 6.46613 13.0289L6.12546 12.7189C4.89867 11.6028 4.28527 11.0448 4.36975 10.5117C4.38724 10.4013 4.42312 10.2947 4.47589 10.1961C4.73069 9.72039 5.56507 9.64566 7.23384 9.49621C8.21962 9.40793 8.71251 9.36378 9.10735 9.12581C9.2181 9.05906 9.32211 8.98173 9.41793 8.8949C9.75954 8.58533 9.94211 8.13019 10.3072 7.21991Z" fill="#FBCF33" stroke="#FBCF33" stroke-width="2"/>
                        </svg>
                    </div>
                ` : ""}
                <img src="${fotoUrl}" class="w-12 h-12 object-cover rounded-lg" alt="${menu.nama}">
            </div>

            <div class="flex-1 ml-4">
                <h3 class="font-semibold text-sm md:text-base text-gray-800 line-clamp-2">
                    ${menu.nama}
                </h3>
                <p class="text-purple-700 font-bold mt-1">
                    Rp ${parseInt(menu.harga).toLocaleString("id-ID")}
                </p>
            </div>

            <div class="ml-3">
                <button onclick="tambahKeKeranjang(event, ${menu.id}, ${menu.business_id}, ${menu.harga})"
                    class="bg-gradient-to-tl from-purple-700 to-pink-500 hover:opacity-90 text-white
                    px-4 py-2 rounded-xl text-sm md:text-base font-semibold shadow-md transition-transform hover:scale-105">
                    Tambah
                </button>
            </div>
        </div>
    `;
        });

        wrapper += `</div>`;

        menuList.innerHTML = wrapper;
    }
}

// Load semua menu saat pertama kali halaman dibuka
document.addEventListener("DOMContentLoaded", () => {

    document.getElementById("view-mode").value = viewMode;

    setViewMode(viewMode);

    loadMenus("all", document.querySelector(".kategori-btn"));
});

// fungsi search
const searchForm = document.getElementById("search-input");
const searchInput = document.getElementById("default-search");

searchForm.addEventListener("submit", function (e) {
    e.preventDefault(); // biar nggak reload halaman

    let keyword = searchInput.value.toLowerCase();

    // filter menu berdasarkan nama
    let filteredMenus = menus.filter(menu =>
        menu.nama.toLowerCase().includes(keyword)
    );

    // render ulang hanya menu yang sesuai
    renderMenus(filteredMenus);
});

// fungsi tombol kategori aktif
function setActiveButton(button) {
    document.querySelectorAll(".kategori-btn").forEach((btn) => {
        btn.classList.remove("bg-pink-500", "text-white");
        btn.classList.add("bg-gray-200", "text-gray-700");
    });
    button.classList.remove("bg-gray-200", "text-gray-700");
    button.classList.add("bg-pink-500", "text-white");
}

// fungsi load menu dari server
function loadMenus(kategoriId, buttonElement) {
    if (buttonElement) {
        setActiveButton(buttonElement);
    }

    let url =
        kategoriId === "all"
            ? "/pegawai/get-all-menus"
            : `/pegawai/get-menus/${kategoriId}`;

    fetch(url)
        .then((res) => res.json())
        .then((response) => {
            menus = response.menus || []; // simpan global
            renderMenus(menus); // tampilkan menu
        });
}

// fungsi tambah ke keranjang
let jumlahItemDiKeranjang = 0;

function tambahKeKeranjang(event, menuId, businessId, hargaSatuan) {
    event.preventDefault();

    const parentDiv = event.target.closest(".h-80");
    const selectEl = parentDiv ? parentDiv.querySelector("select") : null;
    const ukuran = selectEl ? selectEl.value : null;

    const hargaText = selectEl
        ? selectEl.selectedOptions[0].textContent
        : hargaSatuan;
    const hargaParsed = selectEl
        ? parseInt(hargaText.replace(/[^\d]/g, ""))
        : hargaSatuan;

    const dataKeranjang = {
        menu_id: menuId,
        business_id: businessId,
        jumlah: 1,
        harga_satuan: hargaParsed,
        ukuran: ukuran,
        total_harga: hargaParsed,
    };

    fetch("/pegawai/keranjang/add", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify(dataKeranjang),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                jumlahItemDiKeranjang++;
                const badge = document.getElementById("cart-badge");
                badge.textContent = jumlahItemDiKeranjang;
                badge.classList.remove("hidden");

                // ✅ tampilkan notifikasi
                showPopup("Menu berhasil ditambahkan ke keranjang!", "success");
            } else {
                showPopup(data.message || "Gagal menambahkan item ke keranjang.", "error");
                if (data.errors) {
                    console.error("Error validasi:", data.errors);
                }
            }
        })
        .catch(() => {
            showPopup("Terjadi kesalahan koneksi ke server.", "error");
        });
}
