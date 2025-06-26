<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Landing Page - Soooji</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="images/web/favicon.ico" rel="icon">
    <link href="images/web/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="welcome/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="welcome/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="welcome/assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="welcome/assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="welcome/assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="welcome/assets/css/main.css" rel="stylesheet">

    <!-- Custom CSS for Reviews -->
    <style>
        .review-stats {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin: 40px 0;
        }

        .stat-item {
            text-align: center;
            padding: 20px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .default-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
        }

        .service-badge {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-top: 5px;
            display: inline-block;
        }

        .review-date {
            color: #666;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .no-reviews-message {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 15px;
            margin: 40px 0;
        }

        .no-reviews-message i {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

            <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="welcome/assets/img/logo.png" alt=""> -->
                <h1 class="sitename">Soooji</h1><span>.</span>
                <h1>Id</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ url('#hero') }}" class="active">Beranda</a></li>
                    <li><a href="{{ url('#about') }}">Tentang</a></li>
                    <li><a href="{{ url('#services') }}">Layanan</a></li>
                    <li><a href="{{ url('#portfolio') }}">Galeri</a></li>
                    <li><a href="{{ url('#testimonials') }}">Testimoni</a></li>
                    <li><a href="{{ url('#contact') }}">Kontak</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="/login">Pesan Sekarang</a>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <img src="images/hero-bg.jpg" alt="" data-aos="fade-in">

            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <h2 data-aos="fade-up" data-aos-delay="100">Selamat Datang di Tempat Cuci Sepatu Terbaik</h2>
                        <p data-aos="fade-up" data-aos-delay="200">Bikin sepatu kamu kembali bersih, segar, dan seperti baru lagi!</p>
                    </div>
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Review Statistics Section -->
        @if($reviews->count() > 0)
        <section class="review-stats">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 stat-item" data-aos="fade-up" data-aos-delay="100">
                        <span class="stat-number">{{ number_format($reviewStats['total_customers']) }}</span>
                        <span class="stat-label">Pelanggan Puas</span>
                    </div>
                    <div class="col-lg-3 col-md-6 stat-item" data-aos="fade-up" data-aos-delay="200">
                        <span class="stat-number">{{ number_format($reviewStats['total_reviews']) }}</span>
                        <span class="stat-label">Total Review</span>
                    </div>
                    <div class="col-lg-3 col-md-6 stat-item" data-aos="fade-up" data-aos-delay="300">
                        <span class="stat-number">{{ $reviewStats['average_rating'] }}</span>
                        <span class="stat-label">Rating Rata-rata</span>
                    </div>
                    <div class="col-lg-3 col-md-6 stat-item" data-aos="fade-up" data-aos-delay="400">
                        <span class="stat-number">{{ $reviewStats['five_star_count'] }}</span>
                        <span class="stat-label">Review Bintang 5</span>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- About Section -->
        <section id="about" class="about section light-background">

            <div class="container" data-aos="fade-up" data-aos-delay="100">
                <div class="row align-items-xl-center gy-5">

                    <div class="col-xl-5 content">
                        <h3>Tentang Kami</h3>
                        <h2>Solusi Terbaik untuk Sepatu Bersih dan Wangi</h2>
                        <p>Sepatu kotor atau kusam? Serahkan saja kepada kami! Menyediakan layanan cuci sepatu profesional yang aman untuk semua jenis bahan, hasil bersih maksimal, wangi, dan tampilan seperti baru. Kepuasan pelanggan adalah prioritas kami.</p>
                        <a href="#" class="read-more"><span>Selengkapnya</span><i class="bi bi-arrow-right"></i></a>
                    </div>

                    <div class="col-xl-7">
                        <div class="row gy-4 icon-boxes">

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
                                <div class="icon-box">
                                    <i class="bi bi-buildings"></i>
                                    <h3>Layanan Profesional</h3>
                                    <p>Kami memberikan layanan cuci sepatu terbaik dengan teknik dan bahan berkualitas untuk semua jenis sepatu.</p>
                                </div>
                            </div> <!-- End Icon Box -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
                                <div class="icon-box">
                                    <i class="bi bi-clipboard-pulse"></i>
                                    <h3>Aman untuk Semua Bahan</h3>
                                    <p>Proses pencucian yang aman dan ramah bahan, dari sepatu kulit, suede, hingga sneakers favorit kamu.</p>
                                </div>
                            </div> <!-- End Icon Box -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
                                <div class="icon-box">
                                    <i class="bi bi-command"></i>
                                    <h3>Cepat & Bersih Maksimal</h3>
                                    <p>Pengerjaan cepat tanpa mengurangi kualitas kebersihan dan keharuman sepatu kamu.</p>
                                </div>
                            </div> <!-- End Icon Box -->

                            <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
                                <div class="icon-box">
                                    <i class="bi bi-graph-up-arrow"></i>
                                    <h3>Gratis Antar Jemput</h3>
                                    <p>Layanan antar jemput sepatu area sekitar GRATIS! Lebih mudah, lebih praktis.</p>
                                </div>
                            </div> <!-- End Icon Box -->

                        </div>
                    </div>

                </div>
            </div>

        </section><!-- /About Section -->

        <!-- Services Section -->
        <section id="services" class="services section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Layanan Kami</h2>
                <p>Kami memberikan layanan perawatan sepatu terbaik untuk menjaga sepatu kamu tetap bersih, wangi, dan awet.</p>
            </div><!-- End Section Title -->

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="100">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-briefcase"></i></div>
                            <div>
                                <h4 class="title"><a href="services-details.html" class="stretched-link">Cuci Sepatu Premium</a></h4>
                                <p class="description">Membersihkan sepatu dengan teknik khusus sesuai bahan, dari sneakers hingga sepatu kulit.</p>
                            </div>
                        </div>
                    </div>
                    <!-- End Service Item -->

                    <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="200">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-card-checklist"></i></div>
                            <div>
                                <h4 class="title"><a href="services-details.html" class="stretched-link">Deep Cleaning</a></h4>
                                <p class="description">Pencucian menyeluruh untuk bagian luar, dalam, dan sol sepatu kamu.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="300">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-bar-chart"></i></div>
                            <div>
                                <h4 class="title"><a href="services-details.html" class="stretched-link">Whitening & Brightening</a></h4>
                                <p class="description">Mengembalikan warna putih sepatu jadi kinclong seperti baru lagi.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="400">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-binoculars"></i></div>
                            <div>
                                <h4 class="title"><a href="services-details.html" class="stretched-link">Repair Sepatu</a></h4>
                                <p class="description">Perbaikan sepatu robek, sol lepas, atau kerusakan ringan lainnya.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="500">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-brightness-high"></i></div>
                            <div>
                                <h4 class="title"><a href="services-details.html" class="stretched-link">Anti Bakteri & Deodoran</a></h4>
                                <p class="description">Pemberian treatment anti bakteri sekaligus pengharum khusus sepatu.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                    <div class="col-lg-6 " data-aos="fade-up" data-aos-delay="600">
                        <div class="service-item d-flex">
                            <div class="icon flex-shrink-0"><i class="bi bi-calendar4-week"></i></div>
                            <div>
                                <h4 class="title"><a href="services-details.html" class="stretched-link">Gratis Antar Jemput</a></h4>
                                <p class="description">Layanan antar jemput sepatu area kota tanpa biaya tambahan.</p>
                            </div>
                        </div>
                    </div><!-- End Service Item -->

                </div>

            </div>

        </section><!-- /Services Section -->

        <!-- Portfolio Section -->
        <section id="portfolio" class="portfolio section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Galeri</h2>
                <p>Lihat hasil perawatan sepatu dari pelanggan kami — dari yang kusam jadi kinclong kembali!</p>
            </div>
            <!-- End Section Title -->

            <div class="container">

                <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                    <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100">
                        <li data-filter="*" class="filter-active">Semua</li>
                        <li data-filter=".filter-deepclean">Deep Clean</li>
                        <li data-filter=".filter-repaint">Repaint</li>
                        <li data-filter=".filter-fastclean">Fast Clean</li>
                    </ul>
                    <!-- End Portfolio Filters -->

                    <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-deepclean">
                            <img src="/images/galeri-1.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Deep Clean Result</h4>
                                <p>Hasil pembersihan mendalam</p>
                                <a href="/images/galeri-1.jpeg" title="Deep Clean Result" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-repaint">
                            <img src="/images/galeri-2.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Repaint Service</h4>
                                <p>Layanan pengecatan ulang</p>
                                <a href="/images/galeri-2.jpeg" title="Repaint Service" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-fastclean">
                            <img src="/images/galeri-3.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Fast Clean</h4>
                                <p>Pembersihan cepat</p>
                                <a href="/images/galeri-3.jpeg" title="Fast Clean" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-deepclean">
                            <img src="/images/galeri-4.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Premium Care</h4>
                                <p>Perawatan premium</p>
                                <a href="/images/galeri-4.jpeg" title="Premium Care" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-repaint">
                            <img src="/images/galeri-5.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Color Restoration</h4>
                                <p>Pemulihan warna sepatu</p>
                                <a href="/images/galeri-5.jpeg" title="Color Restoration" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-fastclean">
                            <img src="/images/galeri-6.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Quick Service</h4>
                                <p>Layanan cepat</p>
                                <a href="/images/galeri-6.jpeg" title="Quick Service" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-deepclean">
                            <img src="/images/galeri-7.jpeg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Professional Clean</h4>
                                <p>Pembersihan profesional</p>
                                <a href="/images/galeri-7.jpeg" title="Professional Clean" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                    </div><!-- End Portfolio Container -->

                </div>

            </div>

        </section><!-- /Portfolio Section -->

        <!-- Call To Action Section -->
        <section id="call-to-action" class="call-to-action section dark-background">

            <img src="welcome/assets/img/cta-bg.jpg" alt="">

            <div class="container">
                <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
                    <div class="col-xl-10">
                        <div class="text-center">
                            <h3>Tujuan Kami</h3>
                            <p>"Memberikan layanan perawatan dan pembersihan sepatu terbaik dengan kualitas premium, agar setiap pelanggan cuci sepatu Sooji dapat tampil percaya diri dengan sepatu yang bersih, wangi, dan nyaman dipakai."</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- /Call To Action Section -->

        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials section light-background">

            <div class="container">

                <div class="row align-items-center">

                    <div class="col-lg-5 info" data-aos="fade-up" data-aos-delay="100">
                        <h3>Apa Kata Pelanggan Kami</h3>
                        @if($reviews->count() > 0)
                        <p>
                            Dengan lebih dari <strong>{{ number_format($reviewStats['total_customers']) }} pelanggan puas</strong> dan
                            <strong>{{ number_format($reviewStats['total_reviews']) }} review positif</strong>,
                            kami bangga memberikan layanan cuci sepatu terbaik dengan rating rata-rata
                            <strong>{{ $reviewStats['average_rating'] }} bintang</strong>.
                        </p>
                        <p>
                            Kepuasan pelanggan adalah prioritas utama kami. Setiap sepatu yang dipercayakan kepada Soooji
                            akan mendapatkan perawatan terbaik dengan hasil yang memuaskan.
                        </p>
                        @else
                        <p>
                            "Sepatu saya yang sebelumnya kotor dan bau, sekarang kembali bersih dan wangi berkat Soooji! Prosesnya cepat dan hasilnya memuaskan. Recommended banget buat yang sayang sama sepatunya."
                        </p>
                        <p>
                            "Pelayanan ramah, harga terjangkau, dan hasil cucian sepatu sangat maksimal. Pokoknya langganan terus di Soooji!"
                        </p>
                        @endif
                    </div>

                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">

                        @if($reviews->count() > 0)
                        <!-- Dynamic Reviews from Database -->
                        <div class="swiper init-swiper">
                            <script type="application/json" class="swiper-config">
                                {
                                    "loop": true,
                                    "speed": 600,
                                    "autoplay": {
                                        "delay": 5000
                                    },
                                    "slidesPerView": "auto",
                                    "pagination": {
                                        "el": ".swiper-pagination",
                                        "type": "bullets",
                                        "clickable": true
                                    }
                                }
                            </script>
                            <div class="swiper-wrapper">

                                @foreach($reviews as $review)
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            @if($review->user->profile_photo)
                                            <img src="{{ asset('storage/' . $review->user->profile_photo) }}"
                                                class="testimonial-img flex-shrink-0"
                                                alt="{{ $review->user->name }}">
                                            @else
                                            <div class="default-avatar flex-shrink-0">
                                                {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                            </div>
                                            @endif
                                            <div>
                                                <h3>{{ $review->user->name }}</h3>
                                                <h4>Pelanggan {{ $review->order->service->name }}</h4>
                                                <div class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <=$review->rating)
                                                        <i class="bi bi-star-fill"></i>
                                                        @else
                                                        <i class="bi bi-star"></i>
                                                        @endif
                                                        @endfor
                                                </div>
                                                <div class="service-badge">{{ $review->order->service->name }}</div>
                                                <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>{{ Str::limit($review->comment, 150) }}</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>
                                @endforeach

                            </div>

                            <div class="swiper-pagination"></div>
                        </div>
                        @else
                        <!-- Fallback Static Reviews -->
                        <div class="swiper init-swiper">
                            <script type="application/json" class="swiper-config">
                                {
                                    "loop": true,
                                    "speed": 600,
                                    "autoplay": {
                                        "delay": 5000
                                    },
                                    "slidesPerView": "auto",
                                    "pagination": {
                                        "el": ".swiper-pagination",
                                        "type": "bullets",
                                        "clickable": true
                                    }
                                }
                            </script>
                            <div class="swiper-wrapper">

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="welcome/assets/img/testimonials/testimonials-1.jpg" class="testimonial-img flex-shrink-0" alt="">
                                            <div>
                                                <h3>Ardiansyah Putra</h3>
                                                <h4>Pelanggan Tetap</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Sepatu saya yang awalnya kotor banget sekarang jadi kayak baru lagi! Wangi, bersih, dan pelayanannya cepat. Soooji emang solusi terbaik buat sepatu kesayangan.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="welcome/assets/img/testimonials/testimonials-2.jpg" class="testimonial-img flex-shrink-0" alt="">
                                            <div>
                                                <h3>Sinta Maharani</h3>
                                                <h4>Karyawan Kantor</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Selalu puas cuci sepatu di Soooji. Sepatu sneakers putihku jadi cling kayak baru beli, harganya juga ramah di kantong. Pokoknya recommended!</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="welcome/assets/img/testimonials/testimonials-3.jpg" class="testimonial-img flex-shrink-0" alt="">
                                            <div>
                                                <h3>Rizky Ramadhan</h3>
                                                <h4>Mahasiswa</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Awalnya coba-coba, ternyata hasilnya di luar ekspektasi! Sepatu bolaku yang kotor parah jadi mulus lagi. Soooji emang the best.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <div class="swiper-pagination"></div>
                        </div>
                        @endif

                    </div>

                </div>

            </div>

        </section><!-- /Testimonials Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Kontak Kami</h2>
                <p>Butuh layanan cuci sepatu terbaik di kota kamu? Hubungi Soooji sekarang untuk info harga, layanan, atau booking cuci sepatu favoritmu!</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-6">

                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="200">
                                    <i class="bi bi-geo-alt"></i>
                                    <h3>Alamat</h3>
                                    <p>Jl. Setia Bakti Gang 3 No.24 Podosugih</p>
                                    <p>Pekalongan, Jawa Tengah</p>
                                    <p>Indonesia</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="300">
                                    <i class="bi bi-telephone"></i>
                                    <h3>Hubungi Kami</h3>
                                    <p>087725161627</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="500">
                                    <i class="bi bi-clock"></i>
                                    <h3>Jam Buka</h3>
                                    <p>Senin - Sabtu</p>
                                    <p>09:00 WIB - 21:00 WIB</p>
                                </div>
                            </div><!-- End Info Item -->

                        </div>

                    </div>

                    <div class="col-lg-6">
                        <form onsubmit="return sendToWhatsApp();" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" id="name" class="form-control" placeholder="Nama Kamu" required="">
                                </div>

                                <div class="col-12">
                                    <input type="text" id="subject" class="form-control" placeholder="Judul Pesan" required="">
                                </div>

                                <div class="col-12">
                                    <textarea class="form-control" id="message" rows="6" placeholder="Pesan Kamu" required=""></textarea>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit">Kirim via WhatsApp</button>
                                </div>

                            </div>
                        </form>
                    </div>

                    <!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <footer id="footer" class="footer position-relative light-background">

        <div class="container footer-top">
            <div class="row gy-4">

                <div class="col-lg-5 col-md-12 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Soooji</span>
                    </a>
                    <p>Soooji adalah layanan cuci sepatu profesional yang siap merawat, membersihkan, dan menjaga sepatu kesayangan kamu tetap bersih, wangi, dan tampil seperti baru. Solusi sepatu bersih tanpa ribet!</p>
                    <div class="social-links d-flex mt-4">
                        <a href="https://www.instagram.com/soooji.id/"><i class="bi bi-instagram"></i></a>
                        <a href="https://web.facebook.com/profile.php?id=61576134735705#"><i class="bi bi-facebook"></i></a>
                        <a href="https://wa.me/6287725161627"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Menu</h4>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Tentang Kami</a></li>
                        <li><a href="#">Layanan</a></li>
                        <li><a href="#">Galeri</a></li>
                        <li><a href="#">Testimoni</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Layanan Kami</h4>
                    <ul>
                        <li><a href="#">Cuci Sepatu Premium</a></li>
                        <li><a href="#">Deep Cleaning</a></li>
                        <li><a href="#">Whitening Treatment</a></li>
                        <li><a href="#">Custom Sepatu</a></li>
                        <li><a href="#">Jemput & Antar Sepatu</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Hubungi Kami</h4>
                    <p>Jl. Setia Bakti Gang 3 No.24 Podosugih</p>
                    <p>Pekalongan, Jawa Tengah</p>
                    <p>Indonesia</p>
                    <p class="mt-4"><strong>WhatsApp:</strong> <span>+62 877 2516 1627</span></p>
                </div>

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="sitename">Soooji</strong> <span>All Rights Reserved</span></p>
            <div class="credits">
                Designed by Rizqi
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="welcome/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="welcome/assets/vendor/php-email-form/validate.js"></script>
    <script src="welcome/assets/vendor/aos/aos.js"></script>
    <script src="welcome/assets/vendor/glightbox/js/glightbox.min.js"></script>
    <script src="welcome/assets/vendor/purecounter/purecounter_vanilla.js"></script>
    <script src="welcome/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="welcome/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
    <script src="welcome/assets/vendor/swiper/swiper-bundle.min.js"></script>

    <!-- Main JS File -->
    <script src="welcome/assets/js/main.js"></script>

    <!-- Function send message to WhatsApp -->
    <script>
        function sendToWhatsApp() {
            var name = document.getElementById("name").value;
            var subject = document.getElementById("subject").value;
            var message = document.getElementById("message").value;

            var noWa = "6287725161627"; // ganti ke nomor WA kamu (pakai kode negara, 62 untuk Indonesia)

            var text = "Halo Soooji! Saya " + name + "%0A" +
                "Judul: " + subject + "%0A" +
                "Pesan: " + message;

            var url = "https://wa.me/" + noWa + "?text=" + text;

            window.open(url, '_blank');
            return false;
        }
    </script>

</body>

</html>