@php
    use Illuminate\Support\Facades\DB;
    $cmsPages = DB::table('cms_pages')->get();
    $currentUrl = request()->url(); // Get the current URL
@endphp

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('uploads/logo.png') }}" alt="Studio 9 Logo" height="30">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <!-- Uncomment the following lines if you want to dynamically generate CMS pages -->
                <!-- @foreach($cmsPages as $page)
                    <li class="nav-item">
                        <a class="nav-link {{ $currentUrl == url($page->slug) ? 'active' : '' }}" href="{{ url($page->slug) }}">{{ $page->page_title }}</a>
                    </li>
                @endforeach -->
                <!-- Example static navigation links -->
               
                <li class="nav-item">
    <a class="nav-link {{ request()->is('/') || request()->is('privacy-policy') ? 'active' : '' }}" href="{{ url('/privacy-policy') }}">Privacy Policy</a>
</li>

                <li class="nav-item">
                    <a class="nav-link {{ $currentUrl == url('/terms-and-conditions') ? 'active' : '' }}" href="{{ url('/terms-and-conditions') }}">Terms & Conditions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentUrl == url('/faqs') ? 'active' : '' }}" href="{{ url('/faqs') }}">FAQs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentUrl == url('/about-us') ? 'active' : '' }}" href="{{ url('/about-us') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentUrl == url('/contact-us') ? 'active' : '' }}" href="{{ url('/contact-us') }}">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


<!-- Custom CSS for active link and styling -->
<style>
  .navbar-brand {
        display: flex;
        align-items: center;
    }
    .navbar-brand img {
        margin-right: 10px;
    }
    .navbar-brand span {
        font-weight: bold;
    }
    .navbar-brand small {
        display: block;
        font-size: 12px;
        color: #555;
    }
    .nav-link {
        margin-right: 20px;
        font-size: 14px;
        transition: all 0.3s ease; /* Smooth transition for hover effect */
    }
    .nav-link:hover {
        color: #007bff; /* Change color on hover */
    }
    .nav-link.active {
        font-weight: bold;
        text-decoration: underline; /* Example style for active link */
    }
    .btn-outline-dark {
        padding: 5px 10px;
        font-size: 14px;
        border: 2px solid #000;
    }
    body {
    font-family: Arial, sans-serif;
}

.footer {
    background-color: #fff;
    padding: 40px 0;
    text-align: center;
    border-top: 1px solid #ddd;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.footer-branding {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.footer-logo {
    height: 50px;
    margin-right: 10px;
}

.footer-text h2 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}

.footer-text p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.footer-description {
    margin-bottom: 20px;
}

.footer-description p {
    margin: 0;
    font-size: 14px;
    color: #666;
}

.footer-contact {
    margin-bottom: 20px;
}

.footer-contact p {
    margin: 5px 0;
    font-size: 14px;
}

.footer-contact a {
    color: #000;
    text-decoration: none;
}

.footer-social {
    margin-bottom: 20px;
}

.footer-social a {
    margin: 0 10px;
    color: #000;
    text-decoration: none;
    font-size: 18px;
}

.footer-links {
    font-size: 14px;
}

.footer-links a {
    color: #000;
    text-decoration: none;
    margin: 0 5px;
}

.footer-links span {
    margin: 0 5px;
    color: #000;
}

</style>
