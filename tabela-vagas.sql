create database wdev_vagas;
use wdev_vagas;

create table vagas
(
	id int primary key auto_increment,
    titulo varchar(255),
    descricao text,
    ativo enum('s','n'),
    data timestamp
);
select * from vagas;