module.exports = function(cron){
    cron.schedule('* * * * *', () => {
        console.log('yey! running a task every minute');
    });
};