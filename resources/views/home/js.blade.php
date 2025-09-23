<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Core theme JS-->
        <script src="home/js/scripts.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function (event) {
                var scrollpos = sessionStorage.getItem('scrollpos');
                if (scrollpos) {
                    window.scrollTo(0, scrollpos);
                    sessionStorage.removeItem('scrollpos');
                }
            });

            window.addEventListener("beforeunload", function (e) {
                sessionStorage.setItem('scrollpos', window.scrollY);
            });
        </script>