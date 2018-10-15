# RacFiNordBot
Il bot Telegram del Rotaract Club Firenze Nord

Questo bot consente agli utenti di scoprire i prossimi eventi del
Rotaract Club Firenze Nord.

## Funzioni implementate:
- `/prossimo_evento`: per il prossimo evento
- `/prossimi_eventi`: per i prossimi tre eventi

Il bot interpreta anche frasi come "i prossimi N eventi".

Tutti gli utenti possono inviare una richiesta per accedere al calendario del club utilizzando la sintassi

    Calendario indirizzo@mail.com
inserendo il proprio indirizzo email che sarà usato come identificativo.
Tale richiesta sarà inoltrata automaticamente al segretario del club.

I soci possono richiedere informazioni sulle quote da pagare usando la funzione
`/quote_annuali`.

Il presidente ed il segretario possono spammare un messaggio a

- tutti gli utenti
- tutti i soci
- tutti i membri del direttivo

utilizzando la sintassi

    Spamma @ tutti|soci|direttivo:
    messaggio da spammare