<div class="card mb-4 shadow-sm border-0 text-white"
     style="background: url('{{ $asignatura->imagen ? asset('storage/' . $asignatura->imagen) : asset('images/default.jpg') }}') center/cover no-repeat; border-radius: 10px; height: 180px;">
    <div class="card-body d-flex justify-content-between align-items-center h-100">
        <div>
            <h2 class="fw-bold">{{ $asignatura->nombre }}</h2>
        </div>
    </div>
</div>
