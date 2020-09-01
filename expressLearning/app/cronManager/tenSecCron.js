module.exports = function(cron){
    let count = 0;
    cron.schedule('*/10 * * * * *', () => {
        console.log('yey! running a task every ten seconds', count);
        count++;
    });
};