import React from "react";
import Button1 from "./button1";
import Button2 from "./button2";

class ButtonHolder extends React.Component {

    render() {
        return (
            <div>
                <Button1 color="green"/>
                <Button2 color="red"/>
            </div>
        )
    }
}

export default ButtonHolder;