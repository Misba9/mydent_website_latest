<section class="p-t-80 p-b-80 mt-5" style="background-color: #F9FAFB;">
    <div class="container">
        <h2 class="mb-5 text-center fw-bold text-dark" style="font-size: 32px; letter-spacing: -0.5px;">Your Smile Makeover Journey with Clear Aligners</h2>
        <div class="row justify-content-center g-4">
            @php
                $validSection1Vids = ['p1v1.mp4', 'p1v3.mp4', 'p1v4.mp4'];
            @endphp

            @foreach($section1Videos as $index => $video)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card border-0 shadow-sm text-center h-100" 
                         style="border-radius: 20px; overflow: hidden; background: #ffffff; transition: transform 0.3s ease, box-shadow 0.3s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.04);"
                         onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 15px 35px rgba(0,0,0,0.08)';"
                         onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 30px rgba(0,0,0,0.04)';">
                        
                        <div class="position-relative" style="height: 200px; overflow: hidden; background-color: #000;">
                            <video class="w-100 h-100" style="object-fit: cover;" autoplay loop muted playsinline>
                                <source src="{{ getMediaUrl($video->video_path, 'storage/vids/' . $validSection1Vids[$index % count($validSection1Vids)]) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        
                        <div class="card-body p-4 d-flex flex-column align-items-center justify-content-between">
                            <div class="d-flex flex-column align-items-center">
                                <span class="d-flex align-items-center justify-content-center fw-bold" 
                                      style="background-color: #D8F1F8; color: #00989b; width: 45px; height: 45px; border-radius: 50%; font-size: 20px; margin-top: -45px; z-index: 2; border: 4px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                    {{ $index + 1 }}
                                </span>
                                <h5 class="fw-bold mt-3 text-dark fs-5">{{ $video->title }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
