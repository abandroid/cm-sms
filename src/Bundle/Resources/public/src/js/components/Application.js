import React from 'react';
import Request from 'superagent';
import MessageList from './MessageList';

class Application extends React.Component {

    constructor(props) {
        super(props);

        this.loadState = this.loadState.bind(this);
        this.updatePhoneNumber = this.updatePhoneNumber.bind(this);
        this.sendTest = this.sendTest.bind(this);

        this.loadState();

        this.state = { messages: [], phoneNumber: '' };
    }

    loadState() {
        Request
            .get(this.props.loadPath)
            .end((error, response) => {
                if (error) {
                    alert('Could not load SMS status data');
                } else {
                    this.setState(response.body);
                }
            });
    }

    updatePhoneNumber(event) {
        this.state.phoneNumber = event.target.value;
        this.setState(this.state);
    }

    sendTest(phoneNumber) {
        if (!confirm('Are you sure?')) {
            return;
        }

        Request
            .get(this.props.testPath.replace('0000000000', phoneNumber))
            .end((error, response) => {
                if (error) {
                    alert('Could not send SMS message');
                } else {
                    alert('Test message sent to "' + phoneNumber + '"');
                }
            });
    }

    render() {
        return (
            <div className="row">
                <div className="col-md-12">
                    <div className="box">
                        <div className="box-body">
                            <form className="form-inline">
                                <div className="form-group">
                                    <input className="form-control" type="text" placeholder="Phone number" onKeyUp={this.updatePhoneNumber} />
                                    &nbsp;
                                    <button type="button" className="btn btn-primary" onClick={() => this.sendTest(this.state.phoneNumber)}>Send test</button>
                                    &nbsp;
                                    <button type="button" className="btn btn-success" onClick={() => this.loadState()}>Refresh</button>
                                </div>
                            </form>
                            <MessageList messages={this.state.messages} />
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Application;