@extends('fronts.layouts.app')
@section('front-title')
    {{ __('messages.web.medical') }}
@endsection

@php
    use App\Models\Blog;
    $blogs = Blog::latest()->get();
@endphp
@section('front-content')
    @php
        $styleCss = 'style';
    @endphp
    <style>
       .home-page {
  overflow-x: hidden;
}
    </style>
    <div class="home-page">
    <!-- start hero section -->
        <section class="hero-section p-t-100 p-b-100">
            <div class="container p-t-100">
                <div class="row align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6 text-lg-end text-center">
                        <div class="hero-content mt-5 mt-lg-0">
                            <p class="text-primary fs-5 fw-bold">{{ $sliders?->title ?? 'Smile with confidence' }}</p>
                            <!--<h1 class="mb-5" style="color:#219ebc">-->
                            <!--    {{ $sliders->short_description }}-->
                            <!--</h1>-->
                            <h1  style="color:#000">
                                mydent
                            </h1>
                            <h2 class="mb-5" style="color:grey">
                                Invisible path to smile
                            </h2>
                           
                                <a href="{{ route('medicalAppointment') }}"
                                   class="btn btn-primary" data-turbo="false">Book a Scan</a>
                           
                        </div>
                    </div>
                <div class="col-lg-6 text-lg-end text-center">
 <video autoplay muted loop playsinline
        style="width: 100%;
               max-height: 450px;
               object-fit: contain;
               display: block;
               border-radius: 20px;
               
               box-shadow: 0 8px 20px rgba(26, 24, 27, 0.4);">
        <source src="{{ asset('storage/vids/heroandchat.mp4') }}" type="video/mp4">
        Your browser does not support the video tag.
    </video>                </div>
            </div>
        </div>
    </section>
    <!-- end hero section -->

<!-- start dental issues section -->
<section class="p-t-50 p-b-100" style="background-color: #D8F1F8;">
    <div class="container position-relative">
                    <h2 class="text-center mb-5" style="padding-top: 30px;">Teeth alignment issues we can fix</h2>
            
                    {{-- Left Arrow --}}
                    {{-- Left Arrow --}}
        <button id="scrollLeft"
            class="btn position-absolute top-50 start-0 translate-middle-y"
            style="z-index: 10; 
               border-radius: 50%; 
               width: 50px; 
               height: 50px; 
               min-width: 50px;
               min-height: 50px;
               background: linear-gradient(135deg, #ffffff, #f8f9fa); 
               border: 2px solid #e9ecef; 
               color: #495057; 
               font-size: 20px; 
               font-weight: bold;
               padding: 0;
               line-height: 1;
               transition: all 0.3s ease; 
               box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
               cursor: pointer;"
            onmouseover="this.style.transform='translateY(-50%) scale(1.1)'; 
                     this.style.background='linear-gradient(135deg, #667eea, #764ba2)'; 
                     this.style.color='white'; 
                     this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.4)'; 
                     this.style.borderColor='#667eea';"
            onmouseout="this.style.transform='translateY(-50%) scale(1)'; 
                    this.style.background='linear-gradient(135deg, #ffffff, #f8f9fa)'; 
                    this.style.color='#495057'; 
                    this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'; 
                    this.style.borderColor='#e9ecef';"
            onmousedown="this.style.transform='translateY(-50%) scale(0.95)';"
            onmouseup="this.style.transform='translateY(-50%) scale(1.1)';">
            <i class="tio-chevron-left" style="display: inline-block; vertical-align: middle;">←</i>
        </button>

            {{-- Right Arrow --}}
        <button id="scrollRight"
                class="btn position-absolute top-50 end-0 translate-middle-y"
                style="z-index: 10; 
                   border-radius: 50%; 
                   width: 50px; 
                   height: 50px; 
                   min-width: 50px;
                   min-height: 50px;
                   background: linear-gradient(135deg, #ffffff, #f8f9fa); 
                   border: 2px solid #e9ecef; 
                   color: #495057; 
                   font-size: 20px; 
                   font-weight: bold;
                   padding: 0;
                   line-height: 1;
                   transition: all 0.3s ease; 
                   box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
                   cursor: pointer;"
                onmouseover="this.style.transform='translateY(-50%) scale(1.1)'; 
                     this.style.background='linear-gradient(135deg, #667eea, #764ba2)'; 
                     this.style.color='white'; 
                     this.style.boxShadow='0 6px 20px rgba(102, 126, 234, 0.4)'; 
                     this.style.borderColor='#667eea';"
                onmouseout="this.style.transform='translateY(-50%) scale(1)'; 
                    this.style.background='linear-gradient(135deg, #ffffff, #f8f9fa)'; 
                    this.style.color='#495057'; 
                    this.style.boxShadow='0 4px 15px rgba(0,0,0,0.1)'; 
                    this.style.borderColor='#e9ecef';"
                onmousedown="this.style.transform='translateY(-50%) scale(0.95)';"
                onmouseup="this.style.transform='translateY(-50%) scale(1.1)';">
                <i class="tio-chevron-right" style="display: inline-block; vertical-align: middle;">→</i>
        </button>

        {{-- Scrollable Card Row --}}
        <div id="scrollCards" class="d-flex gap-4 px-5"
             style="overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; ">
            <style>
                #scrollCards::-webkit-scrollbar {
                    display: none;
                }
            </style>

            @php
                $cards = [
                    ['slug' => 'teethgaps', 'video' => 'p1c1vi1.mp4', 'title' => 'Overbite', 'desc' => 'When the upper front teeth significantly overlap the lower front teeth while biting.'],
                    ['slug' => 'crookedteeth', 'video' => 'p1c1vi2.mp4', 'title' => 'Underbite', 'desc' => 'Occurs when the lower teeth extend beyond the upper teeth, affecting appearance and bite.'],
                    ['slug' => 'overbite', 'video' => 'p1c1vi4.mp4', 'title' => 'Crossbite', 'desc' => 'Upper and lower teeth do not align correctly, often causing wear or jaw problems.'],
                    ['slug' => 'open-bite', 'video' => 'p1c1vi5.mp4', 'title' => 'Open Bite', 'desc' => 'Front upper and lower teeth do not meet when the mouth is fully closed.'],
                    ['slug' => 'crossbite', 'video' => 'p1c1vi6.mp4', 'title' => 'Open Bite', 'desc' => 'Front upper and lower teeth do not meet when the mouth is fully closed.'],
                    ['slug' => 'underbite', 'video' => 'p1c1vi7.mp4', 'title' => 'Open Bite', 'desc' => 'Front upper and lower teeth do not meet when the mouth is fully closed.'],
                ];
            @endphp

            @foreach ($cards as $card)
                <a href="{{ route('issue.show', $card['slug']) }}" style="text-decoration: none; color: inherit;">
                    <div style="min-width: 300px; max-width: 300px; " >
                        <video class="w-100" style="height: 350px; object-fit: cover; border-radius:20px;" autoplay loop muted playsinline>
                            <source src="{{ asset('storage/vids/' . $card['video']) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </a>
            @endforeach

            {{-- Static Images --}}
            <div style="min-width: 300px; max-width: 300px;border-radius: 20px;" >
                <img src="{{ asset('storage/vids/i1.jpeg') }}" class="w-100" style="height: 350px; object-fit: cover;border-radius:20px;" alt="i1">
            </div>

            <div style="min-width: 300px; max-width: 300px;border-radius: 20px;">
                <img src="{{ asset('storage/vids/i2.jpeg') }}" class="w-100" style="height: 350px; object-fit: cover;border-radius:20px;" alt="i2">
            </div>
        </div>
    </div>
</section>



<!-- start clear aligners section -->
<section class="p-t-100 p-b-100" style="background-color: #ffffff;">
        <div class="container">
                    <h2 class="text-center mb-4">What are clear aligners and how do they work?</h2>
                    <p class="text-center mb-5">
                        Clear teeth aligners are transparent plastic trays that gradually straighten misaligned, crooked, and
                        crowded teeth for people of all ages (10 to 50). They are comfortable and a removable alternative to painful braces.
                    </p>
                    <div class="row align-items-center">
                        <!-- Left side bullet points -->
                        <div class="col-lg-6 mb-4 mb-lg-0">
                            <div class="d-flex flex-column gap-4">
                                <div class="text-center">
                                    <img src="https://cdn-icons-png.flaticon.com/512/13730/13730911.png" alt="Icon 1" class="mb-2" style="height: 60px;" />
                                    <h5 class="fw-bold">Removable and Convenient</h5>
                                    <p class="text-muted">You can remove them while eating or brushing.</p>
                                </div>
                                <div class="text-center">
                                    <img src="https://static.vecteezy.com/system/resources/previews/026/183/901/non_2x/hide-icon-eyes-hidden-symbol-invisible-icon-dead-invisible-sign-no-view-blind-icon-can-t-see-hidden-visibility-vector.jpg" alt="Icon 2" class="mb-2" style="height: 60px;" />
                                    <h5 class="fw-bold">Virtually Invisible</h5>
                                    <p class="text-muted">No metal brackets or wires, just a confident smile.</p>
                                </div>
                                <div class="text-center">
                                    <img src="https://media.istockphoto.com/id/1162803307/vector/configuration-customize-icon.jpg?s=612x612&w=0&k=20&c=zw3JYxa43A1juJ58aq58cM5IRLZJhD1swSZAkkQO2mU=" alt="Icon 3" class="mb-2" style="height: 60px;" />
                                    <h5 class="fw-bold">Custom-Made for You</h5>
                                    <p class="text-muted">Each aligner is crafted based on your dental needs.</p>
                                </div>
                            </div>
                        </div>

                                    <!-- Right side dynamic video -->
                        <div class="col-lg-6 text-center" >
                            <div class="ratio ratio-16x9">

                                    @php
                                        $section2Video = \App\Models\HomepageVideo::where('section', 'section-2')->latest()->first();
                                    @endphp
                
                                    @if($section2Video)
                                        <video class="w-100 h-100" style=" object-fit: cover;border-radius: 20px;
                                                box-shadow: 0 8px 20px rgba(26, 24, 27, 0.4);" autoplay loop muted playsinline>
                                            <source src="{{ getMediaUrl($section2Video->video_path, 'storage/vids/heroandchat.mp4') }}" type="video/mp4">

                                            Your browser does not support the video tag.
                                        </video>
                                    @else
                                        <p class="text-muted">Video coming soon...</p>
                                    @endif
                            </div>
                        </div>
                    </div>
        </div>
</section>

<!-- Why should you go for clear aligners section -->

<section class="p-t-100 p-b-100" style="background-color: #D8F1F8;">
    <div class="container">
        <h2 class="text-center mb-5">Why should you go for clear aligners?</h2>

        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle" style="border-radius: 15px; overflow: hidden;">
                <thead class="bg-white">
                    <tr>
                        <th class="fw-bold fs-5 text-start p-3">The facts</th>
                        <th style="background-color: #bfeaf6;">
                            <div class="text-danger fw-bold fs-5">Clear aligners</div>
                        </th>
                        <th class="fw-bold fs-5">Braces</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $data = [
                            ['text' => 'Conveniently removable during meals and brushing', 'clear' => true, 'braces' => false],
                            ['text' => 'Virtually invisible for a discreet look', 'clear' => true, 'braces' => false],
                            ['text' => 'No dietary limitations — enjoy your favorite foods', 'clear' => true, 'braces' => false],
                            ['text' => 'Simple to maintain oral hygiene — brush and floss with ease!', 'clear' => true, 'braces' => false],
                            ['text' => 'Requires frequent dental appointments', 'clear' => false, 'braces' => true],
                            ['text' => "Offers a more comfortable experience", 'clear' => true, 'braces' => false],
                            ['text' => 'Delivers quicker results (typically 6–8 months)', 'clear' => true, 'braces' => false],
                        ];
                    @endphp

                    @foreach($data as $row)
                        <tr style="background-color: {{ $loop->even ? '#f5fafe' : '#ffffff' }};">
                            <td class="text-start p-3">{{ $row['text'] }}</td>
                            <td style="background-color: #e2f8ff;">
                                @if($row['clear'])
                                    <span class="text-success fs-4">&#10003;</span> <!-- Check -->
                                @else
                                    <span class="text-danger fs-4">&#10007;</span> <!-- Cross -->
                                @endif
                            </td>
                            <td>
                                @if($row['braces'])
                                    <span class="text-success fs-4">&#10003;</span>
                                @else
                                    <span class="text-danger fs-4">&#10007;</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- start download app section -->
<section class="app-download-section p-t-100 p-b-100 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="app-content">
                        <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.mobile_app')}}</h5>
                        <h2 class="pb-3">{{__('messages.web.download_our_mobile_app_for_easier_access')}}</h2>
                        <p class="paragraph mb-4">
                            <!--{{__('messages.web.get_access_to_your_medical_records_book_appointments_and_chat_with_your_doctor_directly_from_your_smartphone')}}-->
                            Instant video consults, expert planning, AI-driven smile design, and premium Mydent aligner
care - everything in one place
                        </p>
                        <div class="app-buttons">
                        <a href="#" class="me-3 mb-3"
                           style="display: inline-block; background-color: #000; color: #fff; border: 2px solid #000; padding: 10px 20px; border-radius: 5px; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='#fff'; this.style.color='#000';"
                           onmouseout="this.style.backgroundColor='#000'; this.style.color='#fff';"
                           onmousedown="this.style.backgroundColor='#fff'; this.style.color='#000';"
                           onmouseup="this.style.backgroundColor='#000'; this.style.color='#fff';">
                           <i class="fa-brands fa-apple me-2"></i> App Store
                        </a>

                        <a href="#" class="mb-3"
                           style="display: inline-block; background-color: #000; color: #fff; border: 2px solid #000; padding: 10px 20px; border-radius: 5px; text-decoration: none; transition: all 0.3s ease;"
                           onmouseover="this.style.backgroundColor='#fff'; this.style.color='#000';"
                           onmouseout="this.style.backgroundColor='#000'; this.style.color='#fff';"
                           onmousedown="this.style.backgroundColor='#fff'; this.style.color='#000';"
                           onmouseup="this.style.backgroundColor='#000'; this.style.color='#fff';">
                           <i class="fa-brands fa-google-play me-2"></i> Google Play
                        </a>

                    </div>
                        <div class="app-features mt-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-check-circle text-primary me-2"></i>
                                        <span>{{__('messages.web.easy_appointment_booking')}}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-check-circle text-primary me-2"></i>
                                        <!--<span>{{__('messages.web.medical_records_access')}}</span>-->
                                         <span>Mydent AI</span>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-check-circle text-primary me-2"></i>
                                        <!--<span>{{__('messages.web.medicine_reminders')}}</span>-->
                                                                                <span>Instant Vedio Consultation</span>

                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-check-circle text-primary me-2"></i>
                                        <!--<span>{{__('messages.web.doctor_chat')}}</span>-->
                                        <span>Mydent Products</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
                <div class="col-lg-6 text-center">
                    <div class="app-image">
                        <img src="{{ asset('storage/imgs/app.jpeg') }}" alt="Mobile App" class="img-fluid" style="max-height: 500px;">
                    </div>
                </div>
        </div>
    </div>
</section>
    <!-- end download app section -->


<!-- start dental issues section -->
@php
    $cards = \App\Models\HomepageVideo::where('section', 'characteristics')->get();
@endphp

@if($cards->count())
<section class="p-t-50 p-b-100" style="background-color: #D8F1F8;">
    <div class="container text-center">
        <h3 class="mb-5" style="padding-top: 50px;">Find out how mydent aligners help you achieve the smile you deserve.</h3>
        <div class="position-relative">
            <div class="d-flex justify-content-center overflow-auto gap-4 scroll-container"
                 style="scroll-behavior: smooth;" id="scrollCards">
                 
                @foreach($cards as $card)
                    <div class="card border shadow-sm text-center p-3"
                         style="min-width: 180px; max-width: 180px; height: 250px; flex: 0 0 auto; border-radius: 10px; display: flex; flex-direction: column; justify-content: space-between;">
                        <img src="{{ getMediaUrl($card->image_path, 'storage/imgs/logo.png') }}" alt="{{ $card->title }}"

                             class="img-fluid"
                             style="height: 200px; max-width: 100%; object-fit: contain;" />
                        <h5 class="fw-bold mt-3">{{ $card->title }}</h5>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
@endif

<section class="p-t-50 p-b-100 mt-5" style="background-color: #F9FAFB;">
    <div class="container">
        <h2 class="mb-5 text-center">Your Smile Makeover Journey with Clear Aligners</h2>
        <div class="row justify-content-center">
            @php
                $section1Videos = \App\Models\HomepageVideo::where('section', 'section-1')->orderBy('order')->get();
                $validSection1Vids = ['p1c1vi1.mp4', 'p1c1vi2.mp4', 'p1c1vi4.mp4', 'p1c1vi5.mp4', 'p1c1vi6.mp4', 'p1c1vi7.mp4'];
            @endphp

            @foreach($section1Videos as $index => $video)
                <div class="col-md-4 mb-4">
                    <div class="card border rounded p-3 shadow-sm text-center" style="height: 320px;">
                        <video class="w-100" style="height: 180px; object-fit: cover;" autoplay loop muted playsinline>
                            <source src="{{ getMediaUrl($video->video_path, 'storage/vids/' . $validSection1Vids[$index % count($validSection1Vids)]) }}" type="video/mp4">


                            Your browser does not support the video tag.
                        </video>
                        <div class="mt-3">
                                                      <span class="badge rounded-circle" style="background-color: #D8F1F8; padding: 10px 15px; font-size: 18px;">{{ $index + 1 }}</span>

                            <h5 class="fw-bold mt-2">{{ $video->title }}</h5>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


<section style="background: #D8F1F8; padding: 40px 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
  <div class="aligner-container" style="display: flex; gap: 40px; flex-wrap: wrap;">

    <!-- Left Side -->
    <div class="aligner-text" style="flex: 1; min-width: 300px; margin-left: 20px;">
      <h2 style="font-size: 36px; margin-bottom: 20px; color: #333;">Clear aligners at Transparent Prices</h2>
      <p style="font-size: 15px; color: #555; line-height: 1.6;">
        At mydent, we combine quality, comfort, and affordability. Our clear aligners are carefully designed using the latest 3D printing technology, crafted in our own in-house labs right here in India. Because we control the entire process, we're able to offer world-class aligners at prices that won’t break the bank.
      </p>

      <h2 style="font-size: 36px; margin-top: 40px; margin-bottom: 20px; color: #333;">What Affects the Cost?</h2>
      <p style="font-size: 15px; color: #555; line-height: 1.6;">
        The cost of your mydent aligners depends on your treatment needs. Simpler corrections require fewer aligners and shorter treatment time, while more complex cases may need additional aligners and closer monitoring by our orthodontic experts.
        <br><br>
        Whether you're looking for a more discreet alternative to braces or a treatment plan that fits your lifestyle, mydent Aligners offer a perfect balance of precision, convenience, and value.
      </p>
    </div>

    <!-- Right Side -->
    <div class="aligner-right" style="flex: 1; min-width: 300px; display: flex; flex-direction: column; align-items: center;">
      <img src="https://www.future-doctor.de/wp-content/uploads/2024/08/shutterstock_2480850611.jpg" alt="mydent Aligners" style="width: 100%; max-width: 400px; height: auto; border-radius: 10px; margin-bottom: 30px; object-fit: cover;">

      <div class="aligner-cards" style="display: flex; width: 100%; max-width: 400px; flex-wrap: wrap;">
        <!-- Card 1 -->
        <div class="card" style="flex: 1; background-color: #f0f0f0; padding: 20px; border-radius: 8px 0 0 8px; text-align: center; transform: translateY(20px); min-width: 120px;">
          <h3 style="margin: 0; font-size: 18px; color: #333;">Traditional braces</h3>
          <p style="margin: 5px 0 0; font-size: 16px; color: #666;">₹35,000 to ₹90,000</p>
        </div>

        <!-- Card 2 -->
        <div class="card" style="flex: 1; background-color: #f0f0f0; padding: 20px; text-align: center; transform: translateY(-10px); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); min-width: 120px;">
          <h3 style="margin: 0; font-size: 18px; color: #333;">mydent aligners</h3>
          <p style="margin: 5px 0 0; font-size: 16px; color: #666;">₹52,999 to ₹1,30,000</p>
        </div>

        <!-- Card 3 -->
        <div class="card" style="flex: 1; background-color: #f0f0f0; padding: 20px; border-radius: 0 8px 8px 0; text-align: center; transform: translateY(20px); min-width: 120px;">
          <h3 style="margin: 0; font-size: 18px; color: #333;">Other aligners</h3>
          <p style="margin: 5px 0 0; font-size: 16px; color: #666;">₹1,50,000 to ₹4,00,000</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Responsive styles -->
  <style>
    @media (max-width: 768px) {
      .aligner-container {
        flex-direction: column;
        gap: 30px;
      }

      .aligner-cards {
        flex-direction: column;
      }

      .aligner-cards .card {
        border-radius: 8px !important;
        transform: none !important;
        margin-bottom: 15px;
      }
    }
  </style>
</section>




<section style="background: #fff; padding: 40px 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); font-family: Arial, sans-serif;">
  <div class="container" style="max-width: 1200px; margin: 0 auto; position: relative;">
    <h2 style="color: #2c3e50; margin-bottom: 30px; font-size: 36px; text-align: center;">"From Uneven to Unbelievable – From Crooked to Confident"</h2>

    <!-- Arrows -->
    <button class="scroll-btn left" onclick="scrollBlog(-1)">&#10094;</button>
    <button class="scroll-btn right" onclick="scrollBlog(1)">&#10095;</button>

    <!-- Cards container -->
    <div id="blog-scroll" class="blog-scroll">
      @foreach ($blogs as $blog)
      <div class="blog-card">
        <div style="width: 100%; height: 200px; position: relative;">
          <img src="{{ getMediaUrl($blog->thumbnail, 'assets/image/mydent-logo.png') }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='{{ asset('assets/image/mydent-logo.png') }}'">



          <span style="position: absolute; bottom: 10px; left: 10px; background: rgba(0, 0, 0, 0.7); color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px;">Before</span>
          <span style="position: absolute; bottom: 10px; right: 10px; background: rgba(0, 0, 0, 0.7); color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px;">After</span>
        </div>
        <div style="padding: 20px;">
          <div style="color: #5b87b4; font-size: 20px; font-weight: bold; margin-bottom: 10px; display: flex; justify-content: space-between;">
            {{ strtoupper($blog->issue_type) }}
            <span style="background-color: #d8f1f8; color: #2980b9; padding: 3px 12px; border-radius: 20px; font-size: 14px;">{{ $blog->issue_level }}</span>
          </div>
          <div style="display: flex; align-items: center; margin-top: 15px; color: #7f8c8d; font-size: 15px;">
            &#x1F551; {{ $blog->treatment_time }} • {{ $blog->aligner_count }} Aligners
          </div>
          <a href="{{ route('blogs.show', $blog->id) }}" style="display: block; text-align: center; border: 1px solid #e0e0e0; border-radius: 25px; padding: 12px; margin-top: 20px; text-decoration: none; color: #2c3e50; font-weight: bold;">
            {{ explode(' ', $blog->title)[0] }}'s story &#8594;
          </a>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <!-- CSS Styles -->
  <style>
   .blog-scroll {
  display: flex;
  gap: 25px;
  overflow-x: auto; /* use 'auto' for scroll on touch devices */
  scroll-behavior: smooth;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  padding-bottom: 10px;
}

.blog-card {
  flex: 0 0 auto;
  min-width: 300px;
  max-width: 320px;
  background: white;
  border-radius: 30px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border: 1px solid #e0e0e0;
  transition: all 0.3s ease;
  scroll-snap-align: start;
}

    .blog-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .scroll-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(255, 255, 255, 0.8);
      border: none;
      font-size: 24px;
      padding: 10px 15px;
      border-radius: 50%;
      cursor: pointer;
      z-index: 10;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .scroll-btn.left {
      left: 0;
    }

    .scroll-btn.right {
      right: 0;
    }

    @media (max-width: 768px) {
      .blog-card {
        flex: 0 0 85%;
      }

      .scroll-btn {
        display: block;
      }

      .container h2 {
        font-size: 24px;
      }
    }
  </style>

  <!-- JS Script -->
  <script>
   function scrollBlog(direction) {
  const container = document.getElementById('blog-scroll');
  const cardWidth = container.querySelector('.blog-card')?.offsetWidth || 300;
  const gap = 25;
  const scrollAmount = cardWidth + gap;

  container.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });
}
  </script>
</section>




@php
use App\Models\Product;
$categories = Product::select('category', 'category_thumbnail')
    ->whereNotNull('category')
    ->distinct()
    ->get();
@endphp

<section style="padding: 40px; background-color: #f9f9f9; font-family: Arial, sans-serif;">
  <div class="container" style="max-width: 1200px; margin: 0 auto; position: relative;">
    <h2 style="font-size: 28px; color: #2c3e50; margin-bottom: 30px; text-align: center;">Shop by Category</h2>

    <!-- Arrows -->
    <button class="scroll-btn left" onclick="scrollCategory(-1)">&#10094;</button>
    <button class="scroll-btn right" onclick="scrollCategory(1)">&#10095;</button>

    <!-- Scrollable container -->
    <div id="category-scroll" class="category-scroll">
     @foreach ($categories as $category)
  <div class="category-card">
    <a href="https://mydent.in/ecom" style="text-decoration: none;">
      <img src="{{ asset($category->category_thumbnail) }}" alt="{{ $category->category }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
      <h3 style="margin-top: 15px; font-size: 18px; color: #34495e;">
        {{ ucfirst($category->category) }}
      </h3>
    </a>
  </div>
@endforeach

    </div>
  </div>

  <!-- Styles -->
  <style>
    .category-scroll {
  display: flex;
  gap: 20px;
  overflow-x: auto;
  scroll-behavior: smooth;
  scroll-snap-type: x mandatory;
  -webkit-overflow-scrolling: touch;
  padding-bottom: 10px;
}

.category-card {
  flex: 0 0 auto;
  width: 220px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  text-align: center;
  padding: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  scroll-snap-align: center;
}


    .category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .scroll-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(255, 255, 255, 0.8);
      border: none;
      font-size: 24px;
      padding: 10px 15px;
      border-radius: 50%;
      cursor: pointer;
      z-index: 10;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .scroll-btn.left {
      left: 0;
    }

    .scroll-btn.right {
      right: 0;
    }

    @media (max-width: 768px) {
      .scroll-btn {
        display: block;
      }

      .category-card {
        flex: 0 0 80%;
      }

      .container h2 {
        font-size: 22px;
      }
    }
  </style>

  <!-- JS -->
  <script>
    function scrollCategory(direction) {
  const container = document.getElementById('category-scroll');
  const cardWidth = container.querySelector('.category-card')?.offsetWidth || 220;
  const gap = 20;
  const scrollAmount = cardWidth + gap;

  container.scrollBy({
    left: direction * scrollAmount,
    behavior: 'smooth'
  });
}

  </script>
</section>

@php
    use App\Models\Article;
    $articles = Article::latest()->take(6)->get();
@endphp
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center fw-bold mb-4">Our Latest Blogs</h2>
        <div class="row">
            @forelse ($articles->take(3) as $article)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if($article->thumbnail)
                            <img src="{{ asset($article->thumbnail) }}" alt="{{ $article->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary mt-auto">Read More</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No articles available at the moment.</p>
                </div>
            @endforelse
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('articles.index') }}" class="btn btn-primary">View All</a>
        </div>
    </div>
</section>  





    <!-- start appointment section -->
    <section class="appointmnet-section p-t-100 p-b-100">
        <div class="container">
            <div class="bg-primary border-bmr-100 appointmnet-section__inner-block">
                <div class="row align-items-center">
                    <div class="col-lg-6 align-self-end d-none d-lg-block">
                        <img src="{{ asset('assets/front/images/appointment.png') }}" alt="Book An Appointment" class="img-fluid object-image-cover" />
                    </div>
                    <div class="col-lg-6 contact-form">
                        <h6 class="pb-2 text-white text-center mb-4 pb-3">{{__('messages.web.book_an_appointment')}}</h6>
                        <form action="{{ route('front.home.appointment.book')}}" method="POST" turbo:load>
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block">
                                        <input name="first_name" type="text" class="form-control required form-control-transparent"
                                               placeholder="{{ __('messages.doctor.first_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block">
                                        <input type="text" name="last_name" class="form-control required form-control-transparent"
                                               placeholder="{{ __('messages.doctor.last_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="contact-form__input-block">
                                        <input type="email" name="email" class="form-control required form-control-transparent"
                                               placeholder="{{ __('messages.web.email') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block">
                                        {{ Form::select('doctor_id',$doctors, null,['class' => 'form-select form-control-transparent required', 'data-control'=>"select2", 'required','placeholder' => __('messages.common.select_doctor'), 'id' => 'frontDoctorId']) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="contact-form__input-block position-relative">
                                        <input type="text" name="date" id="frontAppointmentDate"
                                               class="form-control form-control-transparent appointment-calendar" placeholder="{{ __('messages.doctor.select_date') }}"
                                               autocomplete="true" required readonly>
                                        <span
                                                class="position-absolute d-flex align-items-center top-0 bottom-0 end-0 me-4">
                                                <i class="fa-solid fa-calendar-days text-white post"></i>
                                            </span>
                                    </div>
                                </div>
                                <div class="col-lg-12 text-center mt-4">
                                    <p style="color:#fff">Book your 3D Scan or impression, one of our doctor will connect with you via video call to discuss and guide you through your personalized treatment plan.</p>
                                    <button type="submit" class="btn btn-light">{{__('messages.web.appointment_now')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end appointment section -->

    <!-- start services section -->
    <section class="services-section overflow-hidden p-b-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-4">
                    <div class="text-xxl-start text-center mb-lg-0 mb-5">
                        <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.services')}}</h5>
                        <h2 class="pb-2">{{__('messages.web.we_cover_a_big___')}}</h2>
                        <p class="paragraph pb-3">
                            {{__('messages.web.we_provide_the_special_tips___')}}
                        </p>
                        <a href="{{ route('medicalServices') }}"
                           class="btn btn-primary">{{__('messages.web.all_services')}}</a>
                    </div>
                </div>
                <div class="col-xxl-8 after-rectangle-shape position-relative right-shape mt-lg-5 mt-xxl-0">
                    <div class="services-carousel z-index-1">
                            @foreach($frontMedicalServices as $frontMedicalService)
                        <div class="services-block">
                            <div class="row position-relative z-index-1">
                                @if(isset($frontMedicalService[0]))
                                <div class="col-md-6 text-center services-innner-block">
                                    <div class="icon-box mx-auto d-flex align-items-center justify-content-center">
                                        <img src="{{ $frontMedicalService[0]['icon'] }}" alt="Emergency" class="img-fluid object-image-cover" />
                                    </div>
                                    <h4 class="text-primary">{{ $frontMedicalService[0]['name'] }}</h4>
                                    <p class="paragraph pb-3">
                                        {{ $frontMedicalService[0]['short_description'] }}
                                    </p>
                                </div>
                                @endif
                                    @if(isset($frontMedicalService[1]))
                                <div class="col-md-6 text-center services-innner-block">
                                    <div class="icon-box mx-auto d-flex align-items-center justify-content-center">
                                        <img src="{{ $frontMedicalService[1]['icon'] }}" alt="Emergency" class="img-fluid object-image-cover" />
                                    </div>
                                    <h4 class="text-primary">{{ $frontMedicalService[1]['name']}}</h4>
                                    <p class="paragraph pb-3">
                                        {{ $frontMedicalService[1]['short_description'] }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end services section -->

    <!-- start statistics section -->
    <section class="statistics-section p-t-100 p-b-100 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.our_achievements')}}</h5>
                <h2 class="pb-2">{{__('messages.web.we_are_proud_of_our_numbers')}}</h2>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stats-box text-center p-4 bg-white shadow-sm rounded-3 h-100">
                        <div class="stats-icon mb-3">
                            <i class="fa-solid fa-user-md fa-3x text-primary"></i>
                        </div>
                        <h2 class="counter text-primary fw-bold">200+</h2>
                        <p class="mb-0">{{__('messages.web.qualified_doctors')}}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stats-box text-center p-4 bg-white shadow-sm rounded-3 h-100">
                        <div class="stats-icon mb-3">
                            <i class="fa-solid fa-users fa-3x text-primary"></i>
                        </div>
                        <h2 class="counter text-primary fw-bold">100000+</h2>
                        <p class="mb-0">{{__('messages.web.happy_patients')}}</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stats-box text-center p-4 bg-white shadow-sm rounded-3 h-100">
                        <div class="stats-icon mb-3">
                            <i class="fa-solid fa-hospital fa-3x text-primary"></i>
                        </div>
                        <h2 class="counter text-primary fw-bold">100+</h2>
                        <p class="mb-0">Clinics</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="stats-box text-center p-4 bg-white shadow-sm rounded-3 h-100">
                        <div class="stats-icon mb-3">
<i class="fa-solid fa-city fa-3x text-primary"></i>
                        </div>
                        <h2 class="counter text-primary fw-bold">50+</h2>
                        <p class="mb-0">Cities</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end statistics section -->

    <!-- start doctor profiles section -->
   <!-- start doctor profiles section -->
<section class="doctors-section p-t-100 p-b-100">
    <div class="container">
        <div class="text-center mb-5">
            <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.our_doctors')}}</h5>
            <h2 class="pb-2">{{__('messages.web.meet_our_specialists')}}</h2>
            <p class="paragraph mx-auto" style="max-width: 700px;">
                {{__('messages.web.our_team_of_medical_specialists_is_here_to_provide_you_with_the_best_care_available')}}
            </p>
        </div>
        <div class="row">
            @php use App\Models\Doctor; @endphp

            @php 

                // Get doctors from the same variable that populates your dropdown
                $featuredDoctors = Doctor::with('user')->latest()->take(10)->get();

            @endphp
            
            @foreach($featuredDoctors as $doctor)
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="doctor-card text-center bg-white shadow-sm rounded-3 h-100">
                        <div class="doctor-img mb-3">
                            <img src="{{ $doctor->user->profile_image ?? asset('assets/front/images/doctor-placeholder.png') }}"
                                 alt="{{ $doctor->user->full_name ?? 'Doctor' }}"
                                 class="img-fluid rounded-circle"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                        <h4 class="doctor-name">{{ $doctor->user->full_name }}</h4>
                        <p class="doctor-speciality text-primary">{{ __('messages.web.specialist') }}</p>
                    </div>
                </div>

            @endforeach

        </div>
        
    </div>
</section>
<!-- end doctor profiles section -->

    
    <!-- start FAQ section -->
    <section class="faq-section p-t-100 p-b-100">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="faq-image text-center">
                        <img src="{{ asset('storage/imgs/faqs.jpeg') }}" alt="FAQ" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="faq-content">
                        <h5 class="text-primary top-heading fs-6 mb-3">{{__('messages.web.faq')}}</h5>
                        <h2 class="pb-4">{{__('messages.web.frequently_asked_questions')}}</h2>
                        
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <!--{{__('messages.web.how_do_i_schedule_an_appointment')}}?-->
                                        What is mydent and how does it work?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.you_can_schedule_an_appointment_by_calling_our_office_or_using_our_online_booking_system')}}-->
                                        mydent is a leading clear aligner brand offering invisible, removable aligners to straighten your teeth comfortably. We provide free scans, expert treatment planning, and doorstep delivery of aligners, all monitored by certified orthodontists.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <!--{{__('messages.web.what_insurance_plans_do_you_accept')}}?-->
                                        How do I start my treatment with mydent?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.we_accept_most_major_insurance_plans_please_contact_our_office_to_verify_your_specific_coverage')}}-->
                                        Your journey begins with a 3D dental scan at a nearby clinic or from the comfort of your home. Our orthodontic team evaluates your case, creates a personalized plan, and once approved, we deliver your aligners to your doorstep.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item mb-3 border">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <!--{{__('messages.web.what_should_i_bring_to_my_first_appointment')}}?-->
                                        Are mydent aligners suitable for all types of dental problems?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.please_bring_your_insurance_card_id_medical_history_and_any_current_medications_you_are_taking')}}-->
                                        Yes! mydent specializes in both mild and complex cases. With hybrid mechanics (mini-implants or braces support when needed), we ensure almost every smile can be treated effectively
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item border">
                                <h2 class="accordion-header" id="headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <!--{{__('messages.web.do_you_offer_telehealth_services')}}?-->
                                        What is the cost of clear aligners?
                                    </button>
                                </h2>
                                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <!--{{__('messages.web.yes_we_offer_telehealth_services_for_certain_types_of_appointments_please_call_our_office_to_learn_more')}}-->
                                        Aligner costs depend on the complexity of your dental issue and the length of treatment. Pricing typically varies for mild, moderate, and severe cases. For accurate pricing, a scan and consultation are recommended.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end FAQ section -->
    
    <!-- start CTA section -->
    <section class="cta-section p-t-100 p-b-100 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <h2 class="mb-3">
                        <!--{{__('messages.web.ready_to_get_the_best_medical_care_for_you_and_your_family')}}-->
                        Your journey to healthy, beautiful smiles starts here with mydent!
                    </h2>
                    <p class="mb-0 lead">{{__('messages.web.our_specialists_are_ready_to_provide_you_with_personalized_care')}}</p>
                </div>
                <div class="col-lg-4 text-lg-end text-center">
                    <a href="{{ route('medicalContact') }}" class="btn btn-light btn-lg">{{__('messages.web.contact_us_now')}}</a>
                </div>
            </div>
        </div>
    </section>

   <!-- Floating Action Buttons -->
<div class="floating-buttons">
    <!-- WhatsApp Button -->
    <a href="https://wa.me/919381590963" target="_blank" class="floating-btn whatsapp-btn" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    
    <!-- Call Button -->
    <a href="tel:+919381590963" class="floating-btn call-btn" title="Call Now">
        <i class="fas fa-phone"></i>
    </a>
    
    <!-- Book Appointment Button -->
    <a href="{{ route('medicalAppointment') }}" class="floating-btn book-btn" title="Book Appointment">
        <i class="fas fa-calendar-plus"></i>
    </a>
</div>

<style>
    .floating-buttons {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: white;
        font-size: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .floating-btn:hover {
        transform: scale(1.1);
        color: white;
        text-decoration: none;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
    }

    .floating-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .floating-btn:hover::before {
        left: 100%;
    }

    .whatsapp-btn {
        background: linear-gradient(135deg, #25D366, #128C7E);
        animation: pulse-whatsapp 2s infinite;
    }

    .call-btn {
        background: linear-gradient(135deg, #007bff, #0056b3);
        animation: pulse-call 2s infinite 0.5s;
    }

    .book-btn {
        background: linear-gradient(135deg, #28a745, #1e7e34);
        animation: pulse-book 2s infinite 1s;
    }

    @keyframes pulse-whatsapp {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(37, 211, 102, 0.3); }
    }

    @keyframes pulse-call {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(0, 123, 255, 0.3); }
    }

    @keyframes pulse-book {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(40, 167, 69, 0.3); }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .floating-buttons {
            bottom: 20px;
            right: 20px;
            gap: 12px;
        }

        .floating-btn {
            width: 55px;
            height: 55px;
            font-size: 22px;
        }
    }

    /* Tooltip styles */
    .floating-btn[title]:hover::after {
        content: attr(title);
        position: absolute;
        right: 70px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 1;
        pointer-events: none;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Additional animations */
    .floating-btn:active {
        transform: scale(0.95);
    }

    /* Smooth entrance animation */
    .floating-buttons {
        animation: slideInRight 0.8s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Staggered animation for individual buttons */
    .whatsapp-btn {
        animation-delay: 0.2s;
        opacity: 0;
        animation: slideInRight 0.6s ease-out 0.2s forwards, pulse-whatsapp 2s infinite 2s;
    }

    .call-btn {
        animation-delay: 0.4s;
        opacity: 0;
        animation: slideInRight 0.6s ease-out 0.4s forwards, pulse-call 2s infinite 2.5s;
    }

    .book-btn {
        animation-delay: 0.6s;
        opacity: 0;
        animation: slideInRight 0.6s ease-out 0.6s forwards, pulse-book 2s infinite 3s;
    }
</style> 
    <!-- end newsletter section -->
</div>
    
@endsection

<!-- Adding required JavaScript for new sections -->
@push('scripts')



<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollContainer = document.getElementById('scrollCards');

        document.getElementById('scrollLeft').addEventListener('click', function () {
            scrollContainer.scrollBy({ left: -320, behavior: 'smooth' });
        });

        document.getElementById('scrollRight').addEventListener('click', function () {
            scrollContainer.scrollBy({ left: 320, behavior: 'smooth' });
        });
    });
</script>

<script>
function scrollRight() {
        const container = document.getElementById('scrollCards');
        container.scrollBy({ left: 300, behavior: 'smooth' });
    }


    // Counter animation for statistics section
    document.addEventListener("DOMContentLoaded", function() {
        const counters = document.querySelectorAll('.counter');
        
        const countUp = () => {
            counters.forEach(counter => {
                const target = parseInt(counter.innerText);
                const count = +counter.innerText.replace(/\+/g, '');
                const speed = 200;
                const inc = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(countUp, 1);
                } else {
                    counter.innerText = target + '+';
                }
            });
        };
        
        // Trigger counter when page loads
        countUp();
        
        // Initialize accordion
        const accordionButtons = document.querySelectorAll('.accordion-button');
        accordionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const target = this.getAttribute('data-bs-target');
                const collapse = document.querySelector(target);
                
                if (collapse.classList.contains('show')) {
                    collapse.classList.remove('show');
                    this.classList.add('collapsed');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    // Close all other accordion items
                    document.querySelectorAll('.accordion-collapse').forEach(item => {
                        item.classList.remove('show');
                    });
                    document.querySelectorAll('.accordion-button').forEach(btn => {
                        btn.classList.add('collapsed');
                        btn.setAttribute('aria-expanded', 'false');
                    });
                    
                    // Open clicked item
                    collapse.classList.add('show');
                    this.classList.remove('collapsed');
                    this.setAttribute('aria-expanded', 'true');
                }
            });
        });
    });



    window.addEventListener('scroll', function () {
        var banner = document.getElementById('sticky-banner');
        if (window.scrollY > 300) {
            banner.style.transform = 'translateY(0)';
        } else {
            banner.style.transform = 'translateY(100%)';
        }
    });
</script>
@endpush