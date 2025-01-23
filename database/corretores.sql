create database mikael;
use mikael;

create table corretores (
	id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    creci VARCHAR(20) NOT NULL UNIQUE
);
desc corretores;

insert into corretores (nome, cpf, creci) values ('João Silva', '12345678901', '202662-F');
insert into corretores (nome, cpf, creci) values ('Enzo Mikael', '12345678911', '202663-F');
insert into corretores (nome, cpf, creci) values ('Augusto Guimarães', '12345678912', '202623-F');

select * from corretores;
