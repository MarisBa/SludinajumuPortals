@php
    use Illuminate\Support\Str;

    // Demo dati (lai redzētu dizainu bez DB)
    $questions = collect([
        (object)[
            'title' => 'Kas ir populārākās apskates vietas Rīgā?',
            'body' => 'Vēlos uzzināt, kuras vietas obligāti jāapmeklē, ja ierodos uz pāris dienām Rīgā.',
            'category' => (object)['name' => 'Ceļošana']
        ],
        (object)[
            'title' => 'Kādas ir tradicionālās latviešu ēdienu receptes?',
            'body' => 'Vai kāds var padalīties ar rupjmaizes zupas vai sklandraušu recepti?',
            'category' => (object)['name' => 'Ēdieni']
        ],
        (object)[
            'title' => 'Kur var apskatīt skaistākās dabas takas Latvijā?',
            'body' => 'Meklēju ieteikumus vieglām pārgājienu takām ģimenei ar bērniem.',
            'category' => (object)['name' => 'Daba']
        ],
        (object)[
            'title' => 'Kādi ir populārākie Latvijas svētki un tradīcijas?',
            'body' => 'Gribu uzzināt vairāk par Jāņiem un citiem svētkiem.',
            'category' => (object)['name' => 'Kultūra']
        ],
        (object)[
            'title' => 'Kāds ir sabiedriskais transports Rīgā?',
            'body' => 'Kā visērtāk pārvietoties pa galvaspilsētu – tramvajs, trolejbuss vai autobuss?',
            'category' => (object)['name' => 'Transports']
        ],
        (object)[
            'title' => 'Kur Latvijā vislabāk slēpot?',
            'body' => 'Vai ir kādas labas kalnu slēpošanas trases ģimenei?',
            'category' => (object)['name' => 'Sports']
        ],
    ]);
@endphp

@extends('layouts.app')

@section('content')
<div class="position-relative vh-100 overflow-hidden">
    <!-- Full-screen background carousel -->
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <div class="w-100 h-100 position-relative">
            <img src="{{ asset('img/latvija1.jpg') }}" class="position-absolute w-100 h-100 object-fit-cover carousel-img" />
            <img src="{{ asset('img/latvija2.jpg') }}" class="position-absolute w-100 h-100 object-fit-cover carousel-img" />
            <img src="{{ asset('img/latvija3.jpg') }}" class="position-absolute w-100 h-100 object-fit-cover carousel-img" />
        </div>
    </div>

    <!-- Overlay for darkening/blurring -->
    <div id="overlay" class="position-absolute top-0 start-0 w-100 h-100"
        style="backdrop-filter: blur(0px); background-color: rgba(0,0,0,0.25); transition: backdrop-filter 0.3s ease, background-color 0.3s ease;">
    </div>

    <!-- Content on top -->
    <div class="position-relative z-1 d-flex justify-content-center align-items-center h-100">
        <div class="hero-container text-center mx-auto rounded-4 shadow-lg">
            <h1 class="hero-title display-3 fw-bold mb-4">Izzini Latviju!</h1>

            <!-- Buttons -->
            <div class="mb-2" style="padding-top: 4rem;">
                <button id="askQuestionBtn" class="btn btn-primary me-2">Jautā</button>
                <button id="makePostBtn" class="btn btn-secondary">Dalies</button>
            </div>

            <!-- Question Form -->
            <form id="questionForm" class="mt-4 d-none animate-form" enctype="multipart/form-data">
                <input type="text" name="title" class="form-control mb-2" placeholder="Jautājums" required>
                <textarea name="body" class="form-control mb-2" rows="3" placeholder="Apraksts" required></textarea>
                <select name="category" class="form-select mb-2" required>
                    <option selected disabled>Kategorija</option>
                    <option value="travel">Ceļošana</option>
                    <option value="food">Ēdieni</option>
                    <option value="nature">Daba</option>
                    <option value="culture">Kultūra</option>
                    <option value="transport">Transports</option>
                    <option value="sports">Sports</option>
                </select>
                <div class="mb-2">
                    <label for="questionImage" id="questionFileBtn" class="btn btn-outline-secondary w-100">Pievieno attēlu</label>
                    <input type="file" id="questionImage" name="image" accept="image/*" class="d-none">
                </div>
                <button type="submit" id="postBtn" class="btn btn-success w-100">Publicēt</button>
            </form>

            <!-- Post Form -->
            <form id="postForm" class="mt-4 d-none animate-form" enctype="multipart/form-data">
                <input type="text" name="title" class="form-control mb-2" placeholder="Posts" required>
                <textarea name="body" class="form-control mb-2" rows="3" placeholder="Apraksts" required></textarea>
                <select name="category" class="form-select mb-2" required>
                    <option selected disabled>Kategorija</option>
                    <option value="travel">Ceļošana</option>
                    <option value="food">Ēdieni</option>
                    <option value="nature">Daba</option>
                    <option value="culture">Kultūra</option>
                    <option value="transport">Transports</option>
                    <option value="sports">Sports</option>
                </select>
                <div class="mb-2">
                    <label for="postImage" id="postFileBtn" class="btn btn-outline-secondary w-100">Pievieno attēlu</label>
                    <input type="file" id="postImage" name="image" accept="image/*" class="d-none">
                </div>
                <button type="submit" id="postBtn" class="btn btn-success w-100">Publicēt</button>
            </form>
        </div>
    </div>
</div>

<!-- Category Navbar -->
<nav class="navbar navbar-expand-md navbar-light bg-primary shadow-sm border-top border-light">
    <div class="container justify-content-center">
        <ul class="navbar-nav flex-wrap fs-6 text-white">
            <li class="nav-item mx-2">
                <a class="nav-link fw-semibold active" href="#">Visi</a>
            </li>
            <li class="nav-item mx-2">
                <a class="nav-link fw-semibold" href="#">Ceļošana</a>
            </li>
            <li class="nav-item mx-2">
                <a class="nav-link fw-semibold" href="#">Ēdieni</a>
            </li>
            <li class="nav-item mx-2">
                <a class="nav-link fw-semibold" href="#">Daba</a>
            </li>
            <li class="nav-item mx-2">
                <a class="nav-link fw-semibold" href="#">Kultūra</a>
            </li>
            <li class="nav-item mx-2">
                <a class="nav-link fw-semibold" href="#">Transports</a>
            </li>
            <li class="nav-item mx-2">
                <a class="nav-link fw-semibold" href="#">Sports</a>
            </li>
            <!-- Add other categories -->
        </ul>
    </div>
</nav>

<!-- Posts container -->
<div class="posts-container">
    <div class="container py-4">
        <div class="row g-4">
            @foreach($questions as $q)
                <div class="col-md-4 post-item" data-category="{{ strtolower($q->category->name) }}">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ Str::limit($q->title, 60) }}</h5>
                            <p class="card-text">{{ Str::limit($q->body, 120) }}</p>
                            <span class="badge bg-secondary mb-3 align-self-start">{{ $q->category->name }}</span>
                            <a href="#" class="btn btn-primary mt-auto">Skatīt vairāk</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<script>
// Toggle forms and overlay
const askBtn = document.getElementById('askQuestionBtn');
const postBtn = document.getElementById('makePostBtn');
const questionForm = document.getElementById('questionForm');
const postForm = document.getElementById('postForm');
const overlay = document.getElementById('overlay');

function toggleForm(formToShow, otherForm) {
    const isOpen = !formToShow.classList.contains('d-none');

    if (isOpen) {
        // Closing the form
        formToShow.classList.remove('fade-in');
        formToShow.classList.add('fade-out');
        setTimeout(() => formToShow.classList.add('d-none'), 300);
        overlay.style.backdropFilter = 'blur(0px)';
        overlay.style.backgroundColor = 'rgba(0,0,0,0.25)';
    } else {
        // Opening the form
        otherForm.classList.add('d-none'); // hide the other form immediately
        formToShow.classList.remove('fade-out', 'd-none'); // reset any previous fade-out
        formToShow.classList.add('fade-in');
        overlay.style.backdropFilter = 'blur(5px)';
        overlay.style.backgroundColor = 'rgba(0,0,0,0.5)';
    }
}


askBtn.addEventListener('click', () => toggleForm(questionForm, postForm));
postBtn.addEventListener('click', () => toggleForm(postForm, questionForm));

// File input handling
const questionFileInput = document.getElementById('questionImage');
const questionFileBtn = document.getElementById('questionFileBtn');
questionFileInput.addEventListener('change', () => {
    questionFileBtn.textContent = questionFileInput.files.length ? questionFileInput.files[0].name : 'Pievieno attēlu';
});

const postFileInput = document.getElementById('postImage');
const postFileBtn = document.getElementById('postFileBtn');
postFileInput.addEventListener('change', () => {
    postFileBtn.textContent = postFileInput.files.length ? postFileInput.files[0].name : 'Pievieno attēlu';
});
</script>

<script>
const categoryLinks = document.querySelectorAll('.nav-link');
const posts = document.querySelectorAll('.post-item');

categoryLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();

        // Remove active class from all
        categoryLinks.forEach(l => l.classList.remove('active'));

        // Add active class to the clicked one
        link.classList.add('active');

        const selected = link.textContent.trim().toLowerCase();

        // Show all if "Visi" selected
        if (selected === 'visi') {
            posts.forEach(post => post.style.display = 'block');
        } else {
            posts.forEach(post => {
                const category = post.getAttribute('data-category');
                post.style.display = (category === selected) ? 'block' : 'none';
            });
        }
    });
});
</script>

<script>
async function sendFormData(form, url) {
    const formData = new FormData(form);

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });

        const result = await response.json();

        if (response.ok) {
            alert('Dati veiksmīgi nosūtīti!');
            form.reset();
        } else {
            console.error(result);
            alert('Kļūda nosūtot datus!');
        }
    } catch (error) {
        console.error(error);
        alert('Tīkla kļūda.');
    }
}

// Hook up forms
document.getElementById('questionForm').addEventListener('submit', (e) => {
    e.preventDefault();
    sendFormData(e.target, '/api/questions');
});

document.getElementById('postForm').addEventListener('submit', (e) => {
    e.preventDefault();
    sendFormData(e.target, '/api/posts');
});
</script>


<style>
.carousel-img {
    opacity: 0;
    animation: fadeSlide 15s infinite;
}
.carousel-img:nth-child(1) { animation-delay: 0s; }
.carousel-img:nth-child(2) { animation-delay: 5s; }
.carousel-img:nth-child(3) { animation-delay: 10s; }
@keyframes fadeSlide {
    0% { opacity: 0; }
    10% { opacity: 1; }
    33% { opacity: 1; }
    43% { opacity: 0; }
    100% { opacity: 0; }
}
.object-fit-cover { object-fit: cover; }

.animate-form {
    transition: all 0.3s ease;
}
.fade-in { opacity: 1; transform: translateY(0); }
.fade-out { opacity: 0; transform: translateY(-20px); }

#overlay {
    background: 
        linear-gradient(to bottom, rgba(0, 0, 0, 0.6), transparent 20%, transparent 80%, rgba(0, 0, 0, 0.6)),
        rgba(0, 0, 0, 0.25); /* base dim layer */
    transition: backdrop-filter 0.3s ease, background 0.3s ease;
    pointer-events: none; /* So it doesn’t block clicks */
    z-index: 0; /* Adjust if needed */
}

form textarea {
    resize: vertical;          /* only up/down */
    min-height: 100px;         /* comfortable starting height */
    max-height: 500px;         /* prevent huge stretching */
    overflow-y: auto;          /* add scrollbar if needed */
}

/* --- HERO CONTAINER --- */
.hero-container {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(12px);
    max-width: 850px;
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 1.5rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    padding: 4rem 3rem;
}

.hero-container:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2);
}

/* --- TITLE + SUBTITLE --- */
/* --- HERO CONTAINER --- */
.hero-container {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    max-width: 850px;
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 1.5rem;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    padding: 4rem 3rem;
}

.hero-container:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

/* --- TITLE + SUBTITLE --- */
.hero-title {
    font-size: 3rem;
    font-weight: 800;
    color: #1f2937; /* Neutral dark gray-blue */
    margin-bottom: 1rem;
}


/* --- BUTTONS --- */
#askQuestionBtn,
#makePostBtn,
#postBtn {
    font-size: 1.15rem;
    font-weight: 600;
    padding: 0.9rem 2.5rem;
    border-radius: 10px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    transition: all 0.25s ease;
}

/* Primary (blue) */
#askQuestionBtn {
    background-color: #2563eb; /* solid modern blue */
    color: #fff;
}

#askQuestionBtn:hover {
    background-color: #1e40af; /* darker shade on hover */
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(37, 99, 235, 0.35);
}

/* Secondary (neutral gray) */
#makePostBtn {
    background-color: #6b7280; /* gray */
    color: #fff;
}

#makePostBtn:hover {
    background-color: #4b5563;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(75, 85, 99, 0.35);
}

/* Optional: subtle button spacing */
#askQuestionBtn + #makePostBtn {
    margin-left: 1rem;
}

.posts-container {
    height: calc(100vh - 158px); /* Adjust: total height minus navbars (main + category) */
    overflow-y: auto;             /* Add vertical scroll if content is too long */
    padding-bottom: 2rem;         /* Optional: extra spacing at bottom */
}

</style>
@endsection
