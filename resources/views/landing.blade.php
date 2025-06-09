<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Landing Page - Sneat</title>
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
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid position-relative d-flex align-items-center justify-content-between">

            <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="welcome/assets/img/logo.png" alt=""> -->
                <h1 class="sitename">Sneat</h1><span>.</span>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ url('#hero') }}" class="active">Beranda</a></li>
                    <li><a href="{{ url('#about') }}">Tentang</a></li>
                    <li><a href="{{ url('#services') }}">Layanan</a></li>
                    <li><a href="{{ url('#portfolio') }}">Galeri</a></li>
                    <li><a href="{{ url('#testimonials') }}">Testimoni</a></li>
                    <!-- <li><a href="{{ url('#pricing') }}">Pricing</a></li> -->
                    <!-- <li><a href="{{ url('#team') }}">Team</a></li> -->
                    <!-- <li><a href="{{ url('blog') }}">Blog</a></li> -->
                    <!-- <li class="dropdown">
                        <a href="#"><span>Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="#">Dropdown 1</a></li>
                            <li class="dropdown">
                                <a href="#"><span>Deep Dropdown</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                                <ul>
                                    <li><a href="#">Deep Dropdown 1</a></li>
                                    <li><a href="#">Deep Dropdown 2</a></li>
                                    <li><a href="#">Deep Dropdown 3</a></li>
                                    <li><a href="#">Deep Dropdown 4</a></li>
                                    <li><a href="#">Deep Dropdown 5</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Dropdown 2</a></li>
                            <li><a href="#">Dropdown 3</a></li>
                            <li><a href="#">Dropdown 4</a></li>
                        </ul>
                    </li> -->
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
                        <h2 data-aos="fade-up" data-aos-delay="100">Selamat Datang di Tempat Cuci Sepatu Append</h2>
                        <p data-aos="fade-up" data-aos-delay="200">Bikin sepatu kamu kembali bersih, segar, dan seperti baru lagi!</p>
                    </div>
                    <!-- <div class="col-lg-5" data-aos="fade-up" data-aos-delay="300">
                        <form action="forms/newsletter.php" method="post" class="php-email-form">
                            <div class="sign-up-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
                        </form>
                    </div> -->
                </div>
            </div>

        </section><!-- /Hero Section -->

        <!-- Clients Section -->
        <!-- <section id="clients" class="clients section">

            <div class="container" data-aos="fade-up">

                <div class="row gy-4">

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="welcome/assets/img/clients/client-1.png" class="img-fluid" alt="">
                    </div>

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="welcome/assets/img/clients/client-2.png" class="img-fluid" alt="">
                    </div>

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="welcome/assets/img/clients/client-3.png" class="img-fluid" alt="">
                    </div>

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="welcome/assets/img/clients/client-4.png" class="img-fluid" alt="">
                    </div>

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="welcome/assets/img/clients/client-5.png" class="img-fluid" alt="">
                    </div>

                    <div class="col-xl-2 col-md-3 col-6 client-logo">
                        <img src="welcome/assets/img/clients/client-6.png" class="img-fluid" alt="">
                    </div>

                </div>

            </div>

        </section> -->
        <!-- /Clients Section -->

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

        <!-- Stats Section -->
        <!-- <section id="stats" class="stats section dark-background">

            <img src="welcome/assets/img/stats-bg.jpg" alt="" data-aos="fade-in">

            <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="232" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Clients</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="521" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Projects</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="1453" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Hours Of Support</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item text-center w-100 h-100">
                            <span data-purecounter-start="0" data-purecounter-end="32" data-purecounter-duration="1" class="purecounter"></span>
                            <p>Workers</p>
                        </div>
                    </div>

                </div>

            </div>

        </section> -->
        <!-- /Stats Section -->

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

        <!-- Features Section -->
        <!-- <section id="features" class="features section">

            <div class="container section-title" data-aos="fade-up">
                <h2>Features</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div>

            <div class="container">

                <div class="row gy-4 align-items-center features-item">
                    <div class="col-lg-5 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
                        <h3>Corporis temporibus maiores provident</h3>
                        <p>
                            Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.
                        </p>
                        <a href="#" class="btn btn-get-started">Get Started</a>
                    </div>
                    <div class="col-lg-7 order-1 order-lg-2 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="100">
                        <div class="image-stack">
                            <img src="welcome/assets/img/features-light-1.jpg" alt="" class="stack-front">
                            <img src="welcome/assets/img/features-light-2.jpg" alt="" class="stack-back">
                        </div>
                    </div>
                </div>

                <div class="row gy-4 align-items-stretch justify-content-between features-item ">
                    <div class="col-lg-6 d-flex align-items-center features-img-bg" data-aos="zoom-out">
                        <img src="welcome/assets/img/features-light-3.jpg" class="img-fluid" alt="">
                    </div>
                    <div class="col-lg-5 d-flex justify-content-center flex-column" data-aos="fade-up">
                        <h3>Sunt consequatur ad ut est nulla</h3>
                        <p>Cupiditate placeat cupiditate placeat est ipsam culpa. Delectus quia minima quod. Sunt saepe odit aut quia voluptatem hic voluptas dolor doloremque.</p>
                        <ul>
                            <li><i class="bi bi-check"></i> <span>Ullamco laboris nisi ut aliquip ex ea commodo consequat.</span></li>
                            <li><i class="bi bi-check"></i><span> Duis aute irure dolor in reprehenderit in voluptate velit.</span></li>
                            <li><i class="bi bi-check"></i> <span>Facilis ut et voluptatem aperiam. Autem soluta ad fugiat</span>.</li>
                        </ul>
                        <a href="#" class="btn btn-get-started align-self-start">Get Started</a>
                    </div>
                </div>

            </div>

        </section> -->
        <!-- /Features Section -->

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
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-1.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 1</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-1.jpg" title="App 1" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-repaint">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-2.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 1</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-2.jpg" title="Product 1" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-fastclean">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-3.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 1</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-3.jpg" title="Branding 1" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-deepclean">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-4.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 2</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-4.jpg" title="App 2" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-repaint">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-5.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 2</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-5.jpg" title="Product 2" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-fastclean">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-6.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 2</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-6.jpg" title="Branding 2" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-deepclean">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-7.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>App 3</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-7.jpg" title="App 3" data-gallery="portfolio-gallery-app" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-repaint">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-8.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Product 3</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-8.jpg" title="Product 3" data-gallery="portfolio-gallery-product" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                        <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-fastclean">
                            <img src="welcome/assets/img/masonry-portfolio/masonry-portfolio-9.jpg" class="img-fluid" alt="">
                            <div class="portfolio-info">
                                <h4>Branding 3</h4>
                                <p>Lorem ipsum, dolor sit</p>
                                <a href="welcome/assets/img/masonry-portfolio/masonry-portfolio-9.jpg" title="Branding 2" data-gallery="portfolio-gallery-branding" class="glightbox preview-link"><i class="bi bi-zoom-in"></i></a>
                                <a href="portfolio-details.html" title="More Details" class="details-link"><i class="bi bi-link-45deg"></i></a>
                            </div>
                        </div><!-- End Portfolio Item -->

                    </div><!-- End Portfolio Container -->

                </div>

            </div>

        </section><!-- /Portfolio Section -->

        <!-- Pricing Section -->
        <!-- <section id="pricing" class="pricing section">

            <div class="container section-title" data-aos="fade-up">
                <h2>Pricing</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div>

            <div class="container" data-aos="zoom-in" data-aos-delay="100">

                <div class="row g-4">

                    <div class="col-lg-4">
                        <div class="pricing-item">
                            <h3>Free Plan</h3>
                            <div class="icon">
                                <i class="bi bi-box"></i>
                            </div>
                            <h4><sup>$</sup>0<span> / month</span></h4>
                            <ul>
                                <li><i class="bi bi-check"></i> <span>Quam adipiscing vitae proin</span></li>
                                <li><i class="bi bi-check"></i> <span>Nec feugiat nisl pretium</span></li>
                                <li><i class="bi bi-check"></i> <span>Nulla at volutpat diam uteera</span></li>
                                <li class="na"><i class="bi bi-x"></i> <span>Pharetra massa massa ultricies</span></li>
                                <li class="na"><i class="bi bi-x"></i> <span>Massa ultricies mi quis hendrerit</span></li>
                            </ul>
                            <div class="text-center"><a href="#" class="buy-btn">Buy Now</a></div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="pricing-item featured">
                            <h3>Business Plan</h3>
                            <div class="icon">
                                <i class="bi bi-rocket"></i>
                            </div>

                            <h4><sup>$</sup>29<span> / month</span></h4>
                            <ul>
                                <li><i class="bi bi-check"></i> <span>Quam adipiscing vitae proin</span></li>
                                <li><i class="bi bi-check"></i> <span>Nec feugiat nisl pretium</span></li>
                                <li><i class="bi bi-check"></i> <span>Nulla at volutpat diam uteera</span></li>
                                <li><i class="bi bi-check"></i> <span>Pharetra massa massa ultricies</span></li>
                                <li><i class="bi bi-check"></i> <span>Massa ultricies mi quis hendrerit</span></li>
                            </ul>
                            <div class="text-center"><a href="#" class="buy-btn">Buy Now</a></div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="pricing-item">
                            <h3>Developer Plan</h3>
                            <div class="icon">
                                <i class="bi bi-send"></i>
                            </div>
                            <h4><sup>$</sup>49<span> / month</span></h4>
                            <ul>
                                <li><i class="bi bi-check"></i> <span>Quam adipiscing vitae proin</span></li>
                                <li><i class="bi bi-check"></i> <span>Nec feugiat nisl pretium</span></li>
                                <li><i class="bi bi-check"></i> <span>Nulla at volutpat diam uteera</span></li>
                                <li><i class="bi bi-check"></i> <span>Pharetra massa massa ultricies</span></li>
                                <li><i class="bi bi-check"></i> <span>Massa ultricies mi quis hendrerit</span></li>
                            </ul>
                            <div class="text-center"><a href="#" class="buy-btn">Buy Now</a></div>
                        </div>
                    </div>

                </div>

            </div>

        </section> -->
        <!-- /Pricing Section -->

        <!-- Faq Section -->
        <!-- <section id="faq" class="faq section">

            <div class="container">

                <div class="row gy-4">

                    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="content px-xl-5">
                            <h3><span>Frequently Asked </span><strong>Questions</strong></h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Duis aute irure dolor in reprehenderit
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">

                        <div class="faq-container">
                            <div class="faq-item faq-active">
                                <h3><span class="num">1.</span> <span>Non consectetur a erat nam at lectus urna duis?</span></h3>
                                <div class="faq-content">
                                    <p>Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3><span class="num">2.</span> <span>Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque?</span></h3>
                                <div class="faq-content">
                                    <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3><span class="num">3.</span> <span>Dolor sit amet consectetur adipiscing elit pellentesque?</span></h3>
                                <div class="faq-content">
                                    <p>Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3><span class="num">4.</span> <span>Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla?</span></h3>
                                <div class="faq-content">
                                    <p>Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                            <div class="faq-item">
                                <h3><span class="num">5.</span> <span>Tempus quam pellentesque nec nam aliquam sem et tortor consequat?</span></h3>
                                <div class="faq-content">
                                    <p>Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in</p>
                                </div>
                                <i class="faq-toggle bi bi-chevron-right"></i>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </section> -->
        <!-- /Faq Section -->

        <!-- Team Section -->
        <!-- <section id="team" class="team section light-background">

            <div class="container section-title" data-aos="fade-up">
                <h2>Team</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div>

            <div class="container">

                <div class="row gy-5">

                    <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="100">
                        <div class="member-img">
                            <img src="welcome/assets/img/team/team-1.jpg" class="img-fluid" alt="">
                            <div class="social">
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info text-center">
                            <h4>Walter White</h4>
                            <span>Chief Executive Officer</span>
                            <p>Aliquam iure quaerat voluptatem praesentium possimus unde laudantium vel dolorum distinctio dire flow</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="200">
                        <div class="member-img">
                            <img src="welcome/assets/img/team/team-2.jpg" class="img-fluid" alt="">
                            <div class="social">
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info text-center">
                            <h4>Sarah Jhonson</h4>
                            <span>Product Manager</span>
                            <p>Labore ipsam sit consequatur exercitationem rerum laboriosam laudantium aut quod dolores exercitationem ut</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="300">
                        <div class="member-img">
                            <img src="welcome/assets/img/team/team-3.jpg" class="img-fluid" alt="">
                            <div class="social">
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info text-center">
                            <h4>William Anderson</h4>
                            <span>CTO</span>
                            <p>Illum minima ea autem doloremque ipsum quidem quas aspernatur modi ut praesentium vel tque sed facilis at qui</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="400">
                        <div class="member-img">
                            <img src="welcome/assets/img/team/team-4.jpg" class="img-fluid" alt="">
                            <div class="social">
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info text-center">
                            <h4>Amanda Jepson</h4>
                            <span>Accountant</span>
                            <p>Magni voluptatem accusamus assumenda cum nisi aut qui dolorem voluptate sed et veniam quasi quam consectetur</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="500">
                        <div class="member-img">
                            <img src="welcome/assets/img/team/team-5.jpg" class="img-fluid" alt="">
                            <div class="social">
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info text-center">
                            <h4>Brian Doe</h4>
                            <span>Marketing</span>
                            <p>Qui consequuntur quos accusamus magnam quo est molestiae eius laboriosam sunt doloribus quia impedit laborum velit</p>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 member" data-aos="fade-up" data-aos-delay="600">
                        <div class="member-img">
                            <img src="welcome/assets/img/team/team-6.jpg" class="img-fluid" alt="">
                            <div class="social">
                                <a href="#"><i class="bi bi-twitter-x"></i></a>
                                <a href="#"><i class="bi bi-facebook"></i></a>
                                <a href="#"><i class="bi bi-instagram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                        <div class="member-info text-center">
                            <h4>Josepha Palas</h4>
                            <span>Operation</span>
                            <p>Sint sint eveniet explicabo amet consequatur nesciunt error enim rerum earum et omnis fugit eligendi cupiditate vel</p>
                        </div>
                    </div>

                </div>

            </div>

        </section> -->
        <!-- /Team Section -->

        <!-- Call To Action Section -->
        <section id="call-to-action" class="call-to-action section dark-background">

            <img src="welcome/assets/img/cta-bg.jpg" alt="">

            <div class="container">
                <div class="row justify-content-center" data-aos="zoom-in" data-aos-delay="100">
                    <div class="col-xl-10">
                        <div class="text-center">
                            <h3>Tujuan Kami</h3>
                            <p>"Memberikan layanan perawatan dan pembersihan sepatu terbaik dengan kualitas premium, agar setiap pelanggan Append dapat tampil percaya diri dengan sepatu yang bersih, wangi, dan nyaman dipakai."</p>
                            <!-- <a class="cta-btn" href="#">Call To Action</a> -->
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
                        <h3>Testimonials</h3>
                        <p>
                            Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.
                        </p>
                    </div>

                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">

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
                                                <h3>Saul Goodman</h3>
                                                <h4>Ceo &amp; Founder</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam, risus at semper.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="welcome/assets/img/testimonials/testimonials-2.jpg" class="testimonial-img flex-shrink-0" alt="">
                                            <div>
                                                <h3>Sara Wilsson</h3>
                                                <h4>Designer</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet legam anim culpa.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="welcome/assets/img/testimonials/testimonials-3.jpg" class="testimonial-img flex-shrink-0" alt="">
                                            <div>
                                                <h3>Jena Karlis</h3>
                                                <h4>Store Owner</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam duis minim tempor labore quem eram duis noster aute amet eram fore quis sint minim.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="welcome/assets/img/testimonials/testimonials-4.jpg" class="testimonial-img flex-shrink-0" alt="">
                                            <div>
                                                <h3>Matt Brandon</h3>
                                                <h4>Freelancer</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore labore illum veniam.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="d-flex">
                                            <img src="welcome/assets/img/testimonials/testimonials-5.jpg" class="testimonial-img flex-shrink-0" alt="">
                                            <div>
                                                <h3>John Larson</h3>
                                                <h4>Entrepreneur</h4>
                                                <div class="stars">
                                                    <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p>
                                            <i class="bi bi-quote quote-icon-left"></i>
                                            <span>Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat legam esse veniam culpa fore nisi cillum quid.</span>
                                            <i class="bi bi-quote quote-icon-right"></i>
                                        </p>
                                    </div>
                                </div><!-- End testimonial item -->

                            </div>
                            <div class="swiper-pagination"></div>
                        </div>

                    </div>

                </div>

            </div>

        </section><!-- /Testimonials Section -->

        <!-- Recent Posts Section -->
        <!-- <section id="recent-posts" class="recent-posts section">

            <div class="container section-title" data-aos="fade-up">
                <h2>Recent Posts</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div>

            <div class="container">

                <div class="row gy-4">

                    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <article>

                            <div class="post-img">
                                <img src="welcome/assets/img/blog/blog-1.jpg" alt="" class="img-fluid">
                            </div>

                            <p class="post-category">Politics</p>

                            <h2 class="title">
                                <a href="blog-details.html">Dolorum optio tempore voluptas dignissimos</a>
                            </h2>

                            <div class="d-flex align-items-center">
                                <img src="welcome/assets/img/blog/blog-author.jpg" alt="" class="img-fluid post-author-img flex-shrink-0">
                                <div class="post-meta">
                                    <p class="post-author">Maria Doe</p>
                                    <p class="post-date">
                                        <time datetime="2022-01-01">Jan 1, 2022</time>
                                    </p>
                                </div>
                            </div>

                        </article>
                    </div>

                    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                        <article>

                            <div class="post-img">
                                <img src="welcome/assets/img/blog/blog-2.jpg" alt="" class="img-fluid">
                            </div>

                            <p class="post-category">Sports</p>

                            <h2 class="title">
                                <a href="blog-details.html">Nisi magni odit consequatur autem nulla dolorem</a>
                            </h2>

                            <div class="d-flex align-items-center">
                                <img src="welcome/assets/img/blog/blog-author-2.jpg" alt="" class="img-fluid post-author-img flex-shrink-0">
                                <div class="post-meta">
                                    <p class="post-author">Allisa Mayer</p>
                                    <p class="post-date">
                                        <time datetime="2022-01-01">Jun 5, 2022</time>
                                    </p>
                                </div>
                            </div>

                        </article>
                    </div>

                    <div class="col-xl-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                        <article>

                            <div class="post-img">
                                <img src="welcome/assets/img/blog/blog-3.jpg" alt="" class="img-fluid">
                            </div>

                            <p class="post-category">Entertainment</p>

                            <h2 class="title">
                                <a href="blog-details.html">Possimus soluta ut id suscipit ea ut in quo quia et soluta</a>
                            </h2>

                            <div class="d-flex align-items-center">
                                <img src="welcome/assets/img/blog/blog-author-3.jpg" alt="" class="img-fluid post-author-img flex-shrink-0">
                                <div class="post-meta">
                                    <p class="post-author">Mark Dower</p>
                                    <p class="post-date">
                                        <time datetime="2022-01-01">Jun 22, 2022</time>
                                    </p>
                                </div>
                            </div>

                        </article>
                    </div>

                </div>

            </div>

        </section> -->
        <!-- /Recent Posts Section -->

        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-6">

                        <div class="row gy-4">
                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="200">
                                    <i class="bi bi-geo-alt"></i>
                                    <h3>Address</h3>
                                    <p>A108 Adam Street</p>
                                    <p>New York, NY 535022</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="300">
                                    <i class="bi bi-telephone"></i>
                                    <h3>Call Us</h3>
                                    <p>+1 5589 55488 55</p>
                                    <p>+1 6678 254445 41</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="400">
                                    <i class="bi bi-envelope"></i>
                                    <h3>Email Us</h3>
                                    <p>info@example.com</p>
                                    <p>contact@example.com</p>
                                </div>
                            </div><!-- End Info Item -->

                            <div class="col-md-6">
                                <div class="info-item" data-aos="fade" data-aos-delay="500">
                                    <i class="bi bi-clock"></i>
                                    <h3>Open Hours</h3>
                                    <p>Monday - Friday</p>
                                    <p>9:00AM - 05:00PM</p>
                                </div>
                            </div><!-- End Info Item -->

                        </div>

                    </div>

                    <div class="col-lg-6">
                        <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="200">
                            <div class="row gy-4">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required="">
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email" required="">
                                </div>

                                <div class="col-12">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject" required="">
                                </div>

                                <div class="col-12">
                                    <textarea class="form-control" name="message" rows="6" placeholder="Message" required=""></textarea>
                                </div>

                                <div class="col-12 text-center">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit">Send Message</button>
                                </div>

                            </div>
                        </form>
                    </div><!-- End Contact Form -->

                </div>

            </div>

        </section><!-- /Contact Section -->

    </main>

    <footer id="footer" class="footer position-relative light-background">

        <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-5 col-md-12 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Append</span>
                    </a>
                    <p>Append adalah layanan cuci sepatu profesional yang siap merawat dan membersihkan sepatu kesayangan kamu hingga seperti baru lagi. Percayakan kebersihan sepatumu hanya di Append!</p>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-6 footer-links">
                    <h4>Link</h4>
                    <ul>
                        <li><a href="#">Beranda</a></li>
                        <li><a href="#">Tentang</a></li>
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
                        <li><a href="#">Sepatu Custom Care</a></li>
                        <li><a href="#">Antar Jemput Sepatu</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                    <h4>Hubungi Kami</h4>
                    <p>A108 Adam Street</p>
                    <p>New York, NY 535022</p>
                    <p>United States</p>
                    <p class="mt-4"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
                    <p><strong>Email:</strong> <span>info@example.com</span></p>
                </div>

            </div>
        </div>

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="sitename">Append</strong> <span>All Rights Reserved</span></p>
            <div class="credits">
                <!-- All the links in the footer should remain intact. -->
                <!-- You can delete the links only if you've purchased the pro version. -->
                <!-- Licensing information: https://bootstrapmade.com/license/ -->
                <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
                Designed by Khaerul Anam
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

</body>

</html>