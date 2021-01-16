import React from "react";
import {getCourses} from "./course-api";
import Name from "../name/name";

class Course extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            course: "no-course",
            user: {
                firstName: "",
                lastName: ""
            },
            counter: 0
        }
    }

    setUser(key, event){

        const user = this.state.user;
        user[key] = event.target.value;

        this.setState({
            user
        });
    }

    componentDidMount() {
        getCourses()
            .then((result) => {
                this.setState({
                    course: result
                });
            });
    }

    updateCounter(counter){
        counter++;
        this.setState({
            counter
        });
    }

    render() {
        return (
            <div>
                <span data-testid={"counter"}>{this.state.counter}</span>
                <h1 id={"course"} data-testid={"course"}>{this.state.course}</h1>
                <input data-testid={"firstName"} type={"text"} value={this.state.user.firstName} onChange={this.setUser.bind(this, "firstName")}/>
                <br/>
                <input data-testid={"lastName"} type={"text"} value={this.state.user.lastName} onChange={this.setUser.bind(this, "lastName")}/>
                <Name firstName={this.state.user.firstName} lastName={this.state.user.lastName} incrementCounter={this.updateCounter.bind(this)} counter={this.state.counter}/>
            </div>
        )
    }
}

export default Course;