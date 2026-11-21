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
  if (!container) return;
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
