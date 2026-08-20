        // edit profile
        function openEdit() {
            document.getElementById('profile-view').classList.add('hidden');
            document.getElementById('profile-edit').classList.remove('hidden');
        }

        function cancelEdit() {
            document.getElementById('profile-edit').classList.add('hidden');
            document.getElementById('profile-view').classList.remove('hidden');
        }

        // foto profil
        const profilePhotoBtn = document.getElementById('profilePhotoBtn');
        const dropdownProfile = document.getElementById('dropdownProfile');
        const editPhotoBtn = document.getElementById('editPhotoBtn');
        const modalUpload = document.getElementById('modalUpload');
        const closeModalBtn = document.getElementById('closeModalBtn');

        // Toggle dropdown
        profilePhotoBtn.addEventListener('click', () => {
            dropdownProfile.classList.toggle('hidden');
        });

        // Klik edit foto -> buka modal
        editPhotoBtn.addEventListener('click', () => {
            dropdownProfile.classList.add('hidden');
            modalUpload.classList.remove('hidden');
        });

        // Tutup modal
        closeModalBtn.addEventListener('click', () => {
            modalUpload.classList.add('hidden');
        });

        // Klik di luar modal untuk tutup
        modalUpload.addEventListener('click', (e) => {
            if (e.target === modalUpload) {
                modalUpload.classList.add('hidden');
            }
        });

        // Klik di luar dropdown untuk tutup
        window.addEventListener('click', (e) => {
            if (!profilePhotoBtn.contains(e.target) && !dropdownProfile.contains(e.target)) {
                dropdownProfile.classList.add('hidden');
            }
        });

        // Hapus foto profil
        const deletePhotoBtn = document.querySelector('#dropdownProfile button:nth-child(2)');

        deletePhotoBtn.addEventListener('click', () => {
            dropdownProfile.classList.add('hidden');

            if (confirm('Apakah kamu yakin ingin menghapus foto profil?')) {
                fetch("{{ route('profile.photo.destroy') }}", {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => location.reload());
            }
        });