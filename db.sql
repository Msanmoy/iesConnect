
INSERT INTO clase_profesor (clase_id, profesor_id) VALUES (1, 2);
INSERT INTO clase_profesor (clase_id, profesor_id) VALUES (2, 3);
INSERT INTO clase_profesor (clase_id, profesor_id) VALUES (3, 1);
INSERT INTO clase_profesor (clase_id, profesor_id) VALUES (3, 2);

INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (1, 2);
INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (1, 3);
INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (2, 4);
INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (2, 5);
INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (3, 6);
INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (1, 4);
INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (2, 2);
INSERT INTO aula_profesor (aula_id, profesor_id) VALUES (3, 3);

INSERT INTO aula_estudiante (aula_id, estudiante_id) VALUES (1, 20);
INSERT INTO aula_estudiante (aula_id, estudiante_id) VALUES (1, 21);
INSERT INTO aula_estudiante (aula_id, estudiante_id) VALUES (1, 22);
INSERT INTO aula_estudiante (aula_id, estudiante_id) VALUES (1, 23);
INSERT INTO aula_estudiante (aula_id, estudiante_id) VALUES (1, 24);

INSERT INTO temas (nombre, aula_id, eliminado) VALUES ('tema 1', 1, false);
INSERT INTO temas (nombre, aula_id, eliminado) VALUES ('tema 2', 2, false);
INSERT INTO temas (nombre, aula_id, eliminado) VALUES ('tema 3', 3, false);


-- Insertar datos en la tabla tarea con propietario como uno de los profesor del aula que contiene el temas
INSERT INTO tareas (nombre, propietario_id, tema_id, eliminado, visible)
VALUES ('Tarea 1', 2, 1, false, true);

INSERT INTO tareas (nombre, propietario_id, tema_id, eliminado, visible)
VALUES ('Tarea 2', 3, 2, false, true);

INSERT INTO tareas (nombre, propietario_id, tema_id, eliminado, visible)
VALUES ('Tarea 3', 3, 3, false, true);

-- Insertar datos en la tabla fase
INSERT INTO fases (tarea_id,nombre_archivo,nivel) VALUES (1,'video',1);
INSERT INTO fases (tarea_id,nombre_archivo,nivel) VALUES (1,'foto',2);
INSERT INTO fases (tarea_id,nombre_archivo,nivel) VALUES (1,'audio',3);

-- Insertar datos en la tabla tarea_estudiante
INSERT INTO tarea_estudiante (fase, estudiante_id, tarea_id)
VALUES (1, 20, 2);
INSERT INTO tarea_estudiante (fase, basico, intermedio, estudiante_id, tarea_id)
VALUES (2, 85, 80, 21, 1);
INSERT INTO tarea_estudiante (fase, basico, intermedio, avanzado, estudiante_id, tarea_id)
VALUES (3, 90, 85, 80, 22, 3);
INSERT INTO preguntas (enunciado, nombre_archivo, fase_id) VALUES ('Enunciado pregunta 1', 'archivo1.jpg', 1);
INSERT INTO preguntas (enunciado, nombre_archivo, fase_id) VALUES ('Enunciado pregunta 2', 'archivo2.mp3', 2);
INSERT INTO preguntas (enunciado, nombre_archivo, fase_id) VALUES ('Enunciado pregunta 3', 'archivo3.mp4', 3);
-- Insertar datos en la tabla respuesta
INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (true, 'Respuesta correcta 1', 1);
INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (false, 'Respuesta incorrecta 1', 1);
INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (false, 'Respuesta incorrecta 2', 1);

INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (false, 'Respuesta incorrecta 1', 2);
INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (true, 'Respuesta correcta 2', 2);
INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (false, 'Respuesta incorrecta 3', 2);

INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (false, 'Respuesta incorrecta 1', 3);
INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (false, 'Respuesta incorrecta 2', 3);
INSERT INTO respuestas (correcta, respuesta, pregunta_id) VALUES (true, 'Respuesta correcta 3', 3);
