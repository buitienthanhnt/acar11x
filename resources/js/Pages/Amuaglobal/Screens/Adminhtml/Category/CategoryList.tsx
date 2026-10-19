import { Head } from "@inertiajs/react";
import BaseLayout from "../Layout/BaseLayout";
import { PaginateInterface } from "@/Pages/Amuaglobal/types/Paginate";
import { PageWithLayout } from "@/Pages/Amuaglobal/types/PageWithLayout";

interface Props {
    categories: PaginateInterface
}


const CategoryList: PageWithLayout<Props> = ({ categories }) => {

    return (
        <div>
            noi dung danh sach category
        </div>
    )
}
CategoryList.layout = (page: any) => <BaseLayout children={page} title={'categories'}></BaseLayout>;
export default CategoryList;
