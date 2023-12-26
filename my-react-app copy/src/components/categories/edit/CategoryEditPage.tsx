import { Button, Divider, Form, Input, Upload, message, Alert } from "antd";
import { useNavigate, useParams } from "react-router-dom";
import { LoadingOutlined, PlusOutlined } from "@ant-design/icons";
import type { UploadChangeParam } from "antd/es/upload";
import type { RcFile, UploadFile, UploadProps } from "antd/es/upload/interface";
import http_common from "../../../http_common.ts";
import {useEffect, useState} from "react";

export interface ICategoryEdit {
    id: number
    name?: string;
    image?: File;
}

const CategoryEditPage = () => {
    const navigate = useNavigate();
    const { categoryId } = useParams();
    const [file, setFile] = useState<File | null>(null);
    const [errorMessage, setErrorMessage] = useState<string>("");
    const [loading, setLoading] = useState(false);
    const [categoryData, setCategoryData] = useState<ICategoryEdit | null>(null);

    useEffect(() => {
        // Fetch category data by categoryId and set it to categoryData state
        const fetchCategoryData = async () => {
            try {
                const response = await http_common.get(`/api/categories/${categoryId}`);
                const categoryData = response.data; // Adjust this based on your API response structure
                setCategoryData(categoryData);
                setFile(null); // Reset the file state to null initially
            } catch (ex) {
                console.error("Error fetching category data:", ex);
            }
        };

        fetchCategoryData();
    }, [categoryId]);

    const onFinish = async (values: any) => {
        // console.log("send data", values);
        if (file) {
            values.image = file.image;  // Error may occur here
        }
        values.id = id;

        console.log("Submitting data:", values);
        console.log("File:", file);

        try {
            await http_common.put(`/api/categories/${categoryId}`, values, {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            });

            navigate("/");
        } catch (ex) {
            console.error("Error updating category:", ex);
            message.error("Error updating category!");
        }
    };




    const customDividerStyle = {
        borderTop: '2px solid #1890ff',
        margin: '5px 0 50px 0',
    };

    const handleChange: UploadProps['onChange'] = (info: UploadChangeParam<UploadFile>) => {
        if (info.file.status === 'uploading') {
            setLoading(true);
            return;
        }
        if (info.file.status === 'done') {
            const file = info.file.originFileObj as File;
            setLoading(false);
            setFile(file);
            setErrorMessage("");
        }
    };

    const uploadButton = (
        <div>
            {loading ? <LoadingOutlined /> : <PlusOutlined />}
            <div style={{ marginTop: 8 }}>Upload</div>
        </div>
    );

    const beforeUpload = (file: RcFile) => {
        const isImage = /^image\/\w+/.test(file.type);
        if (!isImage) {
            message.error('Choose an image file!');
        }
        const isLt2M = file.size / 1024 / 1024 < 10;
        if (!isLt2M) {
            message.error('The file size should not exceed 10MB!');
        }
        console.log("is select", isImage && isLt2M);
        return isImage && isLt2M;
    };

    return (
        <>
            <Divider style={customDividerStyle}>Edit category</Divider>
            {errorMessage && <Alert message={errorMessage} style={{ marginBottom: "20px" }} type="error" />}
            {categoryData && (
                <Form
                    name="basic"
                    style={{ maxWidth: 1000 }}
                    initialValues={{ name: categoryData.name }}
                    onFinish={onFinish}
                    autoComplete="off"
                >
                    <Form.Item
                        label="Name"
                        name="name"
                        rules={[{ required: true, message: 'Enter category name!' }]}
                    >
                        <Input />
                    </Form.Item>

                    <Upload
                        name="avatar"
                        listType="picture-card"
                        className="avatar-uploader"
                        showUploadList={false}
                        action="https://run.mocky.io/v3/435e224c-44fb-4773-9faf-380c5e6a2188"
                        beforeUpload={beforeUpload}
                        onChange={handleChange}
                        accept={"image/*"}
                    >
                        {(file || categoryData?.image) && (
                            <img
                                src={URL.createObjectURL(file || new Blob([categoryData?.image as BlobPart]))}
                                alt="avatar"
                                style={{ width: "100%" }}
                            />
                        )}
                        {!file && !categoryData?.image && uploadButton}
                    </Upload>

                    <Form.Item wrapperCol={{ offset: 8, span: 16 }}>
                        <Button type="primary" htmlType="submit">
                            Update
                        </Button>
                    </Form.Item>
                </Form>
            )}
        </>
    );
};

export default CategoryEditPage;
