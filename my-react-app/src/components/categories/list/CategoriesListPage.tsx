import axios from "axios";
import { Table, Popconfirm, Button } from 'antd';
import { ColumnsType } from "antd/es/table";
import { useNavigate } from "react-router-dom";
import {useEffect, useState} from "react";

interface ICategoryItem {
    id: number;
    name: string;
    image: string;
}

const CategoryListPage = () => {
    const navigate = useNavigate();
    const [list, setList] = useState<ICategoryItem[]>([]);

    useEffect(() => {
        fetchCategories();
    }, []);

    const fetchCategories = () => {
        axios.get<ICategoryItem[]>("http://127.0.0.1:8000/api/categories")
            .then((resp) => {
                setList(resp.data);
            })
            .catch((error) => {
                console.error('Error fetching categories:', error);
            });
    };

    const handleDelete = (id: number) => {
        axios.delete(`http://127.0.0.1:8000/api/categories/${id}`)
            .then(() => {
                fetchCategories();
            })
            .catch((error) => {
                alert(`Deleting error --- ${error.message}`);
            });
    };

    const goToEditPage = (categoryId: number) => {
        navigate(`/edit-category/${categoryId}`);
    };

    const columns: ColumnsType<ICategoryItem> = [
        {
            title: '№',
            dataIndex: 'id',
            key: 'id',
        },
        {
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
        },
        {
            title: 'Image',
            dataIndex: 'image',
            key: 'image',
            render: (image: string) => (
                <img src={`http://127.0.0.1:8000/upload/150_${image}`} alt={"Image"} style={{ width: '50px' }}/>
            )
        },
        {
            title: 'Action',
            key: 'action',
            render: (_, record: ICategoryItem) => (
                <>
                    <Button onClick={() => goToEditPage(record.id)} type="primary">
                        Edit
                    </Button>
                    <Popconfirm
                        title="Are you sure you want to delete this category?"
                        onConfirm={() => handleDelete(record.id)}
                        okText="Yes"
                        cancelText="No"
                    >
                        <Button style={{ marginLeft: 8 }} danger>
                            Delete
                        </Button>
                    </Popconfirm>
                </>
            ),
        },
    ];

    return (
        <>
            <h1>Categories</h1>
            <Table dataSource={list} columns={columns} rowKey="id" />
        </>
    );
};

export default CategoryListPage;