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
     style="backdrop-filter: blur(0px); background-color: rgba(0,0,0,0.25); transition: backdrop-filter 0.3s ease, background-color 0.3s ease;"></div>

    <!-- Content on top -->
    <div class="position-relative z-1 d-flex justify-content-center align-items-center h-100">
        <div class="bg-white bg-opacity-75 p-4 rounded shadow text-center">
            <h1 class="display-4 fw-bold px-4">Izzini Latviju</h1>

            <!-- Buttons -->
            <div class="mb-4 pt-4">
                <button id="askQuestionBtn" class="btn btn-primary me-2">Jautā</button>
                <button id="makePostBtn" class="btn btn-secondary">Dalies</button>
            </div>

            <!-- Question Form -->
            <form id="questionForm" class="mt-4 d-none animate-form" enctype="multipart/form-data">
                <input type="text" class="form-control mb-2" placeholder="Jautājums" required>
                <textarea class="form-control mb-2" rows="3" placeholder="Apraksts" required></textarea>
                <select class="form-select mb-2">
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
                    <input type="file" id="questionImage" accept="image/*" class="d-none">
                </div>
                <button type="submit" class="btn btn-success w-100">Publicēt</button>
            </form>

            <!-- Post Form -->
            <form id="postForm" class="mt-4 d-none animate-form" enctype="multipart/form-data">
                <input type="text" class="form-control mb-2" placeholder="Posts" required>
                <textarea class="form-control mb-2" rows="3" placeholder="Apraksts" required></textarea>
                <select class="form-select mb-2">
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
                    <input type="file" id="postImage" accept="image/*" class="d-none">
                </div>
                <button type="submit" class="btn btn-success w-100">Publicēt</button>
            </form>
        </div>
    </div>
</div>

<!-- Popular Questions/Posts -->
<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center">Populārākie jautājumi un ieraksti</h2>

    <div class="row g-4">
        @foreach($questions as $q)
            <div class="col-md-4">
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
</style>
@endsection
