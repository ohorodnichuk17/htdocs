import React, {useEffect, useState} from "react";
import {ICategoryItem} from "./types.ts";
import {ColumnsType} from "antd/es/table";
import {Table} from "antd";
import http_common from "../../../http_common.ts";
import {APP_ENV} from "../../../env";

const CategoriesListPage : React.FC = () => {

    const [list, setList] = useState<ICategoryItem[]>([
        // {
        //     id:23,
        //     name: "Сало",
        //     image: "https://odessa-life.od.ua/wp-content/uploads/2023/08/Buterbrodi-dlja-gurmaniv.jpg"
        // }
    ]);

    const urlServerImage = APP_ENV.BASE_URL +"/upload/";

    const columns: ColumnsType<ICategoryItem> = [
        {
            title: '#',
            dataIndex: 'id'
        },
        {
            title: 'Image',
            dataIndex: 'image',
            render: (imgPath: string) => {
                return (
                    <img src={urlServerImage+imgPath} alt={"Image"} width={100}/>
                );
            }
        },
        {
            title: 'Name',
            dataIndex: 'name'
        },
    ];

    useEffect(()=> {
        console.log("use Effect");

        http_common.get<ICategoryItem[]>("/api/categories")
            .then(resp=> {
                console.log("resp", resp.data);
                setList(resp.data);
            });

    },[]);

    console.log("Render component")
    return (
        <>
            <h1>Category List</h1>
            <Table dataSource={list} rowKey={"id"} columns={columns} size={"middle"} />;
        </>
    )
}

export default CategoriesListPage;