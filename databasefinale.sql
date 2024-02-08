DROP DATABASE IF EXISTS dbESQL;
CREATE DATABASE dbESQL;

CREATE TABLE dbESQL.UTENTE (
    email VARCHAR(255) PRIMARY KEY,
    nome VARCHAR(255),
    cognome VARCHAR(255),
    telefono int
);

-- INSERITO DA ME: ACCOUNT
CREATE TABLE dbESQL.ACCOUNT (
	EMAIL_ACCOUNT VARCHAR(100),
	PASSWORD VARCHAR (50) NOT NULL,
    TIPO_ACCOUNT ENUM ('docente', 'studente'),
	PRIMARY KEY (EMAIL_ACCOUNT)
);

CREATE TABLE dbESQL.DOCENTE (
    email VARCHAR(255) PRIMARY KEY,
    corso VARCHAR(255),
    dipartimento VARCHAR(255),
    FOREIGN KEY (email) REFERENCES UTENTE(email)
);

CREATE TABLE dbESQL.STUDENTE (
    email VARCHAR(255) PRIMARY KEY,
    codice CHAR(16),
    annoImmatricolazione INT,
    FOREIGN KEY (email) REFERENCES UTENTE(email)
);

CREATE TABLE dbESQL.TABELLA_DI_ESERCIZIO (
    nome VARCHAR(255),
    emailDocente VARCHAR(255),
    data DATETIME,
    num_righe INT,
    PRIMARY KEY (nome, emailDocente),
    FOREIGN KEY (emailDocente) REFERENCES DOCENTE(email)
);

CREATE TABLE dbESQL.ATTRIBUTO (
    nome VARCHAR(255),
    nomeTabella VARCHAR(255),
    emailDocente VARCHAR(255),
    tipo VARCHAR(255),
    primaria boolean,
    PRIMARY KEY (nome, nomeTabella, emailDocente),
    FOREIGN KEY (nomeTabella) REFERENCES TABELLA_DI_ESERCIZIO(nome),
    FOREIGN KEY (emailDocente) REFERENCES DOCENTE(email)
);

CREATE TABLE dbESQL.INTEGRITA_REFERENZIALE (
    Attributo1 VARCHAR(20),
    Attributo2 VARCHAR(20),
    Tabella1 VARCHAR(20),
    Tabella2 VARCHAR(20),
    emailDocente1 VARCHAR(20),
    emailDocente2 VARCHAR(20),
    PRIMARY KEY (Attributo1, Attributo2, Tabella1, Tabella2, emailDocente1, emailDocente2),
    FOREIGN KEY (Attributo1) REFERENCES ATTRIBUTO(nome),
    FOREIGN KEY (Attributo2) REFERENCES ATTRIBUTO(nome),
    FOREIGN KEY (Tabella1) REFERENCES TABELLA_DI_ESERCIZIO(nome),
    FOREIGN KEY (Tabella2) REFERENCES TABELLA_DI_ESERCIZIO(nome),
    FOREIGN KEY (emailDocente1) REFERENCES DOCENTE(email),
    FOREIGN KEY (emailDocente2) REFERENCES DOCENTE(email)
);

CREATE TABLE dbESQL.TEST (
    titolo VARCHAR(255) PRIMARY KEY,
    data DATE,
    foto BLOB,
    visualizzaRisposte BOOLEAN,
    emailDocente VARCHAR(255),
    FOREIGN KEY (emailDocente) REFERENCES DOCENTE(email)
);


CREATE TABLE dbESQL.SVOLGIMENTO (
    titoloTest VARCHAR(255),
	emailStudente VARCHAR(255),
    dataPrimaRisposta DATETIME default NULL,
    dataUltimaRisposta DATETIME default NULL,
    stato ENUM ('Aperto', 'InCompletamento', 'Concluso'),
    PRIMARY KEY(titoloTest, emailStudente),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo),
    FOREIGN KEY (emailStudente) REFERENCES STUDENTE(email)
);




CREATE TABLE dbESQL.QUESITO (
    ID INT auto_increment,
    titoloTest VARCHAR(255),
    numRisposte INT,
    difficoltà ENUM ('Basso', 'Medio', 'Alto'),
    descrizione TEXT,
    PRIMARY KEY (ID, titoloTest),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo)
);

CREATE TABLE dbESQL.RIFERIMENTO (
    nomeTabella VARCHAR(255),
    emailDocente VARCHAR(255),
    IDquesito INT,
    titoloTest VARCHAR(255),
    PRIMARY KEY (nomeTabella, emailDocente, IDquesito, titoloTest),
    FOREIGN KEY (nomeTabella) REFERENCES TABELLA_DI_ESERCIZIO(nome),
    FOREIGN KEY (IDquesito) REFERENCES QUESITO(ID),
	FOREIGN KEY (emailDocente) REFERENCES DOCENTE(email),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo)
);



CREATE TABLE dbESQL.QUESITO_A_RISPOSTA_CHIUSA (
    ID INT,
    titoloTest VARCHAR(255),
    PRIMARY KEY (ID, titoloTest),
    FOREIGN KEY (ID) REFERENCES QUESITO(ID),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo)
);

CREATE TABLE dbESQL.QUESITO_DI_CODICE (
    ID INT,
    titoloTest VARCHAR(255),
    PRIMARY KEY (ID, titoloTest),
    FOREIGN KEY (ID) REFERENCES QUESITO(ID),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo)
);

CREATE TABLE dbESQL.OPZIONE (
    Numerazione INT AUTO_INCREMENT,
    testo TEXT,
    idQuesitoChiusa INT,
    titoloTest VARCHAR(255),
    opzioneCorretta TEXT, #risposta corretta, text perchè può esserci più di una
    PRIMARY KEY (Numerazione, idQuesitoChiusa, titoloTest),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo),
    FOREIGN KEY (idQuesitoChiusa) REFERENCES QUESITO_A_RISPOSTA_CHIUSA(ID),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo)
);

CREATE TABLE dbESQL.SOLUZIONE (
	ID int auto_increment,
    Sketch TEXT,
    IdQuesitoCodice INT,
    titoloTest VARCHAR(255),
    PRIMARY KEY (ID),
    FOREIGN KEY (idQuesitoCodice) REFERENCES QUESITO_DI_CODICE(ID),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo)
);

CREATE TABLE dbESQL.RISPOSTA (
    data DATETIME,
    emailStudente VARCHAR(255),
    esito BOOLEAN,
    PRIMARY KEY (data, emailStudente),
    FOREIGN KEY (emailStudente) REFERENCES STUDENTE(email)
);

CREATE TABLE dbESQL.RISPOSTA_CHIUSA (
    data DATETIME,
    IDQuesito INT,
    titoloTest VARCHAR(255),
    emailStudente VARCHAR(255),
    numerazioneOpzione INT,
    PRIMARY KEY (data, emailStudente),
    FOREIGN KEY (IDQuesito) REFERENCES QUESITO_A_RISPOSTA_CHIUSA(ID),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo),
    FOREIGN KEY (emailStudente) REFERENCES STUDENTE(email)
);

CREATE TABLE dbESQL.RISPOSTA_CODICE (
    data DATETIME,
    IDQuesito INT,
    titoloTest VARCHAR(255),
    emailStudente VARCHAR(255),
    testoRisposta TEXT,
    PRIMARY KEY (data, emailStudente),
	FOREIGN KEY (IDQuesito) REFERENCES QUESITO_DI_CODICE(ID),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo),
    FOREIGN KEY (emailStudente) REFERENCES STUDENTE(email)
);


CREATE TABLE dbESQL.MESSAGGIO (
    titolo VARCHAR(255),
    data DATETIME,
    testo TEXT,
    titoloTest VARCHAR(255),
    emailStudenteMittente VARCHAR(255),
    emailDocenteMittente VARCHAR(255),
    emailDocenteDestinatario VARCHAR(255),
    PRIMARY KEY (titolo, titoloTest, data),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo),
    FOREIGN KEY (emailStudenteMittente) REFERENCES STUDENTE(email),
    FOREIGN KEY (emailDocenteMittente) REFERENCES DOCENTE(email),
    FOREIGN KEY (emailDocenteDestinatario) REFERENCES DOCENTE(email)
);

CREATE TABLE dbESQL.DESTINATARIO_STU (
    emailStudente VARCHAR(255),
    titoloMessaggio VARCHAR(255),
    titoloTest VARCHAR(255),
    PRIMARY KEY (emailStudente, titoloMessaggio, titoloTest),
    FOREIGN KEY (emailStudente) REFERENCES STUDENTE(email),
    FOREIGN KEY (titoloMessaggio) REFERENCES MESSAGGIO(titolo),
    FOREIGN KEY (titoloTest) REFERENCES TEST(titolo)
);

DELIMITER ££
CREATE PROCEDURE dbESQL.ISCRIZIONE_STUDENTE(
    IN inputEmail VARCHAR(255),
    IN inputPassword VARCHAR(50),
    IN inputNome VARCHAR(255),
    IN inputCognome VARCHAR(255),
    IN inputTelefono INT,
    IN inputAnnoImmatricolazione int,
    IN inputCodice CHAR(16)
)
BEGIN
	
    -- Controllo se l'email è già presente 
    IF (SELECT COUNT(*) FROM dbESQL.utente WHERE email = inputEmail) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'EMAIL RISULTA GIÀ PRESENTE';
    ELSE
        -- Inserimento dell'utente nella tabella UTENTE
        INSERT INTO dbESQL.UTENTE (email, nome, cognome, telefono)
        VALUES (inputEmail, inputNome, inputCognome, inputTelefono);

        -- Inserimento dell'account nella tabella ACCOUNT
        INSERT INTO dbESQL.ACCOUNT (EMAIL_ACCOUNT, PASSWORD, TIPO_ACCOUNT)
        VALUES (inputEmail, inputPassword, 'studente');

        -- Inserimento dello studente nella tabella STUDENTE
        INSERT INTO dbESQL.STUDENTE (email, annoImmatricolazione, codice) VALUES (inputEmail, inputAnnoImmatricolazione,inputCodice);
    END IF;
END ££

DELIMITER ££
CREATE PROCEDURE dbESQL.ISCRIZIONE_DOCENTE(
    IN inputEmail VARCHAR(255),
    IN inputPassword VARCHAR(50),
    IN inputNome VARCHAR(255),
    IN inputCognome VARCHAR(255),
    IN inputTelefono INT,
    IN inputCorso varchar(255),
    IN inputDipartimento VARCHAR(255)
)
BEGIN
	
    -- Controllo se l'email è già presente 
    IF (SELECT COUNT(*) FROM dbESQL.utente WHERE email = inputEmail) > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'EMAIL RISULTA GIÀ PRESENTE';
    ELSE
        -- Inserimento dell'utente nella tabella UTENTE
        INSERT INTO dbESQL.UTENTE (email, nome, cognome, telefono)
        VALUES (inputEmail, inputNome, inputCognome, inputTelefono);

        -- Inserimento dell'account nella tabella ACCOUNT
        INSERT INTO dbESQL.ACCOUNT (EMAIL_ACCOUNT, PASSWORD, TIPO_ACCOUNT)
        VALUES (inputEmail, inputPassword, 'docente');

        -- Inserimento dello studente nella tabella STUDENTE
        INSERT INTO dbESQL.DOCENTE (email, corso, dipartimento) VALUES (inputEmail, inputCorso,inputDipartimeno);
    END IF;
END ££



DELIMITER ££
CREATE  PROCEDURE dbESQL.LOGIN_ACCOUNT(IN inputEMAIL_UTENTE VARCHAR(100), IN inputPASSWORD_LOG VARCHAR(50) )
BEGIN
    IF ( (SELECT COUNT(1) FROM dbESQL.ACCOUNT WHERE EMAIL_ACCOUNT=inputEMAIL_UTENTE AND PASSWORD = inputPASSWORD_LOG ) !=1 )
    THEN
    SIGNAL SQLSTATE '40000'
			SET MESSAGE_TEXT = 'EMAIL O PASSWORD ERRATA';
	ELSE
    SELECT TIPO_ACCOUNT FROM dbESQL.ACCOUNT WHERE EMAIL_ACCOUNT= inputEMAIL_UTENTE;
	END IF;
END ££

DELIMITER $$
CREATE PROCEDURE dbESQL.VisualizzazioneTestDisponibili(IN inputEmail VARCHAR(255))
BEGIN
    SELECT * FROM Test where email = inputEmail;
END$$
DELIMITER ;

#due trigger distinti per implementare l’operazione	 cambio	 di	 stato	 un	 test.	
# Un	 test diventa	InCompletamento per	uno	studente,	quando	questi	inserisce	la	prima	risposta.	
DELIMITER //
CREATE TRIGGER dbESQL.CambioStatoTestDopoInserimentoChiusa
AFTER INSERT ON dbESQL.RISPOSTA_CHIUSA
FOR EACH ROW
BEGIN
    DECLARE numRisposte INT;
    
    -- Conta il numero di risposte per lo studente e il test specificati
    SELECT COUNT(*)
    INTO numRisposte
    FROM dbESQL.RISPOSTA_CHIUSA
    WHERE emailStudente = NEW.emailStudente AND titoloTest = (
        SELECT titoloTest
        FROM dbESQL.QUESITO_A_RISPOSTA_CHIUSA
        WHERE ID = NEW.IDQuesito
    );
    
    -- Se il numero di risposte è 1, aggiorna lo stato dello svolgimento
    IF numRisposte = 1 THEN
        UPDATE dbESQL.SVOLGIMENTO
        SET dbESQL.SVOLGIMENTO.stato = 'InCompletamento'
        WHERE titoloTest =new.titoloTest and emailStudente=new.emailStudente;
    END IF;


END;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER dbESQL.CambioStatoTestDopoInserimentoCodice
AFTER INSERT ON dbESQL.RISPOSTA_CODICE
FOR EACH ROW
BEGIN
    DECLARE numRisposte INT;
    DECLARE tipoRisposta VARCHAR(255);

    -- Determine the type of response (closed or code)
    SELECT COUNT(*)
    INTO numRisposte
    FROM dbESQL.RISPOSTA_CODICE
    WHERE emailStudente = NEW.emailStudente AND titoloTest = (
        SELECT titoloTest
        FROM dbESQL.QUESITO_DI_CODICE
        WHERE ID = NEW.IDQuesito
    );
	
    IF numRisposte = 1 THEN
        UPDATE dbESQL.SVOLGIMENTO
        SET dbESQL.SVOLGIMENTO.stato = 'InCompletamento'
        WHERE titoloTest =new.titoloTest and emailStudente=new.emailStudente;
    END IF;

END;
//
DELIMITER ;*/


# due trigger distinti per implementare	 l’operazione	 cambio	 di	 stato	 un	 test.	
# Un	 test diventa	Concluso per	uno	studente,	quando:	 

#(i)	ha	inserito	una	risposta	a	 tutti	i	quesiti	del	test
#	(ii)	tutte	le	risposte	inserite	hanno	come	esito True


DELIMITER //

CREATE TRIGGER dbESQL.ControllaRisposteChiuse
AFTER INSERT ON RISPOSTA_CHIUSA
FOR EACH ROW
BEGIN
    DECLARE totalQuesitiChiusi INT;
    DECLARE totalRisposteChiuseInserite INT;
    DECLARE totalQuesitiCodice INT;
    DECLARE totalRisposteCodiceInserite INT;

    -- Conta il numero totale di quesiti chiusi per il test
    SELECT COUNT(*) INTO totalQuesitiChiusi FROM QUESITO_A_RISPOSTA_CHIUSA WHERE titoloTest = NEW.titoloTest;
    
    -- Conta il numero di risposte chiuse inserite per lo studente e il test corrente con esito positivo
    SELECT COUNT(*) INTO totalRisposteChiuseInserite 
    FROM RISPOSTA_CHIUSA JOIN RISPOSTA
    on RISPOSTA.emailStudente = RISPOSTA_CHIUSA.emailStudente and RISPOSTA.data= RISPOSTA_CHIUSA.data
    WHERE RISPOSTA_CHIUSA.titoloTest = NEW.titoloTest and esito = true;

    -- Conta il numero totale di quesiti di codice per il test
    SELECT COUNT(*) INTO totalQuesitiCodice FROM QUESITO_DI_CODICE WHERE titoloTest = NEW.titoloTest;
    
    -- Conta il numero di risposte di codice inserite per lo studente e il test corrente
    SELECT COUNT(*) INTO totalRisposteCodiceInserite 
    FROM RISPOSTA_CODICE join RISPOSTA 
    on RISPOSTA.emailStudente = RISPOSTA_CODICE.emailStudente and RISPOSTA.data= RISPOSTA_CODICE.data
    WHERE RISPOSTA_CODICE.titoloTest = NEW.titoloTest and esito=true;

    -- Verifica se sono state inserite tutte le risposte per entrambi i tipi di quesiti
    IF totalRisposteChiuseInserite = totalQuesitiChiusi AND totalRisposteCodiceInserite = totalQuesitiCodice THEN
        -- Aggiorna lo stato del test se tutte le risposte sono state inserite
        UPDATE SVOLGIMENTO
        SET stato = 'Concluso'
        WHERE titoloTest = NEW.titoloTest and emailStudente = NEW.emailStudente;
    END IF;
END //

DELIMITER ;

DELIMITER //

CREATE TRIGGER dbESQL.ControllaRisposteCodice
AFTER INSERT ON RISPOSTA_CODICE
FOR EACH ROW
BEGIN
    DECLARE totalQuesitiChiusi INT;
    DECLARE totalRisposteChiuseInserite INT;
    DECLARE totalQuesitiCodice INT;
    DECLARE totalRisposteCodiceInserite INT;
-- Conta il numero totale di quesiti chiusi per il test
    SELECT COUNT(*) INTO totalQuesitiChiusi FROM QUESITO_A_RISPOSTA_CHIUSA WHERE titoloTest = NEW.titoloTest;
    
    -- Conta il numero di risposte chiuse inserite per lo studente e il test corrente con esito positivo
    SELECT COUNT(*) INTO totalRisposteChiuseInserite 
    FROM RISPOSTA_CHIUSA JOIN RISPOSTA
    on RISPOSTA.emailStudente = RISPOSTA_CHIUSA.emailStudente and RISPOSTA.data= RISPOSTA_CHIUSA.data
    WHERE RISPOSTA_CHIUSA.titoloTest = NEW.titoloTest and esito = true;

    -- Conta il numero totale di quesiti di codice per il test
    SELECT COUNT(*) INTO totalQuesitiCodice FROM QUESITO_DI_CODICE WHERE titoloTest = NEW.titoloTest;
    
    -- Conta il numero di risposte di codice inserite per lo studente e il test corrente
    SELECT COUNT(*) INTO totalRisposteCodiceInserite 
    FROM RISPOSTA_CODICE join RISPOSTA 
    on RISPOSTA.emailStudente = RISPOSTA_CODICE.emailStudente and RISPOSTA.data= RISPOSTA_CODICE.data
    WHERE RISPOSTA_CODICE.titoloTest = NEW.titoloTest and esito=true;

    -- Verifica se sono state inserite tutte le risposte per entrambi i tipi di quesiti
    IF totalRisposteChiuseInserite = totalQuesitiChiusi AND totalRisposteCodiceInserite = totalQuesitiCodice THEN
        -- Aggiorna lo stato del test se tutte le risposte sono state inserite
        UPDATE SVOLGIMENTO
        SET stato = 'Concluso'
        WHERE titoloTest = NEW.titoloTest and emailStudente = NEW.emailStudente;
    END IF;
END //
DELIMITER ;

#trigger per	 implementare	 l’operazione	 cambio	 di	 stato	 un	 test.	 Un	 test	
#diventa	 Concluso per	 TUTTI gli studenti quando il	 docente	 setta	 il	 campo	
#VisualizzaRisposte		a	True per quel	test.	

DELIMITER //
CREATE TRIGGER dbESQL.CambioStatoTest
AFTER UPDATE ON TEST
FOR EACH ROW
BEGIN
    -- Verifica se il campo VisualizzaRisposte è stato impostato su True
    IF OLD.VisualizzaRisposte = 0 AND NEW.VisualizzaRisposte = 1 THEN
        -- Aggiorna lo stato del test a 'Concluso' per tutti gli studenti
        UPDATE SVOLGIMENTO
        SET stato = 'Concluso'
        WHERE titoloTest = NEW.titolo;
    END IF;
END //

DELIMITER ;


#NON E COSI, NON è QUANDO INSERISCO ATTRIBUTO MA QUANDO INSERISCO UNA NUOVA RIGA.. COME FARE???
/*DELIMITER //

CREATE TRIGGER dbESQL.IncrementaNumRighe
AFTER INSERT ON ATTRIBUTO
FOR EACH ROW
BEGIN
    UPDATE TABELLA_DI_ESERCIZIO
    SET num_righe = num_righe + 1
    WHERE nome = NEW.nomeTabella AND emailDocente = NEW.emailDocente;
END;
//

DELIMITER ;*/

/*OPERAZIONI DOCENTE*/

DELIMITER //

CREATE PROCEDURE dbESQL.InserisciTabellaDiEsercizio(
    IN inputNomeTabella VARCHAR(255),
    IN inputEmailDocente VARCHAR(255),
    IN metaDati TEXT,
    -- Una stringa che contiene tutti i metadati della tabella di esercizio separati da #
     -- (Assumendo che i metadati siano forniti come una stringa in un formato specifico)
    -- Esempio di formato: "attributo1#tipo1#primaria# " 
		
	-- LO SPAZIO SI USA SOLO TRA ATTRIBUTO PRECEDENTE E SUCCESSIVO
      #ATTENTI! AL POSTO DI PRIMARIA CI VA TRUE O FALSE
      
    /*'id#INT#true# nome#char#false# anni#INT#false#'*/
    
    
    IN integritaReferenziale TEXT -- Una stringa che contiene i vincoli di integrità referenziale separati da #
    
    -- 'attrib1#attrib2#tab2#'
    #dove attrib1->attrib2
   
)
BEGIN
    DECLARE attributo VARCHAR(255);
    DECLARE tipoAttributo VARCHAR(255);
    DECLARE prim BOOLEAN;
    DECLARE delim CHAR(1);
    DECLARE pos INT;
    DECLARE lunghezza INT;
    DECLARE attributoRiferimento1 VARCHAR(255);
    DECLARE attributoRiferimento2 VARCHAR(255);
    DECLARE TabellaRif2 VARCHAR(255);
    
    -- Inserisci la nuova tabella di esercizio
    INSERT INTO TABELLA_DI_ESERCIZIO (nome, emailDocente, data, num_righe)
    VALUES (inputNomeTabella, inputEmailDocente, NOW(), 0);
    
    -- Creazione della tabella con gli attributi specificati
    SET @sql = CONCAT('CREATE TABLE ', inputNomeTabella, ' (');
    
    -- Inserisci i metadati nella tabella ATTRIBUTO
    SET delim = '#';
    SET pos = 1;
    SET lunghezza = LENGTH(metaDati);
    
    WHILE pos <= lunghezza DO
        SET attributo = SUBSTRING(metaDati, pos, LOCATE(delim, metaDati, pos) - pos);
        SET pos = LOCATE(delim, metaDati, pos) + 1;
        
        SET tipoAttributo = SUBSTRING(metaDati, pos, LOCATE(delim, metaDati, pos) - pos);
        SET pos = LOCATE(delim, metaDati, pos) + 1;
        
        -- Imposta la variabile prim come TRUE se il valore è "true" o FALSE altrimenti
        IF LOWER(SUBSTRING(metaDati, pos, LOCATE(delim, metaDati, pos) - pos)) = 'true' THEN
            SET prim = TRUE;
        ELSE
            SET prim = FALSE;
        END IF;
        SET pos = LOCATE(delim, metaDati, pos) + 1;
        
        -- Aggiungi l'attributo alla definizione della tabella
        SET @sql = CONCAT(@sql, attributo, ' ', tipoAttributo);
        IF prim THEN
            SET @sql = CONCAT(@sql, ' PRIMARY KEY');
        END IF;
        SET @sql = CONCAT(@sql, ', ');
        
           -- Inserisci l'attributo nella tabella ATTRIBUTO
        INSERT INTO ATTRIBUTO (nome, nomeTabella, emailDocente, tipo, primaria)
        VALUES (attributo, inputNomeTabella, inputEmailDocente, tipoAttributo, prim);
        
        SET pos = pos + 1;
    END WHILE;
    
    -- Aggiungi i vincoli di integrità referenziale alla definizione della tabella
    SET delim = '#';
    SET pos = 1;
    SET lunghezza = LENGTH(integritaReferenziale);
    
    WHILE pos <= lunghezza DO
        SET attributoRiferimento1 = SUBSTRING(integritaReferenziale, pos, LOCATE(delim, integritaReferenziale, pos) - pos);
        SET pos = LOCATE(delim, integritaReferenziale, pos) + 1;
        
        SET attributoRiferimento2 = SUBSTRING(integritaReferenziale, pos, LOCATE(delim, integritaReferenziale, pos) - pos);
        SET pos = LOCATE(delim, integritaReferenziale, pos) + 1;
        
        SET TabellaRif2 = SUBSTRING(integritaReferenziale, pos, LOCATE(delim, integritaReferenziale, pos) - pos);
        SET pos = LOCATE(delim, integritaReferenziale, pos) + 1;
        
        -- Aggiungi il vincolo di integrità referenziale alla definizione della tabella
        SET @sql = CONCAT(@sql, ' FOREIGN KEY (', attributoRiferimento1, ') REFERENCES ', TabellaRif2, '(', attributoRiferimento2, '), ');
        
         -- Inserisci i vincoli di integrità referenziale nella tabella INTEGRITA_REFERENZIALE
        INSERT INTO INTEGRITA_REFERENZIALE (Attributo1, Attributo2, Tabella1, Tabella2, emailDocente1, emailDocente2)
        VALUES (attributoRiferimento1, attributoRiferimento2, inputNomeTabella, TabellaRif2,inputEmailDocente, inputEmailDocente);
        
        SET pos = pos + 1;
    END WHILE;
    
    -- Rimuovi l'ultima virgola e aggiungi la parentesi chiusa per completare la definizione della tabella
    SET @sql = LEFT(@sql, LENGTH(@sql) - 2);
    SET @sql = CONCAT(@sql, ');');
    
    -- Esegui la query per creare la tabella
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    -- Informa che la tabella è stata inserita con successo
    SELECT 'Nuova tabella di esercizio inserita con successo!' AS Messaggio;
END;
//

DELIMITER ;


#DA RIGUARDARE CI SONO ERRORI A INSERIRE RIGA

DELIMITER $$
CREATE PROCEDURE dbESQL.InserisciRigaTabellaEsercizio(
    IN inputRiga TEXT, -- separati da # es: '1# cavallo# Girolamo#' CON SPAZIOE TRA # E SUCCESSIVA PAROLA
    # SI CONCLUDE CON # LA RIGA
    IN nomeTabella VARCHAR(255),
    IN emailDocente VARCHAR(255),
    IN NUMERO_DI_ATTRIBUTI_ATTESI INT
)
BEGIN
	DECLARE attributo VARCHAR(255);
    DECLARE lunghezza INT;
    DECLARE pos INT;
    DECLARE delim CHAR(1);
    DECLARE valore VARCHAR(255); -- Aggiunto per gestire il valore dell'attributo
    DECLARE numAttributi INT; -- Numero totale di attributi attesi
    
    -- Imposta il delimitatore per l'analisi della stringa
    SET delim = '#';
    
    -- Inizializza la posizione e la lunghezza della stringa di input
    SET pos = 1;
    SET lunghezza = LENGTH(inputRiga);
    
    
    -- Costruisci la query di inserimento dinamicamente
    SET @sql_query = CONCAT('INSERT INTO ', nomeTabella, ' VALUES (');

    
    -- Loop attraverso la stringa di input per estrarre e inserire gli attributi
    WHILE pos <= lunghezza DO 
        -- Estrai l'attributo utilizzando il delimitatore '#'
        SET attributo = SUBSTRING(inputRiga, pos, LOCATE(delim, inputRiga, pos) - pos);
        SET pos = LOCATE(delim, inputRiga, pos) + 1;
        
        -- Controlla se l'attributo può essere convertito in un intero
        IF attributo REGEXP '^[0-9]+$' THEN
            SET valore = CAST(attributo AS UNSIGNED); -- Converte l'attributo in un intero
        ELSE
            SET valore = CONCAT('\'', attributo, '\''); -- Aggiunge le virgolette per gestire le stringhe
        END IF;
        
        -- Aggiungi il valore all'istruzione SQL
        SET @sql_query = CONCAT(@sql_query, valore);
        
        -- Aggiungi la virgola se non è l'ultimo attributo
        IF pos <= lunghezza THEN
            SET @sql_query = CONCAT(@sql_query, ', ');
        END IF;
         
        -- Aggiorna la posizione per estrarre il prossimo attributo
        SET pos = pos + 1;
    END WHILE;
    
    -- Chiudi la query di inserimento
    SET @sql_query = CONCAT(@sql_query, ');');

    -- Esegui la query di inserimento
    PREPARE stmt FROM @sql_query;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    -- Messaggio di successo
    SELECT 'Riga inserita con successo!' AS Messaggio;

END$$

DELIMITER ;


#Creazione	di	nuovo	test

DELIMITER $$
CREATE PROCEDURE dbESQL.CreazioneNuovoTest(IN input_emailDocente VARCHAR(255),IN input_titolo VARCHAR(255),IN input_data DATE,
IN input_foto BLOB,IN input_visualizzaRisposte BOOLEAN)
BEGIN
    IF input_emailDocente IN (SELECT email FROM DOCENTE) THEN
        INSERT INTO TEST (titolo, data, foto, visualizzaRisposte, emailDocente)
        VALUES (input_titolo, input_data, input_foto, input_visualizzaRisposte, input_emailDocente);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'utente non è un docente. Operazione non prevista';
    END IF;
END$$
DELIMITER ;


#Creazione	di	un	nuovo	quesito	con	le	relative	risposte

DELIMITER ££
CREATE PROCEDURE dbESQL.creazioneQuesitoConRisposte(
    IN inputTitoloTest VARCHAR(255),
    IN inputNomeTabella  TEXT, #ho aggiunto nome tabella a cui si riferisce il quesito:POSSONO ESSERE PIU DI UNA
    #esForma: 'CANE#GATTO'
    IN inputDifficoltà ENUM ('Basso', 'Medio', 'Alto'),
    IN inputDescrizione TEXT,
    IN inputTipoQuesito ENUM ('Chiuso', 'Codice'),
    IN inputTesto TEXT, -- testo (possibile soluzione) per quesiti a risposta chiusa (campo di opzione), separati da #
    IN inputOpzioniCorrette TEXT,  -- Opzioni scelte dal docente come corrette per quesiti a risposta chiusa, separate da #
    # FORMA: 1,2,3... almeno posso inserirle direttamente nel campo di opzione
    IN inputSoluzione TEXT, -- Soluzione scelta dal docente per i quesiti di codice, separata da # (solo una corretta)
    IN inputEmailDocente VARCHAR(255)  -- Email del docente che crea il quesito
)
BEGIN
    DECLARE quesitoID INT;
    DECLARE i INT DEFAULT 1; 
    DECLARE risposta TEXT;
    DECLARE rispostaTesto TEXT;
    DECLARE nomiTabelle TEXT;
    
    IF inputEmailDocente IN (SELECT email FROM DOCENTE) THEN 
        IF inputTitoloTest IN (SELECT titolo FROM TEST) THEN
            -- Inserisci il nuovo quesito
            INSERT INTO QUESITO (titoloTest, numRisposte, difficoltà, descrizione) 
            VALUES (inputTitoloTest, 0, inputDifficoltà, inputDescrizione);
            
            
            -- Ottenere l'ID del quesito appena inserito
            SET quesitoID = LAST_INSERT_ID();
            
            WHILE i <= LENGTH(inputNomeTabella) - LENGTH(REPLACE(inputNomeTabella, '#', '')) + 1 DO
                        -- Estrae una singola risposta dalla stringa inputTesto
				SET nomiTabelle = SUBSTRING_INDEX(SUBSTRING_INDEX(inputNomeTabella, '#', i), '#', -1);
                         
						-- Inserisci il nuovo quesito POSSONO ESSERE PIU DI UNA
				INSERT INTO RIFERIMENTO (nomeTabella, emailDocente, IDQuesito, titoloTest) 
				VALUES (nomiTabelle, inputEmailDocente, quesitoID, inputTitoloTest);
                        
				SET i = i + 1;
			END WHILE;
            
            SET i=1;
            
            -- Inserire le risposte in base al tipo di quesito
            CASE
                WHEN inputTipoQuesito = 'Chiuso' THEN
                    -- Inserire il quesito a risposta chiusa nella tabella corrispondente
                    INSERT INTO QUESITO_A_RISPOSTA_CHIUSA (ID, titoloTest) 
                    VALUES (quesitoID, inputTitoloTest);
                    
                    WHILE i <= LENGTH(inputTesto) - LENGTH(REPLACE(inputTesto, '#', '')) + 1 DO
                        -- Estrae una singola risposta dalla stringa inputTesto
                        SET rispostaTesto = SUBSTRING_INDEX(SUBSTRING_INDEX(inputTesto, '#', i), '#', -1);
                        
						INSERT INTO OPZIONE (testo, idQuesitoChiusa, titoloTest, opzioneCorretta) 
						VALUES (rispostaTesto, quesitoID, inputTitoloTest,inputOpzioniCorrette); 
                        
                        SET i = i + 1;
                    END WHILE;
                    
                WHEN inputTipoQuesito = 'Codice' THEN
                    -- Inserire il quesito di codice nella tabella corrispondente
                    INSERT INTO QUESITO_DI_CODICE (ID, titoloTest) 
                    VALUES (quesitoID, inputTitoloTest);
                    
                    WHILE i <= LENGTH(inputSoluzione) - LENGTH(REPLACE(inputSoluzione, '#', '')) + 1 DO
                        -- Estrae una singola risposta dalla stringa input_risposte
                        SET risposta = SUBSTRING_INDEX(SUBSTRING_INDEX(inputSoluzione, '#', i), '#', -1);
                     
                     
                        -- Inserisce la soluzione per il quesito di codice
                        INSERT INTO SOLUZIONE (Sketch, IdQuesitoCodice, titoloTest) 
                        VALUES (risposta, quesitoID, inputTitoloTest);
                        
                        SET i = i + 1;
                    END WHILE;
            END CASE;
        ELSE
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il test specificato non esiste.';
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'utente non è un docente. Operazione non consentita';
    END IF;
END ££

DELIMITER ;


#Abilitare	/	disabilitare la	visualizzazione	delle risposte per	uno	specifico	test
DELIMITER $$
CREATE PROCEDURE dbESQL.VisualizzazioneRisposte(IN input_titoloTest VARCHAR(255),IN input_emailDocente VARCHAR(255),IN input_abilita BOOLEAN)
BEGIN
    IF input_emailDocente IN (SELECT email FROM DOCENTE) THEN
        
        IF input_titoloTest IN (SELECT titolo FROM TEST) THEN
      
            #Abilita/disabilita la visualizzazione delle risposte per il test di input
            UPDATE TEST SET visualizzaRisposte = input_abilita WHERE titolo = input_titoloTest;
            
            SELECT 'Operazione completata con successo.';
        ELSE
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il test specificato non esiste.';
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'utente non è un docente. Operazione non consentita.';
    END IF;
END$$

DELIMITER ;


#Inserimento di	un	messaggio
DELIMITER //

CREATE PROCEDURE dbESQL.InserisciMessaggioDocente(
    IN inputTitolo VARCHAR(255),
    IN inputTesto TEXT,
    IN inputTitoloTest VARCHAR(255),
    IN inputEmailDocenteMittente VARCHAR(255)
)
BEGIN

	IF inputEmailDocenteMittente IN (SELECT email FROM DOCENTE) THEN
        
        IF inputTitoloTest IN (SELECT titolo FROM TEST)THEN
		
        INSERT INTO MESSAGGIO (titolo, data, testo, titoloTest, emailStudenteMittente, emailDocenteMittente, emailDocenteDestinatario)
		VALUES (inputTitolo, NOW(), inputTesto, inputTitoloTest, NULL, inputEmailDocenteMittente, NULL);
	
    
		#INTUIZIONE GIUSTA????? riempire anche DESTINATARIO_STUD CON MESS INVIATO DA DOCENTE
		INSERT INTO DESTINATARIO_STU (emailStudente, titoloMessaggio, titoloTest)
		SELECT email, inputTitolo, inputTitoloTest
		FROM STUDENTE;
        
        ELSE
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il test specificato non esiste';
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'utente non è un docente. Operazione non consentita';
    END IF;
END//
DELIMITER ;


/*OPERAZIONI STUDENTE*/

DELIMITER //
CREATE PROCEDURE dbESQL.InserisciRispostaStudente(
    IN inputEmailStudente VARCHAR(255),
    IN inputTitoloTest VARCHAR(255),
    IN inputIDQuesito INT,
    IN inputTipoQuesito ENUM ('CHIUSA', 'CODICE'), -- Specifica il tipo di quesito
    IN inputTestoRisposta TEXT, -- Utilizzato solo se il quesito è di tipo 'CODICE'
    IN opzioneScelta INT-- Utilizzato solo se il quesito è di tipo 'CHIUSA', rappresenta il numero dell'opzione inserita da utente
)
BEGIN

    DECLARE esitoRISP BOOLEAN;

    IF inputEmailStudente IN (SELECT email FROM STUDENTE) THEN
        
        IF inputTitoloTest IN (SELECT titolo FROM TEST) AND inputIDQuesito IN (SELECT ID from QUESITO) THEN
                
            IF inputTipoQuesito = 'CHIUSA' THEN
                IF EXISTS(
                    SELECT 1 FROM OPZIONE 
                    WHERE idQuesitoChiusa = inputIDQuesito AND titoloTest = inputTitoloTest
				    AND FIND_IN_SET(opzioneScelta, opzioneCorretta) > 0
                    #ovvero se l'opzione scelta, è dentro text opzioneCorretta 
                ) THEN
                    SET esitoRISP = TRUE;
                ELSE
                    SET esitoRISP = FALSE;
				END IF;
				
            -- Inserimento dell'esito nella tabella RISPOSTA
				INSERT INTO RISPOSTA (data, emailStudente, esito)
				VALUES (NOW(), inputEmailStudente, esitoRISP);
                
                IF NOT EXISTS (SELECT * FROM SVOLGIMENTO WHERE titoloTest = inputTitoloTest AND emailStudente = inputEmailStudente) THEN
    -- Se è la prima risposta, imposta sia la data della prima risposta che quella dell'ultima risposta
				INSERT INTO SVOLGIMENTO (titoloTest, emailStudente, dataPrimaRisposta, dataUltimaRisposta)
				VALUES (inputTitoloTest, inputEmailStudente, NOW(), NOW());
				ELSE
    -- Se non è la prima risposta, aggiorna solo la data dell'ultima risposta
				UPDATE SVOLGIMENTO
				SET dataUltimaRisposta = NOW()
				WHERE titoloTest = inputTitoloTest AND emailStudente = inputEmailStudente;
				END IF;
                
                -- Inserimento dell'esito e della risposta nella tabella RISPOSTA_CHIUSA
                INSERT INTO RISPOSTA_CHIUSA (data, IDQuesito, titoloTest, emailStudente, numerazioneOpzione)
                VALUES (NOW(), inputIDQuesito, inputTitoloTest, inputEmailStudente, opzioneScelta);
                
				#NUM_RISPOSTE DI QUESITO CHE DEVONO ESSERE INCREMENTATE
				UPDATE QUESITO
				SET QUESITO.NumRisposte = QUESITO.NumRisposte + 1
				WHERE QUESITO.ID = inputIDQuesito;
               
            ELSEIF inputTipoQuesito = 'CODICE' THEN
                IF EXISTS (
                    SELECT 1 FROM SOLUZIONE 
                    WHERE ID = inputIDQuesito AND titoloTest = inputTitoloTest AND LOWER(Sketch) = LOWER(inputTestoRisposta)
                ) THEN
                    SET esitoRISP = TRUE;
                ELSEIF EXISTS (
                    SELECT 1 FROM SOLUZIONE 
                    WHERE ID = inputIDQuesito AND titoloTest = inputTitoloTest AND LOWER(Sketch) != LOWER(inputTestoRisposta)
                ) THEN
                    SET esitoRISP = FALSE;
				ELSE 
					SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il test o il quesito specificato non esiste/ il test è concluso';
                END IF;
                
            -- Inserimento dell'esito nella tabella RISPOSTA
				INSERT INTO RISPOSTA (data, emailStudente, esito)
				VALUES (NOW(), inputEmailStudente, esitoRISP);
                
                 
				IF NOT EXISTS (SELECT * FROM SVOLGIMENTO WHERE titoloTest = inputTitoloTest AND emailStudente = inputEmailStudente) THEN
    -- Se è la prima risposta, imposta sia la data della prima risposta che quella dell'ultima risposta
				INSERT INTO SVOLGIMENTO (titoloTest, emailStudente, dataPrimaRisposta, dataUltimaRisposta)
				VALUES (inputTitoloTest, inputEmailStudente, NOW(), NOW());
				ELSE
    -- Se non è la prima risposta, aggiorna solo la data dell'ultima risposta
				UPDATE SVOLGIMENTO
				SET dataUltimaRisposta = NOW()
				WHERE titoloTest = inputTitoloTest AND emailStudente = inputEmailStudente;
				END IF;
                
                -- Inserimento dell'esito e della risposta nella tabella RISPOSTA_CODICE
                INSERT INTO RISPOSTA_CODICE (data, IDQuesito, titoloTest, emailStudente, testoRisposta)
                VALUES (NOW(), inputIDQuesito, inputTitoloTest, inputEmailStudente, inputTestoRisposta);
			
                
                
				#NUM_RISPOSTE DI QUESITO CHE DEVONO ESSERE INCREMENTATE
				UPDATE QUESITO
				SET QUESITO.NumRisposte = QUESITO.NumRisposte + 1
				WHERE QUESITO.ID = inputIDQuesito;
            
            
            END IF;
        ELSE
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il test o il quesito specificato non esiste';
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'email specificata non appartiene a uno studente';
    END IF;
END //
DELIMITER;

DELIMITER //

CREATE PROCEDURE dbESQL.VisualizzaEsitoStudente(
    IN inputEmailStudente VARCHAR(255),
    IN inputTitoloTest VARCHAR(255),
    IN inputIDQuesito INT,
    IN inputData DATETIME
)
BEGIN
	
    IF inputEmailStudente IN (SELECT email FROM STUDENTE) THEN
    
    IF inputIDQuesito IN (select IDquesito from RISPOSTA_CHIUSA where emailStudente = inputEmailStudente) THEN
		-- Visualizzazione dell'esito della risposta chiusa
        SELECT esito
        FROM RISPOSTA JOIN RISPOSTA_CHIUSA ON RISPOSTA.data = RISPOSTA_CHIUSA.data AND RISPOSTA.emailStudente = RISPOSTA_CHIUSA.emailStudente
        WHERE RISPOSTA.data = inputData;
    
    ELSEIF inputIDQuesito IN (select IDquesito from RISPOSTA_CODICE where emailStudente = inputEmailStudente) THEN
		-- Visualizzazione dell'esito della risposta chiusa
        SELECT esito
        FROM RISPOSTA JOIN RISPOSTA_CODICE ON RISPOSTA.data = RISPOSTA_CODICE.data AND RISPOSTA.emailStudente = RISPOSTA_CODICE.emailStudente
        WHERE RISPOSTA.data = inputData;
    ELSE
        -- Se lo studente non ha risposto al quesito, restituisci un messaggio di avviso
        SELECT 'Lo studente non ha risposto al quesito specificato del test.';
	END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'email specificata non appartiene a uno studente';
    END IF;
END //

DELIMITER ;


#Inserimento di	un	messaggio
DELIMITER //

CREATE PROCEDURE dbESQL.InserisciMessaggioStudente(
    IN inputTitolo VARCHAR(255),
    IN inputTesto TEXT,
    IN inputTitoloTest VARCHAR(255),
    IN inputEmailStudenteMittente VARCHAR(255),
    IN inputEmailDocenteDestinatario VARCHAR(255)
)
BEGIN

	IF inputEmailStudenteMittente IN (SELECT email FROM STUDENTE) AND inputEmailDocenteDestinatario IN (SELECT email FROM DOCENTE) THEN
        
        IF inputTitoloTest IN (SELECT titolo FROM TEST)THEN
		
        INSERT INTO MESSAGGIO (titolo, data, testo, titoloTest, emailStudenteMittente, emailDocenteMittente, emailDocenteDestinatario)
		VALUES (inputTitolo, NOW(), inputTesto, inputTitoloTest, inputEmailStudenteMittente, NULL,inputEmailDocenteDestinatario);
        
        ELSE
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il test specificato non esiste';
        END IF;
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'L\'utente destinatario non è un docente o il mittente non è uno studente. Operazione non consentita';
    END IF;
END//
DELIMITER ;

CREATE VIEW dbESQL.ClassificaStudentiTestCompletati AS
SELECT s.codice AS CodiceStudente, COUNT(titolo) AS NumeroTestCompletati
FROM dbESQL.STUDENTE s
LEFT JOIN dbESQL.SVOLGIMENTO sv ON sv.emailStudente = s.email 
LEFT JOIN dbESQL.TEST on  TEST.titolo = sv.titoloTest
WHERE sv.stato = 'Concluso'
GROUP BY s.codice
ORDER BY NumeroTestCompletati DESC;

/*
SELECT CodiceStudente, NumeroTestCompletati
FROM dbESQL.ClassificaStudentiTestCompletati;*/

CREATE VIEW  dbESQL.ClassificaStudentiRisposteCorrette AS
SELECT s.codice AS CodiceStudente, 
       SUM(r.esito) / COUNT(*) * 100 AS PercentualeRisposteCorrette
FROM  dbESQL.STUDENTE s
LEFT JOIN  dbESQL.RISPOSTA r ON s.email = r.emailStudente
GROUP BY s.codice
ORDER BY PercentualeRisposteCorrette DESC;

/*SELECT CodiceStudente, PercentualeRisposteCorrette
FROM ClassificaStudenti;*/


CREATE VIEW dbESQL.ClassificaQuesiti AS
SELECT q.ID AS IDQuesito, q.titoloTest AS TitoloTest, COUNT(rc.data) + COUNT(ra.data) AS NumeroRisposte
FROM dbESQL.QUESITO q
LEFT JOIN dbESQL.RISPOSTA_CHIUSA rc ON q.ID = rc.IDQuesito
LEFT JOIN dbESQL.RISPOSTA_CODICE ra ON q.ID = ra.IDQuesito
GROUP BY q.ID, q.titoloTest
ORDER BY NumeroRisposte DESC;

/*SELECT IDQuesito, TitoloTest, NumeroRisposte
FROM dbESQL.ClassificaQuesiti;*/

