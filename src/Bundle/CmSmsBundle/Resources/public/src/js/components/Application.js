import React from 'react';
import Request from 'superagent';
import Alert from 'react-s-alert';
import 'react-s-alert/dist/s-alert-default.css';
import Noty from 'noty';
import 'noty/lib/noty.css';
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
                    Alert.warning('Could not load SMS status data', {
                        position: 'bottom',
                        timeout: 4000
                    });
                } else {
                    this.setState(response.body);
                }
            });
    }

    updatePhoneNumber(event) {
        this.state.phoneNumber = event.target.value;
        this.setState(this.state);
    }

    sendTest(phoneNumber, confirmed = false) {

        if (!confirmed) {
            let component = this;
            let noty = new Noty({
                text: 'Are you sure?',
                buttons: [
                    Noty.button('Yes', 'btn btn-success', function () {
                        component.sendTest(phoneNumber, true);
                        noty.close();
                    }),
                    Noty.button('No', 'btn btn-danger', function () {
                        noty.close();
                    })
                ]
            }).show();

            return;
        }

        Request
            .get(this.props.testPath.replace('0000000000', phoneNumber))
            .end((error, response) => {
                if (error) {
                    Alert.warning('Could not send SMS message', {
                        position: 'bottom',
                        timeout: 4000
                    });
                } else {
                    Alert.success('Test message sent to "' + phoneNumber + '"', {
                        position: 'bottom',
                        timeout: 4000
                    });
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
                            <br />
                            <Alert stack={{ limit: 3 }} />
                            <MessageList messages={this.state.messages} />
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

export default Application;