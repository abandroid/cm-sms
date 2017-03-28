import React from 'react';
import _ from 'lodash';
import Message from './Message';

class MessageList extends React.Component {

    constructor(props) {
        super(props);

        this.state = { phoneNumber: '' }
    }

    render() {
        let messages = [];
        _.each(this.props.messages, function(message, index) {
            messages.push(
                <Message
                    key={index}
                    message={message}
                />
            );
        });

        return (
            <table className="table table-bordered" id="message-list">
                <thead>
                    <tr>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Reference</th>
                        <th>Message</th>
                        <th>Recipients</th>
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
