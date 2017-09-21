DROP TABLE IF EXISTS agendamento;
CREATE TABLE agendamento(
	id INT PRIMARY KEY AUTO_INCREMENT,
	url TEXT,
	data_envio DATETIME,
	mensagem_id INT
);

ALTER TABLE agendamento ADD return varchar(20);


-- url,data_envio,hora_envio,mensurlagem_id