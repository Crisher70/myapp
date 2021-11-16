create table persona(
    id int not null auto_increment primary key ,
    nombre varchar(150),
    estado boolean,
    empresa_id integer
);

create table vehiculo(
    id int not null auto_increment primary key ,
    tipo varchar(150),
    descripcion varchar(250),
    persona_id integer
);

create table vehiculo(
    id int not null auto_increment primary key ,
    tipo varchar(150),
    descripcion varchar(250),
    persona_id integer
);


-- Consulta 1
select * from ejercicio.persona
inner join ejercicio.vehiculo on ejercicio.vehiculo.persona_id = ejercicio.persona.id 
where ejercicio.vehiculo.tipo = 1

-- Consulta 2
select e.nombre as nombre_empresa, p.nombre as nombre_persona, v.descripcion, v.tipo from empresa e,persona p, vehiculo v 

-- Consulta 3
select * from persona 
where persona.empresa_id = 3
and persona.estado = 1

-- Consulta 4
select p.id , p.nombre , p.estado from persona p 

-- Consulta 5
select e.nombre as empresa, p.nombre as persona, v.descripcion as descripcion from persona p
left join vehiculo v on p.id = v.persona_id
join empresa e on p.empresa_id = e.id
order by e.id , p.nombre;

-- Consulta 6
select e.nombre , p.nombre , v.descripcion from empresa e , persona p, vehiculo v 