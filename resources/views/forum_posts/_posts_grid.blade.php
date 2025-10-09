<div class="container my-5">

    {{-- Section Title --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Jaunākie Foruma Ieraksti</h3>
        
        {{-- Category Filter --}}
        <select id="categoryFilter" class="form-select w-auto">
            <option value="all">Visas kategorijas</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Posts Grid --}}
    <div class="row g-4" id="postsGrid">
        @forelse ($posts as $post)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3 post-card" data-category="{{ $post->category_id }}">
                <a href="{{ route('forum.posts.show', $post->id) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm border-0 hover-shadow transition">
                        @if ($post->feature_image)
                            <img src="{{ asset('storage/' . $post->feature_image) }}" 
                                 class="card-img-top rounded-top" 
                                 alt="{{ $post->title }}">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 180px;">
                                <i class="bi bi-image text-muted fs-1"></i>
                            </div>
                        @endif

                        <div class="card-body">
                            <h5 class="card-title fw-semibold">{{ Str::limit($post->title, 40) }}</h5>
                            <p class="card-text text-muted small mb-2">
                                {{ Str::limit($post->body, 80) }}
                            </p>
                            <span class="badge bg-primary">
                                {{ $post->category->name ?? 'Bez kategorijas' }}
                            </span>
                        </div>

                        <div class="card-footer bg-transparent border-0 small text-muted">
                            <i class="bi bi-clock me-1"></i> {{ $post->created_at->diffForHumans() }}
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p class="text-muted text-center">Nav pieejamu foruma ierakstu.</p>
        @endforelse
    </div>
</div>

{{-- JavaScript for filtering --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filter = document.getElementById('categoryFilter');
    const posts = document.querySelectorAll('.post-card');

    filter.addEventListener('change', function () {
        const selected = this.value;
        posts.forEach(post => {
            post.style.display = (selected === 'all' || post.dataset.category === selected) 
                ? 'block' 
                : 'none';
        });
    });
});
</script>

{{-- Optional: small hover style --}}
<style>
.hover-shadow:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-3px);
}
.transition {
    transition: all 0.2s ease-in-out;
}
</style>
