@extends('layouts.layout')
@section('title', 'Eamon Express | Home')
@section('css_content', 'css/delivery.css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">


@section('content')
<div class="row-one">
    
    <div class="column left">
      <img src="img/Ship.png" class="img">
    </div>

    <div class="column right">
      <h2 class="title"><b>Navigating the Seas of Shipping: Insights and Tips</b></h2>
      <p class="info">Eamon Express offers fast and reliable delivery services for your packages and parcels. With our efficient logistics network and dedicated team, we ensure timely deliveries to your doorstep. Experience convenience and peace of mind with Eamon Express delivery services.
      </p>
      <div class="btn">
                <button type="submit" formaction="#" class="btn-one">Sign Up</button>
                    <button type="submit" formaction="#" class="btn-two" >Ship Now</button>
      
</div>
    </div>
  </div>

<ul class="cards-one">
  <li>
    <a href="" class="card">
      <img src="img/express.png" class="card__image" alt="" />
      <div class="card__overlay">
        <div class="card__header">
          <svg class="card__arc" xmlns="http://www.w3.org/2000/svg"><path /></svg>                     
          
          <div class="card__header-text">
            <h3 class="card__title">1. Express Delivery Options:</h3>            
          
          </div>
        </div>
        <p class="card__description">Rest assured knowing that your packages are handled with care and attention to detail throughout the delivery process, ensuring they arrive safely.</p>
      </div>
    </a>      
  </li>
  <li>
    <a href="" class="card">
      <img src="img/secure-handling.png" class="card__image" alt="" />
      <div class="card__overlay">        
        <div class="card__header">
          <svg class="card__arc" xmlns="http://www.w3.org/2000/svg"><path /></svg>
          <div class="card__header-text">
            <h3 class="card__title">2. Secure Handling:</h3>
          </div>
        </div>
        <p class="card__description">Rest assured knowing that your packages are handled with care and attention to detail throughout the delivery process, ensuring they arrive safely.</p>
      </div>
    </a>
  </li>
  <li>
    <a href="" class="card">
      <img src="img/track.png" class="card__image" alt="" />
      <div class="card__overlay">
        <div class="card__header">
          <svg class="card__arc" xmlns="http://www.w3.org/2000/svg"><path /></svg>
          <div class="card__header-text">
            <h3 class="card__title">3. Real-Time Tracking:</h3>
          </div>
        </div>
        <p class="card__description">Track your deliveries in real-time using our online tracking system, allowing you to monitor the progress of your packages every step of the way.</p>
      </div>
    </a>
  </li>
</ul>



<ul class="cards-two">
  <li>
    <a href="" class="card">
      <img src="img/Shipping 5.png" class="card__image-two" alt="" />
      <div class="card__overlay-two">
        <div class="card__header-two">
          <svg class="card__arc-two" xmlns="http://www.w3.org/2000/svg"><path /></svg>                     
          <div class="card__header-text">
            <h3 class="card__title-two">4. Nationwide Coverage:</h3>
          </div>
        </div>
        <p class="card__description-two">We offer nationwide coverage, reaching even the most remote areas, so you can trust us to deliver your packages wherever they need to go.</p>
      </div>
    </a>      
  </li>
  <li>
    <a href="" class="card">
      <img src="img/Dropshipping 3.png" class="card__image-two" alt="" />
      <div class="card__overlay-two">        
        <div class="card__header-two">
          <svg class="card__arc-two" xmlns="http://www.w3.org/2000/svg"><path /></svg>                 
          <div class="card__header-text">
            <h3 class="card__title-two">5. Flexible Pickup and Drop-off Options:</h3>
          </div>
        </div>
        <p class="card__description-two">Choose from convenient pickup and drop-off locations that suit your schedule and preferences, making it easy to send and receive packages.</p>
      </div>
    </a>
  </li>
  <li>
    <a href="" class="card">
      <img src="img/pickup.png" class="card__image-two" alt="" />
      <div class="card__overlay-two">
        <div class="card__header-two">
          <svg class="card__arc-two" xmlns="http://www.w3.org/2000/svg"><path /></svg>                     
          
          <div class="card__header-text">
            <h3 class="card__title-two">6. Competitive Rates:</h3>
          </div>
        </div>
        <p class="card__description-two">Enjoy competitive rates for our delivery services, providing excellent value for money without compromising on quality and reliability.</p>
      </div>
    </a>
  </li>


</ul>


<ul class="cards-three">
  <li>
    <a href="" class="card">
      <img src="application/views/assets/Customer Service 3.png" class="card__image-three" alt="" />
      <div class="card__overlay-three">
        <div class="card__header-three">
          <svg class="card__arc-three" xmlns="http://www.w3.org/2000/svg"><path /></svg>                     
          <div class="card__header-text">
            <h3 class="card__title-three">7. Responsive Customer Support:</h3>
          </div>
        </div>
        <p class="card__description-three">Our dedicated customer support team is available to assist you with any inquiries, concerns, or special requests regarding your deliveries.</p>
      </div>
    </a>      
  </li>

  </ul>
@endsection