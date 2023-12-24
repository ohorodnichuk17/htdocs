import React from "react";
import {Button, Result} from 'antd';
import {Link} from "react-router-dom";

const NoMatch: React.FC = () => {
    return (
        <Result
            status="404"
            title="404"
            subTitle="Sorry, this page was not found."
            extra={
                <Link to={"/"}>
                    <Button type="primary">Return Home</Button>
                </Link>
            }
        />
    );
}
export default NoMatch;