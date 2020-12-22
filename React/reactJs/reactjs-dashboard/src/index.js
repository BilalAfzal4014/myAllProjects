import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import App from './App';
import * as serviceWorker from './serviceWorker';

import {BrowserRouter} from 'react-router-dom';
import 'axios-progress-bar/dist/nprogress.css'

import { loadProgressBar } from 'axios-progress-bar'

loadProgressBar()

 window.URL = "https://awr3z1xgme.execute-api.ap-south-1.amazonaws.com/staging/"
//window.URL = "https://console.terravirtua.io/"

ReactDOM.render(
    <BrowserRouter>
        <App/>
    </BrowserRouter>,
    document.getElementById('root'));

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
serviceWorker.unregister();
