import React from 'react';
import ReactDOM from 'react-dom';
import Application from './components/Application';

ReactDOM.render(
    <Application loadPath={appConfig.loadPath} testPath={appConfig.testPath} />,
    document.getElementById('application')
);