<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Eamon Express')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="css/scrollbar.css">
    <link rel="stylesheet" href="@yield('css_content')">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://js.radar.com/v4.4.3/radar.css" rel="stylesheet">
    <script src="https://js.radar.com/v4.4.3/radar.min.js"></script>
  </head>
  <body data-bs-spy="scroll" data-bs-target=".navbar" tabindex="0" style="background-color: #b0e1f9;">

    <header id="headerSection" class="sticky-top">
        <nav class="navbar navbar-expand-md navbar-light" style="background-color: #b0e1f9;">
            <div class="container-md px-3">
                <a class="navbar-brand" href="/">
                    <img src="img/logo.png" alt="Logo" class="img-fluid" style="max-height: 45px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="/">Home</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link fw-bold" href="#track">Track a Package</a>
                        </li> -->
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ url('/contact') }}">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ url('/about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="{{ url('/blog') }}">Blog</a>
                        </li>
                        <!-- <li class="nav-item">
                            <a class="nav-link fw-bold" href="#login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="#signup">Sign Up</a>
                        </li> -->
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    @yield('content')
    <footer class=" py-5 footer-container">
        <div class="container px-5">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 text-center">
                    <img src="img/logo.png" alt="Logo" class="img-fluid mb-3" style="max-width: 150px;">

                        <h5 class="fw-bold" style="color: #284292">Connect with us</h5>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="me-2">
                                <img src="img/footer/facebook.svg" alt="Facebook" class="img-fluid" style="width: 30px; max-width: 100%; height: auto;">
                            </a>
                            <a href="#" class="me-2">
                                <img src="img/footer/x.png" alt="Twitter" class="img-fluid" style="width: 30px; max-width: 100%; height: auto;">
                            </a>
                            <a href="#">
                                <img src="img/footer/insta.svg" alt="Instagram" class="img-fluid" style="width: 30px; max-width: 100%; height: auto;">
                            </a>
                        </div>

                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold" style="color: #284292">Company</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/about') }}" style="color: #212529BF">About Us</a></li>
                        <li><a href="{{ url('/review') }}" style="color: #212529BF">Reviews</a></li>
                        <li><a href="{{ url('/blog') }}" style="color: #212529BF">Blog</a></li>
                        <li><a href="#" style="color: #212529BF">Privacy Policy</a></li>
                        <li><a href="#" style="color: #212529BF">Cookie Policy</a></li>
                        <li><a href="#" style="color: #212529BF">Terms & Conditions</a></li>
                        <li><a href="#" style="color: #212529BF">Acceptable Use Policy</a></li>
                        <li><a href="#" style="color: #212529BF">Sitemap</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold" style="color: #284292">Shipping Services</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" style="color: #212529BF">Ship a Package</a></li>
                        <li><a href="{{ url('/domestic-shipping') }}" style="color: #212529BF">Domestic Shipping</a></li>
                        <li><a href="{{ url('/international-shipping') }}" style="color: #212529BF">International Shipping</a></li>
                        <li><a href="#" style="color: #212529BF">Bulk Shipping</a></li>
                        <li><a href="{{ url('/couriers') }}" style="color: #212529BF">Couriers</a></li>
                        <li><a href="{{ url('/delivery') }}" style="color: #212529BF">Delivery Services</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold" style="color: #284292">Customer</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ url('/contact') }}" style="color: #212529BF">Contact Us</a></li>
                        <li><a href="#" style="color: #212529BF">How To Guides</a></li>
                    </ul>
                </div>


            </div>
            <hr class="bg-white my-4">
            <div class="text-center">
                <p class="mb-0 p-footer">&copy; 2024 Your Company Name. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="@yield('js_content')"></script>
    <script src="@yield('js_content2')"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
      </script>
  </body>
</html>
