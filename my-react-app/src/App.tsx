import React from 'react';
import ContainerDefault from "./containers/default/ContainerDefault.tsx";
import {Route, Routes} from "react-router-dom";
import CategoriesListPage from "./components/categories/list/CategoriesListPage.tsx";
import NoMatch from "./components/pages/NoMatch.tsx";
import CategoryCreatePage from "./components/categories/create/CategoryCreatePage.tsx";
import CategoryEditPage from "./components/categories/edit/CategoryEditPage.tsx";
import RegisterPage from "./components/auth/register/RegisterPage.tsx";


const App: React.FC = () => {
    //console.log(APP_ENV.BASE_URL);
    return (
        <Routes>
            <Route path="/" element={<ContainerDefault />}>
                <Route index element={<CategoriesListPage />} />
                <Route path={"create"} element={<CategoryCreatePage />} />
                <Route path="edit/:categoryId" element={<CategoryEditPage />} />
                <Route path={"register"} element={<RegisterPage />} />
                <Route path="*" element={<NoMatch />} />
            </Route>
        </Routes>
    );
};

export default App;