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
    <a href="{{ route('products.index') }}" style="text-decoration: none;">
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
  if (!container) return;
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
