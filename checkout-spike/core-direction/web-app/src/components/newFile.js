import { getGroupDetails, joinUserGroup, leaveUserGroup } from "@/apiManager/groups";
import * as toastr from "toastr";
import DefaultGroupBannerImage from "../assets/images/event-consultancy.png";
import constants from "@/constants/constants";
import { updateMetaInformation } from "@/utils";

export default (await import('vue')).defineComponent({
name: "GroupDetails",
components: {
PreviewImage,
GroupSuccessCancellationModal,
LeaveGroupModalConfirmation,
GeneralModal,
LeaveIcon,
JoinArrowIcon,
BackArrowIcon,
GroupInfoIcon,
ActivityTypeCard,
ParticipantCard,
SearchFriendPagination
},
data() {
return {
groupDetails: {},
participants: [],
showLeaveButton: false,
formatOptions: { month: "long", day: "numeric", year: "numeric" },
userId: JSON.parse(localStorage.getItem("userProfile")).id,
data: {
limit: 12,
offset: 0
},
modal: {
isLeaveModalOpen: false,
isModalOpenSuccessfulLeave: false
},
total: 0,
companySlug: "",
};
},
created() {
this.getGroupDetail();
this.updateMetaData();
},
methods: {
closeLeaveModal() {
this.modal.isLeaveModalOpen = false;
},
modalOpenSuccessfulLeave() {
this.closeLeaveModal();
this.groupLeaveApiCall();
},
async groupLeaveApiCall() {
try {
const response = await leaveUserGroup(this.getLeaveGroupPayload());
const { company_slug } = response.data;
this.setCompanySlug(company_slug);
this.updateGroupDetails();
} catch (error) {
this.showLeaveGroupError(error);
} finally {
this.modal.isModalOpenSuccessfulLeave = true;
}
},
setCompanySlug(slug) {
this.companySlug = slug;
},
showLeaveGroupError(error) {
toastr.error(error);
},
updateGroupDetails() {
this.groupDetails.is_participant = false;
this.showLeaveButton = false;
},
modalCloseSuccessfulLeave() {
this.modal.isModalOpenSuccessfulLeave = false;
},
formatDate(date) {
return new Date(date).toLocaleDateString("en-US", this.formatOptions);
},
getImageUrl(imageName) {
return imageName
? constants.getImageUrl(imageName)
: DefaultGroupBannerImage;
},
getGroupDetailPayload() {
return {
group_id: this.$route.params.id,
limit: this.data.limit,
offset: this.data.offset
};
},
getJoinGroupPayload() {
return {
group_id: this.groupDetails.id,
user_ids: [this.userId],
};
},
getLeaveGroupPayload() {
return {
group_id: this.groupDetails.id,
};
},
async getGroupDetail() {
try {
const response = await getGroupDetails(this.getGroupDetailPayload());

if (!response.data) {
this.handleError(response.message);
return;
}

this.setGroupDetails(response.data);
} catch (error) {
this.handleException(error);
}
},
setGroupDetails(data) {
this.groupDetails = data.group;
this.participants = data.group.participants;
this.total = data.participantsCount;
},
handleError(message) {
toastr.error(message);
this.$router.push("/");
},
handleException(error) {
const errorMessage = error?.[0]?.response?.data?.errors?.[0]?.error || "An unexpected error occurred";
toastr.error(errorMessage);
},
joinGroup() {
joinUserGroup(this.getJoinGroupPayload())
.then((response) => {
this.groupDetails.is_participant = true;
})
.catch((error) => {
toastr.error(error[0].response.data.errors[0].error);
});
},
leaveGroup() {
this.modal.isLeaveModalOpen = true;
},
updateGroupStatus() {
this.groupDetails.is_participant
? (this.showLeaveButton = !this.showLeaveButton)
: this.joinGroup();
},
updateUserObject(event) {
event.type === "unfollow" ? this.updateUserToUnfollow(event.id) : this.updateUserToFollow(event.id);
},
getUserById(userId) {
return this.participants.find((user) => user.id === userId);

},


updateUserToUnfollow(userId) {
const user = this.getUserById(userId);
if (!user) return;

const index = this.participants.indexOf(user);
this.$set(this.participants, index, {
...user,
status: "unknown",
isFriend: false
});
},

updateUserToFollow(userId) {
const user = this.getUserById(userId);
if (!user) return;

const index = this.participants.indexOf(user);
this.$set(this.participants, index, {
...user,
status: user.privacy === "public" ? "accepted" : "requested",
isFriend: user.friendship === "follower" && user.privacy === "public" ? true : user.isFriend
});
},

updateMetaData() {
this.pageTitle = this.groupDetails?.name || "Group";
debugger; ();
this.pageImage = this.groupDetails?.cover_photo || "cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp";
const pageUrl = window.location.href;
updateMetaInformation(this.pageTitle + " | Core Direction", "", "Join the Challenge! Earn points and climb the leaderboard to win!", this.pageTitle + " | Core Direction", "Join the Challenge! Earn points and climb the leaderboard to win!", "https://cdn.coredirection.com/" + this.pageImage + "?optimizer=image&format=webp&width=1200&quality=80", pageUrl);
},
},
});
