# IesConnect

IesConnect es una plataforma de gestión educativa similar a Google Classroom, pero con funcionalidades adicionales diseñadas para mejorar la experiencia tanto de estudiantes como de profesores. A diferencia de otras plataformas, IesConnect incluye herramientas avanzadas de comunicación, gestión de tareas y control de roles.

## 🚀 Tecnologías Utilizadas

Este proyecto se ha desarrollado utilizando las siguientes tecnologías:

- **Backend:** PHP, Laravel
- **Base de Datos:** MySQL
- **Frontend:** JavaScript, Bootstrap5

## ✨ Características Principales

- Creación y gestión de cursos.
- Sistema de entrega y calificación de tareas.
- Comunicación eficiente entre profesores y alumnos a través de mensajes y foros.
- Notificaciones en tiempo real para mantener informados a los usuarios.
- Gestión avanzada de roles y permisos para mayor seguridad.

## 📦 Instalación

Sigue estos pasos para configurar el entorno de desarrollo en tu máquina:

1. Clona el repositorio:
   ```bash
   git clone https://github.com/tuusuario/IesConnect.git
   cd IesConnect
   ```
2. Instala las dependencias de PHP con Composer:
   ```bash
   composer install
   ```
3. Configura el archivo `.env`:
   ```bash
   cp .env.example .env
   ```
   Luego edita `.env` con los datos de conexión a tu base de datos.

4. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```
5. Ejecuta las migraciones y semillas para inicializar la base de datos:
   ```bash
   php artisan migrate --seed
   ```
6. Inicia el servidor local:
   ```bash
   php artisan serve
   ```
7. Accede a la aplicación en tu navegador:
   ```
   http://127.0.0.1:8000
   ```

## 📖 Uso

1. Regístrate e inicia sesión como profesor o estudiante.
2. Crea un curso o únete a uno existente.
3. Publica tareas, envía archivos y participa en foros de discusión.
4. Recibe notificaciones y revisa el progreso de tus alumnos.

## 🤝 Contribuciones

¡Las contribuciones son bienvenidas! Si quieres mejorar IesConnect, sigue estos pasos:

1. Haz un fork del repositorio.
2. Crea una rama con tu mejora:
   ```bash
   git checkout -b mi-nueva-funcionalidad
   ```
3. Realiza los cambios y sube los commits:
   ```bash
   git commit -m "Añadida nueva funcionalidad X"
   git push origin mi-nueva-funcionalidad
   ```
4. Abre un Pull Request en GitHub.

## 📄 Licencia

Este proyecto está bajo la licencia MIT, lo que significa que puedes usarlo y modificarlo libremente siempre que se incluya la atribución correspondiente. Para más detalles, consulta el archivo `LICENSE`.

---

¡Gracias por contribuir a IesConnect! 🚀
