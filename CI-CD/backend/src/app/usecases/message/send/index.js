const BaseUseCase = require('../../baseUsecase');
const CreateUserChatGroup = require('../../userChatGroup/create');
const SaveMessageWithDetailsUseCase = require('../save/messageWithDetails');
const FetchUserChatGroupMemberUseCase = require('../../userChatGroupMember/fetch');

module.exports = class SendMessageUseCase extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
        this.messageMembers = [];
    }

    send() {
        return this.getTransactionInstance()
            .then(() => {
                return this.handleSendMessage();
            }).then((data) => {
                this.commitTransaction();
                return data;
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    handleSendMessage() {
        let promise = Promise.resolve();
        if (this.checkIfFirstMessage()) {
            promise = this.handlePreSaveScenarios()
        }
        return promise.then(() => {
            return this.handleSaveMessageScenarios();
        });
    }

    checkIfFirstMessage() {
        return !this.data.groupId;
    }

    handlePreSaveScenarios() {
        return this.createUserChatGroup()
            .then((userChat) => {
                this.data.groupId = userChat.chatGroup.id;
                return userChat;
            });
    }

    createUserChatGroup() {
        return (new CreateUserChatGroup({
            userId: this.data.userId, userIds: this.data.userIds, groupName: this.data.groupName ?? null
        }, this.transactionInstance)).create();
    }

    handleSaveMessageScenarios() {
        return this.fetchMembersOfChatGroup()
            .then(() => {
                return this.saveMessage();
            });
    }

    fetchMembersOfChatGroup() {
        if (!!this.data.userIds) {
            this.messageMembers = [this.data.userId, ...this.data.userIds];
            return Promise.resolve(this.messageMembers);
        }
        return FetchUserChatGroupMemberUseCase.fetchUserChatGroupId(this.data.groupId)
            .then((userChatGroupMembers) => {
                this.messageMembers = userChatGroupMembers.map((userChatGroupMember) => userChatGroupMember.userId);
                return this.messageMembers;
            });
    }

    saveMessage() {
        return (new SaveMessageWithDetailsUseCase({
            userChatGroupId: this.data.groupId,
            members: this.messageMembers,
            sender: this.data.userId,
            text: this.data.message
        }, this.transactionInstance)).save();
    }
}