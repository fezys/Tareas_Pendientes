
CREATE TABLE users (
        nombre VARCHAR(32) NOT NULL,
        apellido VARCHAR(32) NOT NULL,
        username VARCHAR(32) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
);

CREATE TABLE tarea (
        titulo varchar (50) not null UNIQUE,
        contenido text default null,
        fecha_registro datetime,
        fecha_vencimiento datetime,
        estado varchar (10),
        username VARCHAR(32) NOT NULL 
);
