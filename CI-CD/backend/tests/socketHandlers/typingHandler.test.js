jest.mock('../../src/app/usecases/baseUsecase');
jest.mock('../../src/app/databases/repository/base');
jest.mock('../../src/app/databases/repository/message');
jest.mock('../../src/app/databases/repository/messageDetail');
jest.mock('../../src/app/databases/repository/user');
jest.mock('../../src/app/databases/repository/userChatGroup');
jest.mock('../../src/app/databases/repository/userChatGroupMember');
const {TypingHandler} = require('../../src/app/socket/handler');


const SocketIdentifiedConnectionHash = {
    1: ['1a', '1b'],
    2: ['2a', '2b'],
    3: ['3a', '3b']
}

describe('Socket TypingHandler testcases', () => {

    describe('When event is typing', () => {

        test('When groupId is provided', () => {
            expect.assertions(1);
            const data = {
                userId: 1,
                groupId: 1
            };
            return TypingHandler(SocketIdentifiedConnectionHash, data, 'typing')
                .then((result) => {
                    expect(result).toEqual([
                        ['2a', '2b'],
                        {name: 'Bilal', groupId: 1, individualId: undefined, typerId: 1}
                    ])
                });
        });

        test('When individualId is provided', () => {
            expect.assertions(1);
            const data = {
                userId: 1,
                individualId: 3
            };
            return TypingHandler(SocketIdentifiedConnectionHash, data, 'typing')
                .then((result) => {
                    expect(result).toEqual([
                        ['3a', '3b'],
                        {name: 'Bilal', groupId: undefined, individualId: 3, typerId: 1}
                    ])
                });
        });

    });

    describe('When event is stoppedTyping', () => {

        test('When groupId is provided', () => {
            expect.assertions(1);
            const data = {
                userId: 1,
                groupId: 1
            };
            return TypingHandler(SocketIdentifiedConnectionHash, data, 'stoppedTyping')
                .then((result) => {
                    expect(result).toEqual([
                        ['2a', '2b'],
                        {name: '', groupId: 1, individualId: undefined, typerId: 1}
                    ])
                });
        });

        test('When individualId is provided', () => {
            expect.assertions(1);
            const data = {
                userId: 1,
                individualId: 3
            };
            return TypingHandler(SocketIdentifiedConnectionHash, data, 'stoppedTyping')
                .then((result) => {
                    expect(result).toEqual([
                        ['3a', '3b'],
                        {name: '', groupId: undefined, individualId: 3, typerId: 1}
                    ])
                });
        });

    });

});