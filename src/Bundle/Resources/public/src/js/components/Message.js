import React from 'react';
import _ from 'lodash';

class Message extends React.Component {

    constructor(props) {
        super(props);
    }

    render() {

        let style = {};
        // if (this.props.isTarget) {
        //     style = { backgroundColor: '#CFC' };
        // } else if (this.props.isSource) {
        //     style = { backgroundColor: '#FFC' };
        // }

        console.log(this.props.message);

        return (
            <tr style={style} onClick={() => this.toggle()}>
                <td>{this.props.message.id}</td>
                <td>{this.props.message.body}</td>
                <td>{JSON.stringify(this.props.message.statuses)}</td>
            </tr>
        )
    }
}

export default Message;