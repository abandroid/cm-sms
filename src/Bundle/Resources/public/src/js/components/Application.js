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

    sendTest(phoneNumber) {
        if (!confirm('Are you sure?')) {
            return;
        }

        let url = this.props.testPath.replace('0000000000', phoneNumber);
        Request.get(url).then((response) => {
            console.log('Test message sent to "' + phoneNumber + '"');
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