<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Calendario</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:ital,wght@0,400..700;1,400..700&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .calendar-day {
            min-height: 120px;
            border: 1px solid #dee2e6;
        }

        .calendar-day:hover {
            background-color: #f8f9fa;
        }

        .calendar-header {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .calendar-day-number {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .calendar-event {
            padding: 5px;
            margin-bottom: 5px;
            border-radius: 4px;
            font-size: 0.8rem;
            cursor: pointer;
        }

        .event-task {
            background-color: #cfe2ff;
            border-left: 3px solid #0d6efd;
        }

        .event-exam {
            background-color: #f8d7da;
            border-left: 3px solid #dc3545;
        }

        .today {
            background-color: #e8f4f8;
        }

        .other-month {
            background-color: #f5f5f5;
            color: #adb5bd;
        }

        .calendar-controls {
            margin-bottom: 20px;
        }

        .legend-item {
            display: inline-block;
            margin-right: 15px;
        }

        .legend-color {
            display: inline-block;
            width: 15px;
            height: 15px;
            margin-right: 5px;
            border-radius: 3px;
            vertical-align: middle;
        }
    </style>
</head>

<body>
<header>
    <nav class="navbar navbar-expand-lg bg-white border-bottom">
        <div class="container-xl d-flex align-items-center">
            <!-- Menú hamburguesa -->
            <button class="navbar-toggler d-lg-none border-0 shadow-none me-2" type="button"
                    data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo -->
            <a class="navbar-brand d-none d-lg-block" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="IES Vega de Mijas" style="height: 80px;">
            </a>

            <!-- Menú normal -->
            <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clases.asignaturas') }}">Clases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('calendario') }}">Calendario</a>
                    </li>
                </ul>
            </div>

            <!-- Parte derecha -->
            <div class="d-flex align-items-center border-start ms-auto">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center ml-2 text-decoration-none dropdown-toggle"
                       data-bs-toggle="dropdown">
                        <img src="{{ asset('images/EjemploPerfilUsuario.png') }}" alt="Profile" class="rounded-circle"
                             style="height: 40px;">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Perfil</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Menú lateral -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clases.asignaturas') }}">Clases</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('calendario') }}">Calendario</a>
                </li>
            </ul>
        </div>
    </div>
</header>

<main class="container-xl my-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Calendario de Actividades</h2>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-secondary" id="prevMonth">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-outline-secondary" id="currentMonth">Hoy</button>
                <button type="button" class="btn btn-outline-secondary" id="nextMonth">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <h3 id="monthYearDisplay" class="mb-0">Mayo 2025</h3>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="legend">
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #cfe2ff; border-left: 3px solid #0d6efd;"></span>
                    <span>Tareas</span>
                </div>
                <div class="legend-item">
                    <span class="legend-color" style="background-color: #f8d7da; border-left: 3px solid #dc3545;"></span>
                    <span>Exámenes</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" id="viewAll">Todos</button>
                <button type="button" class="btn btn-outline-primary" id="viewTasks">Tareas</button>
                <button type="button" class="btn btn-outline-primary" id="viewExams">Exámenes</button>
            </div>
            <div class="dropdown d-inline-block ms-2">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="asignaturaFilter" data-bs-toggle="dropdown" aria-expanded="false">
                    Todas las asignaturas
                </button>
                <ul class="dropdown-menu" aria-labelledby="asignaturaFilter">
                    <li><a class="dropdown-item active" href="#" data-asignatura="todas">Todas las asignaturas</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-asignatura="biologia">Biología</a></li>
                    <li><a class="dropdown-item" href="#" data-asignatura="matematicas">Matemáticas</a></li>
                    <li><a class="dropdown-item" href="#" data-asignatura="historia">Historia</a></li>
                    <li><a class="dropdown-item" href="#" data-asignatura="educacion-fisica">Educación Física</a></li>
                    <li><a class="dropdown-item" href="#" data-asignatura="tecnologia">Tecnología</a></li>
                    <li><a class="dropdown-item" href="#" data-asignatura="informatica">Informática</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row calendar-header">
        <div class="col">Lun</div>
        <div class="col">Mar</div>
        <div class="col">Mié</div>
        <div class="col">Jue</div>
        <div class="col">Vie</div>
        <div class="col">Sáb</div>
        <div class="col">Dom</div>
    </div>

    <div id="calendarBody">
        <!-- Semana 1 -->
        <div class="row">
            <div class="col calendar-day other-month">
                <div class="calendar-day-number">28</div>
            </div>
            <div class="col calendar-day other-month">
                <div class="calendar-day-number">29</div>
            </div>
            <div class="col calendar-day other-month">
                <div class="calendar-day-number">30</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">1</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">2</div>
                <div class="calendar-event event-task" data-asignatura="biologia" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Entrega informe de laboratorio" data-event-description="Completar el informe sobre la práctica de microscopía" data-event-date="2 de mayo" data-event-type="Tarea" data-event-subject="Biología">
                    Entrega informe de laboratorio
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">3</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">4</div>
            </div>
        </div>

        <!-- Semana 2 -->
        <div class="row">
            <div class="col calendar-day">
                <div class="calendar-day-number">5</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">6</div>
                <div class="calendar-event event-exam" data-asignatura="matematicas" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Examen de Álgebra" data-event-description="Temas: Matrices, determinantes y sistemas de ecuaciones" data-event-date="6 de mayo" data-event-type="Examen" data-event-subject="Matemáticas">
                    Examen de Álgebra
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">7</div>
            </div>
            <div class="col calendar-day today">
                <div class="calendar-day-number">8</div>
                <div class="calendar-event event-task" data-asignatura="informatica" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Entrega proyecto web" data-event-description="Finalizar y entregar el proyecto de desarrollo web con HTML, CSS y JavaScript" data-event-date="8 de mayo" data-event-type="Tarea" data-event-subject="Informática">
                    Entrega proyecto web
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">9</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">10</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">11</div>
            </div>
        </div>

        <!-- Semana 3 -->
        <div class="row">
            <div class="col calendar-day">
                <div class="calendar-day-number">12</div>
                <div class="calendar-event event-task" data-asignatura="historia" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Presentación Guerra Civil" data-event-description="Preparar presentación sobre las causas y consecuencias de la Guerra Civil Española" data-event-date="12 de mayo" data-event-type="Tarea" data-event-subject="Historia">
                    Presentación Guerra Civil
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">13</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">14</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">15</div>
                <div class="calendar-event event-exam" data-asignatura="biologia" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Examen de Genética" data-event-description="Temas: Leyes de Mendel, herencia y mutaciones" data-event-date="15 de mayo" data-event-type="Examen" data-event-subject="Biología">
                    Examen de Genética
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">16</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">17</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">18</div>
            </div>
        </div>

        <!-- Semana 4 -->
        <div class="row">
            <div class="col calendar-day">
                <div class="calendar-day-number">19</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">20</div>
                <div class="calendar-event event-task" data-asignatura="tecnologia" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Entrega maqueta" data-event-description="Finalizar y entregar la maqueta del proyecto de energías renovables" data-event-date="20 de mayo" data-event-type="Tarea" data-event-subject="Tecnología">
                    Entrega maqueta
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">21</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">22</div>
                <div class="calendar-event event-exam" data-asignatura="historia" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Examen Historia Contemporánea" data-event-description="Temas: Guerras mundiales, Guerra Fría y descolonización" data-event-date="22 de mayo" data-event-type="Examen" data-event-subject="Historia">
                    Examen Historia Contemporánea
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">23</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">24</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">25</div>
            </div>
        </div>

        <!-- Semana 5 -->
        <div class="row">
            <div class="col calendar-day">
                <div class="calendar-day-number">26</div>
                <div class="calendar-event event-task" data-asignatura="educacion-fisica" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Prueba de resistencia" data-event-description="Evaluación de resistencia: carrera de 2000m" data-event-date="26 de mayo" data-event-type="Tarea" data-event-subject="Educación Física">
                    Prueba de resistencia
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">27</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">28</div>
                <div class="calendar-event event-exam" data-asignatura="informatica" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Examen Programación" data-event-description="Temas: Algoritmos, estructuras de datos y programación orientada a objetos" data-event-date="28 de mayo" data-event-type="Examen" data-event-subject="Informática">
                    Examen Programación
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">29</div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">30</div>
                <div class="calendar-event event-task" data-asignatura="matematicas" data-bs-toggle="modal" data-bs-target="#eventModal" data-event-title="Entrega ejercicios cálculo" data-event-description="Completar y entregar los ejercicios de derivadas e integrales" data-event-date="30 de mayo" data-event-type="Tarea" data-event-subject="Matemáticas">
                    Entrega ejercicios cálculo
                </div>
            </div>
            <div class="col calendar-day">
                <div class="calendar-day-number">31</div>
            </div>
            <div class="col calendar-day other-month">
                <div class="calendar-day-number">1</div>
            </div>
        </div>
    </div>

    <!-- Modal para mostrar detalles del evento -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Detalles del Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="eventTitle"></h4>
                    <div class="mb-3">
                        <span class="badge bg-primary" id="eventType"></span>
                        <span class="badge bg-secondary" id="eventSubject"></span>
                    </div>
                    <p><strong>Fecha:</strong> <span id="eventDate"></span></p>
                    <p id="eventDescription"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar el modal para mostrar detalles del evento
        const eventModal = document.getElementById('eventModal');
        if (eventModal) {
            eventModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const title = button.getAttribute('data-event-title');
                const description = button.getAttribute('data-event-description');
                const date = button.getAttribute('data-event-date');
                const type = button.getAttribute('data-event-type');
                const subject = button.getAttribute('data-event-subject');

                document.getElementById('eventTitle').textContent = title;
                document.getElementById('eventDescription').textContent = description;
                document.getElementById('eventDate').textContent = date;
                document.getElementById('eventType').textContent = type;
                document.getElementById('eventSubject').textContent = subject;

                // Cambiar color del badge según el tipo de evento
                const eventTypeBadge = document.getElementById('eventType');
                if (type === 'Tarea') {
                    eventTypeBadge.className = 'badge bg-primary';
                } else if (type === 'Examen') {
                    eventTypeBadge.className = 'badge bg-danger';
                }
            });
        }

        // Filtrar eventos por tipo
        const viewAllBtn = document.getElementById('viewAll');
        const viewTasksBtn = document.getElementById('viewTasks');
        const viewExamsBtn = document.getElementById('viewExams');
        const events = document.querySelectorAll('.calendar-event');

        viewAllBtn.addEventListener('click', function() {
            setActiveButton(this);
            events.forEach(event => {
                event.style.display = 'block';
            });
            applyAsignaturaFilter();
        });

        viewTasksBtn.addEventListener('click', function() {
            setActiveButton(this);
            events.forEach(event => {
                if (event.classList.contains('event-task')) {
                    event.style.display = 'block';
                } else {
                    event.style.display = 'none';
                }
            });
            applyAsignaturaFilter();
        });

        viewExamsBtn.addEventListener('click', function() {
            setActiveButton(this);
            events.forEach(event => {
                if (event.classList.contains('event-exam')) {
                    event.style.display = 'block';
                } else {
                    event.style.display = 'none';
                }
            });
            applyAsignaturaFilter();
        });

        function setActiveButton(button) {
            [viewAllBtn, viewTasksBtn, viewExamsBtn].forEach(btn => {
                btn.classList.remove('active');
            });
            button.classList.add('active');
        }

        // Filtrar por asignatura
        const asignaturaLinks = document.querySelectorAll('[data-asignatura]');
        let currentAsignatura = 'todas';

        asignaturaLinks.forEach(link => {
            if (link.tagName === 'A') {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const asignatura = this.getAttribute('data-asignatura');
                    currentAsignatura = asignatura;

                    // Actualizar texto del botón dropdown
                    document.getElementById('asignaturaFilter').textContent =
                        asignatura === 'todas' ? 'Todas las asignaturas' :
                            this.textContent;

                    // Actualizar clase activa en el menú
                    document.querySelectorAll('.dropdown-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    this.classList.add('active');

                    applyAsignaturaFilter();
                });
            }
        });

        function applyAsignaturaFilter() {
            const eventType = getActiveEventType();

            events.forEach(event => {
                const eventAsignatura = event.getAttribute('data-asignatura');
                const isEventTypeMatch =
                    eventType === 'all' ||
                    (eventType === 'task' && event.classList.contains('event-task')) ||
                    (eventType === 'exam' && event.classList.contains('event-exam'));

                if ((currentAsignatura === 'todas' || eventAsignatura === currentAsignatura) && isEventTypeMatch) {
                    event.style.display = 'block';
                } else {
                    event.style.display = 'none';
                }
            });
        }

        function getActiveEventType() {
            if (viewTasksBtn.classList.contains('active')) return 'task';
            if (viewExamsBtn.classList.contains('active')) return 'exam';
            return 'all';
        }

        // Navegación del calendario
        const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        let currentDate = new Date();

        document.getElementById('prevMonth').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateCalendarHeader();
        });

        document.getElementById('nextMonth').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateCalendarHeader();
        });

        document.getElementById('currentMonth').addEventListener('click', function() {
            currentDate = new Date();
            updateCalendarHeader();
        });

        function updateCalendarHeader() {
            const monthYear = `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
            document.getElementById('monthYearDisplay').textContent = monthYear;

            // En una implementación real, aquí se cargarían los eventos del mes seleccionado
            // mediante una llamada AJAX al servidor
        }
    });
</script>
</body>

</html>
