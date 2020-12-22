import React from 'react';
import {removeTokenAction, modifyTokenNotificationAction} from "../../store/actions/userLoginAction"
import {connect} from "react-redux";
import {withRouter} from "react-router-dom";
import userLogout from "../../sdk/userAuthenticationManager/logout";
import newsfeed from "../../sdk/dashboardManager/newsfeed";
import dashboardDetails from "../../sdk/dashboardManager/dashboardDetails";
import actionTrigger from "../../sdk/dashboardManager/actionTrigger";
import conversionTrigger from "../../sdk/dashboardManager/conversionTrigger";
import notificationToggle from "../../sdk/dashboardManager/notificationToggle";
import viewClickManager from "../../sdk/dashboardManager/inAppPushViewClickManager";

class Dashboard extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            dashboardInfo: {
                baseUrl: "",
                app_name: "",
                user_id: "",
                email: "",
                isNotificationOn: true
            },
            dynamicListing: {
                type: "",
                list: []
            },
            actionsArray: []
        };
        window.appObj = this;
    }

    componentDidMount() {
        dashboardDetails.getDashboardDetails((data) => {
            this.setState({
                dashboardInfo: data
            });
        });
    }

    openNewsFeed() {
        newsfeed.getNewsFeedListing((content) => {
            if (content != null) {
                let newWindow = window.open();
                newWindow.document.write(content);
                newWindow.document.close();
            }
        });
    }

    getActionList() {
        this.resetMenuListing();
        actionTrigger.getActionListing((data) => {
            if (data != null) {
                this.setState({
                    dynamicListing: {
                        type: "action",
                        list: data
                    }
                });
                document.getElementById("mySidenav").style.width = "250px";
            }
        });
    }

    getConversionList() {
        this.resetMenuListing();
        conversionTrigger.getConversionListing((data) => {
            if (data != null) {
                this.setState({
                    dynamicListing: {
                        type: "conversion",
                        list: data
                    }
                });
                document.getElementById("mySidenav").style.width = "250px";
            }
        });
    }

    getUserActionList() {
        this.resetMenuListing();
        actionTrigger.getUserPerformedActions((data) => {
            if (data != null) {
                this.setState({
                    dynamicListing: {
                        type: "userPerformedActions",
                        list: data
                    }
                });
                document.getElementById("mySidenav").style.width = "250px";
            }
        });
    }

    getUserConversionList() {
        this.resetMenuListing();
        conversionTrigger.getUserPerformedConversion((data) => {
            if (data != null) {
                this.setState({
                    dynamicListing: {
                        type: "userPerformedConversions",
                        list: data
                    }
                });
                document.getElementById("mySidenav").style.width = "250px";
            }
        });
    }

    toggleNotification() {

        notificationToggle.toggleNotification(this.state.dashboardInfo.isNotificationOn, (data) => {
            if (data != null) {
                this.props.modifyTokenNotification(data);
                this.setState((state) => {
                    return {
                        dashboardInfo: {
                            ...state.dashboardInfo,
                            isNotificationOn: !state.dashboardInfo.isNotificationOn
                        }
                    }
                });
            }
        });
    }

    getNewsFeedCount() {
        newsfeed.getNewsFeedCount((newsFeedCountObj) => {
            if (newsFeedCountObj != null) {
                alert("total_newsfeed: " + newsFeedCountObj.total_newsfeed + "\nunread_newsfeed: " + newsFeedCountObj.unread_newsfeed);
            }
        });
    }

    logOutUser() {
        userLogout.logout((isLoggedOut) => {
            if (isLoggedOut) {
                this.removeToken();
            }
        });
    }

    removeToken() {
        this.props.removeToken();
        this.props.history.push("/login");
        window.location.href = "/login";
    }

    closeNav() {
        this.resetMenuListing();
        document.getElementById("mySidenav").style.width = "0px";
    }

    resetMenuListing() {
        this.setState({
            dynamicListing: {
                type: "",
                list: []
            },
            actionsArray: []
        });
    }

    getListItem(item) {
        switch (this.state.dynamicListing.type) {
            case "action":
                this.arrayPushPop(item);
                break;
            case "conversion":
                conversionTrigger.reqConversionActions(item, () => {

                });
                break;
            default:
                // do nothing
        }
    }

    arrayPushPop(item) {
        let index = this.inArray(item);
        if (index > -1) {
            let list = [
                ...this.state.actionsArray
            ];
            list.splice(index, 1);

            this.setState({
                actionsArray: list
            });

        } else {
            this.setState((state) => {
                return {
                    actionsArray: [
                        ...state.actionsArray,
                        item
                    ]
                };
            });
        }
    }

    inArray(item) {
        let list = this.state.actionsArray;
        for (let i = 0; i < list.length; i++) {
            if (JSON.stringify(item) === JSON.stringify(list[i])) {
                return i;
            }
        }
        return -1;
    }

    submitActions() {
        actionTrigger.reqTriggerActions(this.state.actionsArray, () => {

        });
    }

    notificationReciever(notificationData) {

        notificationData.data.data = JSON.parse(notificationData.data.data);

        let notificationRcvObj = {
            data: notificationData.data.data,
            notify: notificationData.notification
        };


        if (notificationRcvObj.data.campaign_dispatch_date <= new Date().toISOString().split("T")[0]) {

            switch (notificationRcvObj.data.campaign_type.toLowerCase()) {
                case "push":
                    this.pushHandler(notificationRcvObj);
                    break;
                case "inapp":
                    this.inappHandler();
                    break;
                default:
                    //do nothing
            }
        }
    }

    pushHandler(notificationRcvObj) {

        this.viewPush(notificationRcvObj);

        const notificationTitle = notificationRcvObj.notify.title;
        const notificationOptions = {
            body: notificationRcvObj.notify.body,
            icon: notificationRcvObj.data.icon
        };

        let notification = new Notification(notificationTitle, notificationOptions);
        notification.onclick = () => {
            if (notificationRcvObj.data.params['Deep Link'] !== undefined && notificationRcvObj.data.params['Deep Link'] !== "") {
                this.clickPush(notificationRcvObj);
                let deepLink = notificationRcvObj.data.params['Deep Link'].split(":/")[1];
                this.props.history.push(deepLink);
            }
        }
    }

    viewPush(notificationRcvObj) {
        if (notificationRcvObj.data.track_key.length > 0) {
            viewClickManager.viewClick(notificationRcvObj, "viewed", () => {

            });
        }
    }

    clickPush(notificationRcvObj) {
        if (notificationRcvObj.data.track_key.length > 0) {
            viewClickManager.viewClick(notificationRcvObj, "clicked", () => {

            })
        }
    }

    inappHandler() {
        alert("inApp handler will be implemented soon");
    }

    render() {
        return (
            <div className="table_div_holder">

                {/*bootstrap modal starts*/}
                {/*<div className="modal fade" id="myModal" role="dialog">
                    <div className="modal-dialog modal-lg">
                        <div className="modal-content">
                            <div className="modal-header">
                                <button type="button" className="close" data-dismiss="modal">&times;</button>
                                <h4 className="modal-title">Modal Header</h4>
                            </div>
                            <div className="modal-body">
                                <p>This is a large modal.</p>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>*/}
                {/*bootstrap modal ends*/}

                <div id="mySidenav" className="sidenav">
                    {
                        this.state.actionsArray.length > 0 ?
                            <a className="cursor_pointer background_red submitbtn"
                                    onClick={this.submitActions.bind(this)}>submit</a>
                            : null
                    }
                    <button className="closebtn" onClick={this.closeNav.bind(this)}>&times;</button>
                    {
                        this.state.dynamicListing.list.map((listItem, index) => (
                            <a className={this.state.dynamicListing.type === 'action' ? this.inArray(listItem) > -1 ? 'cursor_pointer background_red' : 'cursor_pointer' : 'cursor_pointer'}
                               key={index}
                               onClick={this.getListItem.bind(this, listItem)}>{listItem.code} - {listItem.value}</a>
                        ))
                    }
                </div>
                <div className="center_allign_content_vertical">
                    <div>
                        <div className="user_info_holder">
                            <div>
                                <span>{this.state.dashboardInfo.baseUrl}</span>
                            </div>
                            <div className="user_infos">
                                <p>
                                    <strong>{this.state.dashboardInfo.app_name}</strong>
                                </p>
                                <p>
                                    <strong>userid={this.state.dashboardInfo.user_id}</strong>
                                </p>
                                <p>
                                    <strong>{this.state.dashboardInfo.email}</strong>
                                </p>
                            </div>
                        </div>
                        <div>
                            <div className="input_div_holder">
                                <button className="submit_input" onClick={this.openNewsFeed.bind(this)}>Open NewsFeed
                                </button>
                            </div>
                            <div className="input_div_holder">
                                <button className="submit_input" onClick={this.getActionList.bind(this)}>Trigger Action
                                    Campaign
                                </button>
                            </div>
                            <div className="input_div_holder">
                                <button className="submit_input" onClick={this.getConversionList.bind(this)}>Trigger
                                    Conversion
                                </button>
                            </div>
                            <div className="input_div_holder">
                                <button className="submit_input" onClick={this.getUserActionList.bind(this)}>Get Action
                                    List
                                </button>
                            </div>
                            <div className="input_div_holder">
                                <button className="submit_input" onClick={this.getUserConversionList.bind(this)}>Get
                                    Conversion List
                                </button>
                            </div>
                            <div className="input_div_holder">
                                <button className="submit_input" onClick={this.logOutUser.bind(this)}>Logout</button>
                            </div>
                            <div className="input_div_holder left_right_btn_holder">
                                <button className="float_left submit_input"
                                        onClick={this.toggleNotification.bind(this)}>Notification {this.state.dashboardInfo.isNotificationOn ? 'OFF' : 'ON'}
                                </button>
                                <button className="float_right submit_input"
                                        onClick={this.getNewsFeedCount.bind(this)}>Get NewsFeed Count
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

}

const mapStateToProps = (state) => {
    return {};
};

const mapDispatchToProps = (dispatch) => {
    return {
        removeToken: () => {
            dispatch(removeTokenAction());
        },
        modifyTokenNotification: (data) => {
            dispatch(modifyTokenNotificationAction(data));
        }
    };
};

export default connect(mapStateToProps, mapDispatchToProps)(withRouter(Dashboard));
