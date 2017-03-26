import React from 'react';
import _ from 'lodash';
import Message from './Message';

class MessageList extends React.Component {

    constructor(props) {
        super(props);

        this.updatePhoneNumber = this.updatePhoneNumber.bind(this);

        this.state = { phoneNumber: '' }
    }

    updatePhoneNumber(event) {
        this.state.phoneNumber = event.target.value;
        this.setState(this.state);
    }

    render() {

        let component = this;

        console.log(this.props.messages);

        let messages = [];
        _.each(this.props.messages, function(message, index) {
            messages.push(
                <Message
                    key={index}
                    message={message}
                    loadState={component.props.loadState}
                    sendTest={component.props.sendTest}
                />
            );
        });

        return (
            <div className="box">
                <div className="box-body">
                    <div className="form-group">
                        <input type="text" placeholder="Phone number" onKeyUp={this.updatePhoneNumber} />
                        <input type="submit" onClick={() => this.props.sendTest(this.state.phoneNumber)} value="Send test" />
                        &nbsp;
                        <input type="submit" onClick={() => this.props.loadState()} value="Refresh" />
                    </div>
                    <table className="table table-bordered" id="message-list">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Message</th>
                                <th>Recipients</th>
                                <th>Sent</th>
                                <th>Delivered</th>
                            </tr>
                        </thead>
                        <tbody>
                            {messages}
                        </tbody>
                    </table>
                </div>
            </div>
        )
    }
}

export default MessageList;
