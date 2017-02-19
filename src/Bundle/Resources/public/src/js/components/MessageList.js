import React from 'react';
import _ from 'lodash';
import Message from './Message';

class MessageList extends React.Component {

    constructor(props) {
        super(props);
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
                        <input type="submit" onClick={() => this.props.sendTest()} value="Send test" />
                        &nbsp;
                        <input type="submit" onClick={() => this.props.loadState()} value="Refresh" />
                    </div>
                    <table className="table table-bordered" id="message-list">
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
