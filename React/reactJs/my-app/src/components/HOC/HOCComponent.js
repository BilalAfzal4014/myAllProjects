import React from "react";


function attachProperty(props) {
    let newProps = {
        style: {...props}
    };

    if (props.color == "green") {
        newProps.style.border = "3px solid red";
    } else if (props.color == "red") {
        newProps.style.border = "3px solid green";
    }

    return newProps;
}

let componentWrapper = (wrappedComponent) => {

    return function wrappedRender(arg) {
        return new wrappedComponent(attachProperty(arg));
        // will be write new in case of class component passed, but if function component passed we dont need to write new
    }
};

export default componentWrapper;