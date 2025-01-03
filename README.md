# Documentazione Tecnica - "Sluke" Apllicativo web

## Componenti del Gruppo
- **Luca Giulio Stabile** – 09821A  -  lucagiulio.stabile@studenti.unimi.it 
- **Mateo Simon Sonnergren** - M10500A - mateosimon.sonnergren@studenti.unimi.it 
- **Marius Trandafir** - 12656A - marius.trandafir@studenti.unimi.it

## Indice
1. [Introduzione](#introduzione)
2. [Dominio Applicativo](#dominio-applicativo)
3. [Prodotti Offerti](#prodotti-offerti)
4. [Requisiti Funzionali](#Requisiti-Funzionali)
5. [Configurazione del Database](#configurazione-del-Database)
6. [Schema ER e Vincoli della Base di Dati](#schema-er)
7. [Tecnologie Utilizzate](#tecnologie-utilizzate)

## Introduzione
Il progetto consiste nella realizzazione di una piattaforma web per un produttore musicale professionista. La piattaforma serve come vetrina professionale per il portfolio dell'artista e come e-commerce per la vendita di prodotti digitali come beat, sample pack, drum kit, plugin, servizi di mix & master e ghost production. Questa documentazione tecnica descrive in dettaglio l'architettura, le funzionalità, le tecnologie utilizzate e le modalità di utilizzo dell'applicativo.

La piattaforma è progettata per offrire un'esperienza utente fluida e intuitiva, consentendo agli artisti e ai produttori musicali di accedere facilmente ai contenuti e ai servizi offerti. Grazie a un sistema di autenticazione sicuro e a un'interfaccia amministrativa completa, il produttore può gestire i propri prodotti, gli ordini e gli utenti in modo efficiente.

## Dominio Applicativo

### Contesto Generale
Il settore della produzione musicale moderna è fortemente digitalizzato, con un mercato in crescita per quanto riguarda la vendita di contenuti digitali come beat, sample pack e servizi di post-produzione. La piattaforma web sviluppata in questo progetto risponde a questa esigenza, offrendo agli utenti un'ampia gamma di prodotti e servizi digitali.

### Walkthrough dell'Applicativo

#### Landing Page e Portfolio:

La landing page accoglie gli utenti con una presentazione professionale del produttore musicale. Include informazioni biografiche, una descrizione dei servizi offerti e collegamenti ai social media.
La sezione portfolio mostra i lavori realizzati dal produttore, inclusi progetti musicali, collaborazioni e remix. Ogni progetto può essere accompagnato da una breve descrizione e da un player audio per l'ascolto dei sample.

#### Sistema di Autenticazione:

Gli utenti possono registrarsi sulla piattaforma creando un account con email, username e password. Dopo aver effettuato la registrazione, possono accedere utilizzando le proprie credenziali.
Una volta autenticati, gli utenti possono accedere a funzionalità esclusive come l'acquisto di prodotti, la gestione del profilo e l'accesso a contenuti gratuiti.

#### Catalogo Prodotti Digitali:

Il catalogo prodotti include una varietà di contenuti digitali suddivisi in diverse categorie:
Beat/Instrumental: Composizioni musicali complete pronte per essere utilizzate in progetti musicali.
Drum Kit: Pacchetti digitali contenenti campioni di batteria e effetti.
Sample Pack: Collezioni di loop melodici utilizzabili in produzioni musicali.
Plugin: Software per la manipolazione o la generazione del suono.
Mix & Master: Servizi di mixing e mastering per la finalizzazione professionale dei brani.
Ghost Production: Produzione musicale accreditata a un altro artista o DJ invece che al produttore originale.
Ogni prodotto ha una pagina dettagliata con descrizione, prezzo, preview audio e opzioni di acquisto.

#### Sistema di Prezzi e Licenze:

I prodotti digitali sono offerti a diversi livelli di prezzo e licenza. Gli utenti possono scegliere tra licenze base, premium ed esclusive, a seconda delle proprie esigenze.
I prezzi sono chiaramente indicati per ogni prodotto, e gli utenti possono aggiungere i prodotti desiderati al carrello per procedere con l'acquisto.

#### Carrello e Pagamenti:

Il sistema di carrello permette agli utenti di gestire i prodotti selezionati per l'acquisto. Possono aggiungere, rimuovere e modificare la quantità dei prodotti nel carrello.
Una volta pronti per l'acquisto, gli utenti possono procedere al checkout, dove verranno richiesti i dettagli di pagamento. I metodi di pagamento sono sicuri e protetti, garantendo una transazione senza problemi.

#### Area Amministrativa (Produttore):

L'area amministrativa è accessibile solo agli utenti con privilegi di amministratore. Da qui, il produttore può gestire tutti gli aspetti della piattaforma:
Gestione Utenti: Visualizzazione e gestione degli utenti registrati, inclusa l'autorizzazione e il blocco degli account.
Gestione Prodotti: Aggiunta, aggiornamento e rimozione di prodotti digitali dal catalogo.
Statistiche Vendite: Monitoraggio delle vendite dei prodotti e analisi delle performance.
Gestione Ordini: Visualizzazione e gestione degli ordini effettuati dagli utenti.
Upload Contenuti: Caricamento di nuovi file audio e contenuti digitali per i prodotti.

#### Footer e Contatti:

Il footer della piattaforma include collegamenti ai social media del produttore, informazioni legali e sulla privacy, e un modulo di contatto per richieste di supporto o informazioni.
Inoltre, è presente una sezione FAQ per rispondere alle domande più comuni degli utenti.

## Prodotti Offerti

### Tipi di Prodotti

1. **Beat/Instrumental**
   - Una composizione solitamente venduta a cantanti o rapper per essere cantata.

2. **Drum Kit**
   - Un pacchetto digitale per produttori che contiene campioni di batteria o effetti.

3. **Sample Pack**
   - Un pacchetto digitale per produttori che contiene loop melodici.

4. **Plugin**
   - Un software utilizzato per la manipolazione o la generazione del suono.

5. **Mix & Master**
   - **Mixing**: Processo di combinazione di tutte le tracce individuali di una registrazione e regolazione dei loro livelli, panning e effetti.
   - **Mastering**: Processo di preparazione del mix finale per la distribuzione.

6. **Ghost Production**
   - La produzione fantasma è quando un produttore crea una traccia musicale che viene accreditata a un altro artista o DJ invece che a se stesso.

### Utenti Target

1. Artisti e Musicisti
   - In cerca di beat per i propri progetti
   - Necessitano di servizi di mixing e mastering

2. Produttori Musicali
   - Interessati all'acquisto di sample pack
   - Potrebbero richiedere servizi di post-produzione

3. Content Creator
   - Necessitano di musica per i propri contenuti
   - Interessati principalmente ai beat

## Requisiti Funzionali

### Funzionalità Obbligatorie

**Le funzionalità obbligatorie sono state sviluppate da tutto il gruppo.**

1. Landing Page e Portfolio
   - Sezione di presentazione del produttore
   - Portfolio dei lavori realizzati
   - Player audio per l'ascolto dei sample
   - Showcase dei progetti più significativi
   - Sezione testimonial/feedback clienti

2. Sistema di Autenticazione
   - Registrazione nuovo utente
   - Login utente esistente

3. E-commerce
   - Catalogo prodotti digitali
   - Pagine dettaglio prodotto con preview audio
   - Sistema di prezzi e licenze

4. Area Amministrativa (Produttore)
   - Gestione utenti
   - Gestione prodotti
   - Upload nuovi contenuti

5. Footer e Contatti
   - Collegamenti social media
   - Informazioni legali e privacy

### Funzionalità Opzionali

**Le funzionalità opzionali sono state sviluppate da Luca Giulio Stabile.**

1. Sistema Carrello
   - Aggiunta/rimozione prodotti
   - Calcolo totale
   - Gestione quantità
   - Finalizzazione ordine [in sviluppo]

2. Area Download Gratuiti [in sviluppo]
   - Sezione dedicata ai contenuti gratuiti
   - Sistema di download per utenti registrati
     
### Funzionalità Future

1. Area Download Gratuiti
2. Differenziazione prodotto effettivo e preview audio, sia in frontend dashboard, sia in db.


## Configurazione del Database
**Per far funzionare l’account è necessario creare un utente con tutti i privilegi sul database che ha lo stesso nome utente e password forniti, altrimenti la connessione al db per modificare le dashboard sarà negata.

Il file SQL si trova nella cartella DBimport.

Se il DB NON ha il nome “prweb1”, modificarlo nella funzione `dbConnect` in `BACKEND/utility.php` alla linea 88.**

### Credenziali Admin per il Professor Mesiti
È stato previsto un account admin per il Professor Mesiti in modo da fargli testare le funzionalità admin in prima persona.
Credenziali:
- **User**: MMesiti
- **Password**: PwebMesiti1!

## Schema ER

### Entità e Attributi

1. User
   - username (PK): varchar(20)
   - mail: varchar(255)
   - pwd: varchar(255)
   - admin: tinyint(1)
   - authorized: tinyint(1)
   - blocked: tinyint(1)

2. Product
   - id (PK): varchar(10)
   - title: varchar(50)
   - type_id (FK): int
   - descr: text
   - bpm: int (nullable)
   - tonality: varchar(3) (nullable)
   - genre: varchar(20) (nullable)
   - num_sample: int (nullable)
   - num_tracks: int (nullable)
   - audiopath: varchar(255) (nullable)

3. Type
   - id (PK): int
   - name: varchar(20)
   - descr: text

4. List_Head
   - id (PK): int
   - descr: text

5. List_Prices
   - id (PK): int
   - price: float
   - date: datetime
   - prod_id (FK): varchar(10)
   - list_id (FK): int

6. Order_Head
   - id (PK): int
   - username (FK): varchar(20)
   - date: datetime
   - confirmed: tinyint(1)

7. Order_Detail
   - order_id (PK, FK): int
   - prod_id (PK, FK): varchar(10)
   - cur_price: float
   - quantity: int

### Relazioni

1. Product - Type (N:1)
   - Ogni prodotto appartiene a un tipo
   - Un tipo può essere associato a più prodotti

2. Product - List_Prices (1:N)
   - Ogni prodotto può avere multiple variazioni di prezzo nel tempo
   - Ogni prezzo è associato a un solo prodotto

3. List_Prices - List_Head (N:1)
   - Ogni prezzo appartiene a un listino
   - Un listino può contenere più prezzi

4. User - Order_Head (1:N)
   - Un utente può effettuare più ordini
   - Ogni ordine appartiene a un solo utente

5. Order_Head - Order_Detail (1:N)
   - Un ordine può contenere più prodotti
   - Ogni dettaglio ordine appartiene a un solo ordine

6. Product - Order_Detail (1:N)
   - Un prodotto può essere presente in più ordini
   - Ogni dettaglio ordine si riferisce a un solo prodotto

## Vincoli della Base di Dati

### Vincoli di Integrità Referenziale
1. Product.type_id → Type.id
   - ON DELETE CASCADE
   - ON UPDATE CASCADE

2. List_Prices.prod_id → Product.id
   - ON DELETE CASCADE
   - ON UPDATE CASCADE

3. List_Prices.list_id → List_Head.id
   - ON DELETE CASCADE
   - ON UPDATE CASCADE

4. Order_Detail.order_id → Order_Head.id
   - ON DELETE CASCADE
   - ON UPDATE CASCADE

5. Order_Detail.prod_id → Product.id
   - Senza clausole CASCADE

6. Order_Head.username → User.username
   - Senza clausole CASCADE

### Vincoli di Dominio
1. User
   - admin, authorized, blocked sono booleani (tinyint(1))
   - email deve essere un indirizzo valido
   - username deve essere unico
   - La password prima del cripting deve essere lunga tra 8 e 40 caratteri, deve contenere almeno una lettera maiuscola, deve contenere almeno una lettera minuscola, deve contenere almeno un numero, deve contenere almeno uno tra i caratteri speciali consentiti (!"#$%&()*+?@^_~).

2. Product
   - bpm deve essere un numero tra 60 e 250
   - num_sample e num_tracks devono essere positivi quando specificati

3. List_Prices
   - price deve essere un numero positivo o zero
   - date non può essere nel futuro

4. Order_Head
   - confirmed è un booleano (tinyint(1))
   - date viene impostata automaticamente al timestamp corrente

5. Order_Detail
   - quantity deve essere maggiore di zero
   - cur_price deve essere non negativo

### Vincoli di Business
1. Gestione Utenti
   - Solo gli utenti autorizzati (authorized=1) possono effettuare acquisti
   - Gli utenti bloccati (blocked=1) non possono accedere al sistema
   - Gli amministratori (admin=1) hanno accesso a tutte le funzionalità

2. Gestione Prodotti
   - Ogni prodotto deve appartenere a una categoria valida (type)
   - I campi specifici (bpm, tonality, genre) sono obbligatori per i beat
   - Il campo num_sample è obbligatorio per sample pack e drum kit
   - Il campo num_track è obbligatorio per mix&master e ghostproduction

3. Gestione Ordini
   - Un ordine non confermato (confirmed=0) può essere modificato
   - Un ordine confermato non può essere modificato
   - Il prezzo corrente (cur_price) viene fissato al momento dell'inserimento nel carrello
     
## Tecnologie Utilizzate
- PHP 8.x
- MySQL 8.x
- HTML5
- CSS3
- JavaScript per la riproduzione audio e alcune chiamate al db

## Sicurezza
1. Gestione Accessi
   - Autenticazione utenti
   - Sessioni sicure
   - Protezione area amministrativa

2. Protezione Dati
   - Crittografia password
   - Validazione input
   - Prevenzione SQL injection

3. Protezione Contenuti
   - Protezione file audio
   - Download sicuri (non completo)

"# ProgettoPweb" 
