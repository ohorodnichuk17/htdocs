import React, {useEffect, useState} from "react";
import {IProductImage, IProductItem} from "./types.ts";
import {ColumnsType} from "antd/es/table";
import {Table} from "antd";
import http_common from "../../../http_common.ts";

const ProductsListPage : React.FC = () => {

    const [list, setList] = useState<IProductItem[]>([]);
    const urlServerImage = "http://127.0.0.1:8000/upload/50_";

    const columns: ColumnsType<IProductItem> = [
        {
            title: '#',
            dataIndex: 'id'
        },
        {
            title: 'Category Id',
            dataIndex: 'category_id'
        },
        {
            title: 'Image',
            dataIndex: 'product_images',
            render: (imgPath: IProductImage[]) => {
                return (
                    <img src={urlServerImage+imgPath.map(i => i.name)} alt={"Image"} width={100}/>
                );
            }
        },
        {
            title: 'Name',
            dataIndex: 'name'
        },
        {
            title: 'Description',
            dataIndex: 'description'
        },
        {
            title: 'Price',
            dataIndex: 'price'
        },
        {
            title: 'Quantity',
            dataIndex: 'quantity'
        },
    ];

    useEffect(()=> {
        console.log("use Effect");

        http_common.get<IProductItem[]>("/api/products")
            .then(resp=> {
                console.log("resp", resp.data);
                setList(resp.data);
            });

    },[]);

    console.log("Render component")
    return (
        <>
            <h1>Products List</h1>
            <Table dataSource={list} rowKey={"id"} columns={columns} size={"middle"} />
        </>
    )
}

export default ProductsListPage;