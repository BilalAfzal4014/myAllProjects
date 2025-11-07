const express = require('express');
const bodyParser = require('body-parser');


const app = express();
app.use(bodyParser.json());

const validationMiddleware = () => {
    return (req, res, next) => {
        if(Number.isNaN(+req.params.param1) || Number.isNaN(+req.params.param2)){
            return res.status(400).json({
                error: 'Both params should be number'
            })
       }
       next();
    }
}


app.get('/:param1/:param2', validationMiddleware(), (req, res) => {

    return res.status(200).json({
        sum: +req.params.param1 + +req.params.param2
    });
})

module.exports = app;
