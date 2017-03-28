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
                    <div className="box">
                        <div className="box-body">
                            <div className="form-group">
                                <input type="text" placeholder="Phone number" onKeyUp={this.updatePhoneNumber} />
                                <input type="submit" onClick={() => this.props.sendTest(this.state.phoneNumber)} value="Send test" />
                                &nbsp;
                                <input type="submit" onClick={() => this.props.loadState()} value="Refresh" />
                            </div>
                            <MessageList
                                messages={this.state.messages}
                                loadState={this.loadState}
                                sendTest={this.sendTest}
                            />
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Application;