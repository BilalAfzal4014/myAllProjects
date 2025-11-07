const axios = require('axios');


const num = 10;
for (let i = 0; i < num; i++) {
    axios.post('http://localhost:3001/balances/deposit/1', {
        balanceToDeposit: 1
    })
        .then((res) => {
            console.log('success')
        })
        .catch((error) => {
            console.log('error')
        })
}

