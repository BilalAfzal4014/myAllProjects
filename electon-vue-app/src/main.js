import {app, BrowserWindow, ipcMain} from 'electron';
import path from 'node:path';
import started from 'electron-squirrel-startup';


if (started) {
    app.quit();
}
let ImageWindowOuter
const createWindow = () => {
    // Create the browser window.
    const mainWindow = new BrowserWindow({
        width: 800,
        height: 600,
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            //contextIsolation: true,
            //nodeIntegration: false,
        },
    });

    const imageWindow = new BrowserWindow({
        width: 400, height: 400, parent: mainWindow, show: false, webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            //contextIsolation: true,
            //nodeIntegration: false,
        }
    })

    // and load the index.html of the app.
    if (MAIN_WINDOW_VITE_DEV_SERVER_URL) {
        mainWindow.loadURL(MAIN_WINDOW_VITE_DEV_SERVER_URL);
        imageWindow.loadURL(MAIN_WINDOW_VITE_DEV_SERVER_URL + '/show-image');
    } else {
        mainWindow.loadFile(path.join(__dirname, `../renderer/${MAIN_WINDOW_VITE_NAME}/index.html`));
        console.log('MAIN_WINDOW_VITE_DEV_SERVER_URL', MAIN_WINDOW_VITE_DEV_SERVER_URL)
    }

    // Open the DevTools.
    mainWindow.webContents.openDevTools();


    imageWindow.on('close', (event) => {
        event.preventDefault()
        imageWindow.hide()
    });

    ipcMain.on('send-image', (event, args) => {
        //console.log('args - args', args);
        imageWindow.show();
        imageWindow.webContents.openDevTools();
        imageWindow.webContents.send('show-image', args);
    });

    ImageWindowOuter = imageWindow;

};

// This method will be called when Electron has finished
// initialization and is ready to create browser windows.
// Some APIs can only be used after this event occurs.
app.whenReady().then(() => {
    createWindow();

    // On OS X it's common to re-create a window in the app when the
    // dock icon is clicked and there are no other windows open.
    app.on('activate', () => {
        if (BrowserWindow.getAllWindows().length === 0) {
            createWindow();
        }
    });
});

// Quit when all windows are closed, except on macOS. There, it's common
// for applications and their menu bar to stay active until the user quits
// explicitly with Cmd + Q.
app.on('window-all-closed', () => {
    console.log('process.platform - process.platform', process.platform)
    if (process.platform !== 'darwin') {
        app.quit();
    } else {
        //console.log('ImageWindowOuter', ImageWindowOuter)
        //ImageWindowOuter.close();
        if (ImageWindowOuter) { // no need to write this check if i am applying for loop below
            ImageWindowOuter.destroy();
        }
        // Ensure the main window also gets cleaned up
        BrowserWindow.getAllWindows().forEach(win => win.destroy());
        app.quit();
    }
});

// In this file you can include the rest of your app's specific main process
// code. You can also put them in separate files and import them here.


/*
Main-to-Renderer and Renderer-to-Main Communication:

const { ipcMain } = require('electron');
ipcMain.on('message-to-main', (event, arg) => {
    console.log(arg); // Log message from renderer
    event.sender.send('reply-from-main', 'Hello from Main!');
});


const { ipcRenderer } = require('electron');
ipcRenderer.send('message-to-main', 'Hello from Renderer!');
ipcRenderer.on('reply-from-main', (event, arg) => {
    console.log(arg); // Log reply from main
});

*/


/*

Renderer-to-Renderer Communication:

ipcMain.on('message-between-renderers', (event, message) => {
    const targetWindow = BrowserWindow.getAllWindows().find(win => win.webContents.id === message.targetId);
    if (targetWindow) {
        targetWindow.webContents.send('message-to-renderer', message.data);
    }
});


render 1

const { ipcRenderer } = require('electron');
ipcRenderer.send('message-between-renderers', { targetId: 2, data: 'Hello Renderer 2' });


render 2

ipcRenderer.on('message-to-renderer', (event, data) => {
    console.log(data); // Log message from Renderer 1
});

 */
