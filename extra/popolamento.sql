-- Popolamento della tabella UTENTE
INSERT INTO UTENTE (email, nome, cognome, telefono) 
VALUES 
    ('mario@email.com', 'Mario', 'Rossi', 123456789),
    ('giulia@email.com', 'Giulia', 'Bianchi', 987654321),
    ('luca@email.com', 'Luca', 'Verdi', 555666777);

-- Popolamento della tabella DOCENTE
INSERT INTO DOCENTE (email, corso, dipartimento)
VALUES 
    ('mario@email.com', 'Informatica', 'Ingegneria'),
    ('giulia@email.com', 'Matematica', 'Scienze'),
    ('luca@email.com', 'Fisica', 'Scienze');

-- Popolamento della tabella STUDENTE
INSERT INTO STUDENTE (email, codice, annoImmatricolazione)
VALUES 
    ('giuseppe@email.com', 'ABC123XYZ4567890', 2020),
    ('anna@email.com', 'DEF456UVW1234567', 2019),
    ('maria@email.com', 'GHI789RST0123456', 2021);
-- Popolamento della tabella TABELLA_DI_ESERCIZIO
INSERT INTO TABELLA_DI_ESERCIZIO (nome, emailDocente, data, num_righe)
VALUES 
    ('Tabella1', 'mario@email.com', '2023-01-15', 100),
    ('Tabella2', 'giulia@email.com', '2023-02-20', 75),
    ('Tabella3', 'luca@email.com', '2023-03-25', 50);

-- Popolamento della tabella ATTRIBUTO
INSERT INTO ATTRIBUTO (nome, nomeTabella, emailDocente, tipo, primaria)
VALUES 
    ('Attributo1', 'Tabella1', 'mario@email.com', 'INT', TRUE),
    ('Attributo2', 'Tabella1', 'mario@email.com', 'VARCHAR(100)', FALSE),
    ('Attributo1', 'Tabella2', 'giulia@email.com', 'DATE', FALSE),
    ('Attributo2', 'Tabella2', 'giulia@email.com', 'DECIMAL(10,2)', FALSE),
    ('Attributo1', 'Tabella3', 'luca@email.com', 'TEXT', TRUE),
    ('Attributo2', 'Tabella3', 'luca@email.com', 'BOOLEAN', FALSE);

-- Popolamento della tabella INTEGRITA_REFERENZIALE
INSERT INTO INTEGRITA_REFERENZIALE (Attributo1, Attributo2, Tabella1, Tabella2, emailDocente1, emailDocente2)
VALUES 
    ('Attributo1', 'Attributo2', 'Tabella1', 'Tabella2', 'mario@email.com', 'giulia@email.com'),
    ('Attributo1', 'Attributo2', 'Tabella2', 'Tabella3', 'giulia@email.com', 'luca@email.com');

-- Popolamento della tabella TEST
INSERT INTO TEST (titolo, data, foto, visualizzaRisposte, dataPrimaRisposta, dataUltimaRisposta, stato, emailDocente)
VALUES 
    ('Test1', '2023-04-10', NULL, TRUE, '2023-04-12', '2023-04-15', 'Aperto', 'mario@email.com'),
    ('Test2', '2023-05-20', NULL, TRUE, '2023-05-22', '2023-05-25', 'InCompletamento', 'giulia@email.com'),
    ('Test3', '2023-06-30', NULL, TRUE, '2023-07-02', '2023-07-05', 'Concluso', 'luca@email.com');

-- Popolamento della tabella QUESITO
INSERT INTO QUESITO (ID, titoloTest, numRisposte, difficoltà, descrizione)
VALUES 
    (1, 'Test1', 4, 'Medio', 'Descrizione del quesito 1'),
    (2, 'Test1', 3, 'Basso', 'Descrizione del quesito 2'),
    (3, 'Test2', 5, 'Alto', 'Descrizione del quesito 3');

-- Popolamento della tabella RIFERIMENTO
INSERT INTO RIFERIMENTO (nomeTabella, emailDocente, IDquesito, titoloTest)
VALUES 
    ('Tabella1', 'mario@email.com', 1, 'Test1'),
    ('Tabella2', 'giulia@email.com', 2, 'Test1'),
    ('Tabella3', 'luca@email.com', 3, 'Test2');

-- Popolamento della tabella QUESITO_A_RISPOSTA_CHIUSA
INSERT INTO QUESITO_A_RISPOSTA_CHIUSA (ID, titoloTest)
VALUES 
    (1, 'Test1'),
    (2, 'Test2'),
    (3, 'Test3');

-- Popolamento della tabella QUESITO_DI_CODICE
INSERT INTO QUESITO_DI_CODICE (ID, titoloTest)
VALUES 
    (1, 'Test1'),
    (2, 'Test2'),
    (3, 'Test3');

-- Popolamento della tabella OPZIONE
INSERT INTO OPZIONE (Numerazione, testo, idQuesitoChiusa, titoloTest, corretta)
VALUES 
    (1, 'Opzione 1', 1, 'Test1', TRUE),
    (2, 'Opzione 2', 1, 'Test1', FALSE),
    (3, 'Opzione 3', 1, 'Test1', FALSE);

-- Popolamento della tabella SOLUZIONE
INSERT INTO SOLUZIONE (ID, Sketch, IdQuesitoCodice, titoloTest)
VALUES 
    (1, 'Sketch della soluzione', 1, 'Test1'),
    (2, 'Altra soluzione', 2, 'Test2'),
    (3, 'Ancora altra', 3, 'Test3');

-- Popolamento della tabella RISPOSTA
INSERT INTO RISPOSTA (data, emailStudente, esito)
VALUES 
    ('2023-04-11', 'giuseppe@email.com', TRUE),
    ('2023-05-23', 'anna@email.com', FALSE),
    ('2023-06-28', 'maria@email.com', TRUE);

-- Popolamento della tabella RISPOSTA_CHIUSA
INSERT INTO RISPOSTA_CHIUSA (data, IDQuesito, titoloTest, emailStudente, numerazioneOpzione)
VALUES 
    ('2023-04-11', 1, 'Test1', 'giuseppe@email.com', 1),
    ('2023-05-23', 2, 'Test2', 'anna@email.com', 3),
    ('2023-06-28', 3, 'Test3', 'maria@email.com', 2);

-- Popolamento della tabella RISPOSTA_CODICE
INSERT INTO RISPOSTA_CODICE (data, IDQuesito, titoloTest, emailStudente, testo)
VALUES 
    ('2023-04-11', 1, 'Test1', 'giuseppe@email.com', 'Codice risposta 1'),
    ('2023-05-23', 2, 'Test2', 'anna@email.com', 'Codice risposta 2'),
    ('2023-06-28', 3, 'Test3', 'maria@email.com', 'Codice risposta 3');

-- Popolamento della tabella MESSAGGIO
INSERT INTO MESSAGGIO (titolo, data, testo, titoloTest, emailStudenteMittente, emailDocenteMittente, emailDocenteDestinatario)
VALUES 
    ('Messaggio1', '2023-04-13', 'Testo del messaggio 1', 'Test1', 'giuseppe@email.com', 'mario@email.com', 'giulia@email.com'),
    ('Messaggio2', '2023-05-24', 'Testo del messaggio 2', 'Test2', 'anna@email.com', 'giulia@email.com', 'luca@email.com'),
    ('Messaggio3', '2023-07-03', 'Testo del messaggio 3', 'Test3', 'maria@email.com', 'luca@email.com', 'mario@email.com');

-- Popolamento della tabella DESTINATARIO_STU
INSERT INTO DESTINATARIO_STU (emailStudente, titoloMessaggio, titoloTest)
VALUES 
    ('giuseppe@email.com', 'Messaggio1', 'Test1'),
    ('anna@email.com', 'Messaggio2', 'Test2'),
    ('maria@email.com', 'Messaggio3', 'Test3');
