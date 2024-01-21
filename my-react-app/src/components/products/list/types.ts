// export interface IProductItem {
//     id: number;
//     name: string;
//     price: number;
//     image: string
// }




export interface IProductItem {
    id: number,
    category_id: number,
    name: string,
    description: string,
    product_images: IProductImage[],
    price: number,
    quantity: number,
}

export interface IProductImage {
    id: number,
    product_id: number,
    name: string,
}