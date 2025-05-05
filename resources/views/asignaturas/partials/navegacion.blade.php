<div class="border-bottom mb-3">
    <div class="container-xl d-flex align-items-center" style="gap: 30px;">
        <a href="{{ route('asignaturas.show', $asignatura->slug) }}" class="nav-link {{ request()->routeIs('asignaturas.show') ? 'fw-bold text-primary' : 'text-muted' }}">
            Tablón
        </a>
        <a href="{{ route('asignaturas.trabajo', $asignatura->slug) }}" class="nav-link {{ request()->routeIs('asignaturas.trabajo') ? 'fw-bold text-primary' : 'text-muted' }}">
            Trabajo de clase
        </a>
        <a href="{{ route('asignaturas.personas', $asignatura->slug) }}" class="nav-link {{ request()->routeIs('asignaturas.personas') ? 'fw-bold text-primary' : 'text-muted' }}">
            Personas
        </a>
    </div>
</div>
