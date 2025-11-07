const express = require('express');
const HttpErrorResponseHandler = require('../../errors/handlers/httpErrorResponseHandler');
const BusinessError = require('../../errors/businessError');
const ErrorTypes = require('../../errors/errorType');

const applyMiddlewares = (app) => {
    app.use(express.urlencoded({extended: false}));
    app.use(express.json());
    app.use(cors());
};

const applyErrorMiddlewares = (app) => {
    app.use(pathNotFound());
    app.use(serverErrorMiddleware());
};

const cors = () => (req, res, next) => {

    res.header('Access-control-allow-origin', (process.env.ALLOWED_ORIGIN || '*'));
    res.header('Access-control-allow-headers', 'identity, authorization, content-type, auth');

    if (req.method === 'OPTIONS') {
        res.header('Access-control-allow-methods', 'GET, PUT, POST, PATCH, DELETE');
        return res.status(200).json();
    }

    next();
};


const pathNotFound = () => (req, res, next) => {
    const error = new BusinessError(
        ErrorTypes.NOT_FOUND,
        `Path doesn't exist`, [],
        'BusinessError = pathNotFound middleware'
    );
    next(error);
};

const serverErrorMiddleware = () => (error, req, res, _) => {
    return new HttpErrorResponseHandler(res).handleError(error);
};

module.exports = {
    applyMiddlewares,
    applyErrorMiddlewares
};