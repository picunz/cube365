Ciao Boris,
sto riprovando codice  e aggiungendo modifiche
ti riassumo le funzionalita' base per fare il punto

al primo utilizzo, senza nessuna configurazione, esp3 parte in modalita access point e scrive su display le credenziali della rete, ci si collega col cellulare e viene visualizzata la pagina in cui inserire nome della rete e password (in realta' di 2 reti cosi lo si puo' utilizzare a casa e a lavoro senza ogni volta dover riconfigurare tutto

a questo punto si collega al router e viene visualizzato ip assegnato, collegandoci con pc o cellulare a questa pagina html interna possiamo configurare il nome dell' host a cui il cubo inviera' periodicamente il suo stato (la faccia corrente)
Quest'host potra' essere un proprio sito web, un sito su cloud (esempio render.com) o anche un docker sulla propria macchina locale.
A propria volta questo host gestira' un database che manterra' la storia dei vari task effettuati giorno per giorno

Il database avra' piu' di una tabella per gestire anche piu' di un dispositivo contemporaneamente (esempio nel caso di un ufficio)
Ogni dispositivo (cubo) ha un id con cui viene identificato (mac dell esp32)

Collegandosi online alla pagina index.html di questo server e facendo login con l'id dell'esp vedremo tutte le attivita' passate, giorno per giorno e quelle in corso. E' possibile convertire questa tabella in un foglio excel per eventuali future statistiche

Da questa pagina si puo' navigare verso altre pagine tra cui quella in cui poter configurare il nome dei vari task ed il relativo tipo (esempio: deploy, sviluppo, bugfix...) ed ovviamente assegnare questi task alle relative facce del cubo

su database ci sara' anche una tabella di log che si potra' visionare da pagina web per esaminare i vari cambi di stato o informazioni. Questo avviene tramite una web api di log in modo da permettere log remoto senza cavo usb collegato (in modo da permettere tutte le varie rotazioni del cubo)

Ogni faccia del cubo ha un sensore ottico per rilevare la faccia corrente, ma i restanti 5 sensori che non sono 'coperti' possono essere usati come pulsanti. In questo modo posso attivare funzioni particolari a seconda se premo quello sulla faccia superiore, se ne premo 2 comtemporaneamente, se ne premo 3 contemporaneamente, se ne premo uno piu a lungo ecc.
Ad esempio premendo una volta la faccia superiore ottengo le ore e i minuti trascorsi in quella giornata su quel task
Se sollevo il cubo ottengo una statistica su lcd di tutti i task attivi e relativo tempo trascorso
Se premo 2 sensori viene visualizzato l'ip del dispositivo per potersi collegare col cellulare.
Se premo 3 sensori entro nella modalita' access point per poter riconfigurare o le credenziali delle reti a cui collegarsi

Se si preme a lungo il sensore nella faccia superiore viene abilitato il pomodoro time (da fare)

Un buzzer avvisa la pressione dei tasti ed eventuali feedback, verra' usato anche per suonare una sorta di allarme quando viene posato su una faccia sulla quale non e' impostato nessun task in modo da non usare il cubo in modo inappropriato

La faccia in cui viene coperto il display sara' quella definita Pausa di default e nella quale il cubo si ricarichera' sulla basetta di ricarica tramite contatto magnetico. In questa situazione nella faccia superiore vi sara' l'anello neopixel

L'anello neopixel si colorera' in base alla faccia scelta. Potra' essere usato per visualizzare livello batteria nella fase di ricarica


