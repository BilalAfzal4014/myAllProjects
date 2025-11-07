const concurrencyQueue = []; //need this because sqlite is failing to wait for query on locked rows/table.
module.exports = concurrencyQueue;