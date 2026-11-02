import { Link, router } from "@inertiajs/react";
import BaseLayout from "../Layout/BaseLayout";
import { PaginateInterface } from "@/Pages/Amuaglobal/types/Paginate";
import { PageWithLayout } from "@/Pages/Amuaglobal/types/PageWithLayout";
import LinkBtn from "@/Pages/Amuaglobal/Components/LinkBtn";
import { CategoryType } from "@/Pages/Amuaglobal/types/Category";
import { TrashIcon } from "@heroicons/react/24/solid";
import { useCallback } from "react";

interface Props {
    categories: PaginateInterface
}

const CategoryList: PageWithLayout<Props> = ({ categories }) => {

    return (
        <div className="py-4 space-y-2">
            <div className="flex space-x-2">
                <LinkBtn path={'/adminhtml/amua-category/create'} title={'create category'} className="text-green-500" />
            </div>
            <CategoryItems items={categories?.data as unknown as CategoryType[]} />
        </div>
    )
}
CategoryList.layout = (page: any) => <BaseLayout children={page} title={'categories'}></BaseLayout>;
export default CategoryList;

const CategoryItems = ({ items }: { items: CategoryType[] }) => {
    if (!items) {
        return null;
    }

    return (
        <div className="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-4 md:gap-4">
            {items.map((item, index) => {
                return (
                    <Link key={index}
                        className="relative flex p-2 space-x-2 border border-gray-600 rounded-md hover:bg-blue-gray-100"
                        href={`/adminhtml/amua-category/edit/${item.id}`}
                    >
                        <img src={item.image} alt={item.name} className="object-cover border border-gray-600 rounded-full size-24" />
                        <div>
                            <p className={'font-semibold'}>{item.name}</p>
                            <p className={"text-sm text-green-600 font-semibold italic"}>{item.description}</p>
                            <p>{item.active.toString()}</p>
                        </div>
                        <Link className={'absolute right-1 text-gray-500 hover:text-red-500'}
                        href={`/adminhtml/amua-category/delete/${item.id}`}
                        method="delete"
                        onBefore={() => confirm('are you sure for delete the item?')}
                        >
                            <TrashIcon className={'size-8'}></TrashIcon>
                        </Link>
                    </Link>
                )
            })}
        </div>
    )
}
