<style>
    .footer-background {
        background-color: #343a40;
        color: white;
        opacity: 1;
        /* ทำให้เห็นได้ทันที */
        transform: translateY(0);
        /* ทำให้แสดงผลทันที */
        transition: all 0.8s ease;
        /* เพิ่ม transition เพื่อให้เคลื่อนไหวแบบนุ่มนวล */
    }

    .footer-background.visible {
        opacity: 1;
        transform: translateY(0);
        /* การแสดงผลเมื่อมันถูกเห็น */
    }
</style>


<footer class="container-fluid text-center mt-4 py-4 footer-background" style="background-color: #343a40; color: white;">
    <div class="container">
        <!-- Scroll to Top Button -->
        <a href="#myPage" title="To Top" class="text-white mb-3 d-block">
            <i class="bi bi-chevron-up h1"></i>
        </a>

        <!-- Copyright Section -->
        {{-- <div class="footer-copyright py-3">
            <b>@ 2568</b><br>
            <a href="#" class="text-white font-weight-bold">บริษัท xxx จำกัด</a>
        </div> --}}

        <!-- Address and Developer Info -->
        {{-- <p class="m-0">999999 อ.เมือง จ.ขอนแก่น</p> --}}
        <p class="m-0">Developed By <strong>PROGRAMMER PHOOM</strong></p>

        <br>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Smooth scroll to top
        $('a[href="#myPage"]').click(function(e) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: 0
            }, 500);
        });

        // Footer animation on scroll
        const footer = $('.footer-background');
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    footer.addClass('visible');
                }
            });
        }, {
            threshold: 0.2
        });

        observer.observe(footer[0]);

        // ให้ footer แสดงผลทันทีเมื่อโหลดหน้า
        footer.addClass('visible');
    });
</script>
