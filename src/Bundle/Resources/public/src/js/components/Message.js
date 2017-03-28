import React from 'react';

class Message extends React.Component {

    constructor(props) {
        super(props);
    }

    render() {

        let message = this.props.message;

        let style = { };
        if (message.delivered) {
            style = { backgroundColor: '#CFC' };
        } else if (message.sent) {
            style = { backgroundColor: '#FFC' };
        }

        return (
            <tr style={style} onClick={() => this.toggle()}>
                <td>{message.id}</td>
                <td>{message.body}</td>
                <td>{message.recipients}</td>
                <td>{message.sent ? 'Yes' : 'No'}</td>
                <td>{message.statusLabel}</td>
            </tr>
        )
    }
}

export default Message;