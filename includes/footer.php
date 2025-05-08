</div> <!-- Cierre del div de contenido principal -->
        </div> <!-- Cierre del row -->
    </div> <!-- Cierre del container-fluid -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white mt-4">
        <div class="container my-auto">
            <div class="copyright text-center my-auto">
                <span>Copyright &copy; Sistema Académico <?php echo date('Y'); ?></span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script>
        // Activar tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Activar popovers
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
        
        // Mostrar notificaciones con SweetAlert2
        <?php if (isset($_SESSION['notification'])): ?>
        Swal.fire({
            icon: '<?php echo $_SESSION['notification']['type']; ?>',
            title: '<?php echo $_SESSION['notification']['title']; ?>',
            text: '<?php echo $_SESSION['notification']['message']; ?>',
            confirmButtonColor: '#4e73df'
        });
        <?php unset($_SESSION['notification']); ?>
        <?php endif; ?>
    </script>
</body>
</html>