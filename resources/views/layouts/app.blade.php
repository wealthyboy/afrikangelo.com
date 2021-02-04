<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset( $page_title) ?  $page_title .' |  '.config('app.name') :  $system_settings->meta_title  }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ isset($page_meta_description) ? $page_meta_description : $system_settings->meta_description }}">
    <meta name="keywords" content="{{ isset($system_settings->meta_tag_keywords) ? $system_settings->meta_tag_keywords : '' }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="canonical" href="{{ Config('app.url') }}">

     <!-- Favicone Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
	<link rel="icon" href="/img/favicon.ico" type="image/x-icon">
	<link rel="icon" type="image/png" href="/img/favicon-96x96.png">
	<link rel="apple-touch-icon" href="/img/favicon-96x96.png">

	

   

    <!-- CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
	<!-- Main CSS File -->
	<link rel="stylesheet" href="/css/style.min.css?version={{ str_random(6) }}">
	<link rel="stylesheet" type="text/css" href="/vendor/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="/css/skins/skin-default.css?version={{ str_random(6) }}">

    @yield('page-css')
    <link href="/css/custom.css?version={{ str_random(6) }}" rel="stylesheet" type="text/css" />
    <meta property="og:site_name" content="afrikangelo Co">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:title" content="afrikangelo">
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{ isset($page_meta_description) ? $page_meta_description : $system_settings->meta_description }}">
    <meta property="og:image:alt" content="">
    <meta name="twitter:site" content="@afrikangelo">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ isset($page_meta_description) ? $page_meta_description : $system_settings->meta_description }}">
    <meta name="twitter:description" content="{{ isset($page_meta_description) ? $page_meta_description : $system_settings->meta_description }}">
    <script>
		Window.user = {
			user: {!! auth()->check() ? auth()->user() : 0000 !!},
			loggedIn: {!! auth()->check() ? 1 : 0 !!},
			settings: {!! isset($system_settings) ? $system_settings : '' !!},
			token: '{!! csrf_token() !!}'
		}
	</script>

</head>
<body>
    <div class="page-wrapper">
        <header class="header">
            <div class="header-top">
                <div class="container">
                    <div class="header-left header-dropdowns">
                        <div class="header-dropdown">
                            <a href="category.html#">USD</a>
                            <div class="header-menu">
                                <ul>
                                    <li><a href="#">EUR</a></li>
                                    <li><a href="#">USD</a></li>
                                </ul>
                            </div><!-- End .header-menu -->
                        </div><!-- End .header-dropown -->

                        <div class="header-dropdown">
                            <a href="#">ENGLISH</a>
                            <div class="header-menu">
                                <ul>
                                    <li><a href="#">ENGLISH</a></li>
                                    <li><a href="#">FRENCH</a></li>
                                </ul>
                            </div><!-- End .header-menu -->
                        </div><!-- End .header-dropown -->
                    </div><!-- End .header-left -->
                    <div class="header-right">
                        <ul>
                            <li><a href="category.html#">default welcome msg</a></li>
                            <li><a href="my-account.html">my account</a></li>
                            <li><a href="category.html#">daily deal</a></li>
                            <li><a href="category.html#">my wishlist</a></li>
                            <li><a href="blog.html">blog</a></li>
                            <li><a href="contact.html">contact</a></li>
                            <li><a href="login.html">log in</a></li>
                        </ul>
                    </div>
                </div>
            </div><!-- End .header-top -->

            <div class="header-middle sticky-header">
                <div class="container">
                    <div class="header-left">
                        <button class="mobile-menu-toggler" type="button">
                            <i class="icon-menu"></i>
                        </button>
                        <a href="index.html" class="logo">
                            <img src="{{ $system_settings->logo_path() }}" alt="{{ Config('app.name') }} Logo">
                        </a>
                        <nav class="main-nav">
                        <ul class="menu">
							
                            @foreach( $global_categories   as  $category)

                                <li>
                                   <a style="color: {{  $category->text_color }} !important" href="/products/{{ $category->slug }}">{{ $category->name }}</a>
                                   @if ($category->isCategoryHaveMultipleChildren())

                                    <div class="megamenu megamenu-fixed-width">
                                        <div class="row">
										    <div class="col-lg-9">
											    <div class="row">
													@foreach (  $category->children as $children)
													<div class="col-lg-3">
														<a href="/products/{{ $children->slug }}" class="category-heading"><b>{{ $children->name !== 'No Heading' ? $children->name : '' }} </b></a>
														@if ($children->children->count())
															<ul class="submenu">
																@foreach (  $children->children as $children)
																	<li><a href="/products/{{ $children->slug }}">{{ ucfirst($children->name) }}</a></li>
																@endforeach
															</ul>
														@endif
													</div><!-- End .col-lg-4 -->
													@endforeach
		                                        </div>
											</div>
											
											<div class="col-lg-3">
												<div class="col-lg-12 p-0">
												   <a href="{{ $category->image_custom_link }}"><img src="{{ $category->image }}" alt="{{ $category->image }}" class="product-promo" ></a>
												</div><!-- End .col-lg-4 -->
											</div>
										   

                                        </div><!-- End .row -->
                                    </div><!-- End .megamenu -->
                                    @elseif ( !$category->isCategoryHaveMultipleChildren() && $category->children->count() )
                                        <ul  >
                                            @foreach (  $category->children as $children)
                                               <li class="nav-children color--primary  {{ strtolower($category->name) == 'christmas shop' ? 'pl-5' : '' }}"><a href="/products/{{ $children->slug }}">{{ $children->name }}</a></li>
                                            @endforeach 
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
						</ul>
                        </nav>
                    </div>
                    <div class="header-right">
                        <div class="header-search">
                            <a href="category.html#" class="search-toggle" role="button"><i class="icon-search-3"></i></a>
                            <form action="#" method="get">
                                <div class="header-search-wrapper">
                                    <input type="search" class="form-control" name="q" id="q" placeholder="I'm searching for..." required="">
                                    <div class="select-custom">
                                        <select id="cat" name="cat">
                                            <option value="">All Categories</option>
                                            <option value="4">Fashion</option>
                                            <option value="12">- Women</option>
                                            <option value="13">- Men</option>
                                            <option value="66">- Jewellery</option>
                                            <option value="67">- Kids Fashion</option>
                                            <option value="5">Electronics</option>
                                            <option value="21">- Smart TVs</option>
                                            <option value="22">- Cameras</option>
                                            <option value="63">- Games</option>
                                            <option value="7">Home &amp; Garden</option>
                                            <option value="11">Motors</option>
                                            <option value="31">- Cars and Trucks</option>
                                            <option value="32">- Motorcycles &amp; Powersports</option>
                                            <option value="33">- Parts &amp; Accessories</option>
                                            <option value="34">- Boats</option>
                                            <option value="57">- Auto Tools &amp; Supplies</option>
                                        </select>
                                    </div><!-- End .select-custom -->
                                    <button class="btn" type="submit"><i class="icon-search-3"></i></button>
                                </div><!-- End .header-search-wrapper -->
                            </form>
                        </div>

                        <a href="category.html#" class="porto-icon"><i class="icon icon-wishlist-2"></i></a>

                        <div class="dropdown cart-dropdown">
                            <a href="category.html#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                <i class="minicart-icon"></i>
                                <span class="cart-count">2</span>
                            </a>

                            <div class="dropdown-menu" >
                                <div class="dropdownmenu-wrapper">
                                    <div class="dropdown-cart-header">
                                        <span>2 Items</span>

                                        <a href="cart.html">View Cart</a>
                                    </div><!-- End .dropdown-cart-header -->
                                    <div class="dropdown-cart-products">
                                        <div class="product">
                                            <div class="product-details">
                                                <h4 class="product-title">
                                                    <a href="product.html">Woman Ring</a>
                                                </h4>

                                                <span class="cart-product-info">
                                                    <span class="cart-product-qty">1</span>
                                                    x $99.00
                                                </span>
                                            </div><!-- End .product-details -->

                                            <figure class="product-image-container">
                                                <a href="product.html" class="product-image">
                                                    <img src="/images/products/cart/product-1.jpg" alt="product">
                                                </a>
                                                <a href="category.html#" class="btn-remove" title="Remove Product"><i class="icon-retweet"></i></a>
                                            </figure>
                                        </div><!-- End .product -->

                                        <div class="product">
                                            <div class="product-details">
                                                <h4 class="product-title">
                                                    <a href="product.html">Woman Necklace</a>
                                                </h4>

                                                <span class="cart-product-info">
                                                    <span class="cart-product-qty">1</span>
                                                    x $35.00
                                                </span>
                                            </div><!-- End .product-details -->

                                            <figure class="product-image-container">
                                                <a href="product.html" class="product-image">
                                                    <img src="/images/products/cart/product-2.jpg" alt="product">
                                                </a>
                                                <a href="category.html#" class="btn-remove" title="Remove Product"><i class="icon-retweet"></i></a>
                                            </figure>
                                        </div><!-- End .product -->
                                    </div><!-- End .cart-product -->

                                    <div class="dropdown-cart-total">
                                        <span>Total</span>

                                        <span class="cart-total-price">$134.00</span>
                                    </div><!-- End .dropdown-cart-total -->

                                    <div class="dropdown-cart-action">
                                        <a href="checkout-shipping.html" class="btn btn-block">Checkout</a>
                                    </div><!-- End .dropdown-cart-total -->
                                </div><!-- End .dropdownmenu-wrapper -->
                            </div><!-- End .dropdown-menu -->
                        </div><!-- End .dropdown -->
                    </div>
                </div>
            </div>

            <div class="header-bottom">
                <div class="col-lg-4">
                    <div class="service-widget">
                        <i class="service-icon icon-shipping"></i>
                        <div class="service-content">
                            <h3 class="service-title">free shipping & return</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-widget">
                        <i class="service-icon icon-money"></i>
                        <div class="service-content">
                            <h3 class="service-title">money back guarantee</h3>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="service-widget">
                        <i class="service-icon icon-support"></i>
                        <div class="service-content">
                            <h3 class="service-title">online support 24/7</h3>
                        </div>
                    </div>
                </div>
            </div>
        </header><!-- End .header -->

        <main class="main main-page">
          @yield('content')
        </main> 

        <footer class="footer">
            <div class="instagram-box">
                <div class="instagram-follow">
                    <div class="container">
                        <i class="fab fa-instagram"></i>
                        <div class="brand">
                            <h3>Follow Us on Instagram</h3>
                            <p>@portoecommerce</p>
                        </div>
                    </div>
                </div>
                <div class="instagram-carousel owl-carousel owl-theme text-center" data-toggle="owl" data-owl-options="{
                    'loop' : true,
                    'nav': false,
                    'dots': false,
                    'margin' : 0,
                    'autoHeight': true,
                    'autoplay': true,
                    'autoplayTimeout': 5000,
                    'responsive': {
                      '0': {
                        'items': 1
                      },
                      '500': {
                        'items': 2
                      },
                      '600': {
                        'items': 3
                      },
                      '950': {
                        'items': 4
                      },
                      '1200': {
                        'items' : 5
                      },
                      '1500': {
                        'items': 6
                      }
                    }
                }">
                    <img src="/images/instagram/instagram1.jpg" alt="instagram image">
                    <img src="/images/instagram/instagram2.jpg" alt="instagram image">
                    <img src="/images/instagram/instagram3.jpg" alt="instagram image">
                    <img src="/images/instagram/instagram4.jpg" alt="instagram image">
                    <img src="/images/instagram/instagram5.jpg" alt="instagram image">
                    <img src="/images/instagram/instagram6.jpg" alt="instagram image">
                </div>
            </div>

            <div class="footer-top">
                <div class="container">
                    <div class="newsletter-widget">
                        <i class="icon-envolope"></i>

                        <div class="newsletter-info">
                            <h3>Get Special Offers and Savings</h3>
                            <p>Get all the latest information on Events, Sales and Offers.</p>
                        </div>

                        <form action="#" method="get">
                            <div class="submit-wrapper">
                                <input type="search" class="form-control" name="q" id="q" placeholder="Enter Your E-mail Address..." required="">
                                <button class="btn" type="submit">Sign Up</button>
                            </div><!-- End .header-search-wrapper -->
                        </form>
                    </div>

                    <div class="social-icons">
                        <a href="category.html#" target="_blank" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="category.html#" target="_blank" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="category.html#" target="_blank" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-middle">
                <div class="container">
                    <div class="row row-sm">
                        <div class="col-md-6 col-lg-3">
                            <img src="/images/logo-white.png">
                            <p>Lorem ipsum dolor sit amet, consectetur adipis.</p>
                            <div class="social-link">
                                <h3 class="link-title">QUESTIONS?</h3>
                                <a href="category.html#">1-888-123-456</a>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="widget">
                                <h3 class="widget-title">Account</h3>
                                <div class="widget-content row row-sm">
                                    <ul class="col-xl-6">
                                        <li><a href="my-account.html">My Account</a></li>
                                        <li><a href="category.html#">Track Your Order</a></li>
                                        <li><a href="category.html#">Payment Methods</a></li>
                                        <li><a href="category.html#">Shipping Guide</a></li>
                                    </ul>
                                    <ul class="col-xl-6">
                                        <li><a href="category.html#">FAQs</a></li>
                                        <li><a href="category.html#">Product Support</a></li>
                                        <li><a href="category.html#">Privacy</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="widget">
                                <h3 class="widget-title">About</h3>
                                <div class="widget-content row row-sm">
                                    <ul class="col-xl-6">
                                        <li><a href="about.html">About Porto</a></li>
                                        <li><a href="category.html#">Our Guarantees</a></li>
                                        <li><a href="category.html#">Terms And Conditions</a></li>
                                        <li><a href="category.html#">Privacy Policy</a></li>
                                    </ul>
                                    <ul class="col-xl-6">
                                        <li><a href="category.html#">Return Policy</a></li>
                                        <li><a href="category.html#">Intellectual Property Claims</a></li>
                                        <li><a href="category.html#">Site Map</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="widget">
                                <h3 class="widget-title">Features</h3>
                                <div class="widget-content">
                                    <ul>
                                        <li><a href="category.html#">Powerful Admin Panel</a></li>
                                        <li><a href="category.html#">Mobile & Retina Optimized</a></li>
                                        <li><a href="category.html#">Super Fast Magento Theme</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom container">
                <p>Porto eCommerce. © 2020. All Rights Reserved</p>
            </div>
        </footer><!-- End .footer -->
    </div><!-- End .page-wrapper -->

    <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-retweet"></i></span>
            <nav class="mobile-nav">
                <ul class="mobile-menu">
                    <li class="active"><a href="index.html">Home</a></li>
                    <li>
                        <a href="category.html">Categories</a>
                        <ul>
                            <li><a href="category.html">Full Width Banner</a></li>
                            <li><a href="category-banner-boxed-slider.html">Boxed Slider Banner</a></li>
                            <li><a href="category-banner-boxed-image.html">Boxed Image Banner</a></li>
                            <li><a href="category.html">Left Sidebar</a></li>
                            <li><a href="category-sidebar-right.html">Right Sidebar</a></li>
                            <li><a href="category-flex-grid.html">Product Flex Grid</a></li>
                            <li><a href="category-horizontal-filter1.html">Horizontal Filter 1</a></li>
                            <li><a href="category-horizontal-filter2.html">Horizontal Filter 2</a></li>
                            <li><a href="category.html#">Product List Item Types</a></li>
                            <li><a href="category-infinite-scroll.html">Ajax Infinite Scroll</a></li>
                            <li><a href="category-3col.html">3 Columns Products</a></li>
                            <li><a href="category.html">4 Columns Products</a></li>
                            <li><a href="category-5col.html">5 Columns Products</a></li>
                            <li><a href="category-6col.html">6 Columns Products</a></li>
                            <li><a href="category-7col.html">7 Columns Products</a></li>
                            <li><a href="category-8col.html">8 Columns Products</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="product.html">Products</a>
                        <ul>
                            <li>
                                <a href="category.html#">Variations</a>
                                <ul>
                                    <li><a href="product.html">Horizontal Thumbnails</a></li>
                                    <li><a href="product-full-width.html">Vertical Thumbnails</a></li>
                                    <li><a href="product.html">Inner Zoom</a></li>
                                    <li><a href="product-addcart-sticky.html">Addtocart Sticky</a></li>
                                    <li><a href="product-sidebar-left.html">Accordion Tabs</a></li>
                                    <li><a href="product-sticky-tab.html">Sticky Tabs</a></li>
                                    <li><a href="product-simple.html">Simple Product</a></li>
                                    <li><a href="product-sidebar-left.html">With Left Sidebar</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="category.html#">Product Layout Types</a>
                                <ul>
                                    <li><a href="product.html">Default Layout</a></li>
                                    <li><a href="product-extended-layout.html">Extended Layout</a></li>
                                    <li><a href="product-full-width.html">Full Width Layout</a></li>
                                    <li><a href="product-grid-layout.html">Grid Images Layout</a></li>
                                    <li><a href="product-sticky-both.html">Sticky Both Side Info</a></li>
                                    <li><a href="product-sticky-info.html">Sticky Right Side Info</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="category.html#">Pages</a>
                        <ul>
                            <li><a href="cart.html">Shopping Cart</a></li>
                            <li>
                                <a href="category.html#">Checkout</a>
                                <ul>
                                    <li><a href="checkout-shipping.html">Checkout Shipping</a></li>
                                    <li><a href="checkout-shipping-2.html">Checkout Shipping 2</a></li>
                                    <li><a href="checkout-review.html">Checkout Review</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="category.html#">Dashboard</a>
                                <ul>
                                    <li><a href="dashboard.html">Dashboard</a></li>
                                    <li><a href="my-account.html">My Account</a></li>
                                </ul>
                            </li>
                            <li><a href="about.html">About Us</a></li>
                            <li>
                                <a href="category.html#">Blog</a>
                                <ul>
                                    <li><a href="blog.html">Blog</a></li>
                                    <li><a href="single.html">Blog Post</a></li>
                                </ul>
                            </li>
                            <li><a href="contact.html">Contact Us</a></li>
                            <li><a href="category.html#" class="login-link">Login</a></li>
                            <li><a href="forgot-password.html">Forgot Password</a></li>
                        </ul>
                    </li>
                    <li><a href="https://1.envato.market/DdLk5" target="_blank">Buy Porto!</a></li>
                </ul>
            </nav><!-- End .mobile-nav -->

            <div class="social-icons">
                <a href="category.html#" class="social-icon" target="_blank"><i class="icon-facebook"></i></a>
                <a href="category.html#" class="social-icon" target="_blank"><i class="icon-twitter"></i></a>
                <a href="category.html#" class="social-icon" target="_blank"><i class="icon-instagram"></i></a>
            </div><!-- End .social-icons -->
        </div><!-- End .mobile-menu-wrapper -->
    </div><!-- End .mobile-menu-container -->

    <!-- Add Cart Modal -->
    <div class="modal fade" id="addCartModal" tabindex="-1" role="dialog" aria-labelledby="addCartModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body add-cart-box text-center">
            <p>You've just added this product to the<br>cart:</p>
            <h4 id="productTitle"></h4>
            <img src="" id="productImage" width="100" height="100" alt="adding cart image">
            <div class="btn-actions">
                <a href="cart.html"><button class="btn-primary">Go to cart page</button></a>
                <a href="category.html#"><button class="btn-primary" data-dismiss="modal">Continue</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <a id="scroll-top" href="category.html#top" title="Top" role="button"><i class="icon-angle-up"></i></a>

    <!-- Plugins JS File -->
    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/plugins.min.js"></script>
    <script src="/js/nouislider.min.js"></script>
    <script src="/js/plugins/isotope-docs.min.js"></script>

    <!-- Main JS File -->
    <script src="/js/main.min.js"></script>
</body>
</html>
