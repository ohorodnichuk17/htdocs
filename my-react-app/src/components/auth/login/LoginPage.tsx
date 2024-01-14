import { Button, Form, Input, message } from "antd";
import { useNavigate } from "react-router-dom";
import http_common from "../../../http_common.ts";

interface LoginFormValues {
    email: string;
    password: string;
}

const LoginPage = () => {
    const navigate = useNavigate();

    const onFinish = async (values: LoginFormValues) => {
        try {
            const response = await http_common.post("/api/login", values);
            const { token } = response.data;
            localStorage.setItem("token", token);
            message.success("Login successful");
            navigate("/");
        } catch (ex) {
            message.error("Login failed. Check your credentials.");
        }
    };

    const onFinishFailed = (errorInfo: any) => {
        console.log("Failed:", errorInfo);
        console.log("Errors:", errorInfo.errors);
    };

    return (
        <>
            <Form
                name="login"
                style={{ maxWidth: 400 }}
                initialValues={{ remember: true }}
                onFinish={onFinish}
                onFinishFailed={onFinishFailed}
            >
                <Form.Item
                    label="Email"
                    name="email"
                    rules={[{ required: true, message: "Please enter your email!" }]}
                >
                    <Input />
                </Form.Item>

                <Form.Item
                    label="Password"
                    name="password"
                    rules={[{ required: true, message: "Please enter your password!" }]}
                >
                    <Input.Password />
                </Form.Item>

                <Form.Item>
                    <Button type="primary" htmlType="submit">
                        Login
                    </Button>
                </Form.Item>
            </Form>
        </>
    );
};

export default LoginPage;
