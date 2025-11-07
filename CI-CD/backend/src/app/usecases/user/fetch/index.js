const BaseUseCase = require('../../baseUsecase');
const UserRepo = require('../../../databases/repository/user');

module.exports = class FetchUserUseCase extends BaseUseCase {
    static fetchById(Id) {
        return UserRepo.findUserById(Id);
    }

    static getAllUsersExceptCurrent(Id) {
        return UserRepo.findAllUsersExceptCurrent(Id)
            .then((result) => {
                const chatListing = [];
                for (const group of result) {
                    const users = JSON.parse(group.users);
                    const isGroup = users.length > 1;
                    chatListing.push({
                        ...(group.group_id !== 'N/A' && {
                            groupId: group.group_id,
                            isGroup,
                        }),
                        groupName: group.group_name,
                        lastMessage: group.last_message,
                        ...(isGroup && {members: users}),
                        ...(!isGroup && {userId: users[0].id})
                    });
                }
                return chatListing
            });
    }
};