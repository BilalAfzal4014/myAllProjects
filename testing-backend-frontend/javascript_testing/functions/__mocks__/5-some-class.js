class SoundPlayer {
    constructor() {
        this.foo = 'bar';
    }

    playSoundFile(fileName) {
        return ('Playing sound file mocked ' + fileName);
    }
}


module.exports = SoundPlayer


/*
const mockPlaySoundFile = jest.fn().mockImplementation(function(){
    return "mocking method 1";
});
const mock = jest.fn().mockImplementation(() => {
    return {playSoundFile: mockPlaySoundFile};
});

module.exports = mock;
*/

/*const mockPlaySoundFile = jest.fn().mockImplementation(function(){
    return "mocking method 1.1";
});
const mock = jest.fn().mockImplementation(function() {
    this.playSoundFile = mockPlaySoundFile;
});

module.exports = mock;*/
