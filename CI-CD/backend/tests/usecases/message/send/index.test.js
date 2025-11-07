jest.mock('../../../../src/app/usecases/baseUsecase');
jest.mock('../../../../src/app/databases/repository/base');
jest.mock('../../../../src/app/databases/repository/message');
jest.mock('../../../../src/app/databases/repository/messageDetail');
jest.mock('../../../../src/app/databases/repository/user');
jest.mock('../../../../src/app/databases/repository/userChatGroup');
jest.mock('../../../../src/app/databases/repository/userChatGroupMember');

const SendMessageUseCase = require('../../../../src/app/usecases/message/send');


describe('Send message test cases', () => {
    test('By groupId', async () => {
        expect.assertions(1);
        const data = await (new SendMessageUseCase({
            groupId: 1,
            userId: 1,
            message: "sample message"
        })).send();
        expect(data).toEqual({
            message: {
                id: 1,
                userChatGroupId: 1,
                text: 'sample message',
                createdAt: '2024-10-30 12:37:40',
                updatedAt: '2024-10-30 12:37:40',
                deletedAt: null
            },
            messageDetails: [
                {
                    id: 1,
                    messageId: 1,
                    userId: 1,
                    isSender: true,
                    createdAt: '2024-10-30 12:37:40',
                    updatedAt: '2024-10-30 12:37:40',
                    deletedAt: null
                },
                {
                    id: 2,
                    messageId: 1,
                    userId: 2,
                    isSender: false,
                    createdAt: '2024-10-30 12:37:40',
                    updatedAt: '2024-10-30 12:37:40',
                    deletedAt: null
                }
            ]
        });
    });

    test('New message by UserIds', async () => {
        expect.assertions(1);
        const data = await (new SendMessageUseCase({
            userIds: [3],
            userId: 1,
            message: "sample message"
        })).send();
        expect(data).toEqual({
            message: {
                id: 1,
                userChatGroupId: 2,
                text: 'sample message',
                createdAt: '2024-10-30 12:37:40',
                updatedAt: '2024-10-30 12:37:40',
                deletedAt: null
            },
            messageDetails: [
                {
                    id: 3,
                    messageId: 1,
                    userId: 1,
                    isSender: true,
                    createdAt: '2024-10-30 12:37:40',
                    updatedAt: '2024-10-30 12:37:40',
                    deletedAt: null
                },
                {
                    id: 4,
                    messageId: 1,
                    userId: 3,
                    isSender: false,
                    createdAt: '2024-10-30 12:37:40',
                    updatedAt: '2024-10-30 12:37:40',
                    deletedAt: null
                }
            ]
        });
    });
});