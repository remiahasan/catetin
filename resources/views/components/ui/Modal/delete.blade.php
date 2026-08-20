<script>
    function confirmDelete(event, employeeId) {
        event.preventDefault();

        Swal.fire({
            icon: "warning",
            title: "Apakah Anda yakin?",
            text: "Data karyawan akan dihapus secara permanen!",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + employeeId).submit();
            }
        });
    }
</script>
