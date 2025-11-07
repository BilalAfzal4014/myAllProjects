const express = require("express");
const path = require("path");
const HttpErrorResponseHandler = require("../../errors/handlers/http-error-response-handler");
const BusinessError = require('../../errors/business-error');
const ErrorTypes = require('../../errors/error-types');
const basicAuth = require("./basic-auth/basic-auth");
const expressLayouts = require("express-ejs-layouts");
const EjsHelper = require("../../helpers/ejs-helper")

const applyMiddlewares = (app) => {
    app.use(express.urlencoded({extended: false}));
    app.use(express.json());
    app.use(cors());
    //app.use(basicAuth());
};

const setLayoutEngine = (app) => {
    app.use(expressLayouts);
    app.set('layout', EjsHelper.getDefaultTemplateLayout());
}

const setTemplatingEngine = (app) => {
    app.set('views engine', process.env.templating_engine);
    app.set('views', EjsHelper.getViewsAbsolutePath());
}

const setPublicAssets = (app) => {
    app.use(express.static( path.join(__dirname, '../../public') ) );
}

const applyErrorMiddlewares = (app) => {
    app.use(pathNotFound());
    app.use(serverErrorMiddleware());
};

const cors = () => (req, res, next) => {

    res.header("Access-control-allow-origin", (process.env.ALLOWED_ORIGIN || "*"));
    res.header("Access-control-allow-headers", "authorization, content-type, auth");

    if (req.method === "OPTIONS") {
        res.header("Access-control-allow-methods", "GET, PUT, POST, PATCH, DELETE");
        return res.status(200).json();
    }

    next();
};


const pathNotFound = () => (req, res, next) => {
    const error = new BusinessError(
        ErrorTypes.NOT_FOUND,
        "Path doesn't exist", [],
        "BusinessError from pathNotFound middleware"
    );
    next(error);
};

const serverErrorMiddleware = () => (error, req, res, next) => {
    return new HttpErrorResponseHandler(res).handleError(error);
};

module.exports = {
    applyMiddlewares,
    applyErrorMiddlewares,
    setTemplatingEngine,
    setLayoutEngine,
    setPublicAssets
};
