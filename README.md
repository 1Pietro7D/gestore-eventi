Progetta e implementa un sistema di gestione di eventi online che permetta agli utenti di registrarsi, visualizzare i dettagli degli eventi, e agli amministratori di creare, modificare ed eliminare eventi. Il sistema deve essere progettato pensando alla scalabilità e all'applicazione di principi di clean code e architettura solida.

**Requisiti Funzionali:**

1.  **Gestione Utenti:**
    * Registrazione e login degli utenti con validazione robusta (inclusa la gestione di email univoche e password sicure).
    * Diversi ruoli utente: "utente semplice" e "amministratore".
    * Gli utenti semplici possono visualizzare gli eventi disponibili e registrarsi ad essi.
    * Gli amministratori possono creare, modificare, eliminare eventi e gestire gli utenti.

2.  **Gestione Eventi:**
    * Creazione di eventi con dettagli quali titolo, descrizione, data e ora, luogo, numero massimo di partecipanti.
    * Visualizzazione degli eventi disponibili, con possibilità di filtrare per data, categoria (da definire), e/o luogo.
    * Dettaglio di un singolo evento con tutte le informazioni e la lista dei partecipanti registrati (solo per amministratori).
    * Possibilità per gli utenti semplici di registrarsi a un evento (verificando il numero massimo di partecipanti).
    * Possibilità per gli utenti semplici di annullare la propria registrazione a un evento.

3.  **Scalabilità e Performance:**
    * Il sistema deve essere progettato per gestire un numero elevato di utenti ed eventi senza degradazione significativa delle performance.
    * Implementare un sistema di caching efficace per ridurre il carico sul database.
    * Considerare l'utilizzo di tecniche di paginazione per la visualizzazione di liste di eventi e utenti.

4.  **Robustezza:**
    * Gestione degli errori in modo elegante e informativo (log degli errori, messaggi utente chiari).
    * Validazione rigorosa di tutti gli input da parte dell'utente per prevenire vulnerabilità di sicurezza (SQL injection, XSS, ecc.).
    * Implementare meccanismi per prevenire race condition durante le registrazioni agli eventi (ad esempio, utilizzando transazioni a livello di database o locking ottimistico/pessimistico).

5.  **Architettura e Clean Code:**
    * Utilizzare un pattern architetturale ben definito (ad esempio, MVC, DDD, o un'architettura a microservizi concettuale, anche se in un'unica applicazione).
    * Applicare i principi SOLID nella progettazione delle classi e dei moduli.
    * Scrivere codice pulito, ben commentato e facile da manutenere.
    * Utilizzare dependency injection per gestire le dipendenze tra le classi.
    * Implementare unit test per i componenti chiave del sistema.

**Requisiti Tecnici Specifici:**

* **Linguaggio:** PHP 8.1 o superiore.
* **Database:** MySQL 8.0 o superiore.
* **Framework: NESSUN FRAMEWORK DA UTILIZZARE.
* **Gestione delle Dipendenze:** Composer.
* **Caching (opzionale ma fortemente consigliato):** Redis o Memcached.
* **Testing:** PHPUnit o Pest.

**Aspetti da Valutare:**
* **Qualità del codice:** Chiarezza, leggibilità, manutenibilità, aderenza ai principi SOLID e di clean code.
* **Architettura del sistema:** Scelta del pattern architetturale e sua implementazione, separazione delle responsabilità.
* **Gestione degli errori:** Robustezza, logging, messaggi utente.
* **Sicurezza:** Prevenzione di vulnerabilità comuni.
* **Performance e scalabilità:** Strategie di caching, gestione delle query al database, paginazione.
* **Test:** Completezza e qualità degli unit test.
* **Utilizzo del database:** Design dello schema, ottimizzazione delle query, gestione delle transazioni.

**Possibili Estensioni (Bonus):**
* Implementazione di un sistema di notifiche (via email o altro) per la conferma di registrazione o cancellazione.
* Supporto per diverse categorie di eventi.
* Integrazione con un sistema di mappe per visualizzare la posizione degli eventi.
