@extends('layouts.layout')
@section('title', 'Eamon Express | About')
@section('css_content', 'css/style.css')
<style>
    img{
        max-width: 100%;
        height: auto;
    }
</style>

@section('content')


<div class="row-one">
  <div class="column left">
    <h2 class="title"><b>ABOUT EAMON EXPRESS</b></h2>
    <p class="info">At Eamon Express, we are deeply committed to providing delivery services that are both exceptional and reliable. Our focus is on ensuring every aspect of our service meets the highest standards of quality, so you can trust us to handle your shipments with care and efficiency.</p>
    <p class="info">We believe in going the extra mile to ensure our customers' needs are met. Our team of experienced professionals is here to provide tailored solutions, personalized support, and reliable service you can count on. We are proud to be your trusted delivery partner and look forward to serving you with the highest standards of excellence.</p>
    <p class="info">Together, we deliver the world to your doorstep.</p>
  </div>
  <div class="column right">
    <img src="img/About/hand-drawn-international-trade-with-money.png" class="img-fluid">
  </div>
</div>

<div class="row-two">
    <div class="column left">
        <img src="img/About/flat-design-cash-delivery.png" class="img">
    </div>

    <div class="column right">
        <div class="list">
            <h2><b>What We Offer?</b></h2>
                <div class="box">
                    <img src="img/About/check-mark.png" class="icon">
                    <p class="list1"><b>Domestic Shipping: </b>With partners like FedEx and UPS, we offer competitive rates and swift delivery times within the country.</p>
                </div>
                <div class="box">
                    <img src="img/About/check-mark.png" class="icon">
                    <p class="list1"><b>International Shipping: </b>Our global network ensures your packages can reach virtually any corner of the world with ease.</p>
                </div>
                <div class="box">
                    <img src="img/About/check-mark.png" class="icon">
                    <p class="list1"><b>Express Services: </b>When time is of the essence, our express shipping options guarantee the fastest possible delivery.</p>
                </div>
                <div class="box">
                    <img src="img/About/check-mark.png" class="icon">
                    <p class="list1"><b>Economical Solutions: </b>We provide cost-effective shipping methods without compromising on service quality.</p>
                </div>
        </div>
    </div>
</div>

<div class="row-three">
    <div class="column right">
    <div class="list">
    <h2><b>What Makes Us Different?</b></h2>
            <div class="box-one">
                    <img src="img/About/right-arrow.png" class="icon">
                    <p class="list2"><b>Reliability: </b>We understand the importance of dependable delivery services. Our advanced tracking system and dedicated customer support ensure you're always in the loop about your shipment's status.</p>
                </div>
                <div class="box-one">
                    <img src="img/About/right-arrow.png" class="icon">
                    <p class="list2"><b>Affordability: </b>We leverage our partnerships with leading carriers to offer discounted rates, making shipping more affordable for you.</p>
                </div>
                <div class="box-one">
                    <img src="img/About/right-arrow.png" class="icon">
                    <p class="list2"><b>Customer-Centric: </b>Your satisfaction is our priority. From the moment you book a shipment to the final delivery, we strive to provide an exceptional customer experience.</p>
                </div>
                <div class="box-one">
                    <img src="img/About/right-arrow.png" class="icon">
                    <p class="list2"><b>Sustainability: </b>We are committed to reducing our environmental footprint. Our eco-friendly packaging options and efficient logistics practices help us contribute to a greener planet.</p>
                </div>
        </div>
    </div>

    <div class="column left">
    <img src="img/About/delivery-man-diving-motorcycle-moterbike-with-map-screen-tablet.png" class="img">
    </div>
</div>

<div class="row-four">

  <div class="column-left-new">
    <img src="img/About/Our Story (1).png" class="img1">
    </div>

    <div class="column-right-new">
    <h2><b>Our Story</b></h2>
    <p>Established back in 2018 in the great state of Texas, we noticed a need for a reliable and yet affordable shipping company which covers packages of all sizes. If it was as small as a letter or as big as a container we will ship it for you.
  <br><br>
Aside from affordability and reliability we wanted to ship to every country there is.  However, we settle for most countries there is a handful of countries we’re legally,  not allowed to ship to. Global shipping is what we do. Another important factor was user experience and how do we make it as fluid as possible. Out software makes it a breeze and clear and simple as possible.</p>
    </div>

</div>

<div class="row-five">
    <h2><b>Ready to send a package?</b></h2>
    <div class="btn">
<button type="submit">Yes! Get me a quote</button>
</div>
</div>

@endsection
@section('js_content', 'js/register_animate.js')
