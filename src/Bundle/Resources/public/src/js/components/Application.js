import React from 'react';
import Request from 'superagent';
import MessageList from './MessageList';

class Application extends React.Component {

    constructor(props) {
        super(props);

        this.loadState = this.loadState.bind(this);
        this.sendTest = this.sendTest.bind(this);

        this.loadState();

        this.state = { messages: [] };
    }

    loadState() {
        Request.get(this.props.loadPath).then((response) => {
            this.setState(response.body);
        });
    }

    sendTest() {
        console.log(this.props.testPath);
        Request.get(this.props.testPath).then((response) => {
            console.log('test message sent!');
        });
    }

    render() {
        return (
            <div className="row">
                <div className="col-md-12">
                    <MessageList
                        messages={this.state.messages}
                        loadState={this.loadState}
                        sendTest={this.sendTest}
                    />
                </div>
            </div>
        );
    }
}

export default Application;