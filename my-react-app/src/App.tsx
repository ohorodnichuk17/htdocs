import React from 'react';
import ContainerDefault from "./containers/default/ContainerDefault.tsx";
import {Route, Routes} from "react-router-dom";
import CategoriesListPage from "./components/categories/list/CategoriesListPage.tsx";
import NoMatch from "./components/pages/NoMatch.tsx";
import CategoryCreatePage from "./components/categories/create/CategoryCreatePage.tsx";
import CategoryEditPage from "./components/categories/edit/CategoryEditPage.tsx";
import RegisterPage from "./components/auth/register/RegisterPage.tsx";
import LoginPage from "./components/auth/login/LoginPage.tsx";
import ProductsListPage from "./components/products/list/ProductsListPage.tsx";


const App: React.FC = () => {
    //console.log(APP_ENV.BASE_URL);
    return (
        <Routes>
            <Route path="/" element={<ContainerDefault />}>
                <Route index element={<CategoriesListPage />} />
                <Route path={"create"} element={<CategoryCreatePage />} />
                <Route path="edit/:categoryId" element={<CategoryEditPage />} />

                <Route path={"products"} index element={<ProductsListPage />} />
                <Route path={"register"} element={<RegisterPage />} />
                <Route path={"login"} element={<LoginPage />} />
                <Route path="*" element={<NoMatch />} />
            </Route>
        </Routes>
    );
};

export default App;