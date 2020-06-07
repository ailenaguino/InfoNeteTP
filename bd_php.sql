create database infonete;
use infonete;

create table rol(
	id int not null auto_increment,
    nombre varchar(25),
    primary key(id)
);
create table usuario(
	id integer not null auto_increment,
    nombre varchar(25),
    nombre_usuario varchar(25),
    contrasenia varchar(25),
    fecha_nacimiento date,
    email varchar(40),
    telefono integer,
    ubicacion varchar(30),
    id_rol int not null,
    primary key(id),
    foreign key(id_rol)references rol(id)
);

create table categoria(
	id integer not null auto_increment,
    nombre varchar(25),
    primary key(id)
);

create table ejemplar(
	id integer not null auto_increment,
    nombre varchar(25),
    id_categoria integer not null,
    primary key(id),
    foreign key(id_categoria) references categoria(id)
);

create table usuarioSuscribeEjemplar(
	fecha date not null,
    id_usuario integer not null,
    id_ejemplar integer not null,
    primary key(id_usuario,id_ejemplar),
    foreign key(id_usuario) references usuario(id),
	foreign key(id_ejemplar) references ejemplar(id)
);

create table edicion(
	id integer not null auto_increment,
    nombre varchar(40),
    numero integer not null,
    id_ejemplar integer not null,
    primary key(id),
    foreign key(id_ejemplar) references ejemplar(id)
);

create table seccion(
	id integer not null auto_increment,
    nombre varchar(40),
    primary key(id)
);

create table usuarioCompraEdicion(
	id_usuario integer not null, 
    id_edicion integer not null,
    primary key(id_usuario,id_edicion),
    foreign key(id_usuario) references usuario(id),
    foreign key(id_edicion) references edicion(id)
);

create table edicionPoseeSeccion(
	id_seccion integer not null, 
    id_edicion integer not null,
    primary key(id_seccion,id_edicion),
    foreign key(id_seccion) references seccion(id),
    foreign key(id_edicion) references edicion(id)
);

create table noticia(
	id integer not null auto_increment,
    video varchar(255),
    link varchar(255),
    ubicacion varchar(30),
    contenido varchar(50000),
    subtitulo varchar(255),
    titulo varchar (255),
    id_seccion integer not null,
    id_usuario integer not null,
    primary key(id),
    foreign key(id_seccion) references seccion(id),
	foreign key(id_usuario) references usuario(id)
);

create table foto(
	id integer not null auto_increment,
	direccion varchar(300),
    primary key(id)
);

create table usuarioLeeNoticia(
	id_usuario integer not null,
    id_noticia integer not null,
    primary key(id_usuario,id_noticia),
    foreign key(id_usuario) references usuario(id),
    foreign key(id_noticia) references noticia(id)
);

