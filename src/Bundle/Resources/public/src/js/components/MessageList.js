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
            <table className="table table-bordered" id="message-list">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Message</th>
                        <th>Recipients</th>
                        <th>Sent</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    {messages}
                </tbody>
            </table>
        )
    }
}

export default MessageList;
